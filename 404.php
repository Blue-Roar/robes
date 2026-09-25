<?php
/**
 * 404模板
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$randomPosts = getRandomPosts(4);
?>

<div class="main-body">
    <?php $this->need('sidebar.php'); ?>
    
    <div class="content-area">
        <div class="fade-top" id="fadeTop"></div>
        <div class="content-inner">
            <div class="content-scroll" id="feed">
                <div class="error-page">
                    <div class="error-code">404</div>
                    <h2 class="error-title">页面走丢了</h2>
                    <p class="error-desc">你访问的页面不存在或已被删除，可能是链接有误或页面已被移除</p>
                    <form class="error-search" action="<?php $this->options->siteUrl(); ?>" method="get">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="s" placeholder="搜索文章…" autocomplete="off">
                    </form>
                    <div class="error-actions">
                        <a href="<?php $this->options->siteUrl(); ?>" class="error-btn"><i class="fa-solid fa-home"></i> 返回首页</a>
                        <a href="javascript:history.back()" class="error-btn error-btn-outline"><i class="fa-solid fa-arrow-left"></i> 返回上页</a>
                    </div>

                    <?php if (!empty($randomPosts)): ?>
                    <div class="error-recommend">
                        <h3 class="error-recommend-title"><i class="fa-solid fa-shuffle"></i> 随便看看</h3>
                        <div class="error-recommend-grid">
                            <?php foreach ($randomPosts as $rp): ?>
                            <?php $rpImages = getPostImages($rp['text'], 1); $rpCover = !empty($rpImages) ? $rpImages[0] : ''; ?>
                            <a class="error-recommend-card" href="<?php echo \Typecho\Router::url('post', array('cid' => $rp['cid'], 'slug' => $rp['slug']), $this->options->index); ?>">
                                <?php if ($rpCover): ?>
                                <div class="error-recommend-cover"><img src="<?php echo htmlspecialchars($rpCover); ?>" alt="" loading="lazy"></div>
                                <?php else: ?>
                                <div class="error-recommend-cover error-recommend-fallback"><span><?php echo mb_substr($rp['title'], 0, 1); ?></span></div>
                                <?php endif; ?>
                                <div class="error-recommend-info">
                                    <span class="error-recommend-card-title"><?php echo htmlspecialchars($rp['title']); ?></span>
                                    <span class="error-recommend-date"><?php echo date('Y-m-d', $rp['created']); ?></span>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
