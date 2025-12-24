<?php
declare(strict_types=1);

?>
<aside class="sidebar" role="navigation" aria-label="Section navigation">
  <div class="sidebar__brand">
    <div class="sidebar__monogram" aria-hidden="true">AP</div>
    <div class="sidebar__brandtext">
      <div class="sidebar__name"><?= h($SITE["name"]) ?></div>
      <div class="sidebar__meta"><?= h($SITE["location"]) ?></div>
    </div>
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
    <div class="sidebar__contact">
      <div><?= h($SITE["email"]) ?></div>
      <div><?= h($SITE["phone"]) ?></div>
    </div>
  </div>
</aside>
