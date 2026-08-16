<?php
/**
 * Minimal social metadata and structured data.
 *
 * @package AmpPortfolioCore
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

remove_action('wp_head', 'rel_canonical');

function amp_portfolio_meta_description(): string
{
    if (is_singular()) {
        $excerpt = get_the_excerpt();
        if ($excerpt !== '') {
            return wp_strip_all_tags($excerpt);
        }
    }
    return (string) get_bloginfo('description');
}

add_action('wp_head', static function (): void {
    if (is_admin() || is_feed() || is_404()) {
        return;
    }

    $title = wp_get_document_title();
    $description = amp_portfolio_meta_description();
    if (is_singular()) {
        $url = get_permalink();
    } elseif (is_post_type_archive()) {
        $url = get_post_type_archive_link((string) get_query_var('post_type'));
    } elseif (is_front_page()) {
        $url = home_url('/');
    } else {
        $url = home_url('/');
    }
    $type = is_singular(['amp_project', 'amp_research']) ? 'article' : 'website';
    $image = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : get_theme_file_uri('assets/images/photo-banner.jpg');

    echo "\n";
    printf('<meta name="description" content="%s">' . "\n", esc_attr($description));
    printf('<link rel="canonical" href="%s">' . "\n", esc_url($url));
    printf('<meta property="og:type" content="%s">' . "\n", esc_attr($type));
    printf('<meta property="og:title" content="%s">' . "\n", esc_attr($title));
    printf('<meta property="og:description" content="%s">' . "\n", esc_attr($description));
    printf('<meta property="og:url" content="%s">' . "\n", esc_url($url));
    printf('<meta property="og:image" content="%s">' . "\n", esc_url((string) $image));
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => 'Alejandro Perez',
        'url' => home_url('/'),
        'email' => 'mailto:AlejandroMPerez714@gmail.com',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'San Antonio',
            'addressRegion' => 'TX',
            'addressCountry' => 'US',
        ],
        'sameAs' => [
            'https://www.linkedin.com/in/alejandromperez714/',
            'https://github.com/Alejandro-M-Perez',
            'https://www.youtube.com/@AMP_Engineering',
        ],
    ];

    if (is_singular(['amp_project', 'amp_research'])) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => get_post_type() === 'amp_research' ? 'ScholarlyArticle' : 'CreativeWork',
            'name' => get_the_title(),
            'description' => $description,
            'url' => get_permalink(),
            'author' => ['@type' => 'Person', 'name' => 'Alejandro Perez'],
        ];
    }

    printf(
        '<script type="application/ld+json">%s</script>' . "\n",
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    );
}, 5);

add_filter('wp_robots', static function (array $robots): array {
    if (is_404()) {
        $robots['noindex'] = true;
    }
    if (wp_get_environment_type() !== 'production') {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
    }
    return $robots;
});
