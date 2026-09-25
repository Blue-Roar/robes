<?php
/**
 * 导航页面模板
 * @package custom
 * @type page
 * @title 导航
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
incrementPostViews($this->cid);
$wordCount = getWordCount($this->content);
$lastModified = getLastModified($this->cid);
$isUpdated = ($lastModified > $this->created);

/**
 * 渲染单个链接卡片
 * @param array $link 形如 ['title'=>'', 'desc'=>'', 'link'=>'', 'icon'=>'', 'avatar'=>'', 'sort'=>'']
 */
function _renderLinkCard($link) {
    $name   = $link['title']  ?? ($link['name'] ?? '未知');
    $url    = $link['link']   ?? ($link['url']  ?? '#');
    $desc   = $link['desc']   ?? ($link['description'] ?? '');
    $sort   = $link['sort']   ?? '';
    $icon   = $link['icon']   ?? '';
    $avatar = $link['avatar'] ?? '';

    // 头像回退处理
    if (empty($avatar)) { 
        if (!empty($url) && $url !== '#') { // 默认用链接目标站点的 favicon
            $host = parse_url($url, PHP_URL_HOST);
            if ($host) {
                $avatar = '//' . $host . '/favicon.ico';
            }
        } else { // 如果连 URL 都没有，回退到当前站点 logo
            $avatar = themeOption('faviconUrl', '/favicon.ico');
        }
    }

    ?>
    <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener noreferrer" class="link-card" data-url="<?php echo htmlspecialchars($url); ?>">
        <div class="link-avatar"><?php if (!empty($icon)): ?>
            <div class="link-avatar-text"><i class="fa-xl <?php echo htmlspecialchars($icon); ?>"></i></div>
        <?php else: ?>
            <?php if ($avatar): ?>
                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="<?php echo htmlspecialchars($name); ?>" loading="lazy"
                    onerror="this.parentElement.innerHTML='<div class=\'link-avatar-text\'>' + '<?php echo htmlspecialchars(mb_substr($name, 0, 1)); ?>' + '</div>'">
            <?php else: ?>
                <div class="link-avatar-text"><?php echo htmlspecialchars(mb_substr($name, 0, 1)); ?></div>
            <?php endif; ?>
        <?php endif; ?></div>
        <div class="link-info">
            <div class="link-name"><?php echo htmlspecialchars($name); ?></div>
            <?php if ($desc): ?><div class="link-desc"><?php echo htmlspecialchars(mb_substr($desc, 0, 50)); ?></div><?php endif; ?>
            <div class="link-meta">
                <?php if (!empty($sort)): ?>
                    <span class="link-category-tag"><i class="fa-solid fa-tag"></i> <?php echo htmlspecialchars($sort); ?></span>
                <?php endif; ?>
                <span class="link-status" title="检测中"></span>
            </div>
        </div>
        <div class="link-external-icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></div>
    </a>
    <?php
}

// 1. 解码 HTML 实体
$raw = html_entity_decode($this->content, ENT_QUOTES, 'UTF-8');
// 2. 去掉 <p> <br /> 等标签
$raw = strip_tags($raw);
// 3. 去掉首尾空白
$raw = trim($raw);
// 4. 解析
$navigationContent = json_decode($raw, true);
$jsonError = json_last_error();

// 5. 整理数据：平铺数组 -> 全部链接 + 按分类分组
$allLinks = [];          // 全部链接（平铺）
$linksByCategory = [];   // 分类 => 链接数组

if ($jsonError === JSON_ERROR_NONE && is_array($navigationContent)) {
    foreach ($navigationContent as $item) {
        if (!is_array($item)) continue;
        if (empty($item['link'])) continue;

        // 分类名取 sort 字段，没有则归入「未分类」
        $catName = !empty($item['sort']) ? $item['sort'] : '未分类';

        $allLinks[] = $item;
        if (!isset($linksByCategory[$catName])) {
            $linksByCategory[$catName] = [];
        }
        $linksByCategory[$catName][] = $item;
    }
}
?>

<div class="main-body">
    <?php $this->need('sidebar.php'); ?>

    <div class="content-area">
        <div class="fade-top" id="fadeTop"></div>
        <div class="content-inner">
            <div class="content-scroll" id="feed">
                <article class="article standalone-page">

                <?php if ($jsonError !== JSON_ERROR_NONE): ?>
                    <div class="links-empty">
                        <i class="fa-solid fa-circle-exclamation" style="font-size:32px;margin-bottom:12px;display:block;color:#ef4444;"></i>
                        JSON 错误：<?php echo htmlspecialchars(json_last_error_msg(), ENT_QUOTES, 'UTF-8'); ?>
                        <pre><?php echo htmlspecialchars($raw, ENT_QUOTES, 'UTF-8'); ?></pre>
                    </div>
                <?php elseif (empty($allLinks)): ?>
                    <div class="links-empty">
                        <i class="fa-solid fa-link-slash" style="font-size:32px;margin-bottom:12px;display:block;color:var(--text-light);"></i>
                        暂无导航链接
                    </div>
                <?php else: ?>
                    <?php
                    // 是否显示分类 tab：主题选项开启 且 分类数 > 1
                    $showCat = themeOption('showLinksCategory', '1') == '1' && count($linksByCategory) > 1;
                    ?>

                    <?php if ($showCat): ?>
                    <div class="links-category-tabs" id="linksCategoryTabs">
                        <button class="links-cat-tab active" data-category="all">
                            全部
                            <span class="links-cat-count"><?php echo count($allLinks); ?></span>
                        </button>
                        <?php foreach ($linksByCategory as $catName => $catLinks): ?>
                            <button class="links-cat-tab" data-category="<?php echo htmlspecialchars($catName); ?>">
                                <?php echo htmlspecialchars($catName); ?>
                                <span class="links-cat-count"><?php echo count($catLinks); ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- 全部（平铺） -->
                    <div class="links-category-group" data-category="all">
                        <div class="links-grid">
                            <?php foreach ($allLinks as $link): ?>
                                <?php _renderLinkCard($link); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- 分类（默认隐藏） -->
                    <?php foreach ($linksByCategory as $catName => $catLinks): ?>
                    <div class="links-category-group" data-category="<?php echo htmlspecialchars($catName); ?>" style="display:none">
                        <div class="links-grid">
                            <?php foreach ($catLinks as $link): ?>
                                <?php _renderLinkCard($link); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php else: ?>
                    <!-- 不显示分类：全部平铺 -->
                    <div class="links-category-group">
                        <div class="links-grid">
                            <?php foreach ($allLinks as $link): ?>
                                <?php _renderLinkCard($link); ?>
                            <?php endforeach; ?>
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