<?php
/**
 *文章详情模板
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
incrementPostViews($this->cid);
$wordCount = getWordCount($this->content);
$readTime = max(1, ceil($wordCount / 500));
$lastModified = getLastModified($this->cid);
$isUpdated = ($lastModified > $this->created);
?>

<div class="main-body">
    <?php $this->need('sidebar.php'); ?>
    
    <div class="content-area">
        <div class="fade-top" id="fadeTop"></div>
        <div class="content-inner">
            <div class="content-scroll" id="feed">
                <article class="article">
                    <?php if (themeOption('enableTOC', '1') === '1'): ?>
                    <div class="article-title-row">
                        <h1 class="article-title"><?php $this->title(); ?></h1>
                        <button class="toc-inline-btn" id="tocInlineBtn" title="展开目录" style="display:none;">
                            <i class="fa-solid fa-list"></i>
                        </button>
                    </div>
                    <div class="toc-wrap" id="articleTOC"></div>
                    <?php else: ?>
                    <h1 class="article-title"><?php $this->title(); ?></h1>
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
                            <i class="fa-regular fa-clock"></i>
                            约 <?php echo $readTime; ?> 分钟
                        </span>
                        <span class="article-meta-item">
                            <i class="fa-regular fa-file-word"></i>
                            <?php echo $wordCount; ?> 字
                        </span>
                    </div>

                    
                    <?php $postAvatarImg = themeOption('postAvatar', ''); if ($postAvatarImg): ?>
                    <div class="article-author-bar">
                        <img class="article-author-avatar" src="<?php echo htmlspecialchars($postAvatarImg); ?>" alt="">
                        <span class="article-author-name"><?php echo themeOption('blogName', $this->options->title); ?></span>
                    </div>
                    <?php endif; ?>

                    
                    <div class="article-content" id="articleContent">
                        <?php ob_start(); $this->content(); $c = ob_get_clean(); echo themePhotoFilter::processShortcodes($c); ?>
                    </div>

                    
                                        
                    
                    <div class="like-wrap">
                        <button class="like-btn" id="likeBtn" data-cid="<?php $this->cid(); ?>" data-liked="<?php echo isset($_COOKIE['like_'.$this->cid]) ? '1' : '0'; ?>">
                            <i class="<?php echo isset($_COOKIE['like_'.$this->cid]) ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                            <span class="like-text"><?php echo isset($_COOKIE['like_'.$this->cid]) ? '已喜欢' : '喜欢这篇文章'; ?></span>
                            <span class="like-count"><?php
                                try {
                                    $row = $this->db->fetchRow($this->db->select('likes')->from('table.contents')->where('cid = ?', $this->cid));
                                    echo $row ? intval($row['likes']) : 0;
                                } catch (Exception $e) { echo 0; }
                            ?></span>
                        </button>
                    </div>

                    <div class="article-tags" id="articleTags">
                        <?php $this->tags('', true, ''); ?>
                    </div>

                    <?php if (themeOption('enablePostNav', '1') === '1'): ?>
                    <?php /* 上一篇 / 下一篇 */ ?>
                    <div class="post-nav">
                        <?php $prev = getAdjacentPost($this->cid, 'prev'); if ($prev): ?>
                        <a class="post-nav-item post-nav-prev" href="<?php echo \Typecho\Router::url('post', array('cid' => $prev['cid'], 'slug' => $prev['slug']), $this->options->index); ?>">
                            <span class="post-nav-label"><i class="fa-solid fa-chevron-left"></i> 上一篇</span>
                            <span class="post-nav-title"><?php echo htmlspecialchars($prev['title']); ?></span>
                        </a>
                        <?php else: ?>
                        <div class="post-nav-item post-nav-prev post-nav-empty">
                            <span class="post-nav-label"><i class="fa-solid fa-chevron-left"></i> 上一篇</span>
                            <span class="post-nav-title">已经是第一篇了</span>
                        </div>
                        <?php endif; ?>
                        <?php $next = getAdjacentPost($this->cid, 'next'); if ($next): ?>
                        <a class="post-nav-item post-nav-next" href="<?php echo \Typecho\Router::url('post', array('cid' => $next['cid'], 'slug' => $next['slug']), $this->options->index); ?>">
                            <span class="post-nav-label">下一篇 <i class="fa-solid fa-chevron-right"></i></span>
                            <span class="post-nav-title"><?php echo htmlspecialchars($next['title']); ?></span>
                        </a>
                        <?php else: ?>
                        <div class="post-nav-item post-nav-next post-nav-empty">
                            <span class="post-nav-label">下一篇 <i class="fa-solid fa-chevron-right"></i></span>
                            <span class="post-nav-title">已经是最后一篇了</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; // enablePostNav ?>


                </article>

                
                <div id="comments">
                    <?php $this->need('comments.php'); ?>
                </div>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
