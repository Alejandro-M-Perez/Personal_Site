<?php
/**
 * Native editor metadata fields.
 *
 * @package AmpPortfolioCore
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

function amp_portfolio_meta_field_definitions(): array
{
    return [
        'amp_technologies' => __('Technologies', 'amp-portfolio-core'),
        'amp_collaborators' => __('Collaborators', 'amp-portfolio-core'),
        'amp_publication_date' => __('Publication or completion date', 'amp-portfolio-core'),
        'amp_repository_url' => __('Repository URL', 'amp-portfolio-core'),
        'amp_external_url' => __('External project or demo URL', 'amp-portfolio-core'),
        'amp_paper_url' => __('Paper or PDF URL', 'amp-portfolio-core'),
    ];
}

add_action('add_meta_boxes', static function (): void {
    foreach (['amp_project', 'amp_research'] as $post_type) {
        add_meta_box(
            'amp-portfolio-details',
            __('Portfolio details', 'amp-portfolio-core'),
            'amp_portfolio_render_meta_box',
            $post_type,
            'normal',
            'default'
        );
    }
});

function amp_portfolio_render_meta_box(WP_Post $post): void
{
    wp_nonce_field('amp_portfolio_save_meta', 'amp_portfolio_meta_nonce');
    foreach (amp_portfolio_meta_field_definitions() as $key => $label) {
        $value = (string) get_post_meta($post->ID, $key, true);
        $type = str_ends_with($key, '_url') ? 'url' : 'text';
        printf(
            '<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" type="%3$s" id="%1$s" name="%1$s" value="%4$s"></p>',
            esc_attr($key),
            esc_html($label),
            esc_attr($type),
            esc_attr($value)
        );
    }
}

add_action('save_post', static function (int $post_id): void {
    $nonce = isset($_POST['amp_portfolio_meta_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['amp_portfolio_meta_nonce']))
        : '';

    if (!wp_verify_nonce($nonce, 'amp_portfolio_save_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    foreach (array_keys(amp_portfolio_meta_field_definitions()) as $key) {
        if (!isset($_POST[$key])) {
            delete_post_meta($post_id, $key);
            continue;
        }
        $raw = wp_unslash($_POST[$key]);
        $value = str_ends_with($key, '_url') ? esc_url_raw($raw) : sanitize_text_field($raw);
        if ($value === '') {
            delete_post_meta($post_id, $key);
        } else {
            update_post_meta($post_id, $key, $value);
        }
    }
});
