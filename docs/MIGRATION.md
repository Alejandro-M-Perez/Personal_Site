# WordPress migration runbook

## 1. Hosting preflight

Confirm these capabilities with the current hosting provider before changing production:

- A currently supported WordPress release and a PHP version supported by that release (PHP 8.1+ is required by this custom code).
- MySQL or MariaDB supported by the selected WordPress release.
- HTTPS on both staging and production.
- A staging site or isolated subdomain protected from public indexing.
- Scheduled file and database backups with a tested restore path.
- Automatic WordPress security updates and a way to review PHP/server error logs privately.
- SSH/SFTP or an equivalent controlled deployment path.

If any item is unavailable, stop and select a WordPress-capable host before migration.

## 2. Back up the live site

1. Create a timestamped archive of the live document root outside this repository.
2. Record the active PHP version, domain settings, redirects, and DNS values.
3. Verify both PDFs under `/downloads/` are present in the backup.
4. Restore the archive to a temporary directory and verify that it opens before continuing.

Keep this backup for at least one week after production cutover.

## 3. Prepare staging

1. Install a fresh, supported WordPress release on the staging domain.
2. Set `WP_ENVIRONMENT_TYPE` to `staging`, and set `WP_DEBUG_DISPLAY` to `false`.
3. Copy only these repository paths into staging:
   - `wp-content/themes/amp-portfolio`
   - `wp-content/plugins/amp-portfolio-core`
   - `tools/import-content.php` temporarily, outside the public web root when possible
4. Keep the existing `/downloads/` files at their current public paths so old links continue working.
5. Activate **AMP Portfolio Core**, then **AMP Engineering Portfolio**.
6. Set permalinks to **Post name** and save.
7. Run `wp eval-file tools/import-content.php` from the WordPress root.
8. Delete the staging copy of the importer after it succeeds.
9. In Settings → Reading, confirm Home is the static front page.
10. Upload a square 512×512 site icon in Settings → General. Until then, the theme supplies an SVG favicon.

Non-production environments receive `noindex,nofollow` automatically from the site plugin.

## 4. Content review

Before production, verify rather than infer:

- Graduation status and degree wording.
- Any Experience entry that still says “Present.”
- Employment dates, role titles, and organization names.
- Repository URLs, especially the existing `Mujocu_Robot_Leg` spelling.
- Project results, metrics, screenshots, and media that are not present in the legacy source.
- Paper titles, authorship, publication venue, and publication dates.

Remove the visible staging content-review notice only after these facts are confirmed.

## 5. Acceptance checks

- Check 320, 390, 768, and 1365px viewport widths with no horizontal document overflow.
- Navigate every page and menu using only the keyboard.
- Confirm the responsive menu exposes named controls and traps no focus.
- Check heading order, focus visibility, image alternatives, link names, and contrast.
- Verify Home, Experience, Project/Research archives and details, PDFs, social links, favicon, XML sitemap, robots output, canonical URLs, social metadata, and the 404 template.
- Confirm redirects from `/?s=home`, `/?s=research`, `/?s=projects`, `/?s=experience`, and `/projects/ai-project-manager`.
- Run Lighthouse on Home, one Project, and one Research entry on mobile and desktop. Target at least 90 in Performance, Accessibility, Best Practices, and SEO.
- Confirm public responses contain no PHP warnings, stack traces, debug toolbar, or directory listings.

## 6. Production cutover

1. Create fresh file and database backups immediately before cutover.
2. Deploy the verified custom theme and plugin to a clean production WordPress installation.
3. Import the reviewed staging database or repeat the idempotent importer and approved content edits.
4. Set `WP_ENVIRONMENT_TYPE` to `production`; keep `WP_DEBUG_DISPLAY` disabled.
5. Preserve `/downloads/` and apply the legacy redirects.
6. Clear application, host, and CDN caches.
7. Repeat link, redirect, responsive, metadata, and Lighthouse checks against production.

## 7. Rollback

If production authentication, content, routing, PDFs, or primary pages fail:

1. Stop further writes to WordPress.
2. Restore the previous document root and DNS/routing configuration.
3. Restore the pre-cutover database if WordPress replaced an existing database.
4. Clear caches and verify the legacy Home, Research, Projects, and Experience pages.
5. Record the failure and correct it in staging before another cutover.
