<?php
/**
 * 底部模板
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

</div>

  <div class="bottom-bar">
    <span><?php echo str_replace('{year}', date('Y'), themeOption('footerText', '&copy; {year} ' . $this->options->title)); ?></span>
    <?php $icp = themeOption('icpText', ''); if ($icp): ?>
    <div class="dot"></div>
    <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($icp); ?></a>
    <?php endif; ?>
    <div class="dot"></div>
    <span>Theme <a href="https://github.com/Blue-Roar/robes" target="_blank" rel="noopener noreferrer">Robes</a></span>
  </div>
</div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<?php if (themeOption('enableLightbox', '1') === '1'): ?>
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" id="lightboxClose"><i class="fa-solid fa-xmark"></i></button>
    <button class="lightbox-nav lightbox-prev" id="lightboxPrev"><i class="fa-solid fa-chevron-left"></i></button>
    <div class="lightbox-img-wrap">
        <img id="lightboxImg" src="" alt="">
    </div>
    <button class="lightbox-nav lightbox-next" id="lightboxNext"><i class="fa-solid fa-chevron-right"></i></button>
    <div class="lightbox-counter" id="lightboxCounter"></div>
</div>
<?php endif; ?>

<script>
var ROBES = {
    enableLightbox: <?php echo themeOption("enableLightbox", "1") === "1" ? "true" : "false"; ?>,
    enablePJAX: <?php echo themeOption("enablePJAX", "1") === "1" ? "true" : "false"; ?>,
    enableLinkCheck: <?php echo themeOption("enableLinkCheck", "1") === "1" ? "true" : "false"; ?>,
    darkMode: '<?php echo themeOption("darkMode", "auto"); ?>',
    themeUrl: '<?php $this->options->themeUrl(); ?>'
};
</script>
<script src="<?php $this->options->themeUrl('js/main.v2.js'); ?>" defer></script>
<?php
$customJS = themeOption('customJS', '');
if ($customJS) echo '<script>' . $customJS . '</script>';
?>
<?php $this->footer(); ?>
</body>
</html>
