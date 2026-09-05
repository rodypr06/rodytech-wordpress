#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
LOCAL_THEME_DIR="${REPO_ROOT}/rodytech-theme"
TARGET="staging"
DEPLOY_HOST=""
REMOTE_THEME_DIR=""
REMOTE_BACKUP_ROOT=""
SITE_URL=""
SKIP_SMOKE=0
NO_BACKUP=0
DRY_RUN=0
CONFIRM_PRODUCTION=0
EXPECTED_SHA=""
ALLOWED_THEME_PREFIX=""
ALLOWED_BACKUP_PREFIX=""

fail() {
  printf '%s\n' "$1" >&2
  exit 1
}

validate_host() {
  [[ "$1" =~ ^[A-Za-z0-9][A-Za-z0-9._@-]*$ ]] || fail "Unsafe deployment host."
}

validate_remote_path() {
  local path="$1" prefix="$2" label="$3"
  [[ "$path" == /* && "$path" != "/" ]] || fail "$label must be an absolute non-root path."
  [[ "$prefix" == /* && "$prefix" != "/" ]] || fail "$label requires an approved absolute prefix."
  [[ "$path" =~ ^/[A-Za-z0-9._/-]+$ ]] || fail "$label contains unsafe characters."
  [[ "$prefix" =~ ^/[A-Za-z0-9._/-]+$ ]] || fail "$label prefix contains unsafe characters."
  [[ "$path" != *"/../"* && "$path" != */.. && "$path" != *"/./"* ]] || fail "$label contains traversal segments."
  prefix="${prefix%/}"
  [[ "$path" == "$prefix" || "$path" == "$prefix/"* ]] || fail "$label is outside its approved prefix."
}

usage() {
  cat <<'EOF'
Usage: scripts/deploy-theme.sh [--staging | --production] [options]

Fail-closed deployment for the RodyTech WordPress theme. Staging is the default,
but it requires explicitly configured staging coordinates. Production additionally
requires an acknowledgement, exact commit SHA, clean tree, backup, and smoke test.

Targets:
  --staging              Deploy to staging (default)
  --production           Select production; does not bypass production gates

Options:
  --host HOST             SSH host alias or target
  --remote-dir PATH       Remote theme directory
  --backup-root PATH      Remote backup root
  --site-url URL          Site URL used for smoke tests
  --expected-sha SHA      Required for production; must equal checked-out HEAD
  --confirm-production    Required acknowledgement for production mutation
  --skip-smoke            Staging only
  --no-backup             Staging only
  --dry-run               Preview rsync changes without mutation
  -h, --help              Show this help text

Staging values may also come from RODYTECH_STAGING_HOST,
RODYTECH_STAGING_THEME_DIR, RODYTECH_STAGING_BACKUP_ROOT, and
RODYTECH_STAGING_SITE_URL. Production values may come from the corresponding
RODYTECH_PRODUCTION_* variables.
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --staging) TARGET="staging"; shift ;;
    --production) TARGET="production"; shift ;;
    --host) DEPLOY_HOST="$2"; shift 2 ;;
    --remote-dir) REMOTE_THEME_DIR="$2"; shift 2 ;;
    --backup-root) REMOTE_BACKUP_ROOT="$2"; shift 2 ;;
    --site-url) SITE_URL="$2"; shift 2 ;;
    --expected-sha) EXPECTED_SHA="$2"; shift 2 ;;
    --confirm-production) CONFIRM_PRODUCTION=1; shift ;;
    --skip-smoke) SKIP_SMOKE=1; shift ;;
    --no-backup) NO_BACKUP=1; shift ;;
    --dry-run) DRY_RUN=1; shift ;;
    -h|--help) usage; exit 0 ;;
    *) printf 'Unknown argument: %s\n' "$1" >&2; usage >&2; exit 1 ;;
  esac
done

if [[ "$TARGET" == "production" ]]; then
  DEPLOY_HOST="${DEPLOY_HOST:-${RODYTECH_PRODUCTION_HOST:-}}"
  REMOTE_THEME_DIR="${REMOTE_THEME_DIR:-${RODYTECH_PRODUCTION_THEME_DIR:-}}"
  REMOTE_BACKUP_ROOT="${REMOTE_BACKUP_ROOT:-${RODYTECH_PRODUCTION_BACKUP_ROOT:-/root/theme-backups}}"
  SITE_URL="${SITE_URL:-${RODYTECH_PRODUCTION_SITE_URL:-https://blog.rodytech.ai}}"
  ALLOWED_THEME_PREFIX="${RODYTECH_PRODUCTION_ALLOWED_THEME_PREFIX:-/var/lib/docker/volumes/wordpress-local_wordpress_data/_data/wp-content/themes}"
  ALLOWED_BACKUP_PREFIX="${RODYTECH_PRODUCTION_ALLOWED_BACKUP_PREFIX:-/root/theme-backups}"
