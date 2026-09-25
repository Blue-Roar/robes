<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 相册
 *
 * @package custom
 * @type page
 * @title 相册
 */
$this->need('header.php');

$db = \Typecho\Db::get();
$prefix = $db->getPrefix();
$rows = $db->fetchAll(
    $db->select('table.contents.cid', 'table.contents.title', 'table.contents.slug', 'table.contents.text', 'table.contents.created', 'table.contents.commentsNum')
    ->from('table.contents')
    ->join($prefix . 'relationships', 'table.contents.cid = ' . $prefix . 'relationships.cid', \Typecho\Db::LEFT_JOIN)
    ->where('table.contents.type = ?', 'post')
    ->where('table.contents.status = ?', 'publish')
    ->where($prefix . 'relationships.mid = ?', intval(themeOption('galleryCategoryId', '6')))
    ->order('table.contents.created', \Typecho\Db::SORT_DESC)
);

$albums = array();
foreach ($rows as $row) {
    $text = $row['text'];
    $imgs = array();
    // <img> 标签
    if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/', $text, $m)) $imgs = array_merge($imgs, $m[1]);
    // Markdown 图片语法 ![](url)
    if (preg_match_all('/!\[[^\]]*\]\(([^)]+)\)/', $text, $m)) $imgs = array_merge($imgs, $m[1]);
    // 裸图片 URL
    if (preg_match_all('/https?:\/\/[^\s"\'<>\)]+\.(?:jpg|jpeg|png|gif|webp|bmp)/i', $text, $m)) $imgs = array_merge($imgs, $m[0]);
    // 本地上传路径
    if (preg_match_all('/\/usr\/uploads\/[^\s"\'<>\)]+/i', $text, $m)) $imgs = array_merge($imgs, $m[0]);
    $imgs = array_values(array_unique($imgs));

    $albums[] = array(
        'title'    => $row['title'],
        'cover'    => !empty($imgs) ? $imgs[0] : '',
        'date'     => date('Y-m-d', $row['created']),
        'count'    => count($imgs),
        'comments' => intval($row['commentsNum']),
        'cid'      => $row['cid'],
        'slug'     => $row['slug'],
    );
}
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

                    <?php if (!empty($albums)): ?>
                        <div class="album-grid">
                            <?php foreach ($albums as $album): ?>
                                <a class="album-card" href="<?php echo \Typecho\Router::url('post', array('cid' => $album['cid'], 'slug' => $album['slug']), $this->options->index); ?>">
                                    <div class="album-cover">
                                        <?php if ($album['cover']): ?>
                                            <img src="<?php echo htmlspecialchars($album['cover']); ?>" alt="" loading="lazy">
                                        <?php else: ?>
                                            <div class="album-cover-empty"><i class="fa-solid fa-images"></i></div>
                                        <?php endif; ?>
                                        <span class="album-count"><i class="fa-solid fa-image"></i> <?php echo $album['count']; ?></span>
                                    </div>
                                    <div class="album-info">
                                        <span class="album-title"><?php echo htmlspecialchars($album['title']); ?></span>
                                        <span class="album-meta"><?php echo $album['date']; ?> · <?php echo $album['comments']; ?> 评论</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="links-empty">
                            <i class="fa-solid fa-images" style="font-size:32px;margin-bottom:12px;display:block;color:var(--text-light);"></i>
                            暂无相册，请在分类ID=<?php echo intval(themeOption('galleryCategoryId', '6')); ?>的文章中插入图片
                        </div>
                    <?php endif; ?>
                </article>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
