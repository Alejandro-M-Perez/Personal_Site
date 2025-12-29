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

    window.addEventListener('load', function() {
      setTimelineHeight();
      setTimeout(setTimelineHeight, 50);
    });
    window.addEventListener('resize', setTimelineHeight);
  })();
</script>
