<?php
declare(strict_types=1);
?>

<footer class="paper-footer">
  <hr class="rule" />
  <p class="fineprint quote">
    “<?= h($RANDOM_QUOTE) ?>”
  </p>
</footer>
<script>
  (function() {
    function setTimelineHeight() {
      var timeline = document.querySelector('.experience-timeline');
      if (!timeline) {
        return;
      }
      var pageHeight = document.documentElement.scrollHeight;
      timeline.style.setProperty('--page-height', pageHeight + 'px');
    }

    function bindSidebarToggle() {
      var toggle = document.querySelector('.sidebar__toggle');
      var layout = document.querySelector('.layout');
      if (!toggle || !layout) {
        return;
      }
      toggle.addEventListener('click', function() {
        var isCollapsed = layout.classList.toggle('is-sidebar-collapsed');
        toggle.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
      });
    }

    window.addEventListener('load', function() {
      setTimelineHeight();
      setTimeout(setTimelineHeight, 50);
      bindSidebarToggle();
    });
    window.addEventListener('resize', setTimelineHeight);
  })();
</script>
