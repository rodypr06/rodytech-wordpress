#!/usr/bin/env bash

set -euo pipefail

SITE_URL="https://blog.rodytech.ai"
TIMEOUT_SECONDS=20

usage() {
  cat <<'EOF'
Usage: scripts/smoke-test.sh [options]

Options:
  --site-url URL          Base site URL (default: https://blog.rodytech.ai)
  --timeout SECONDS       curl timeout per request (default: 20)
  -h, --help              Show this help text
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --site-url) SITE_URL="$2"; shift 2 ;;
    --timeout) TIMEOUT_SECONDS="$2"; shift 2 ;;
    -h|--help) usage; exit 0 ;;
    *) printf 'Unknown argument: %s\n' "$1" >&2; usage >&2; exit 1 ;;
  esac
done

command -v curl >/dev/null 2>&1 || { printf 'curl is required.\n' >&2; exit 1; }

check_route() {
  local name="$1" path="$2" expected_code="$3" marker="$4"
  local body code
  body="$(mktemp)"
  code="$(curl -sS -L --max-time "$TIMEOUT_SECONDS" -o "$body" -w '%{http_code}' "${SITE_URL%/}${path}")"
  if [[ "$code" != "$expected_code" ]] || ! grep -Fq "$marker" "$body"; then
    rm -f "$body"
    printf '%s FAIL (HTTP %s; expected %s and marker %s)\n' "$name" "$code" "$expected_code" "$marker" >&2
    exit 1
  fi
  rm -f "$body"
  printf '%s OK (HTTP %s)\n' "$name" "$code"
}

check_route home / 200 editorial-hero
check_route articles /articles/ 200 editorial-hero-archive
check_route search '/?s=ansible' 200 archive-search-form
check_route not-found /definitely-missing-rodytech-route/ 404 'Page not found'

LATEST_JSON="$(mktemp)"
trap 'rm -f "$LATEST_JSON"' EXIT
curl -fsSL --max-time "$TIMEOUT_SECONDS" "${SITE_URL%/}/wp-json/wp/v2/posts?per_page=1&_fields=link" > "$LATEST_JSON"
LATEST_LINK="$(python3 -c 'import json,sys; rows=json.load(open(sys.argv[1])); print(rows[0]["link"] if rows else "")' "$LATEST_JSON")"
if [[ -z "$LATEST_LINK" ]]; then
  printf 'single FAIL (no published post returned by REST)\n' >&2
  exit 1
fi
SINGLE_BODY="$(mktemp)"
SINGLE_CODE="$(curl -sS -L --max-time "$TIMEOUT_SECONDS" -o "$SINGLE_BODY" -w '%{http_code}' "$LATEST_LINK")"
if [[ "$SINGLE_CODE" != "200" ]] || ! grep -Fq 'article-content' "$SINGLE_BODY"; then
  rm -f "$SINGLE_BODY"
  printf 'single FAIL (HTTP %s)\n' "$SINGLE_CODE" >&2
  exit 1
fi
rm -f "$SINGLE_BODY"
printf 'single OK (HTTP 200)\n'
printf 'Smoke tests passed for %s\n' "$SITE_URL"
