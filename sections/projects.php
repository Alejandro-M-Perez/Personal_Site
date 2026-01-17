<?php
declare(strict_types=1);

$githubProjects = [
  [
    "name" => "ArduEncoder",
    "description" => "Arduino code to process encoder data and export data over serial.",
    "url" => "https://github.com/Alejandro-M-Perez/ArduEncoder",
    "language" => "C++",
    "updated" => "2024-10-18T06:00:39Z",
    "fork" => false,
  ],
  [
    "name" => "build-mart-experiment-tools",
    "description" => "Tools and code for Build Mart project management experiment, including AI Project Manager service, Latin square assigner, data logger, and Minecraft 1.21 instrumentation plugin.",
    "url" => "https://github.com/Alejandro-M-Perez/build-mart-experiment-tools",
    "language" => "Python",
    "updated" => "2025-07-31T04:14:55Z",
    "fork" => false,
  ],
  [
    "name" => "DinoMon",
    "description" => "Config files for my GitHub profile.",
    "url" => "https://github.com/Alejandro-M-Perez/DinoMon",
    "language" => "",
    "updated" => "2024-02-25T06:04:27Z",
    "fork" => true,
  ],
  [
    "name" => "Mujocu_Robot_Leg",
    "description" => "My mujocu projects.",
    "url" => "https://github.com/Alejandro-M-Perez/Mujocu_Robot_Leg",
    "language" => "",
    "updated" => "2025-05-27T15:16:44Z",
    "fork" => false,
  ],
  [
    "name" => "Rowdyhax-Timemachine",
    "description" => "RowdyHacks hackathon project that simulates a time machine for conversations with historical figures.",
    "url" => "https://github.com/Alejandro-M-Perez/Rowdyhax-Timemachine",
    "language" => "Python",
    "updated" => "2024-10-28T20:29:18Z",
    "fork" => false,
  ],
];

$formatGithubDate = static function (string $value): string {
  $date = DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $value);
  if ($date === false) {
    $date = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s\Z', $value);
  }
  return $date === false ? $value : $date->format('M j, Y');
};

$githubMeta = static function (array $repo) use ($formatGithubDate): string {
  $parts = [];
  if (!empty($repo["language"])) {
    $parts[] = "Language: " . $repo["language"];
  }
  if (!empty($repo["fork"])) {
    $parts[] = "Fork";
  }
  if (!empty($repo["updated"])) {
    $parts[] = "Updated: " . $formatGithubDate($repo["updated"]);
  }
  return implode(" · ", $parts);
};
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
  <h2>GitHub Projects</h2>

  <?php foreach ($githubProjects as $repo): ?>
    <article class="paper-item">
      <div class="paper-item__text">
        <h3><?= h($repo["name"]) ?></h3>
        <?php $meta = $githubMeta($repo); ?>
        <?php if ($meta !== ""): ?>
          <div class="paper-entry__meta"><?= h($meta) ?></div>
        <?php endif; ?>
        <p><?= h($repo["description"] ?: "No description provided.") ?></p>
        <div class="paper-actions">
          <a class="btn" href="<?= h($repo["url"]) ?>">View Repository</a>
        </div>
      </div>
    </article>
  <?php endforeach; ?>
</section>
