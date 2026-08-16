<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$links = [
    'Email' => 'mailto:AlejandroMPerez714@gmail.com',
    'LinkedIn' => 'https://www.linkedin.com/in/alejandromperez714/',
    'GitHub' => 'https://github.com/Alejandro-M-Perez',
    'YouTube' => 'https://www.youtube.com/@AMP_Engineering',
];
?>
<nav class="amp-social-links" aria-label="<?php esc_attr_e('Contact and social links', 'amp-portfolio-core'); ?>">
    <?php foreach ($links as $label => $url) : ?>
        <a href="<?php echo esc_url($url); ?>"<?php echo str_starts_with($url, 'mailto:') ? '' : ' rel="me noopener noreferrer"'; ?>><?php echo esc_html($label); ?></a>
    <?php endforeach; ?>
</nav>
