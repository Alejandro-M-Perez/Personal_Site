<?php
declare(strict_types=1);

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

<section class="paper-section photo-banner">
  <img class="photo-banner__image"
       src="<?= $SITE["assets_base"] ?>/img/photo-banner.jpg"
       alt="Photo banner" />
</section>

<section class="paper-section">
  <h2>About Me</h2>
  <p>
    I am a mechanical engineering student at <?= h($SITE["university"]) ?> focused on robotics, embedded systems,
    and machine learning. This site is formatted to present research, projects, and
    artifacts (papers, videos, pictures, & code).
  </p>
</section>
