<?php
declare(strict_types=1);

// Define research items as data so you can expand easily.
$projects = [
  [
    "title" => "AI Project Manager",
    "body"  => "Worked with Dr. Kai and a graduate student to evaluate LLM performance for construction project management, using Minecraft to simulate construction activities. Later wrote a conference paper discussing results.",
    "links" => [
      ["label" => "Download Paper", "href" => "/downloads/ai-project-manager-paper.pdf"],
      ["label" => "View Project",   "href" => "/projects/ai-project-manager"],
    ],
    "media" => [
      ["type" => "image", "src" => "/assets/img/placeholders/paper-thumb.png", "alt" => "Paper thumbnail placeholder"],
    ],
  ],
  [
    "title" => "AI Construction Lab",
    "body"  => "Worked with Dr. Kai and a PhD student to develop a simulated environment for a mobile manipulator to train brick-laying behavior. Included point cloud and RGB data to simulate an RGB-D camera. Wrote a Python script using OpenGL to extract and save data for training; developed a control system using simulation data.",
    "links" => [
      ["label" => "Download Code",  "href" => "https://github.com/yourname/ai-construction-lab"],
      ["label" => "View Dataset",   "href" => "/projects/ai-construction-lab"],
    ],
    "media" => [
      ["type" => "image", "src" => "/assets/img/placeholders/lab-thumb.png", "alt" => "Lab image placeholder"],
    ],
  ],
];
?>

<header class="paper-header">
  <h1 class="paper-title"><?= h($SITE["name"]) ?></h1>
  <div class="paper-subtitle">
    Email: <?= h($SITE["email"]) ?> | <?= h($SITE["phone"]) ?> | <?= h($SITE["location"]) ?>
  </div>
  <hr class="rule" />
  <p class="paper-abstract"><em><?= h($SITE["tagline"]) ?></em></p>
  <hr class="rule rule--light" />
</header>

<section class="paper-section">
  <h2>Research & Projects</h2>

  <?php foreach ($projects as $p): ?>
    <article class="paper-item">
      <div class="paper-item__text">
        <h3><?= h($p["title"]) ?></h3>
        <p><?= h($p["body"]) ?></p>

        <?php if (!empty($p["links"])): ?>
          <div class="paper-actions">
            <?php foreach ($p["links"] as $a): ?>
              <a class="btn" href="<?= h($a["href"]) ?>"><?= h($a["label"]) ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <?php if (!empty($p["media"])): ?>
        <div class="paper-item__media">
          <?php foreach ($p["media"] as $m): ?>
            <?php if ($m["type"] === "image"): ?>
              <img src="<?= h($m["src"]) ?>" alt="<?= h($m["alt"]) ?>" />
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </article>
  <?php endforeach; ?>
</section>
