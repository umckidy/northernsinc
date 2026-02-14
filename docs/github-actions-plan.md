# Northerns Inc GitHub Actions Plan

## What to commit

- `site/wp-content/themes/catch-flames-child/**` (custom theme code)
- `.github/workflows/**` (CI/deploy/backup automation)
- `docs/**` and `scripts/**` (runbooks and deployment helpers)

## What to keep out of git

- Any file with real passwords, API keys, private keys, or DB dumps
- WordPress runtime and generated content (`uploads`, logs, caches)
- `wp-config.php`

## Deployment model

Use GitHub Actions to deploy the active WordPress theme to the VM over SSH + rsync.

1. Developer pushes to `main`
2. `deploy-prod.yml` runs in GitHub Actions
3. Workflow connects to VM and syncs `site/wp-content/themes/catch-flames/`
4. Workflow fixes ownership/permissions and verifies HTTPS response

## NGINX config management

The production NGINX site config is tracked under `infra/nginx/sites-available/northernsinc.org.prod` and deployed via `Deploy NGINX Config`.

- The workflow uploads the file to `/tmp/northernsinc.org.prod` and then installs it into `/etc/nginx/sites-available/northernsinc.org.prod`
- It runs `nginx -t` before reloading
- It keeps a timestamped backup under `/etc/nginx/sites-available/backup-<timestamp>/`

## Required GitHub Secrets

Add these in repository or `production` environment secrets:

- `VM_HOST` (example: `34.x.x.x`)
- `VM_USER` (example: `umckidy`)
- `VM_PORT` (usually `22`)
- `VM_SSH_KEY` (private key for deployment user)
- `VM_KNOWN_HOSTS` (output from `ssh-keyscan -H <host>`)

## Branch protections

- Protect `main`
- Require pull request before merge (recommended)
- Require CI status checks to pass
- Use `production` environment approval gate for deploys (recommended)

## First-time dry run checklist

1. Add secrets in GitHub
2. Run CI workflow manually or by PR
3. Run deploy workflow with `workflow_dispatch`
4. Confirm child theme files updated on VM
5. Confirm site health with `curl -I https://northernsinc.org`
