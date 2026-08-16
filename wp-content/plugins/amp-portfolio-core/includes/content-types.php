<?php
/**
 * Portfolio content types and metadata.
 *
 * @package AmpPortfolioCore
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

function amp_portfolio_register_content_types(): void
{
    register_post_type('amp_project', [
        'labels' => [
            'name' => __('Projects', 'amp-portfolio-core'),
            'singular_name' => __('Project', 'amp-portfolio-core'),
            'add_new_item' => __('Add New Project', 'amp-portfolio-core'),
            'edit_item' => __('Edit Project', 'amp-portfolio-core'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => 'projects',
        'rewrite' => ['slug' => 'projects', 'with_front' => false],
        'menu_icon' => 'dashicons-hammer',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields'],
        'template' => [
            ['core/paragraph', ['placeholder' => __('Describe the problem, your approach, and the result.', 'amp-portfolio-core')]],
            ['core/heading', ['level' => 2, 'content' => __('Key contributions', 'amp-portfolio-core')]],
            ['core/list'],
        ],
    ]);

    register_post_type('amp_research', [
        'labels' => [
            'name' => __('Research', 'amp-portfolio-core'),
            'singular_name' => __('Research Entry', 'amp-portfolio-core'),
            'add_new_item' => __('Add Research Entry', 'amp-portfolio-core'),
            'edit_item' => __('Edit Research Entry', 'amp-portfolio-core'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => 'research',
        'rewrite' => ['slug' => 'research', 'with_front' => false],
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields'],
        'template' => [
            ['core/paragraph', ['placeholder' => __('Summarize the research question, method, and result.', 'amp-portfolio-core')]],
            ['core/heading', ['level' => 2, 'content' => __('Methods and contribution', 'amp-portfolio-core')]],
            ['core/paragraph'],
        ],
    ]);

    $string_meta = [
        'amp_technologies',
        'amp_collaborators',
        'amp_publication_date',
    ];

    foreach (['amp_project', 'amp_research'] as $post_type) {
        foreach ($string_meta as $key) {
            register_post_meta($post_type, $key, [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }

        foreach (['amp_repository_url', 'amp_external_url', 'amp_paper_url'] as $key) {
            register_post_meta($post_type, $key, [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
                'sanitize_callback' => 'esc_url_raw',
                'auth_callback' => static fn (): bool => current_user_can('edit_posts'),
            ]);
        }
    }
}

add_action('init', 'amp_portfolio_register_content_types');
