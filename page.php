<?php
/**
 *独立页面模板
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

                    <div class="article-meta">
                        <span class="article-meta-item">
                            <i class="fa-regular fa-calendar"></i>
                            <?php $this->date('Y年m月d日'); ?>
                        </span>
                        <?php if ($isUpdated): ?>
                        <span class="article-meta-item article-meta-updated">
                            <i class="fa-solid fa-rotate"></i>
                            更新于 <?php echo date('Y年m月d日', $lastModified); ?>
                        </span>
                        <?php endif; ?>
                        <span class="article-meta-item">
                            <i class="fa-regular fa-eye"></i>
                            <?php echo getPostViews($this->cid); ?> 阅读
                        </span>
                        <span class="article-meta-item">
                            <i class="fa-regular fa-comment"></i>
                            <?php $this->commentsNum('0', '1', '%d'); ?> 评论
                        </span>
                        <span class="article-meta-item">
                            <i class="fa-regular fa-file-word"></i>
                            <?php echo $wordCount; ?> 字
                        </span>
                    </div>

                    <div class="article-content" id="articleContent">
                        <?php ob_start(); $this->content(); $c = ob_get_clean(); echo themePhotoFilter::processShortcodes($c); ?>
                    </div>

                    <div class="like-wrap">
                        <button class="like-btn" id="likeBtn" data-cid="<?php $this->cid(); ?>" data-liked="<?php echo isset($_COOKIE['like_'.$this->cid]) ? '1' : '0'; ?>">
                            <i class="<?php echo isset($_COOKIE['like_'.$this->cid]) ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                            <span class="like-text"><?php echo isset($_COOKIE['like_'.$this->cid]) ? '已喜欢' : '喜欢这个页面'; ?></span>
                            <span class="like-count"><?php
                                try {
                                    $row = $this->db->fetchRow($this->db->select('likes')->from('table.contents')->where('cid = ?', $this->cid));
                                    echo $row ? intval($row['likes']) : 0;
                                } catch (Exception $e) { echo 0; }
                            ?></span>
                        </button>
                    </div>
                </article>

                <div id="comments">
                    <?php $this->need('comments.php'); ?>
                </div>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
