"""Offline teaching model. No network, payments, credentials or production writes.

Run: python examples/reconciliation_lab.py
Each test gets a temporary SQLite database. The modeled side effect and
deduplication result share ONE database transaction at the receiver. This does
not make separate providers, emails or local/remote databases atomic.
"""
import hashlib
import json
import sqlite3
import tempfile
import threading
import unittest
from concurrent.futures import ThreadPoolExecutor
from contextlib import closing
from pathlib import Path


class PayloadConflict(Exception):
    pass


class Receiver:
    def __init__(self, path):
        self.path = str(path)
        with closing(self.connect()) as db, db:
            db.executescript('''
                CREATE TABLE IF NOT EXISTS writes (
                    id INTEGER PRIMARY KEY, payload TEXT NOT NULL);
                CREATE TABLE IF NOT EXISTS receipts (
                    operation_key TEXT PRIMARY KEY, payload_hash TEXT NOT NULL,
                    result_id INTEGER NOT NULL REFERENCES writes(id));
            ''')

    def connect(self):
        return sqlite3.connect(self.path, timeout=5)

    def submit(self, key, payload, fault=None):
        # Demo-only canonicalization: callers use small JSON dicts of strings/ints.
        canonical = json.dumps(payload, sort_keys=True, separators=(',', ':'), allow_nan=False)
        digest = hashlib.sha256(canonical.encode()).hexdigest()
        db = self.connect()
        try:
            db.execute('BEGIN IMMEDIATE')
            existing = db.execute('SELECT payload_hash, result_id FROM receipts WHERE operation_key=?', (key,)).fetchone()
            if existing:
                if existing[0] != digest:
                    raise PayloadConflict('Same key, different payload')
                result = existing[1]
            else:
                result = db.execute('INSERT INTO writes(payload) VALUES (?)', (canonical,)).lastrowid
                db.execute('INSERT INTO receipts VALUES (?, ?, ?)', (key, digest, result))
            if fault == 'before_commit':
                raise TimeoutError('Injected interruption before commit')
            db.commit()
        except Exception:
            db.rollback()
            raise
        finally:
            db.close()
        if fault == 'after_commit':
            raise TimeoutError('Injected lost response after commit')
        return result

    def lookup(self, key):
        with closing(self.connect()) as db, db:
            row = db.execute('SELECT result_id FROM receipts WHERE operation_key=?', (key,)).fetchone()
        # ABSENT is not evidence of historical failure: retention may have pruned it.
        return {'state': 'SUCCEEDED', 'result_id': row[0]} if row else {'state': 'UNKNOWN'}

    def prune_receipt(self, key):
        with closing(self.connect()) as db, db:
            db.execute('DELETE FROM receipts WHERE operation_key=?', (key,))

    def count(self):
        with closing(self.connect()) as db, db:
            return db.execute('SELECT COUNT(*) FROM writes').fetchone()[0]


class ReconciliationLab(unittest.TestCase):
    def setUp(self):
        self.directory = tempfile.TemporaryDirectory(prefix='rodytech-reconciliation-')
        self.addCleanup(self.directory.cleanup)
        self.receiver = Receiver(Path(self.directory.name) / 'receiver.sqlite')
        self.payload = {'reference': 'demo-order-1', 'units': 3}

    def test_lost_response_replay_returns_original_result(self):
        with self.assertRaises(TimeoutError):
            self.receiver.submit('operation-1', self.payload, 'after_commit')
        # Reopen receiver to ensure the result is in SQLite, not process memory.
        receiver = Receiver(self.receiver.path)
        original = receiver.lookup('operation-1')['result_id']
        self.assertEqual(receiver.submit('operation-1', self.payload), original)
        self.assertEqual(receiver.count(), 1)

    def test_interruption_before_commit_rolls_back_local_transaction(self):
        with self.assertRaises(TimeoutError):
            self.receiver.submit('operation-1', self.payload, 'before_commit')
        self.assertEqual(self.receiver.count(), 0)
        self.assertEqual(self.receiver.lookup('operation-1'), {'state': 'UNKNOWN'})
        self.receiver.submit('operation-1', self.payload)
        self.assertEqual(self.receiver.count(), 1)

    def test_changed_payload_conflicts_without_a_second_write(self):
        original = self.receiver.submit('operation-1', self.payload)
        with self.assertRaises(PayloadConflict):
            self.receiver.submit('operation-1', {**self.payload, 'units': 4})
        self.assertEqual(self.receiver.lookup('operation-1')['result_id'], original)
        self.assertEqual(self.receiver.count(), 1)

    def test_four_simultaneous_attempts_converge(self):
        barrier = threading.Barrier(4)
        def attempt(_):
            barrier.wait(timeout=5)
            return self.receiver.submit('operation-1', self.payload)
        with ThreadPoolExecutor(max_workers=4) as pool:
            results = list(pool.map(attempt, range(4)))
        self.assertEqual(len(set(results)), 1)
        self.assertEqual(self.receiver.count(), 1)

    def test_new_key_after_timeout_creates_a_duplicate(self):
        with self.assertRaises(TimeoutError):
            self.receiver.submit('operation-1', self.payload, 'after_commit')
        self.receiver.submit('replacement-key', self.payload)
        self.assertEqual(self.receiver.count(), 2)

    def test_pruning_receipt_reopens_duplicate_window(self):
        self.receiver.submit('operation-1', self.payload)
        self.receiver.prune_receipt('operation-1')
        self.assertEqual(self.receiver.lookup('operation-1'), {'state': 'UNKNOWN'})
        self.receiver.submit('operation-1', self.payload)
        self.assertEqual(self.receiver.count(), 2)

    def test_read_only_reconciliation_resolves_missing_client_completion(self):
        # Receiver committed; caller never saved completion. No shared transaction.
        with self.assertRaises(TimeoutError):
            self.receiver.submit('operation-1', self.payload, 'after_commit')
        recovered = self.receiver.lookup('operation-1')
        self.assertEqual(recovered['state'], 'SUCCEEDED')
        self.assertEqual(self.receiver.count(), 1)


if __name__ == '__main__':
    unittest.main(verbosity=2)
