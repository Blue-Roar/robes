<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 友情链接
 *
 * @package custom
 * @type page
 * @title 友链
 *
 */
$this->need('header.php');

// 获取所有友情链接（适配寒泥 Links 插件数据表）
$allLinks = array();
$db = \Typecho\Db::get();
$prefix = $db->getPrefix();
$tableName = $prefix . 'links';

// 尝试多种方式查询，兼容不同插件版本
try {
    $tableExists = false;
    try {
        if (strtolower($db->getAdapterName()) === 'pdomysql' || strpos($db->getAdapterName(), 'Mysql') !== false) {
            $result = $db->query('SHOW TABLES LIKE \'' . $tableName . '\'');
            $tableExists = (bool)$db->fetchRow($result);
        } else {
            $result = $db->query('SELECT name FROM sqlite_master WHERE type=\'table\' AND name=\'' . $tableName . '\'');
            $tableExists = (bool)$db->fetchRow($result);
        }
    } catch (Exception $e2) {
        $tableExists = true;
    }

    if ($tableExists) {
        try {
            $allLinks = $db->fetchAll(
                $db->select()->from($tableName)
                    ->where('state = ?', 1)
                    ->order('`order`', \Typecho\Db::SORT_ASC)
            );
        } catch (Exception $e3) {
            $allLinks = $db->fetchAll(
                $db->select()->from($tableName)
                    ->order('`order`', \Typecho\Db::SORT_ASC)
            );
        }
    } else {
        $allLinks = '__TABLE_MISSING__';
    }
} catch (Exception $e) {
    $allLinks = '__ERROR__';
    $linksError = $e->getMessage();
}

// 提取分类
$categories = array();
$linksByCategory = array();
foreach ($allLinks as $link) {
    if (empty($link['url'])) continue;
    $cat = !empty($link['sort']) ? $link['sort'] : '未分类';
    if (!isset($categories[$cat])) {
        $categories[$cat] = 0;
        $linksByCategory[$cat] = array();
    }
    $categories[$cat]++;
    $linksByCategory[$cat][] = $link;
}

// 渲染单个友链卡片的函数
function _renderLinkCard($link) {
    $name  = $link['name'] ?? '好友';
    $url   = $link['url'] ?? '#';
    $img   = $link['image'] ?? '';
    $desc  = $link['description'] ?? '';
    $email = $link['email'] ?? '';
    if (empty($img) && !empty($email))
        $img = 'https://weavatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=80&d=mp';
    ?>
    <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener noreferrer" class="link-card" data-url="<?php echo htmlspecialchars($url); ?>">
        <div class="link-avatar">
            <?php if ($img): ?>
                <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($name); ?>" loading="lazy" onerror="this.parentElement.innerHTML='<div class=\'link-avatar-text\'>' + '<?php echo mb_substr($name, 0, 1); ?>' + '</div>'">
            <?php else: ?>
                <div class="link-avatar-text"><?php echo mb_substr($name, 0, 1); ?></div>
            <?php endif; ?>
        </div>
        <div class="link-info">
            <div class="link-name"><?php echo htmlspecialchars($name); ?></div>
            <?php if ($desc): ?><div class="link-desc"><?php echo mb_substr($desc, 0, 50); ?></div><?php endif; ?>
            <div class="link-meta">
                <?php if (!empty($link['sort'])): ?>
                    <span class="link-category-tag"><i class="fa-solid fa-tag"></i> <?php echo htmlspecialchars($link['sort']); ?></span>
                <?php endif; ?>
                <span class="link-status" title="检测中"></span>
            </div>
        </div>
        <div class="link-external-icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></div>
    </a>
    <?php
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

                    <?php if ($allLinks === '__TABLE_MISSING__'): ?>
                        <div class="links-empty">
                            <i class="fa-solid fa-plug" style="font-size:32px;margin-bottom:12px;display:block;color:var(--text-light);"></i>
                            友情链接数据表不存在，请先启用寒泥友情链接插件
                        </div>
                    <?php elseif ($allLinks === '__ERROR__'): ?>
                        <div class="links-empty">
                            <i class="fa-solid fa-circle-exclamation" style="font-size:32px;margin-bottom:12px;display:block;color:#ef4444;"></i>
                            查询友情链接出错：<?php echo htmlspecialchars($linksError ?? '未知错误'); ?>
                        </div>
                    <?php elseif (empty($allLinks)): ?>
                        <div class="links-empty">
                            <i class="fa-solid fa-link-slash" style="font-size:32px;margin-bottom:12px;display:block;color:var(--text-light);"></i>
                            暂无友情链接，请在后台添加
                        </div>
                    <?php else: ?>
                        <?php $showCat = themeOption('showLinksCategory', '1') == '1' && !empty($categories); ?>

                        <?php if ($showCat): ?>
                        <div class="links-category-tabs" id="linksCategoryTabs">
                            <button class="links-cat-tab active" data-category="all">
                                全部
                                <span class="links-cat-count"><?php echo count($allLinks); ?></span>
                            </button>
                            <?php foreach ($categories as $catName => $catCount): ?>
                                <button class="links-cat-tab" data-category="<?php echo htmlspecialchars($catName); ?>">
                                    <?php echo htmlspecialchars($catName); ?>
                                    <span class="links-cat-count"><?php echo $catCount; ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <!-- 全部（平铺，无分组） -->
                        <div class="links-category-group" data-category="all">
                            <div class="links-grid">
                                <?php foreach ($allLinks as $link): if (empty($link['url'])) continue; _renderLinkCard($link); endforeach; ?>
                            </div>
                        </div>

                        <!-- 分类（默认隐藏） -->
                        <?php foreach ($linksByCategory as $catName => $catLinks): ?>
                        <div class="links-category-group" data-category="<?php echo htmlspecialchars($catName); ?>" style="display:none">
                            <div class="links-grid">
                                <?php foreach ($catLinks as $link): _renderLinkCard($link); endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <?php else: ?>
                        <div class="links-category-group">
                            <div class="links-grid">
                                <?php foreach ($allLinks as $link): if (empty($link['url'])) continue; _renderLinkCard($link); endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>

                </article>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
