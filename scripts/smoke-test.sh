#!/usr/bin/env bash

set -euo pipefail

SITE_URL="https://blog.rodytech.ai"
TIMEOUT_SECONDS=20

usage() {
  cat <<'EOF'
Usage: scripts/smoke-test.sh [options]

Run public smoke tests against the RodyTech blog.

Options:
  --site-url URL          Base site URL (default: https://blog.rodytech.ai)
  --timeout SECONDS       curl timeout per request (default: 20)
  -h, --help              Show this help text
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --site-url)
      SITE_URL="$2"
      shift 2
      ;;
    --timeout)
      TIMEOUT_SECONDS="$2"
      shift 2
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

if ! command -v curl >/dev/null 2>&1; then
  printf 'curl is required but not installed.\n' >&2
  exit 1
fi

check_route() {
  local path="$1"
  local marker="$2"
  local url="${SITE_URL%/}${path}"
  local body

  printf 'Checking %s ... ' "${url}"
  body="$(curl -fsSL -L --max-time "${TIMEOUT_SECONDS}" "${url}")"

  if [[ "${body}" != *"${marker}"* ]]; then
    printf 'FAIL\n' >&2
    printf 'Expected marker not found: %s\n' "${marker}" >&2
    exit 1
  fi

  printf 'OK\n'
}

check_route "/" "editorial-hero"
check_route "/" "story-grid"
check_route "/articles" "editorial-hero editorial-hero-archive"
check_route "/category/artificial-intelligence/" "Category"
check_route "/?s=ansible" "Search results"

printf 'Smoke tests passed for %s\n' "${SITE_URL}"
