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
$months = [];
if ($minStart instanceof DateTimeImmutable && $maxEnd instanceof DateTimeImmutable) {
  $cursor = $minStart->modify('first day of this month');
  while ($cursor <= $maxEnd) {
    $months[] = [
      "date" => $cursor,
      "isYear" => $cursor->format('m') === '01',
      "label" => $cursor->format('Y'),
    ];
    $cursor = $cursor->modify('+1 month');
  }
}

foreach ($months as $month) {
  $posPct = 0.0;
  if ($maxEnd instanceof DateTimeImmutable && $rangeSeconds > 0) {
    $posPct = (($maxEnd->getTimestamp() - $month["date"]->getTimestamp()) / $rangeSeconds) * 100;
  }
  if ($month["isYear"]) {
    $yearDots[] = [
      "pos" => $posPct,
      "label" => $month["label"],
    ];
  } else {
    $monthDots[] = ["pos" => $posPct];
  }
}
$roleCount = max(1, count($timeline));

?>
<aside class="experience-timeline">
  <details class="timeline-panel" open>
    <summary>Timeline</summary>
    <div class="timeline">
      <div class="timeline__range">
        <span><?= $minStart ? h($minStart->format('M Y')) : 'Start' ?></span>
        <span><?= $maxEnd ? h($maxEnd->format('M Y')) : 'Present' ?></span>
      </div>
      <div class="timeline__track" aria-hidden="true">
        <?php foreach ($monthDots as $dot): ?>
          <span class="timeline__dot timeline__dot--month"
                style="--pos: <?= number_format($dot["pos"], 2, '.', '') ?>;"></span>
        <?php endforeach; ?>
        <?php foreach ($yearDots as $dot): ?>
          <span class="timeline__dot timeline__dot--year"
                style="--pos: <?= number_format($dot["pos"], 2, '.', '') ?>;">
            <span class="timeline__dot-label"><?= h($dot["label"]) ?></span>
          </span>
        <?php endforeach; ?>
        <?php foreach ($timeline as $item): ?>
          <?php
            $start = $item["start"];
            $end = $item["end"];
            $startPos = 0.0;
            $spanPct = 0.0;
            if ($start instanceof DateTimeImmutable && $end instanceof DateTimeImmutable && $maxEnd instanceof DateTimeImmutable && $rangeSeconds > 0) {
              $startPos = (($maxEnd->getTimestamp() - $end->getTimestamp()) / $rangeSeconds) * 100;
              $spanPct = (($end->getTimestamp() - $start->getTimestamp()) / $rangeSeconds) * 100;
            }
            $hue = (int) round(($item["index"] * (360 / $roleCount)) % 360);
            $color = "hsl(" . $hue . ", 70%, 40%)";
            $offset = max(0.0, (12.0 * (1 - min(1.0, $spanPct / 100.0))));
            $lane = $item["index"] % 4;
            $laneOffset = $lane * 8;
            $labelOffsets = [-18, -6, 6, 18];
            $labelOffset = $labelOffsets[$lane];
          ?>
          <div class="timeline__segment"
               style="--start: <?= number_format($startPos, 2, '.', '') ?>; --span: <?= number_format($spanPct, 2, '.', '') ?>; --seg-color: <?= h($color) ?>; --seg-offset: <?= number_format($offset, 2, '.', '') ?>px; --lane-offset: <?= (int) $laneOffset ?>px; --label-offset: <?= (int) $labelOffset ?>px;">
            <span class="timeline__segment-line"></span>
            <span class="timeline__segment-label">
              <span class="timeline__segment-dot" style="--seg-color: <?= h($color) ?>;"></span>
              <?= h($item["role"]["title"]) ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </details>
</aside>
