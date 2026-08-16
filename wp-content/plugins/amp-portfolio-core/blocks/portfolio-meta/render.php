<?php
/** @var array $attributes */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$post_id = get_the_ID();
if (!$post_id) {
    return;
}

$details = [
    'amp_technologies' => __('Technologies', 'amp-portfolio-core'),
    'amp_collaborators' => __('Collaborators', 'amp-portfolio-core'),
    'amp_publication_date' => __('Date', 'amp-portfolio-core'),
];

$links = [
    'amp_repository_url' => __('View repository', 'amp-portfolio-core'),
    'amp_external_url' => __('View project', 'amp-portfolio-core'),
    'amp_paper_url' => __('View or download paper', 'amp-portfolio-core'),
];

$has_details = false;
foreach (array_keys($details) as $key) {
    if ((string) get_post_meta($post_id, $key, true) !== '') {
        $has_details = true;
        break;
    }
}

if (!$has_details && !array_filter(array_map(static fn (string $key): string => (string) get_post_meta($post_id, $key, true), array_keys($links)))) {
    return;
}
?>
<section class="amp-meta" aria-label="<?php esc_attr_e('Entry details', 'amp-portfolio-core'); ?>">
    <?php if ($has_details) : ?>
        <dl>
            <?php foreach ($details as $key => $label) : ?>
                <?php $value = (string) get_post_meta($post_id, $key, true); ?>
                <?php if ($value !== '') : ?>
                    <dt><?php echo esc_html($label); ?></dt>
                    <dd><?php echo esc_html($value); ?></dd>
                <?php endif; ?>
            <?php endforeach; ?>
        </dl>
    <?php endif; ?>
    <div class="amp-button-row">
        <?php foreach ($links as $key => $label) : ?>
            <?php $url = (string) get_post_meta($post_id, $key, true); ?>
            <?php if ($url !== '') : ?>
                <a class="amp-button" href="<?php echo esc_url($url); ?>"<?php echo str_starts_with($url, home_url('/')) ? '' : ' rel="noopener noreferrer"'; ?>><?php echo esc_html($label); ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
