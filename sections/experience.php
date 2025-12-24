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
    "dates" => "10/09/2023 – Present",
    "body"  => "Grew the organization and led officers delivering projects and workshops to members; incorporated as a 501(c)(3) and managed finances with budgets and balance sheets."
  ],
  [
    "title" => "University Rover Challenge — Mechatronics Lead",
    "dates" => "08/12/2023 – Present",
    "body"  => "Led a small team; managed Gantt chart and BOM; researched suspension/steering concepts; designed and prototyped rover frame and drivetrain components."
  ],
];

?>
<section class="paper-section">
  <h2>Experience</h2>
  <?php foreach ($roles as $r): ?>
    <article class="paper-entry">
      <h3><?= h($r["title"]) ?></h3>
      <div class="paper-entry__meta"><?= h($r["dates"]) ?></div>
      <p><?= h($r["body"]) ?></p>
    </article>
  <?php endforeach; ?>
</section>
