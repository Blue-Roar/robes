<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 留言
 *
 * @package custom
 * @type page
 * @title 留言
 *
 */
$this->need('header.php');
?>

<div class="main-body">
    <?php $this->need('sidebar.php'); ?>
    
    <div class="content-area">
        <div class="fade-top" id="fadeTop"></div>
        <div class="content-inner">
            <div class="content-scroll" id="feed">
                <article class="article standalone-page">
                    <?php if ($this->content): ?>
                        <div class="article-content"><?php $this->content(); ?></div>
                    <?php endif; ?>

                    <!-- 留言排行 -->
                    <?php
                    $db = \Typecho\Db::get();
                    $prefix = $db->getPrefix();
                    $siteAuthor = $this->options->screenName;
                    $topCommentersQuery = $db->select('author', 'mail', array('COUNT(coid)' => 'cnt'))
                        ->from($prefix . 'comments')
                        ->where('status = ?', 'approved')
                        ->group('author')
                        ->order('cnt', \Typecho\Db::SORT_DESC)
                        ->limit(10);
                    if ($siteAuthor) $topCommentersQuery->where('author != ?', $siteAuthor);
                    $topCommenters = $db->fetchAll($topCommentersQuery);
                    if (!empty($topCommenters)):
                    ?>
                    <div class="pg-leaderboard">
                        <div class="pg-lb-title"><i class="fa-solid fa-trophy"></i> 活跃榜</div>
                        <div class="pg-lb-list">
                            <?php foreach ($topCommenters as $i => $c): ?>
                                <?php
                                $mail = isset($c['mail']) ? trim($c['mail']) : '';
                                $author = isset($c['author']) ? $c['author'] : '匿名';
                                $hash = $mail ? md5(strtolower($mail)) : md5('');
                                $cnt = isset($c['cnt']) ? $c['cnt'] : 0;
                                ?>
                                <div class="pg-lb-item">
                                    <span class="pg-lb-rank pg-lb-rank-<?php echo min($i + 1, 3); ?>"><?php echo $i + 1; ?></span>
                                    <img class="pg-lb-avatar" src="https://weavatar.com/avatar/<?php echo $hash; ?>?s=48&d=mp" alt="" loading="lazy" onerror="this.style.display='none'">
                                    <span class="pg-lb-name"><?php echo htmlspecialchars($author); ?></span>
                                    <span class="pg-lb-count"><?php echo $cnt; ?> 条</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div id="comments">
                        <?php $this->need('comments.php'); ?>
                    </div>
                </article>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
