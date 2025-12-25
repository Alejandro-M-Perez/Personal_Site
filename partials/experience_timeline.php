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

if ($minStart instanceof DateTimeImmutable) {
  $minStart = $minStart->modify('first day of this month')->setTime(0, 0);
}
if ($maxEnd instanceof DateTimeImmutable) {
  $maxEnd = $maxEnd->modify('last day of this month')->setTime(23, 59, 59);
}

$rangeSeconds = 1;
if ($minStart instanceof DateTimeImmutable && $maxEnd instanceof DateTimeImmutable) {
  $rangeSeconds = max(1, $maxEnd->getTimestamp() - $minStart->getTimestamp());
}

$monthDots = [];
$yearDots = [];
$monthCount = 0;
if ($minStart instanceof DateTimeImmutable && $maxEnd instanceof DateTimeImmutable) {
  $cursor = $minStart->modify('first day of this month');
  while ($cursor <= $maxEnd) {
    $pct = (($cursor->getTimestamp() - $minStart->getTimestamp()) / $rangeSeconds) * 100;
    if ($cursor->format('m') === '01') {
      $yearDots[] = [
        "pct" => $pct,
        "label" => $cursor->format('Y'),
      ];
    } else {
      $monthDots[] = ["pct" => $pct];
    }
    $monthCount++;
    $cursor = $cursor->modify('+1 month');
  }
}

$timelineHeight = max(280, $monthCount * 12);
$roleCount = max(1, count($timeline));

?>
<aside class="experience-timeline">
  <details class="timeline-panel" open>
    <summary>Timeline</summary>
    <div class="timeline" style="--timeline-height: <?= (int) $timelineHeight ?>px;">
      <div class="timeline__range">
        <span><?= $minStart ? h($minStart->format('M Y')) : 'Start' ?></span>
        <span><?= $maxEnd ? h($maxEnd->format('M Y')) : 'Present' ?></span>
      </div>
      <div class="timeline__track" aria-hidden="true">
        <?php foreach ($monthDots as $dot): ?>
          <span class="timeline__dot timeline__dot--month"
                style="--pos: <?= number_format($dot["pct"], 2, '.', '') ?>;"></span>
        <?php endforeach; ?>
        <?php foreach ($yearDots as $dot): ?>
          <span class="timeline__dot timeline__dot--year"
                style="--pos: <?= number_format($dot["pct"], 2, '.', '') ?>;">
            <span class="timeline__dot-label"><?= h($dot["label"]) ?></span>
          </span>
        <?php endforeach; ?>
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
            $spanPct = max($spanPct, 1.5);
            $hue = (int) round(($item["index"] * (360 / $roleCount)) % 360);
            $color = "hsl(" . $hue . ", 70%, 40%)";
          ?>
          <div class="timeline__segment"
               style="--start: <?= number_format($startPct, 2, '.', '') ?>; --span: <?= number_format($spanPct, 2, '.', '') ?>; --seg-color: <?= h($color) ?>;">
            <span class="timeline__segment-line"></span>
            <span class="timeline__segment-label"><?= h($item["role"]["title"]) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </details>
</aside>
