# RodyTech Blog Deployment Workflow

The theme deploy path is intentionally fail-closed. Running the deploy script with no configured staging target cannot touch production.

## Targets

### Staging (default)

Provide the staging coordinates through environment variables or matching command-line options:

```bash
export RODYTECH_STAGING_HOST=<ssh-alias>
export RODYTECH_STAGING_THEME_DIR=<staging-theme-path>
export RODYTECH_STAGING_SITE_URL=<staging-url>
./scripts/deploy-theme.sh --staging
```

A dry run is available:

```bash
./scripts/deploy-theme.sh --staging --dry-run
```

### Production

Production requires every gate below:

1. `--production`
2. `--confirm-production`
3. `--expected-sha` exactly matching the checked-out commit
4. A clean Git working tree
5. A successful pre-deploy backup
6. Post-deploy smoke tests

```bash
export RODYTECH_PRODUCTION_HOST=<ssh-alias>
export RODYTECH_PRODUCTION_THEME_DIR=<production-theme-path>
./scripts/deploy-theme.sh \
  --production \
  --confirm-production \
  --expected-sha "$(git rev-parse HEAD)"
```

Production refuses `--no-backup` and `--skip-smoke`. Founder approval remains an operational prerequisite; CLI gates do not replace that approval.

## What the deploy does

1. Validates the target and required tools.
2. Records the exact Git commit being deployed.
3. Verifies the remote theme directory exists.
4. Creates a timestamped backup unless staging explicitly opts out or the command is a dry run.
5. Synchronizes only the theme directory.
6. Normalizes theme ownership and permissions.
7. Runs route smoke tests unless staging explicitly opts out.

The script does not deploy plugins, uploads, database content, credentials, ads, or publisher configuration.

## Smoke coverage

`scripts/smoke-test.sh` verifies:

- `/` returns 200 and the editorial homepage marker.
- `/articles/` returns 200 and the archive marker.
- Search returns 200 with the search form.
- A deliberately missing route returns the theme’s 404 page.
- WordPress REST returns the current latest post.
- The latest post URL returns 200 with article content.

The latest post is discovered through WordPress REST rather than depending on a hardcoded slug.

## Rollback

Each standard deployment prints its timestamped backup directory. Restore that directory to the configured theme target, normalize ownership and permissions, and rerun the smoke suite. Do not combine rollback with database, plugin, or publisher changes.
