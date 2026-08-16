# AMP Engineering Portfolio

Source for [amp-engr.com](https://amp-engr.com/). The `main` branch preserves the legacy PHP site in Git history; `wordpress-migration` transitions the working tree to custom WordPress source for staged deployment.

## Repository layout

- `wp-content/themes/amp-portfolio/` — lightweight native WordPress block theme.
- `wp-content/plugins/amp-portfolio-core/` — portable Projects, Research, metadata, redirects, and SEO essentials.
- `tools/import-content.php` — idempotent WP-CLI content importer.
- `tools/validate.ps1` — repository safety and static validation.
- `docs/` — hosting, staging, deployment, and rollback instructions.
- `downloads/` — legacy public paper URLs retained for link compatibility.

WordPress core, `wp-config.php`, uploads, databases, credentials, logs, caches, and backups must never be committed.

## Local validation

```powershell
pwsh -File tools/validate.ps1
```

If PHP 8.1 or newer is installed:

```powershell
Get-ChildItem -Recurse -Filter *.php wp-content,tools | ForEach-Object { php -l $_.FullName }
```

## Migration

Follow [docs/MIGRATION.md](docs/MIGRATION.md). Do not replace the live PHP site until staging passes the acceptance checklist.
