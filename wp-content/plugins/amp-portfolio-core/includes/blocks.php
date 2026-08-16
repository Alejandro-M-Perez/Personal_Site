<?php
/**
 * Server-rendered blocks used by the portfolio theme.
 *
 * @package AmpPortfolioCore
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', static function (): void {
    register_block_type(AMP_PORTFOLIO_CORE_DIR . 'blocks/portfolio-meta');
    register_block_type(AMP_PORTFOLIO_CORE_DIR . 'blocks/social-links');
});
