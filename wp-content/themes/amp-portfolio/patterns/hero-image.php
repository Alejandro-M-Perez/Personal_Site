<?php
/**
 * Title: Engineering hero image
 * Slug: amp-portfolio/hero-image
 * Categories: featured
 * Inserter: no
 */

$image_url = get_theme_file_uri('assets/images/photo-banner.jpg');
?>
<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"amp-hero-image"} -->
<figure class="wp-block-image size-full amp-hero-image"><img src="<?php echo esc_url($image_url); ?>" alt="Engineering prototypes including humanoid robots, a rover, and laboratory hardware" /></figure>
<!-- /wp:image -->
