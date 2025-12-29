<?php
declare(strict_types=1);

$roles = [
  [
    "title" => "BW Design Group — Controls Integration Engineer (Full-time)",
    "dates" => "06/02/2025 – 12/19/2025",
    "body"  => "Designed control panels and PLC code to automate processes in high precision industries. Worked with OEMs, fabrication shops, and contractors; managed installation, demolition, RMAs, and upgrades."
  ],
  [
    "title" => "Road Runner Dynamics — Humanoid Robotics (Design & Simulation)",
    "dates" => "02/15/2024 – 05/15/2025",
    "body"  => "Designed CAD models and built prototypes while developing MuJoCo MPC simulations for humanoid robots used in education and RoboCup. Built a vision system using a 3D camera with position feedback; developed flexible test beds for motion studies."
  ],
  [
    "title" => "UTSA Robotics & Automation Society — President",
    "dates" => "08/12/2024 – Present",
    "body"  => "Grew the organization and led officers delivering projects and workshops to members; incorporated as a 501(c)(3) and managed finances with budgets and balance sheets."
  ],
  [
    "title" => "UTSA Robotics & Automation Society — Treasurer",
    "dates" => "10/09/2023 – 08/12/2024",
    "body"  => "Grew the organization and led officers delivering projects and workshops to members; incorporated as a 501(c)(3) and managed finances with budgets and balance sheets."
  ],
  [
    "title" => "University Rover Challenge — Mechatronics Lead",
    "dates" => "08/12/2023 – Present",
    "body"  => "Led a small team; managed Gantt chart and BOM; researched suspension/steering concepts; designed and prototyped rover frame and drivetrain components."
  ],
];

// Sort experiences by start date (Newest on top).
$parseDate = static function (string $value): ?DateTimeImmutable {
  $date = DateTimeImmutable::createFromFormat('m/d/Y', trim($value));
  return $date === false ? null : $date;
};

$parseRange = static function (string $dates) use ($parseDate): array {
  $parts = preg_split('/\s*–\s*/u', $dates);
  if (!$parts || count($parts) !== 2) {
    return [null, null];
  }

  $start = $parseDate($parts[0]);
  $endRaw = strtolower(trim($parts[1]));
  $end = $endRaw === 'present' ? new DateTimeImmutable('today') : $parseDate($parts[1]);

  return [$start, $end];
};

usort($roles, static function (array $a, array $b) use ($parseRange): int {
  [$startA] = $parseRange($a["dates"]);
  [$startB] = $parseRange($b["dates"]);
  if (!$startA && !$startB) {
    return 0;
  }
  if (!$startA) {
    return 1;
  }
  if (!$startB) {
    return -1;
  }
  return $startA <=> $startB;
});

?>
<section class="paper-section experience-section">
  <div class="experience-layout">
    <div class="experience-main">
      <h2>Experience</h2>
      <?php foreach ($roles as $r): ?>
        <article class="paper-entry">
          <h3><?= h($r["title"]) ?></h3>
          <div class="paper-entry__meta"><?= h($r["dates"]) ?></div>
          <p><?= h($r["body"]) ?></p>
        </article>
      <?php endforeach; ?>
    </div>

    <?php include __DIR__ . '/../partials/experience_timeline.php'; ?>
  </div>
</section>
