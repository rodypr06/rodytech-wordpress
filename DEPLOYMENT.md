# RodyTech Blog Deployment Workflow

This repo now includes a repeatable deployment path for the live WordPress theme on `helix-worker`.

## Scope

- Local theme source: `rodytech-theme/`
- Remote host: `helix-worker`
- Live theme path:
  `/var/lib/docker/volumes/wordpress-local_wordpress_data/_data/wp-content/themes/rodytech-theme/`
- Public site:
  `https://blog.rodytech.ai`

The workflow is intentionally conservative:

- It creates a remote backup before deploy.
- It syncs files with `rsync` but does **not** use `--delete`.
- It runs public smoke tests after deploy.

That choice avoids accidentally removing remote files during concurrent work. If you intentionally need a remote cleanup, do it as a separate, explicit step.

## Files

- `scripts/deploy-theme.sh`
  Main deployment entrypoint.
- `scripts/smoke-test.sh`
  Public route smoke checks for the live site.

## Requirements

- Local `ssh`
- Local `rsync`
- Local `curl`
- SSH access to `helix-worker`

## Standard Deploy

From the repo root:

```bash
./scripts/deploy-theme.sh
```

This does:

1. Verifies the remote theme path exists.
2. Creates a remote backup under `/root/theme-backups/<timestamp>/`.
3. Syncs `rodytech-theme/` to the live theme path.
4. Normalizes remote ownership and permissions.
5. Runs smoke tests against `https://blog.rodytech.ai`.

## Dry Run

Preview the sync before deploying:

```bash
./scripts/deploy-theme.sh --dry-run --skip-smoke
```

## Smoke Tests Only

Run the public checks without deploying:

```bash
./scripts/smoke-test.sh
```

Current smoke coverage:

- `/`
- `/articles`
- `/category/artificial-intelligence/`
- `/?s=ansible`

These routes were chosen because they are stable public surfaces and do not depend on workstreams that are still in progress, such as author routing.

## Useful Options

Deploy to a different host alias:

```bash
./scripts/deploy-theme.sh --host some-other-host
```

Deploy to a different public URL:

```bash
./scripts/deploy-theme.sh --site-url https://staging.example.com
```

Skip backup or smoke tests when you have a specific reason:

```bash
./scripts/deploy-theme.sh --no-backup --skip-smoke
```

Those flags should be exceptional, not the default path.

## Rollback

Every standard deploy prints the backup directory it created, for example:

```text
Backup: /root/theme-backups/20260420-184500
```

To roll back manually on `helix-worker`:

```bash
ssh helix-worker
cp -a /root/theme-backups/20260420-184500/. /var/lib/docker/volumes/wordpress-local_wordpress_data/_data/wp-content/themes/rodytech-theme/
chown -R root:root /var/lib/docker/volumes/wordpress-local_wordpress_data/_data/wp-content/themes/rodytech-theme/
find /var/lib/docker/volumes/wordpress-local_wordpress_data/_data/wp-content/themes/rodytech-theme/ -type d -exec chmod 755 {} +
find /var/lib/docker/volumes/wordpress-local_wordpress_data/_data/wp-content/themes/rodytech-theme/ -type f -exec chmod 644 {} +
```

Then re-run:

```bash
./scripts/smoke-test.sh
```

## Recommended Operator Sequence

1. Review local changes:
   `git status --short`
2. Dry run the deploy:
   `./scripts/deploy-theme.sh --dry-run --skip-smoke`
3. Perform the real deploy:
   `./scripts/deploy-theme.sh`
4. If smoke tests fail, stop and inspect before making another deploy.

## Notes

- This workflow deploys only theme files. It does not handle database migrations, uploads, plugins, or WordPress settings.
- If a future workstream introduces new critical public routes, add them to `scripts/smoke-test.sh`.
