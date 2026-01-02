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
        <svg class="sidebar__icon" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M3 6.5h18v11H3z" fill="none" stroke="currentColor" stroke-width="1.5"/>
          <path d="M3.5 7l8.5 6 8.5-6" fill="none" stroke="currentColor" stroke-width="1.5"/>
        </svg>
        Email
      </a>
      <a class="sidebar__button" href="https://www.linkedin.com/in/alejandromperez714/" target="_blank" rel="noreferrer">
        <svg class="sidebar__icon" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M4 8h3.5v12H4zM5.75 4.5a1.75 1.75 0 1 1 0 3.5 1.75 1.75 0 0 1 0-3.5zM11 8h3.3v1.6h.05c.46-.86 1.6-1.77 3.3-1.77 3.53 0 4.18 2.32 4.18 5.35V20H18v-5.3c0-1.27-.02-2.9-1.77-2.9-1.77 0-2.04 1.38-2.04 2.8V20H11z" fill="currentColor"/>
        </svg>
        LinkedIn
      </a>
      <a class="sidebar__button" href="https://www.youtube.com/@AMP_Engineering" target="_blank" rel="noreferrer">
        <svg class="sidebar__icon" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M22 12s0-3.4-.44-4.9a2.76 2.76 0 0 0-1.94-1.94C18.1 4.5 12 4.5 12 4.5s-6.1 0-7.62.66A2.76 2.76 0 0 0 2.44 7.1C2 8.6 2 12 2 12s0 3.4.44 4.9a2.76 2.76 0 0 0 1.94 1.94c1.52.66 7.62.66 7.62.66s6.1 0 7.62-.66a2.76 2.76 0 0 0 1.94-1.94C22 15.4 22 12 22 12z" fill="currentColor"/>
          <path d="M10 9.75v4.5L14.5 12z" fill="#fff"/>
        </svg>
        YouTube
      </a>
      <a class="sidebar__button" href="https://github.com/Alejandro-M-Perez" target="_blank" rel="noreferrer">
        <svg class="sidebar__icon" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M12 2.5a9.5 9.5 0 0 0-3 18.5c.48.08.66-.2.66-.46v-1.6c-2.7.6-3.27-1.2-3.27-1.2-.45-1.1-1.1-1.4-1.1-1.4-.9-.6.07-.6.07-.6 1 .07 1.5 1 1.5 1 .9 1.5 2.4 1.1 3 .84.1-.65.36-1.1.65-1.35-2.15-.25-4.4-1.1-4.4-4.9 0-1.1.4-2 1-2.7-.1-.25-.45-1.3.1-2.7 0 0 .85-.27 2.8 1a9.7 9.7 0 0 1 5.1 0c1.95-1.27 2.8-1 2.8-1 .55 1.4.2 2.45.1 2.7.6.7 1 1.6 1 2.7 0 3.82-2.25 4.65-4.4 4.9.37.32.7.94.7 1.9v2.8c0 .26.18.55.67.46A9.5 9.5 0 0 0 12 2.5z" fill="currentColor"/>
        </svg>
        GitHub
      </a>
    </div>
  </div>
</aside>
