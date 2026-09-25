<?php
/**
 * 简单无元信息无互动页面模板
 * @package custom
 * @type page
 * @title 简单
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
incrementPostViews($this->cid);
$wordCount = getWordCount($this->content);
$lastModified = getLastModified($this->cid);
$isUpdated = ($lastModified > $this->created);
?>

<div class="main-body">
    <?php $this->need('sidebar.php'); ?>
    
    <div class="content-area">
        <div class="fade-top" id="fadeTop"></div>
        <div class="content-inner">
            <div class="content-scroll" id="feed">
                <article class="article standalone-page">
                    <?php if (themeOption('enableTOC', '1') === '1'): ?>
                    <div class="article-title-row">
                        <h1 class="article-title"><?php $this->title(); ?></h1>
                        <button class="toc-inline-btn" id="tocInlineBtn" title="展开目录" style="display:none;">
                            <i class="fa-solid fa-list"></i>
                        </button>
                    </div>
                    <div class="toc-wrap" id="articleTOC"></div>
                    <?php endif; ?>

                    <div class="article-content" id="articleContent">
                        <?php ob_start(); $this->content(); $c = ob_get_clean(); echo themePhotoFilter::processShortcodes($c); ?>
                    </div>
                </article>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
