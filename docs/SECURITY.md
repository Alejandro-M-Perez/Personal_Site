# Security and repository policy

- Never commit passwords, database credentials, authentication tokens, private keys, WordPress salts, `.env` values, database exports, backups, or production logs.
- Keep WordPress core and plugins updated. This repository intentionally tracks only the custom theme and site plugin.
- Keep `WP_DEBUG_DISPLAY` disabled in every public environment. Send logs to a non-public location when diagnostic logging is required.
- Use least-privilege WordPress accounts, unique passwords, multifactor authentication where the host supports it, and HTTPS-only administration.
- Review all third-party plugins before installation. The initial site requires no page builder, contact form, analytics, or SEO plugin.
- Test both file and database restoration on staging before relying on a backup system.
- Do not place timestamped archives or database dumps inside the repository directory, even if `.gitignore` would exclude them.
