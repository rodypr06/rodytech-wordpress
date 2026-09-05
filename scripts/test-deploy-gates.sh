#!/usr/bin/env bash
set -euo pipefail

SOURCE_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT
mkdir -p "$TMP/repo/scripts" "$TMP/repo/rodytech-theme" "$TMP/bin"
cp "$SOURCE_ROOT/scripts/deploy-theme.sh" "$TMP/repo/scripts/deploy-theme.sh"
cat > "$TMP/repo/scripts/smoke-test.sh" <<'SH'
#!/usr/bin/env bash
printf 'smoke %s\n' "$*" >> "$DEPLOY_TEST_LOG"
SH
cat > "$TMP/bin/git" <<'SH'
#!/usr/bin/env bash
case "$*" in
  *"rev-parse HEAD"*) printf '%s\n' '0123456789abcdef0123456789abcdef01234567' ;;
  *"status --porcelain"*) : ;;
  *) exit 2 ;;
esac
SH
cat > "$TMP/bin/ssh" <<'SH'
#!/usr/bin/env bash
printf 'ssh %s\n' "$*" >> "$DEPLOY_TEST_LOG"
SH
cat > "$TMP/bin/rsync" <<'SH'
#!/usr/bin/env bash
printf 'rsync %s\n' "$*" >> "$DEPLOY_TEST_LOG"
SH
chmod +x "$TMP/repo/scripts/"*.sh "$TMP/bin/"*

export PATH="$TMP/bin:$PATH"
export DEPLOY_TEST_LOG="$TMP/deploy.log"
export RODYTECH_PRODUCTION_HOST=deploy-host
export RODYTECH_PRODUCTION_THEME_DIR=/var/lib/docker/volumes/wordpress-local_wordpress_data/_data/wp-content/themes/rodytech-theme
export RODYTECH_PRODUCTION_BACKUP_ROOT=/root/theme-backups
export RODYTECH_PRODUCTION_SITE_URL=https://blog.example.test

expect_refusal() {
  : > "$DEPLOY_TEST_LOG"
  if "$TMP/repo/scripts/deploy-theme.sh" "$@" >/dev/null 2>&1; then
    printf 'Expected refusal: %s\n' "$*" >&2
    exit 1
  fi
  [[ ! -s "$DEPLOY_TEST_LOG" ]] || { printf 'Refused deploy reached ssh/rsync: %s\n' "$*" >&2; exit 1; }
}

expect_refusal --production
expect_refusal --production --confirm-production --expected-sha wrong
RODYTECH_PRODUCTION_THEME_DIR=/ expect_refusal --production --confirm-production --expected-sha 0123456789abcdef0123456789abcdef01234567
RODYTECH_PRODUCTION_THEME_DIR="/var/lib/docker/volumes/wordpress-local_wordpress_data/_data/wp-content/themes/rodytech-theme';touch /tmp/nope" expect_refusal --production --confirm-production --expected-sha 0123456789abcdef0123456789abcdef01234567

: > "$DEPLOY_TEST_LOG"
"$TMP/repo/scripts/deploy-theme.sh" --production --confirm-production --expected-sha 0123456789abcdef0123456789abcdef01234567 >/dev/null
PY_LOG="$DEPLOY_TEST_LOG"
if command -v cygpath >/dev/null 2>&1; then
  PY_LOG="$(cygpath -w "$DEPLOY_TEST_LOG")"
fi
python3 - "$PY_LOG" <<'PY'
from pathlib import Path
import sys
lines = Path(sys.argv[1]).read_text().splitlines()
verify = next(i for i, line in enumerate(lines) if line.startswith('ssh ') and 'test -d' in line)
backup = next(i for i, line in enumerate(lines) if line.startswith('ssh ') and 'mkdir -p' in line)
rsync = next(i for i, line in enumerate(lines) if line.startswith('rsync '))
permissions = next(i for i, line in enumerate(lines) if line.startswith('ssh ') and 'chown -R' in line)
smoke = next(i for i, line in enumerate(lines) if line.startswith('smoke '))
assert verify < backup < rsync < permissions < smoke, lines
PY

printf 'deploy_gate_tests_ok\n'
