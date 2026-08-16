<?php
/**
 * Safe configuration example. Copy values from environment variables or the
 * host's secret manager; never place real credentials in this repository.
 */

declare(strict_types=1);

define('WP_ENVIRONMENT_TYPE', getenv('WP_ENVIRONMENT_TYPE') ?: 'staging');
define('WP_DEBUG', filter_var(getenv('WP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOL));
define('WP_DEBUG_DISPLAY', false);
define('DISALLOW_FILE_EDIT', true);
define('FORCE_SSL_ADMIN', true);

// Database constants and authentication salts belong in the host's protected
// configuration and are intentionally omitted.
