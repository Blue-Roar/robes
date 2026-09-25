<?php
/**
 * 平凡的日子也是限量版。
 * @package Robes
 * @author Robes
 * @version 1.1
 * @link https://robes.xin/
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$cardStyle = themeOption('cardStyle', 'social');
?>

<div class="main-body">
    <?php $this->need('sidebar.php'); ?>
    
    <div class="content-area">
        <div class="fade-top" id="fadeTop"></div>
        <div class="content-inner">
            <div class="content-scroll" id="feed">
                
                <form class="mobile-search" action="<?php $this->options->siteUrl(); ?>" method="get">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="s" placeholder="搜索文章…" autocomplete="off" value="<?php if($this->is('search')) $this->archiveTitle(' - ', '', ''); ?>">
                </form>

                <?php if ($this->is('search')): ?>
                <div class="search-result-hint">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>搜索 <strong><?php $this->archiveTitle(' - ', '', ''); ?></strong> 的结果，共 <?php echo $this->length; ?> 篇文章</span>
                </div>
                <?php endif; ?>

                
                <?php if ($this->is('index') && themeOption('enableHitokoto', '1') === '1'): ?>
                <div class="hitokoto-banner" id="hitokoto">
                    <i class="fa-solid fa-quote-left" style="color:var(--accent);margin-right:10px;font-size:14px;position:relative;z-index:2;"></i>
                    <span class="hitokoto-text" id="hitokotoText">加载中…</span>
                </div>
                <?php endif; ?>

                
                <?php if (themeOption('enableSortTabs', '1') === '1'): ?>
                <div class="content-tabs" id="sortTabs">
                    <span class="content-tab active" data-sort="default">最新发布</span>
                    <span class="content-tab" data-sort="views">热度排序</span>
                    <span class="content-tab" data-sort="comments">评论排序</span>
                </div>
                <?php endif; ?>

                
                <?php
                    // ── 置顶文章 ──
                    $stickyCids = array();
                    if ($this->is('index')):
                        $stickyIdsStr = trim(themeOption('stickyPostIds', ''));
                        if (!empty($stickyIdsStr)):
                            $stickyIds = array_filter(array_map('intval', explode(',', $stickyIdsStr)), function($v) { return $v > 0; });
                            $stickyIds = array_slice($stickyIds, 0, 3);
                            if (!empty($stickyIds)):
                                try {
                                    $db = \Typecho\Db::get();
                                    $stickyRows = $db->fetchAll(
                                        $db->select('table.contents.cid', 'table.contents.title', 'table.contents.slug', 'table.contents.text', 'table.contents.created', 'table.contents.commentsNum')
                                        ->from('table.contents')
                                        ->where('table.contents.type = ?', 'post')
                                        ->where('table.contents.status = ?', 'publish')
                                        ->where('table.contents.cid IN ?', $stickyIds)
                                    );
                                    $orderMap = array_flip($stickyIds);
                                    usort($stickyRows, function($a, $b) use ($orderMap) {
                                        return ($orderMap[$a['cid']] ?? 999) - ($orderMap[$b['cid']] ?? 999);
                                    });
                                    if (!empty($stickyRows)):
                                        $stickyCids = array_column($stickyRows, 'cid');
                    ?>
                    <div class="sticky-wrap sticky-count-<?php echo count($stickyRows); ?>">
                        <?php foreach ($stickyRows as $srow): ?>
                            <?php
                                $sprocessed = themePhotoFilter::processShortcodes($srow['text']);
                                $simg = getPostImages($sprocessed, 1);
                                $scover = !empty($simg) ? $simg[0] : '';
                                $sviews = getPostViews($srow['cid']);
                                $surl = \Typecho\Router::url('post', array('cid' => $srow['cid'], 'slug' => $srow['slug']), \Helper::options()->index);
                            ?>
                            <a class="sticky-card" href="<?php echo $surl; ?>">
                                <div class="sticky-img">
                                    <?php if ($scover): ?>
                                        <img src="<?php echo htmlspecialchars($scover); ?>" alt="" loading="lazy">
                                    <?php else: ?>
                                        <div class="sticky-img-fallback"><?php echo mb_substr($srow['title'], 0, 1); ?></div>
                                    <?php endif; ?>
                                    <span class="sticky-badge">置顶</span>
                                </div>
                                <div class="sticky-body">
                                    <div class="sticky-title"><?php echo htmlspecialchars($srow['title']); ?></div>
                                    <div class="sticky-divider"></div>
                                    <div class="sticky-meta">
                                        <span><i class="fa-regular fa-clock"></i> <?php echo date('Y-m-d', $srow['created']); ?></span>
                                        <span><i class="fa-regular fa-eye"></i> <?php echo $sviews; ?></span>
                                        <span><i class="fa-regular fa-comment"></i> <?php echo intval($srow['commentsNum']); ?></span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php
                                    endif;
                                } catch (Exception $e) {}
                            endif;
                        endif;
                    endif;
                    ?>

                    <?php if ($this->have()): ?>
                    <?php while ($this->next()): ?>
                        <?php
                        if (in_array($this->cid, $stickyCids)) continue;
                        $postViews = getPostViews($this->cid);
                        $postComments = $this->commentsNum;
                        ?>

                        <?php if ($cardStyle === 'simple'): ?>
                        
                        <a href="<?php $this->permalink(); ?>" class="post-card-simple" data-views="<?php echo $postViews; ?>" data-comments="<?php echo $postComments; ?>">
                            <div class="pcs-body">
                                <h3 class="pcs-title"><?php $this->title(); ?></h3>
                                <p class="pcs-excerpt"><?php echo getExcerpt($this->content, 120); ?></p>
                                <div class="pcs-meta">
                                    <span class="pcs-meta-item"><i class="fa-regular fa-clock"></i> <?php echo $this->date('Y-m-d'); ?></span>
                                    <span class="pcs-meta-item"><i class="fa-regular fa-eye"></i> <?php echo $postViews; ?></span>
                                    <span class="pcs-meta-item"><i class="fa-regular fa-comment"></i> <?php $this->commentsNum('0', '1', '%d'); ?></span>
                                </div>
                            </div>
                        </a>

                        <?php else: ?>
                        
                        <div class="post-card" data-views="<?php echo $postViews; ?>" data-comments="<?php echo $postComments; ?>">
                            <div class="post-header">
                                <?php $logoImg = themeOption('logoImage', ''); if ($logoImg): ?>
                                    <img class="post-avatar" src="<?php echo $logoImg; ?>" alt="">
                                <?php else: ?>
                                    <div class="post-avatar post-avatar-text"><?php echo mb_substr($this->options->title, 0, 1); ?></div>
                                <?php endif; ?>
                                <div class="post-meta">
                                    <div class="post-nickname"><a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a></div>
                                    <div class="post-time"><?php echo humanTimeDiff($this->created); ?> · <?php $this->author->screenName(); ?></div>
                                </div>
                            </div>

                            <a href="<?php $this->permalink(); ?>" class="post-content-link">
                                <div class="post-content">
                                    <?php echo getExcerpt($this->content, 200); ?>
                                </div>
                            </a>

                            <?php $images = getPostImages($this->content, (int)themeOption('showImageCount', 9)); if (!empty($images)): ?>
                                <div class="img-grid g<?php echo min(count($images), 9); ?>">
                                    <?php foreach ($images as $i => $img): ?>
                                        <div class="img-cell">
                                            <img src="<?php echo $img; ?>" alt="" loading="lazy">
                                            <?php if ($i === 8 && count($images) > 9): ?>
                                                <div class="img-more">+<?php echo count($images) - 9; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="post-actions">
                                <button class="action-btn" onclick="location.href='<?php $this->permalink(); ?>#comments'">
                                    <i class="fa-regular fa-comment"></i>
                                    <span><?php $this->commentsNum('0', '1', '%d'); ?></span>
                                </button>
                                <button class="action-btn" onclick="sharePost('<?php $this->permalink(); ?>', '<?php $this->title(); ?>')">
                                    <i class="fa-solid fa-share-nodes"></i>
                                    <span>分享</span>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>

                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="post-card" style="text-align:center;color:var(--text-secondary);padding:40px;">
                        <i class="fa-solid fa-inbox" style="font-size:32px;margin-bottom:12px;display:block;color:var(--text-light);"></i>
                        还没有发表文章
                    </div>
                <?php endif; ?>

                
                <?php if ($this->have()): ?>
                    <div class="pagination">
                        <?php $this->pageNav('← 上一页', '下一页 →'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
