# Restore Runbook (Northerns Inc)

This repo manages:

- WordPress themes:
  - `/var/www/html/northernsinc.org/wp-content/themes/catch-flames/` (baseline)
  - `/var/www/html/northernsinc.org/wp-content/themes/northernsinc-modern/` (modern child)
- NGINX prod site config: `/etc/nginx/sites-available/northernsinc.org.prod`
- Nightly backups written on VM: `/var/backups/northernsinc/`

This runbook is optimized for quick recovery after a bad change.

## Quick checks

On your machine:

```bash
curl -fI https://northernsinc.org
```

On the VM:

```bash
ssh grandpa "sudo nginx -t && sudo systemctl status nginx --no-pager -l | head"
```

List available backups:

```bash
ssh grandpa "sudo ls -lah /var/backups/northernsinc | tail -n 50"
```

## Restore 1: Theme (appearance/code)

Use this if a theme edit broke the layout or caused PHP errors.

If you want to compare looks, you can switch between themes in WordPress Admin:

- Appearance -> Themes -> activate either `Catch Flames` (baseline) or `Northerns Inc Modern` (child)

Recommended (safe) rollback path:

1. Revert the bad commit in git:

```bash
git log --oneline -20
git revert <bad_commit_sha>
git push origin main
```

2. GitHub Actions deploy will run automatically. You can also manually run:

- GitHub -> Actions -> `Deploy Production Theme` -> `Run workflow`

## Restore 2: NGINX config (redirects/502/SSL)

Use this if a config change caused redirect loops, 502 errors, or TLS issues.

1. Revert the bad commit in git:

```bash
git log --oneline -20
git revert <bad_commit_sha>
git push origin main
```

2. Run the deploy workflow:

- GitHub -> Actions -> `Deploy NGINX Config` -> `Run workflow`

3. Validate:

```bash
ssh grandpa "sudo nginx -t"
curl -fI https://northernsinc.org
```

## Restore 3: Database (content, menus, settings)

Use this if WP Admin changes broke menus/widgets/customizer settings, or content was deleted.

Pick the last known good dump:

- `/var/backups/northernsinc/northernsinc_db_<YYYY-MM-DD_HHMMSS>.sql.gz`

Restore command (on VM):

```bash
ssh grandpa "set -euo pipefail; \
  f=/var/backups/northernsinc/northernsinc_db_<TIMESTAMP>.sql.gz; \
  sudo test -f \"$f\"; \
  sudo zcat \"$f\" | sudo mysql northernsinc_db"
```

Validate:

```bash
curl -fI https://northernsinc.org
```

Notes:

- This overwrites DB state with the dump contents.
- If you want a safety copy of the current DB first:

```bash
ssh grandpa "ts=$(date +%F_%H%M%S); sudo mysqldump northernsinc_db | gzip > /var/backups/northernsinc/pre_restore_${ts}.sql.gz"
```

## Restore 4: Uploads (media library)

Use this if images/PDFs are missing.

Pick the last known good archive:

- `/var/backups/northernsinc/northernsinc_uploads_<YYYY-MM-DD_HHMMSS>.tar.gz`

Restore command (on VM):

```bash
ssh grandpa "set -euo pipefail; \
  ts=$(date +%F_%H%M%S); \
  f=/var/backups/northernsinc/northernsinc_uploads_<TIMESTAMP>.tar.gz; \
  sudo test -f \"$f\"; \
  sudo mv /var/www/html/northernsinc.org/wp-content/uploads /var/www/html/northernsinc.org/wp-content/uploads.bak_${ts}; \
  sudo tar -xzf \"$f\" -C /var/www/html/northernsinc.org/wp-content; \
  sudo chown -R www-data:www-data /var/www/html/northernsinc.org/wp-content/uploads"
```

Validate:

```bash
curl -fI https://northernsinc.org
```

Notes:

- The archive contains an `uploads/` folder; extraction target is `wp-content/`.
- This renames current uploads to a timestamped backup first.

## Common recovery combos

- Theme looks wrong after code edit: Restore 1
- Site looks wrong after WP Admin edits: Restore 3 (DB)
- Images/PDFs missing: Restore 4 (uploads)
- Redirect loop / 502: Restore 2 (NGINX)
