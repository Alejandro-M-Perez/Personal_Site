<?php
declare(strict_types=1);

// Define research items as data so you can expand easily.
$projects = [
  [
    "title" => "AI Project Manager",
    "body"  => "Worked with Dr. Cai and a graduate student to evaluate LLM performance for construction project management, using Minecraft to simulate construction activities. Later wrote a conference paper discussing results.",
    "links" => [
      ["label" => "Download Paper", "href" => "/downloads/AIPM_Study-3.pdf"],
      ["label" => "View Project",   "href" => "/projects/ai-project-manager"],
      ["label" => "Download Code",      "href" => "https://github.com/AIConLab/ai-pm"],
    ],
    "media" => [
      ["type" => "pdf", "src" => "/downloads/AIPM_Study-3.pdf", "label" => "AI Project Manager paper preview"],
    ],
  ],
  [
    "title" => "AI Construction Lab",
    "body"  => "Worked with Dr. Cai and a PhD student to develop a simulated environment for a mobile manipulator to train brick-laying behavior. Included point cloud and RGB data to simulate an RGB-D camera. Wrote a Python script using OpenGL to extract and save data for training; developed a control system using simulation data.",
    "links" => [
      ["label" => "Download Lab Notes", "href" => "/downloads/AIcon Lab Weekly Reports.pdf"],
    ],
    "media" => [
      ["type" => "pdf", "src" => "/downloads/AIcon Lab Weekly Reports.pdf", "label" => "AI Construction Lab notes preview"],
    ],
  ],
];
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

<section class="paper-section">
  <h2>Research & Projects</h2>

  <?php foreach ($projects as $p): ?>
    <article class="paper-item">
      <div class="paper-item__text">
        <h3><?= h($p["title"]) ?></h3>
        <p><?= h($p["body"]) ?></p>

        <?php if (!empty($p["links"])): ?>
          <div class="paper-actions">
            <?php foreach ($p["links"] as $a): ?>
              <a class="btn" href="<?= h($a["href"]) ?>"><?= h($a["label"]) ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <?php if (!empty($p["media"])): ?>
        <div class="paper-item__media">
          <?php foreach ($p["media"] as $m): ?>
            <?php if ($m["type"] === "image"): ?>
              <img src="<?= h($m["src"]) ?>" alt="<?= h($m["alt"]) ?>" />
            <?php elseif ($m["type"] === "pdf"): ?>
              <div class="pdf-viewer" data-pdf-src="<?= h($m["src"]) ?>">
                <div class="pdf-toolbar">
                  <span class="pdf-label"><?= h($m["label"] ?? "PDF preview") ?></span>
                  <button class="btn pdf-btn" type="button" data-action="prev">Prev</button>
                  <button class="btn pdf-btn" type="button" data-action="next">Next</button>
                  <span class="pdf-page">Page <span data-page-num>1</span> / <span data-page-count>?</span></span>
                  <button class="btn pdf-btn" type="button" data-action="zoom-out">-</button>
                  <button class="btn pdf-btn" type="button" data-action="zoom-in">+</button>
                </div>
                <div class="pdf-canvas-wrap">
                  <canvas class="pdf-canvas"></canvas>
                </div>
                <p class="pdf-fallback">Having trouble viewing? <a href="<?= h($m["src"]) ?>">Download the PDF</a>.</p>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </article>
  <?php endforeach; ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
<script>
  (function () {
    if (!window.pdfjsLib) return;
    window.pdfjsLib.GlobalWorkerOptions.workerSrc =
      "https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js";

    document.querySelectorAll(".pdf-viewer").forEach((viewer) => {
      const pdfUrl = viewer.dataset.pdfSrc;
      const canvas = viewer.querySelector(".pdf-canvas");
      const context = canvas.getContext("2d");
      const pageNumEl = viewer.querySelector("[data-page-num]");
      const pageCountEl = viewer.querySelector("[data-page-count]");
      const prevBtn = viewer.querySelector('[data-action="prev"]');
      const nextBtn = viewer.querySelector('[data-action="next"]');
      const zoomOutBtn = viewer.querySelector('[data-action="zoom-out"]');
      const zoomInBtn = viewer.querySelector('[data-action="zoom-in"]');

      let pdfDoc = null;
      let pageNum = 1;
      let pageRendering = false;
      let pageNumPending = null;
      let scale = 1.1;

      function renderPage(num) {
        pageRendering = true;
        pdfDoc.getPage(num).then((page) => {
          const viewport = page.getViewport({ scale });
          canvas.height = viewport.height;
          canvas.width = viewport.width;

          const renderTask = page.render({ canvasContext: context, viewport });
          return renderTask.promise;
        }).then(() => {
          pageRendering = false;
          pageNumEl.textContent = String(num);
          if (pageNumPending !== null) {
            const pending = pageNumPending;
            pageNumPending = null;
            renderPage(pending);
          }
        });
      }

      function queueRenderPage(num) {
        if (pageRendering) {
          pageNumPending = num;
        } else {
          renderPage(num);
        }
      }

      function onPrevPage() {
        if (pageNum <= 1) return;
        pageNum -= 1;
        queueRenderPage(pageNum);
      }

      function onNextPage() {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum += 1;
        queueRenderPage(pageNum);
      }

      function onZoom(delta) {
        const nextScale = Math.min(2.0, Math.max(0.6, scale + delta));
        if (nextScale === scale) return;
        scale = nextScale;
        queueRenderPage(pageNum);
      }

      window.pdfjsLib.getDocument(pdfUrl).promise.then((doc) => {
        pdfDoc = doc;
        pageCountEl.textContent = String(pdfDoc.numPages);
        renderPage(pageNum);
      }).catch(() => {
        viewer.classList.add("is-error");
      });

      prevBtn.addEventListener("click", onPrevPage);
      nextBtn.addEventListener("click", onNextPage);
      zoomOutBtn.addEventListener("click", () => onZoom(-0.1));
      zoomInBtn.addEventListener("click", () => onZoom(0.1));
    });
  })();
</script>
