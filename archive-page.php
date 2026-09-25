<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
/**
 * 归档页面
 *
 * @package custom
 * @type page
 * @title 归档
 */

$db = \Typecho\Db::get();
$rows = $db->fetchAll(
    $db->select('table.contents.cid', 'table.contents.slug', 'table.contents.created', 'table.contents.title', 'table.contents.commentsNum')
    ->from('table.contents')
    ->where('table.contents.type = ?', 'post')
    ->where('table.contents.status = ?', 'publish')
    ->order('table.contents.created', \Typecho\Db::SORT_DESC)
);
$siteUrl = \Helper::options()->siteUrl;
$cids = array_column($rows, 'cid');
$viewsMap = $cids ? getBatchViews($cids) : array();

$allPosts = array();
$years = array();

foreach ($rows as $row) {
    $y = date('Y', $row['created']);
    if (!isset($years[$y])) $years[$y] = 0;
    $years[$y]++;
    $v = isset($viewsMap[$row['cid']]) ? $viewsMap[$row['cid']] : 0;
    $c = intval($row['commentsNum']);
    $c = intval($row['commentsNum']);

    $permalink = \Typecho\Router::url('post', array('cid' => $row['cid'], 'slug' => $row['slug']), \Helper::options()->index);
    $allPosts[] = array(
        'year'  => $y,
        'md'    => date('n月j日', $row['created']),
        'title' => $row['title'],
        'url'   => $permalink,
        'views' => $v,
        'cmts'  => $c,
    );
}
krsort($years);

$grouped = array();
foreach ($allPosts as $p) {
    $grouped[$p['year']][] = $p;
}

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

                    <!-- 年份筛选标签 -->
                    <div class="links-category-tabs" id="linksCategoryTabs">
                        <?php foreach ($years as $y => $cnt): ?>
                            <button class="links-cat-tab<?php echo $y === max(array_keys($years)) ? ' active' : ''; ?>" data-category="<?php echo $y; ?>">
                                <?php echo $y; ?>
                                <span class="links-cat-count"><?php echo $cnt; ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- 文章列表 -->
                    <div class="pg-arch-list" id="archList">
                        <?php foreach ($grouped as $y => $posts): ?>
                            <div class="links-category-group" data-category="<?php echo $y; ?>"<?php echo $y !== max(array_keys($grouped)) ? ' style="display:none"' : ''; ?>>
                                <?php foreach ($posts as $p): ?>
                                    <a class="pg-arch-row" href="<?php echo $p['url']; ?>">
                                        <span class="pg-arch-row-dot"></span>
                                        <span class="pg-arch-row-date"><?php echo $p['md']; ?></span>
                                        <span class="pg-arch-row-title"><?php echo htmlspecialchars($p['title']); ?></span>
                                        <span class="pg-arch-row-meta">
                                            <?php if ($p['views'] > 0): ?><span><i class="fa-regular fa-eye"></i> <?php echo $p['views']; ?></span><?php endif; ?>
                                            <?php if ($p['cmts'] > 0): ?><span><i class="fa-regular fa-comment"></i> <?php echo $p['cmts']; ?></span><?php endif; ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </article>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
