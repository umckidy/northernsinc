# Northerns Inc Website Ops

This repo is the source of truth for:

- The active WordPress theme code deployed to the VM
- The production NGINX site config deployed to the VM
- GitHub Actions workflows that lint, deploy, and create weekly backups

It intentionally does not track WordPress core, database contents, or media uploads.

## What is tracked

- Active theme: `site/wp-content/themes/catch-flames/`
- NGINX prod config: `infra/nginx/sites-available/northernsinc.org.prod`
- Workflows: `.github/workflows/`
- Runbooks/docs: `docs/`

## Workflow diagram

```mermaid
flowchart TD
  Dev[Developer] -->|push to main| GitHub[(GitHub Repo)]

  GitHub --> CI[CI: PHP lint\n.github/workflows/ci.yml]

  GitHub --> DeployTheme[Deploy Production Theme\n.github/workflows/deploy-prod.yml]
  DeployTheme -->|SSH + rsync| VMTheme[/VM: /var/www/html/northernsinc.org/wp-content/themes/catch-flames/]
  DeployTheme -->|health check| Site[https://northernsinc.org]

  GitHub --> DeployNginx[Deploy NGINX Config\n.github/workflows/deploy-nginx.yml]
  DeployNginx -->|SSH + install| VMNginx[/VM: /etc/nginx/sites-available/northernsinc.org.prod/]
  DeployNginx -->|nginx -t + reload| Nginx[nginx]

  GitHub --> Backup[Backup Weekly\n.github/workflows/backup-weekly.yml]
  Backup -->|SSH runs mysqldump + tar| Backups[/VM: /var/backups/northernsinc/]
```

## GitHub Secrets required

Configured in GitHub Actions secrets:

- `VM_HOST`
- `VM_USER`
- `VM_PORT`
- `VM_SSH_KEY`
- `VM_KNOWN_HOSTS`

## Restore

See `docs/restore.md` for fast recovery steps.
