<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/config/site.php';

$SECTIONS = require __DIR__ . '/config/sections.php';
$QUOTES   = require __DIR__ . '/config/quotes.php';

$RANDOM_QUOTE = $QUOTES[array_rand($QUOTES)];

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/config/site.php';
$SECTIONS = require __DIR__ . '/config/sections.php';

// Force default to About if no section is specified
if (!isset($_GET['s']) || $_GET['s'] === '') {
  header('Location: /?s=home', true, 302);
  exit;
}

// Determine active section (default: research)
$activeKey = $_GET['s'] ?? $SITE['default_section'];
if (!isset($SECTIONS[$activeKey])) {
  http_response_code(404);
  $activeKey = $SITE['default_section'];
}

$activeSection = $SECTIONS[$activeKey];

// Render
require __DIR__ . '/partials/head.php';
?>
<body>
  <div class="layout">
    <?php require __DIR__ . '/partials/sidebar.php'; ?>

    <main class="content" role="main">
      <?php
        // Each section module returns HTML
        $sectionFile = __DIR__ . '/sections/' . $activeSection['file'];
        if (!is_file($sectionFile)) {
          echo "<h1>Section not found.</h1>";
        } else {
          require $sectionFile;
        }
      ?>
      <?php require __DIR__ . '/partials/footer.php'; ?>
    </main>
  </div>
</body>
</html>
