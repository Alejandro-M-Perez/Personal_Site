<?php
declare(strict_types=1);

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
    "dates" => "10/09/2023 – Present",
    "body"  => "Grew the organization and led officers delivering projects and workshops to members; incorporated as a 501(c)(3) and managed finances with budgets and balance sheets."
  ],
  [
    "title" => "University Rover Challenge — Mechatronics Lead",
    "dates" => "08/12/2023 – Present",
    "body"  => "Led a small team; managed Gantt chart and BOM; researched suspension/steering concepts; designed and prototyped rover frame and drivetrain components."
  ],
];

$timeline = [];
$minStart = null;
$maxEnd = null;
foreach ($roles as $idx => $role) {
  [$start, $end] = $parseRange($role["dates"]);
  if ($start instanceof DateTimeImmutable && ($minStart === null || $start < $minStart)) {
    $minStart = $start;
  }
  if ($end instanceof DateTimeImmutable && ($maxEnd === null || $end > $maxEnd)) {
    $maxEnd = $end;
  }
  $timeline[] = [
    "role" => $role,
    "start" => $start,
    "end" => $end,
    "index" => $idx,
  ];
}

$palette = ["#0f5c7a", "#b33b24", "#3b7a3b", "#6b4e9b", "#a36a00"];
$rangeSeconds = 1;
if ($minStart instanceof DateTimeImmutable && $maxEnd instanceof DateTimeImmutable) {
  $rangeSeconds = max(1, $maxEnd->getTimestamp() - $minStart->getTimestamp());
}

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

    <aside class="experience-timeline">
      <details class="timeline-panel" open>
        <summary>Timeline</summary>
        <div class="timeline">
          <div class="timeline__range">
            <span><?= $minStart ? h($minStart->format('M Y')) : 'Start' ?></span>
            <span><?= $maxEnd ? h($maxEnd->format('M Y')) : 'Present' ?></span>
          </div>
          <div class="timeline__track">
            <?php foreach ($timeline as $item): ?>
              <?php
                $start = $item["start"];
                $end = $item["end"];
                $startPct = 0.0;
                $spanPct = 0.0;
                if ($start instanceof DateTimeImmutable && $end instanceof DateTimeImmutable && $minStart instanceof DateTimeImmutable) {
                  $startPct = (($start->getTimestamp() - $minStart->getTimestamp()) / $rangeSeconds) * 100;
                  $spanPct = (($end->getTimestamp() - $start->getTimestamp()) / $rangeSeconds) * 100;
                }
                $spanPct = max($spanPct, 2.0);
                $color = $palette[$item["index"] % count($palette)];
              ?>
              <div class="timeline__bar"
                   style="--start: <?= number_format($startPct, 2, '.', '') ?>; --span: <?= number_format($spanPct, 2, '.', '') ?>; --bar-color: <?= h($color) ?>;">
                <span class="timeline__bar-fill" aria-hidden="true"></span>
                <span class="timeline__label"><?= h($item["role"]["title"]) ?></span>
                <span class="timeline__dates"><?= h($item["role"]["dates"]) ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </details>
    </aside>
  </div>
</section>
