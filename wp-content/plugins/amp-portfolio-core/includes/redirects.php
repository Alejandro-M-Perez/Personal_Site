<?php
/**
 * Legacy URL redirects.
 *
 * @package AmpPortfolioCore
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', static function (): void {
    if (is_admin() || wp_doing_ajax()) {
        return;
    }

    $legacy_section = isset($_GET['s']) ? sanitize_key(wp_unslash($_GET['s'])) : '';
    $destinations = [
        'home' => home_url('/'),
        'research' => get_post_type_archive_link('amp_research'),
        'projects' => get_post_type_archive_link('amp_project'),
        'experience' => home_url('/experience/'),
    ];

    if ($legacy_section !== '' && isset($destinations[$legacy_section]) && $destinations[$legacy_section]) {
        wp_safe_redirect((string) $destinations[$legacy_section], 301, 'AMP Portfolio Core');
        exit;
    }

    $path = trim((string) wp_parse_url(wp_unslash($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH), '/');
    if ($path === 'projects/ai-project-manager') {
        $research = get_page_by_path('ai-project-manager', OBJECT, 'amp_research');
        if ($research instanceof WP_Post) {
            wp_safe_redirect(get_permalink($research), 301, 'AMP Portfolio Core');
            exit;
        }
    }
});

add_filter('redirect_canonical', static function ($redirect_url, string $requested_url) {
    if (isset($_GET['s'])) {
        return false;
    }
    return $redirect_url;
}, 10, 2);
