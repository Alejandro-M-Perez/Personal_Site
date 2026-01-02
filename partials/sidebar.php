<?php
declare(strict_types=1);

?>
<aside class="sidebar" role="navigation" aria-label="Section navigation">
  <div class="sidebar__brand">
    <!-- <div class="sidebar__monogram" aria-hidden="true">AP</div>
    <div class="sidebar__brandtext">
      <div class="sidebar__name"><?= h($SITE["name"]) ?></div>
      <div class="sidebar__meta"><?= h($SITE["location"]) ?></div>
    </div> -->
    <button class="sidebar__toggle" type="button" aria-expanded="true" aria-label="Toggle sidebar">
      <span aria-hidden="true">|||</span>
    </button>
  </div>

  <nav class="sidebar__nav">
    <?php foreach ($SECTIONS as $key => $section): ?>
      <?php $isActive = ($key === $activeKey); ?>
      <a class="navitem <?= $isActive ? 'is-active' : '' ?>"
         href="?s=<?= h($key) ?>"
         aria-current="<?= $isActive ? 'page' : 'false' ?>">
        <img class="navitem__icon"
             src="<?= $SITE["assets_base"] ?>/img/icons/<?= h($section["icon"]) ?>"
             alt=""
             loading="lazy" />
        <span class="navitem__label"><?= h($section["title"]) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar__footer">
    <div class="sidebar__actions">
      <a class="sidebar__button" href="mailto:alejandromperez714@gmail.com">
        <img class="sidebar__icon"
             src="<?= $SITE["assets_base"] ?>/img/icons/email.svg"
             alt=""
             aria-hidden="true" />
        Email
      </a>
      <a class="sidebar__button" href="https://www.linkedin.com/in/alejandromperez714/" target="_blank" rel="noreferrer">
        <img class="sidebar__icon"
             src="<?= $SITE["assets_base"] ?>/img/icons/linkedin.svg"
             alt=""
             aria-hidden="true" />
        LinkedIn
      </a>
      <a class="sidebar__button" href="https://www.youtube.com/@AMP_Engineering" target="_blank" rel="noreferrer">
        <img class="sidebar__icon"
             src="<?= $SITE["assets_base"] ?>/img/icons/youtube.svg"
             alt=""
             aria-hidden="true" />
        YouTube
      </a>
      <a class="sidebar__button" href="https://github.com/Alejandro-M-Perez" target="_blank" rel="noreferrer">
        <img class="sidebar__icon"
             src="<?= $SITE["assets_base"] ?>/img/icons/github.svg"
             alt=""
             aria-hidden="true" />
        GitHub
      </a>
    </div>
  </div>
</aside>
