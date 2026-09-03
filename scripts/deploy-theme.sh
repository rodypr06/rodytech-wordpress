#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
LOCAL_THEME_DIR="${REPO_ROOT}/rodytech-theme"

DEPLOY_HOST="helix-worker"
REMOTE_THEME_DIR="/var/lib/docker/volumes/wordpress-local_wordpress_data/_data/wp-content/themes/rodytech-theme"
REMOTE_BACKUP_ROOT="/root/theme-backups"
SITE_URL="https://blog.rodytech.ai"
SKIP_SMOKE=0
NO_BACKUP=0
DRY_RUN=0

usage() {
  cat <<'EOF'
Usage: scripts/deploy-theme.sh [options]

Deploy the local rodytech-theme directory to the live WordPress theme path on helix-worker.

Options:
  --host HOST             SSH host alias or target (default: helix-worker)
  --remote-dir PATH       Remote theme directory
  --backup-root PATH      Remote backup root (default: /root/theme-backups)
  --site-url URL          Public site URL used for smoke tests
  --skip-smoke            Skip post-deploy smoke tests
  --no-backup             Skip the remote backup step
  --dry-run               Show planned rsync changes without deploying
  -h, --help              Show this help text

Notes:
  - Deploy uses rsync without --delete to avoid removing concurrent remote additions.
  - Smoke tests call scripts/smoke-test.sh unless --skip-smoke is set.
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --host)
      DEPLOY_HOST="$2"
      shift 2
      ;;
    --remote-dir)
      REMOTE_THEME_DIR="$2"
      shift 2
      ;;
    --backup-root)
      REMOTE_BACKUP_ROOT="$2"
      shift 2
      ;;
    --site-url)
      SITE_URL="$2"
      shift 2
      ;;
    --skip-smoke)
      SKIP_SMOKE=1
      shift
      ;;
    --no-backup)
      NO_BACKUP=1
      shift
      ;;
    --dry-run)
      DRY_RUN=1
      shift
      ;;
    -h|--help)
      usage
      exit 0
      ;;
    *)
      printf 'Unknown argument: %s\n' "$1" >&2
      usage >&2
      exit 1
      ;;
  esac
done

if [[ ! -d "${LOCAL_THEME_DIR}" ]]; then
  printf 'Local theme directory not found: %s\n' "${LOCAL_THEME_DIR}" >&2
  exit 1
fi

if ! command -v ssh >/dev/null 2>&1; then
  printf 'ssh is required but not installed.\n' >&2
  exit 1
fi

if ! command -v rsync >/dev/null 2>&1; then
  printf 'rsync is required but not installed.\n' >&2
  exit 1
fi

if [[ "${DRY_RUN}" -eq 1 ]]; then
  NO_BACKUP=1
fi

printf '==> Verifying remote target on %s\n' "${DEPLOY_HOST}"
ssh -o BatchMode=yes "${DEPLOY_HOST}" "test -d '${REMOTE_THEME_DIR}'"

TIMESTAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="${REMOTE_BACKUP_ROOT}/${TIMESTAMP}"

if [[ "${NO_BACKUP}" -eq 0 ]]; then
  printf '==> Creating remote backup: %s\n' "${BACKUP_DIR}"
  ssh "${DEPLOY_HOST}" "mkdir -p '${BACKUP_DIR}' && cp -a '${REMOTE_THEME_DIR}/.' '${BACKUP_DIR}/'"
else
  printf '==> Skipping remote backup\n'
fi

RSYNC_ARGS=(
  -az
  --human-readable
  --itemize-changes
  --omit-dir-times
)

if [[ "${DRY_RUN}" -eq 1 ]]; then
  RSYNC_ARGS+=(--dry-run)
  printf '==> Dry run enabled; no files will be changed\n'
fi

printf '==> Syncing local theme to %s:%s\n' "${DEPLOY_HOST}" "${REMOTE_THEME_DIR}"
rsync "${RSYNC_ARGS[@]}" "${LOCAL_THEME_DIR}/" "${DEPLOY_HOST}:${REMOTE_THEME_DIR}/"

if [[ "${DRY_RUN}" -eq 0 ]]; then
  printf '==> Normalizing remote permissions\n'
  ssh "${DEPLOY_HOST}" "
    chown -R root:root '${REMOTE_THEME_DIR}' &&
    find '${REMOTE_THEME_DIR}' -type d -exec chmod 755 {} + &&
    find '${REMOTE_THEME_DIR}' -type f -exec chmod 644 {} +
  "
fi

if [[ "${SKIP_SMOKE}" -eq 0 ]]; then
  printf '==> Running smoke tests against %s\n' "${SITE_URL}"
  "${SCRIPT_DIR}/smoke-test.sh" --site-url "${SITE_URL}"
else
  printf '==> Skipping smoke tests\n'
fi

printf '\nDeploy complete.\n'
printf 'Host: %s\n' "${DEPLOY_HOST}"
printf 'Remote theme dir: %s\n' "${REMOTE_THEME_DIR}"
if [[ "${NO_BACKUP}" -eq 0 ]]; then
  printf 'Backup: %s\n' "${BACKUP_DIR}"
fi
