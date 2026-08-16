<?php
/**
 * Theme setup for AMP Engineering Portfolio.
 *
 * @package AmpPortfolio
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', static function (): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_editor_style('style.css');
});

add_action('wp_enqueue_scripts', static function (): void {
    wp_enqueue_style(
        'amp-portfolio',
        get_stylesheet_uri(),
        [],
        (string) wp_get_theme()->get('Version')
    );
});

add_action('wp_head', static function (): void {
    if (!has_site_icon()) {
        printf(
            '<link rel="icon" href="%s" type="image/svg+xml">' . "\n",
            esc_url(get_theme_file_uri('assets/images/favicon.svg'))
        );
    }
}, 2);

add_action('wp_body_open', static function (): void {
    echo '<a class="amp-skip-link" href="#main-content">' . esc_html__('Skip to main content', 'amp-portfolio') . '</a>';
});
