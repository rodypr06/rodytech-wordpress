# After the Timeout: tested example and publication package

Prepared September 15, 2026. Status: example verified locally; article additions and distribution copy are DRAFTS, not published or sent.

Update the existing article rather than creating another post:
https://blog.rodytech.ai/after-the-timeout-how-to-reconcile-uncertain-api-writes-safely/

## Proposed reader summary

Place this near the opening, before the detailed state model. Preserve the original publication date; add a truthful update date only after publication.

**Before you retry a write**

| What you can establish | Next step |
| --- | --- |
| The receiver confirms success for the original operation | Record its result; do not issue another operation. |
| The receiver confirms that processing continues | Keep the operation open and check again under a bounded schedule. |
| The provider permits replay for this operation and the original key is still retained | Replay the same payload with the same key under that provider's documented rules. |
| The result is missing, the key may have expired, or the receiver cannot be queried | Preserve UNKNOWN and reconcile or escalate. A missing record does not establish failure. |
| The same key is being reused with changed inputs | Stop and resolve the mismatch. Do not silently change the logical operation. |

## Proposed practical example

**Try the failure before you trust the retry**

We built a small offline exercise that injects an exception just before a database commit, then another just after commit to model a lost response. It also sends four simultaneous matching attempts, changes the payload under an existing key, retries with a new key, and removes a retained receipt.

In this model, replaying the same retained key and payload returned the original result with one stored write. Changing the key or deleting the receipt allowed a second write. A lookup recovered the committed result without submitting another write.

The boundary matters: this receiver stores its business write and deduplication receipt in one SQLite transaction. The example does not make an external email, payment, or second database part of that transaction. Its injected exceptions are controlled simulations, not process-kill or network-partition tests. A production system needs its own failure tests against its actual provider contract.

The runnable file is `examples/reconciliation_lab.py` in the website repository. Before adding a public download link, verify that readers can access the repository or publish a reviewed copy as a public resource. Do not promise an accessible download from a private GitHub URL.

## Run and observed results

Run from the repository root with Python 3.12 or later:

```sh
python examples/reconciliation_lab.py
```

No package installation, network requests, real credentials or provider account are needed. The script uses a fresh temporary SQLite database per test and removes it on cleanup.

Verified September 15 on Windows, Python 3.12.14, SQLite 3.53.1: **7 tests passed in 0.470 seconds**. This is an execution receipt, not a performance benchmark. The first run exposed database connections remaining open during Windows cleanup; closing them explicitly fixed the harness, and all seven tests then passed.

| Executed scenario | Observed assertion |
| --- | --- |
| Lost response after commit; reopen receiver and replay original identity | Same result ID, one write |
| Injected interruption before commit; retry | Zero writes after rollback, one after retry |
| Existing key with changed payload | Conflict, original result retained, one write |
| Four concurrent matching attempts | One result ID, one write |
| New key after lost response | Two writes: duplicate reproduced |
| Delete receipt and reuse original key | Lookup reports UNKNOWN; replay creates a second write |
| Caller has no completion; read-only receiver lookup | Success recovered, one write |

Not tested: actual provider APIs, process termination, host failure, storage corruption, lease/fencing behavior, retention timing, distributed transactions, multi-tenant identity, authentication, concurrent receipt pruning, load or production throughput. The example accepts a narrow JSON payload shape and is not a reusable idempotency library.

## Source verification and article caveats

Reviewed the live article and these primary references on September 15:

- [SQLite transactions](https://www.sqlite.org/lang_transaction.html): SQLite permits one simultaneous writer; BEGIN IMMEDIATE claims the write transaction up front and may encounter contention. This explains the model's serialized receiver boundary, not a cross-service guarantee.
- [Stripe idempotent requests](https://docs.stripe.com/api/idempotent_requests): Stripe retains the first executed result, including errors, compares parameters, and can treat a key as new after its record is pruned. The article should avoid suggesting that every provider behaves identically or that replay always yields success. No Stripe request was executed for this package.
- [Current Journal article](https://blog.rodytech.ai/after-the-timeout-how-to-reconcile-uncertain-api-writes-safely/): the proposed additions complement its existing state and retention discussion. The AWS-specific claims remain part of the wider audit; these checks do not certify all existing citations.

Suggested narrow edit to the current replay paragraph: add “Check the provider's retention window, parameter-matching rules, concurrent-request behavior and treatment of saved error responses first.”

## Draft email

Subject: Before you retry, find out what already happened

A missing response does not tell you whether a write happened. Our next Journal note walks through the evidence to preserve and the decisions that follow: record success, keep waiting, replay within a verified contract, or escalate an unknown outcome.

The offline example makes two duplicate paths visible: a new key after a lost response, and a replay after the receiver's receipt was removed. It also shows matching retries converging inside one database transaction. That is a useful boundary to test, not a universal “exactly once” promise.

[Verified updated article URL]

Which uncertain write is hardest to reconcile in your system?

Use the configured provider's verified sender, consent scope, mailing address, preference and unsubscribe footer. Do not send this draft before newsletter acceptance passes.

## Draft LinkedIn posts

**Post one**

A timeout is the moment to preserve an operation's identity, not create a new one.

In a small offline exercise, the receiver committed a write and then lost its response. Replaying the original key returned the stored result. Retrying with a new key created a duplicate.

Before retrying, check what the receiver guarantees, how long it retains the key, and whether the payload still matches. When evidence is missing, keep the outcome unknown and reconcile it.

[Verified updated article URL; campaign=reconciliation_series, content=identity_post]

**Post two**

“We use idempotency keys” leaves an important question unanswered: for how long?

Our offline example retained the business write, deleted its deduplication receipt, then replayed the same key. A second write was accepted.

That is a deliberately narrow simulation. Your provider has its own retention rules. Make those rules part of the recovery design before a delayed retry discovers them for you.

[Verified updated article URL; campaign=reconciliation_series, content=retention_post]

## Publication and distribution gates

- Apply additions to the existing article after a final editorial pass; do not duplicate the post or change its original date.
- Verify source links and final public resource access. The existing generic incident checklist can complement this example but is not a substitute for provider-specific recovery instructions.
- Confirm the newsletter account and audience consent, then validate campaign links and the unsubscribe journey.
- For a selective DEV crosspost, inspect the account and current canonical-link workflow first; point the canonical URL at the updated Journal article. Do not publish a second version before verifying that relationship.
- Record platform, final URLs, campaign/post identifiers, actual delivery/publication time and results. An uncertain response requires lookup before retrying.
- Flowspace Week 4 card remains incomplete until the updated article, resource and authorized distribution are actually live.
