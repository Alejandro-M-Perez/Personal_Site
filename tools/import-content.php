<?php
/**
 * Idempotent content migration for use with `wp eval-file tools/import-content.php`.
 *
 * WordPress must already be loaded by WP-CLI. Re-running updates entries matched by
 * post type and slug without creating duplicates.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run this file through WP-CLI: wp eval-file tools/import-content.php\n");
    exit(1);
}

function amp_import_upsert(array $post, array $meta = []): int
{
    $existing = get_page_by_path($post['post_name'], OBJECT, $post['post_type']);
    if ($existing instanceof WP_Post) {
        $post['ID'] = $existing->ID;
    }

    $post_id = wp_insert_post(wp_slash($post), true);
    if (is_wp_error($post_id)) {
        throw new RuntimeException($post_id->get_error_message());
    }

    foreach ($meta as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
    return $post_id;
}

$home_content = <<<'BLOCKS'
<!-- wp:paragraph -->
<p>I am a mechanical engineering student at the University of Texas at San Antonio focused on robotics, embedded systems, and machine learning. This site presents selected research, engineering projects, papers, media, and code.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
  <!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/research/">Research</a></div><!-- /wp:button -->
  <!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/projects/">Projects</a></div><!-- /wp:button -->
  <!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/experience/">Experience</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
BLOCKS;

$experience_content = <<<'BLOCKS'
<!-- wp:paragraph {"backgroundColor":"paper","className":"amp-content-review"} -->
<p class="amp-content-review has-paper-background-color has-background"><strong>Content review:</strong> Confirm all “Present” roles, graduation information, and employment dates before production launch.</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">BW Design Group — Controls Integration Engineer (Full-time)</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>June 2, 2025 – December 19, 2025</strong></p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Designed control panels and PLC code to automate processes in high-precision industries. Worked with OEMs, fabrication shops, and contractors; managed installation, demolition, RMAs, and upgrades.</p><!-- /wp:paragraph -->
<!-- wp:separator --><hr class="wp-block-separator has-alpha-channel-opacity"/><!-- /wp:separator -->
<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">UTSA Robotics &amp; Automation Society — President</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>August 12, 2024 – Present</strong></p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Grew the organization and led officers delivering projects and workshops to members; incorporated as a 501(c)(3) and managed finances with budgets and balance sheets.</p><!-- /wp:paragraph -->
<!-- wp:separator --><hr class="wp-block-separator has-alpha-channel-opacity"/><!-- /wp:separator -->
<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Road Runner Dynamics — Humanoid Robotics (Design &amp; Simulation)</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>February 15, 2024 – May 15, 2025</strong></p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Designed CAD models and built prototypes while developing MuJoCo MPC simulations for humanoid robots used in education and RoboCup. Built a vision system using a 3D camera with position feedback; developed flexible test beds for motion studies.</p><!-- /wp:paragraph -->
<!-- wp:separator --><hr class="wp-block-separator has-alpha-channel-opacity"/><!-- /wp:separator -->
<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">UTSA Robotics &amp; Automation Society — Treasurer</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>October 9, 2023 – August 12, 2024</strong></p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Supported organizational growth, project delivery, workshops, incorporation, and financial management.</p><!-- /wp:paragraph -->
<!-- wp:separator --><hr class="wp-block-separator has-alpha-channel-opacity"/><!-- /wp:separator -->
<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">University Rover Challenge — Mechatronics Lead</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>August 12, 2023 – Present</strong></p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Led a small team; managed the Gantt chart and bill of materials; researched suspension and steering concepts; designed and prototyped rover frame and drivetrain components.</p><!-- /wp:paragraph -->
BLOCKS;

$home_id = amp_import_upsert([
    'post_type' => 'page',
    'post_name' => 'home',
    'post_title' => 'Home',
    'post_content' => $home_content,
    'post_status' => 'publish',
]);

amp_import_upsert([
    'post_type' => 'page',
    'post_name' => 'experience',
    'post_title' => 'Experience',
    'post_content' => $experience_content,
    'post_status' => 'publish',
]);

update_option('show_on_front', 'page');
update_option('page_on_front', $home_id);
update_option('blogname', 'Alejandro Perez');
update_option('blogdescription', 'Engineering student and AI researcher focused on robotics and machine learning.');

$projects = [
    [
        'slug' => 'arduencoder',
        'title' => 'ArduEncoder',
        'excerpt' => 'Arduino software for processing encoder data and exporting measurements over serial.',
        'content' => '<!-- wp:paragraph --><p>Arduino code developed to process encoder data and export measurements over a serial connection for downstream analysis.</p><!-- /wp:paragraph -->',
        'meta' => ['amp_technologies' => 'C++, Arduino', 'amp_repository_url' => 'https://github.com/Alejandro-M-Perez/ArduEncoder'],
    ],
    [
        'slug' => 'build-mart-experiment-tools',
        'title' => 'Build Mart Experiment Tools',
        'excerpt' => 'Tools for a construction project-management experiment using an AI project manager and Minecraft instrumentation.',
        'content' => '<!-- wp:paragraph --><p>A collection of tools for the Build Mart project-management experiment, including an AI Project Manager service, Latin-square assignment, data logging, and Minecraft 1.21 instrumentation.</p><!-- /wp:paragraph -->',
        'meta' => ['amp_technologies' => 'Python, Minecraft', 'amp_repository_url' => 'https://github.com/Alejandro-M-Perez/build-mart-experiment-tools'],
    ],
    [
        'slug' => 'mujoco-robot-leg',
        'title' => 'MuJoCo Robot Leg',
        'excerpt' => 'Design and simulation work for a robotic leg using MuJoCo.',
        'content' => '<!-- wp:paragraph --><p>Robot-leg design and simulation work using MuJoCo. Add verified design decisions, test results, and media before production launch.</p><!-- /wp:paragraph -->',
        'meta' => ['amp_technologies' => 'MuJoCo, robotics simulation', 'amp_repository_url' => 'https://github.com/Alejandro-M-Perez/Mujocu_Robot_Leg'],
    ],
    [
        'slug' => 'rowdyhacks-time-machine',
        'title' => 'RowdyHacks Time Machine',
        'excerpt' => 'A hackathon project that simulates conversations with historical figures.',
        'content' => '<!-- wp:paragraph --><p>A RowdyHacks project that creates a “time machine” experience for simulated conversations with historical figures.</p><!-- /wp:paragraph -->',
        'meta' => ['amp_technologies' => 'Python', 'amp_repository_url' => 'https://github.com/Alejandro-M-Perez/Rowdyhax-Timemachine'],
    ],
];

foreach ($projects as $project) {
    amp_import_upsert([
        'post_type' => 'amp_project',
        'post_name' => $project['slug'],
        'post_title' => $project['title'],
        'post_excerpt' => $project['excerpt'],
        'post_content' => $project['content'],
        'post_status' => 'publish',
    ], $project['meta']);
}

$research = [
    [
        'slug' => 'ai-project-manager',
        'title' => 'AI Project Manager',
        'excerpt' => 'Evaluation of LLM performance for construction project management in a Minecraft simulation.',
        'content' => '<!-- wp:paragraph --><p>Worked with Dr. Cai and a graduate student to evaluate large-language-model performance for construction project management, using Minecraft to simulate construction activities. The work later informed a conference paper.</p><!-- /wp:paragraph -->',
        'meta' => [
            'amp_collaborators' => 'Dr. Cai and a graduate researcher',
            'amp_technologies' => 'Large language models, Minecraft, Python',
            'amp_paper_url' => '/downloads/AIPM_Study-3.pdf',
            'amp_repository_url' => 'https://github.com/AIConLab/ai-pm',
        ],
    ],
    [
        'slug' => 'ai-construction-lab',
        'title' => 'AI Construction Lab',
        'excerpt' => 'A simulated RGB-D environment and control workflow for training mobile-manipulator brick-laying behavior.',
        'content' => '<!-- wp:paragraph --><p>Worked with Dr. Cai and a PhD student to develop a simulated environment for mobile-manipulator brick laying. The environment included point-cloud and RGB data to simulate an RGB-D camera. Developed a Python and OpenGL workflow to extract training data and a control system using simulation data.</p><!-- /wp:paragraph -->',
        'meta' => [
            'amp_collaborators' => 'Dr. Cai and a PhD researcher',
            'amp_technologies' => 'Python, OpenGL, RGB-D, point clouds, robotics simulation',
            'amp_paper_url' => '/downloads/AIcon%20Lab%20Weekly%20Reports.pdf',
        ],
    ],
];

foreach ($research as $entry) {
    amp_import_upsert([
        'post_type' => 'amp_research',
        'post_name' => $entry['slug'],
        'post_title' => $entry['title'],
        'post_excerpt' => $entry['excerpt'],
        'post_content' => $entry['content'],
        'post_status' => 'publish',
    ], $entry['meta']);
}

flush_rewrite_rules();
fwrite(STDOUT, "AMP portfolio content imported successfully.\n");
