<?php
/**
 * Plugin Name: AMP Portfolio Core
 * Description: Portable Projects, Research, metadata, redirects, and SEO essentials for amp-engr.com.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 8.1
 * Author: Alejandro Perez
 * License: GPL-2.0-or-later
 * Text Domain: amp-portfolio-core
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

define('AMP_PORTFOLIO_CORE_VERSION', '1.0.0');
define('AMP_PORTFOLIO_CORE_DIR', plugin_dir_path(__FILE__));

require_once AMP_PORTFOLIO_CORE_DIR . 'includes/content-types.php';
require_once AMP_PORTFOLIO_CORE_DIR . 'includes/meta-boxes.php';
require_once AMP_PORTFOLIO_CORE_DIR . 'includes/blocks.php';
require_once AMP_PORTFOLIO_CORE_DIR . 'includes/redirects.php';
require_once AMP_PORTFOLIO_CORE_DIR . 'includes/seo.php';

register_activation_hook(__FILE__, static function (): void {
    amp_portfolio_register_content_types();
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
