# AMP Portfolio Core

Site-specific functionality for `amp-engr.com`. Keeping this functionality in a plugin means Projects and Research remain available when the theme changes.

## Features

- `amp_project` and `amp_research` public content types with clean archives and REST/block-editor support.
- Native metadata fields for technologies, collaborators, dates, repositories, external projects, and papers.
- Server-rendered metadata and social-link blocks.
- Permanent redirects from the legacy `?s=` navigation.
- Basic canonical, Open Graph, Twitter Card, and JSON-LD output.
- Automatic `noindex,nofollow` outside the WordPress `production` environment.

## Required activation order

1. Activate **AMP Portfolio Core**.
2. Activate **AMP Engineering Portfolio**.
3. Set permalinks to **Post name** and save once.
4. Run the content importer described in `docs/MIGRATION.md`.