else
  DEPLOY_HOST="${DEPLOY_HOST:-${RODYTECH_STAGING_HOST:-}}"
  REMOTE_THEME_DIR="${REMOTE_THEME_DIR:-${RODYTECH_STAGING_THEME_DIR:-}}"
  REMOTE_BACKUP_ROOT="${REMOTE_BACKUP_ROOT:-${RODYTECH_STAGING_BACKUP_ROOT:-/root/theme-backups/staging}}"
  SITE_URL="${SITE_URL:-${RODYTECH_STAGING_SITE_URL:-}}"
  ALLOWED_THEME_PREFIX="${RODYTECH_STAGING_ALLOWED_THEME_PREFIX:-}"
  ALLOWED_BACKUP_PREFIX="${RODYTECH_STAGING_ALLOWED_BACKUP_PREFIX:-/root/theme-backups/staging}"
fi

for value_name in DEPLOY_HOST REMOTE_THEME_DIR REMOTE_BACKUP_ROOT SITE_URL ALLOWED_THEME_PREFIX ALLOWED_BACKUP_PREFIX; do
  if [[ -z "${!value_name}" ]]; then
    printf '%s is required for the %s target.\n' "$value_name" "$TARGET" >&2
    exit 1
  fi
done

validate_host "$DEPLOY_HOST"
validate_remote_path "$REMOTE_THEME_DIR" "$ALLOWED_THEME_PREFIX" "REMOTE_THEME_DIR"
validate_remote_path "$REMOTE_BACKUP_ROOT" "$ALLOWED_BACKUP_PREFIX" "REMOTE_BACKUP_ROOT"
[[ "${REMOTE_THEME_DIR##*/}" == "rodytech-theme" ]] || fail "REMOTE_THEME_DIR must end in /rodytech-theme."
[[ "$SITE_URL" =~ ^https?://[A-Za-z0-9._:/-]+$ ]] || fail "SITE_URL contains unsafe characters."

if [[ ! -d "$LOCAL_THEME_DIR" ]]; then
  printf 'Local theme directory not found: %s\n' "$LOCAL_THEME_DIR" >&2
  exit 1
fi

for command_name in git ssh rsync; do
  command -v "$command_name" >/dev/null 2>&1 || {
    printf '%s is required but not installed.\n' "$command_name" >&2
    exit 1
  }
done

HEAD_SHA="$(git -C "$REPO_ROOT" rev-parse HEAD)"
if [[ "$TARGET" == "production" ]]; then
  if [[ "$CONFIRM_PRODUCTION" -ne 1 ]]; then
    printf 'Production deploy refused: pass --confirm-production.\n' >&2
    exit 1
  fi
  if [[ -z "$EXPECTED_SHA" || "$EXPECTED_SHA" != "$HEAD_SHA" ]]; then
    printf 'Production deploy refused: --expected-sha must equal HEAD (%s).\n' "$HEAD_SHA" >&2
    exit 1
  fi
  if [[ -n "$(git -C "$REPO_ROOT" status --porcelain)" ]]; then
    printf 'Production deploy refused: working tree is not clean.\n' >&2
    exit 1
  fi
  if [[ "$NO_BACKUP" -eq 1 || "$SKIP_SMOKE" -eq 1 ]]; then
    printf 'Production deploy refused: backup and smoke tests are mandatory.\n' >&2
    exit 1
  fi
fi

if [[ "$DRY_RUN" -eq 1 ]]; then
  NO_BACKUP=1
fi

printf '==> Target: %s\n' "$TARGET"
printf '==> Commit: %s\n' "$HEAD_SHA"
printf '==> Verifying remote target on %s\n' "$DEPLOY_HOST"
ssh -o BatchMode=yes "$DEPLOY_HOST" "test -d '${REMOTE_THEME_DIR}'"

TIMESTAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="${REMOTE_BACKUP_ROOT}/${TIMESTAMP}"
if [[ "$NO_BACKUP" -eq 0 ]]; then
  printf '==> Creating remote backup: %s\n' "$BACKUP_DIR"
  ssh -o BatchMode=yes "$DEPLOY_HOST" "mkdir -p '${BACKUP_DIR}' && cp -a '${REMOTE_THEME_DIR}/.' '${BACKUP_DIR}/'"
fi

RSYNC_ARGS=(-az --human-readable --itemize-changes --omit-dir-times)
if [[ "$DRY_RUN" -eq 1 ]]; then
  RSYNC_ARGS+=(--dry-run)
  printf '==> Dry run enabled; no files will be changed\n'
fi

printf '==> Syncing theme to %s:%s\n' "$DEPLOY_HOST" "$REMOTE_THEME_DIR"
rsync "${RSYNC_ARGS[@]}" "${LOCAL_THEME_DIR}/" "${DEPLOY_HOST}:${REMOTE_THEME_DIR}/"

if [[ "$DRY_RUN" -eq 0 ]]; then
  ssh -o BatchMode=yes "$DEPLOY_HOST" "chown -R root:root '${REMOTE_THEME_DIR}' && find '${REMOTE_THEME_DIR}' -type d -exec chmod 755 {} + && find '${REMOTE_THEME_DIR}' -type f -exec chmod 644 {} +"
fi

if [[ "$SKIP_SMOKE" -eq 0 ]]; then
  "$SCRIPT_DIR/smoke-test.sh" --site-url "$SITE_URL"
fi

printf 'Deploy complete: target=%s commit=%s\n' "$TARGET" "$HEAD_SHA"
if [[ "$NO_BACKUP" -eq 0 ]]; then
  printf 'Backup: %s\n' "$BACKUP_DIR"
fi
