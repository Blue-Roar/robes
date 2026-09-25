<?php
/**
 * Robes
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;


function themeConfig($form) {
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('blogName', null, '', _t('博客名称'), _t('显示在顶栏左侧')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('logoImage', null, '', _t('Logo图片'), _t('填写图片URL，留空显示首字')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('footerText', null, '', _t('底部版权')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('icpText', null, '', _t('备案信息'), _t('如：粤ICP备12345678号')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('accentColor', null, '#94c8d8', _t('强调色'), _t('如 #94c8d8')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('bgColor', null, '#E0E5EB', _t('背景色')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('glassBlur', null, '20px', _t('模糊度'), _t('如 16px、20px')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('glassOpacity', null, '0.45', _t('透明度'), _t('0-1之间')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('sidebarWidth', null, '230', _t('侧边栏宽度'), _t('单位px')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('cardStyle', array('social' => '社交风格', 'simple' => '简约风格'), 'social', _t('卡片风格')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('enablePJAX', array('1' => '开启', '0' => '关闭'), '1', _t('PJAX无刷新')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('enableLightbox', array('1' => '开启', '0' => '关闭'), '1', _t('图片灯箱')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('showImageCount', array('9' => '最多9张', '6' => '最多6张', '4' => '最多4张', '0' => '不限制'), '9', _t('首页图片数量')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('galleryCategoryId', null, '6', _t('相册分类ID'), _t('相册页面显示哪个分类下的文章')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('stickyPostIds', null, '', _t('置顶文章ID'), _t('填写文章ID，多个用英文逗号分隔，如：1,3,5')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('enableHitokoto', array('1' => '开启', '0' => '关闭'), '1', _t('首页一言')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('enableSortTabs', array('1' => '开启', '0' => '关闭'), '1', _t('排序切换')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('enableTOC', array('1' => '开启', '0' => '关闭'), '1', _t('文章目录')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('darkMode', array('auto' => '跟随系统', 'light' => '默认浅色', 'dark' => '默认深色', 'switch' => '用户切换'), 'auto', _t('深色模式'), _t('auto=跟随系统偏好, light=默认浅色, dark=默认深色, switch=允许用户手动切换')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('showSidebarCategories', array('1' => '显示', '0' => '隐藏'), '1', _t('侧边栏分类'), _t('是否在侧边栏显示分类列表')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('showSidebarTags', array('1' => '显示', '0' => '隐藏'), '1', _t('侧边栏标签'), _t('是否在侧边栏显示标签云')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('borderRadius', array('small' => '小 (8px)', 'medium' => '中 (14px)', 'large' => '大 (20px)'), 'medium', _t('圆角大小')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('topbarHeight', array('compact' => '紧凑 (70px)', 'standard' => '标准 (90px)', 'loose' => '宽松 (110px)'), 'standard', _t('顶栏高度')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('postAvatar', null, '', _t('文章页头像'), _t('文章详情页显示的头像URL，留空使用Logo')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('enablePostNav', array('1' => '显示', '0' => '隐藏'), '1', _t('上下篇导航'), _t('文章底部的上一篇/下一篇')));

    // 社交链接
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('socialGithub', null, '', _t('GitHub'), _t('填写用户名或完整链接')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('socialTwitter', null, '', _t('Twitter/X'), _t('填写用户名或完整链接')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('socialWeibo', null, '', _t('微博'), _t('填写主页链接')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('socialWechat', null, '', _t('微信'), _t('填写微信号')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('socialBilibili', null, '', _t('Bilibili'), _t('填写UID或主页链接')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('socialTelegram', null, '', _t('Telegram'), _t('填写用户名')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('socialEmail', null, '', _t('邮箱'), _t('填写邮箱地址')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('socialRss', null, '', _t('RSS'), _t('留空使用默认')));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('siteStartDate', null, '', _t('建站日期'), _t('格式：2024-01-01，运行时间自动计算')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('faviconUrl', null, '', _t('Favicon'), _t('填写图标URL，留空使用默认')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('ogImage', null, '', _t('默认分享图'), _t('无文章图片时使用的默认缩略图URL')));

    // 背景光晕
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('orbColor', null, '', _t('光晕颜色'), _t('如 #8B9CF7，留空使用默认')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Text('orbSize', null, '', _t('光晕大小'), _t('单位px，默认400，建议200-600')));

    // 友链设置
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('showLinksCategory', array('1' => '显示', '0' => '隐藏'), '1', _t('友链分类'), _t('是否在友链页面显示分类筛选')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Select('enableLinkCheck', array('1' => '开启', '0' => '关闭'), '1', _t('友链检测'), _t('是否检测友情链接的在线状态')));

    // 背景SVG
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Textarea('bgSvgLight', null, '', _t('背景SVG'), _t('粘贴SVG代码或CSS渐变，留空使用默认云朵背景')));

    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Textarea('customCSS', null, '', _t('自定义CSS')));
    $form->addInput(new \Typecho\Widget\Helper\Form\Element\Textarea('customJS', null, '', _t('自定义JS')));

    // 引入 Tab UI
    $tabsFile = dirname(__FILE__) . '/admin/config-tabs.html';
    if (file_exists($tabsFile)) {
        include $tabsFile;
    }
}

// ══════════════════════════════════════

function themeOption($key, $default = '') {
    $val = \Helper::options()->{$key};
    return ($val !== null && $val !== '') ? $val : $default;
}

function themeCSSVars() {
    $accent = themeOption('accentColor', '#94c8d8');
    $bg = themeOption('bgColor', '#E0E5EB');
    $blur = themeOption('glassBlur', '20px');
    $opacity = floatval(themeOption('glassOpacity', '0.45'));
    $sidebarW = themeOption('sidebarWidth', '230');
    $accentHover = adjustBrightness($accent, -20);
    $glassBg = hexToRGBA('#ffffff', $opacity);
    $glassBgStrong = hexToRGBA('#ffffff', min(1, $opacity + 0.17));

    // 圆角
    $radiusMap = array('small' => '8', 'medium' => '14', 'large' => '20');
    $radiusKey = themeOption('borderRadius', 'medium');
    $radius = isset($radiusMap[$radiusKey]) ? $radiusMap[$radiusKey] : '14';

    // 顶栏高度
    $heightMap = array('compact' => '70', 'standard' => '90', 'loose' => '110');
    $heightKey = themeOption('topbarHeight', 'standard');
    $topbarH = isset($heightMap[$heightKey]) ? $heightMap[$heightKey] : '90';

    echo "<style>:root{--accent:{$accent};--accent-hover:{$accentHover};--accent-soft:" . hexToRGBA($accent, 0.1) . ";--accent-glow:" . hexToRGBA($accent, 0.25) . ";--bg:{$bg};--glass-blur:{$blur};--glass-bg:{$glassBg};--glass-bg-strong:{$glassBgStrong};--sidebar-w:{$sidebarW}px;--radius:{$radius}px;--radius-sm:" . max(6, $radius - 4) . "px;--radius-xs:" . max(4, $radius - 8) . "px;--topbar-h:{$topbarH}px}\n";

    // 自定义光晕
    $orbColor = trim(themeOption('orbColor', ''));
    $orbSize = intval(themeOption('orbSize', 0));
    if ($orbColor || $orbSize) {
        $size = $orbSize > 0 ? $orbSize : 400;
        if ($orbColor) {
            echo ".orb-1,.orb-2,.orb-3{background:{$orbColor}!important}\n";
        }
        if ($orbSize) {
            echo ".orb-1,.orb-2,.orb-3{width:{$size}px!important;height:{$size}px!important}\n";
        }
    }

    // 自定义背景
    $bgSvgLight = trim(themeOption('bgSvgLight', ''));
    if ($bgSvgLight) {
        if (stripos($bgSvgLight, 'gradient') !== false || stripos($bgSvgLight, 'repeating') !== false) {
            echo "html,body{background-image:{$bgSvgLight}!important}\n";
        } else {
            $encoded = 'data:image/svg+xml,' . rawurlencode($bgSvgLight);
            echo "html,body{background-image:url(\"{$encoded}\")!important;background-repeat:repeat!important;background-size:auto!important}\n";
        }
    }

    $customCSS = themeOption('customCSS', '');
    if ($customCSS) echo $customCSS . "\n";
    echo "</style>\n";
}

function adjustBrightness($hex, $steps) {
    $hex = ltrim($hex, '#');
    return sprintf('#%02x%02x%02x',
        max(0, min(255, hexdec(substr($hex, 0, 2)) + $steps)),
        max(0, min(255, hexdec(substr($hex, 2, 2)) + $steps)),
        max(0, min(255, hexdec(substr($hex, 4, 2)) + $steps))
    );
}

function hexToRGBA($hex, $alpha) {
    $hex = ltrim($hex, '#');
    return "rgba(" . hexdec(substr($hex, 0, 2)) . "," . hexdec(substr($hex, 2, 2)) . "," . hexdec(substr($hex, 4, 2)) . ",{$alpha})";
}

function getPostImages($content, $limit = 9) {
    $urls = array();
    if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/', $content, $m)) $urls = array_merge($urls, $m[1]);
    if (preg_match_all('/<img[^>]+data-src=["\']([^"\']+)["\']/', $content, $m)) $urls = array_merge($urls, $m[1]);
    if (preg_match_all('/\[img\]\s*(https?:\/\/[^\s\[\]]+?)\s*\[\/img\]/i', $content, $m)) $urls = array_merge($urls, $m[1]);
    if (preg_match_all('/!\[[^\]]*\]\(([^)]+)\)/', $content, $m)) $urls = array_merge($urls, $m[1]);
    if (preg_match_all('/<a[^>]+href=["\']([^"\']+?\.(?:jpg|jpeg|png|gif|webp|bmp))["\'][^>]*>/i', $content, $m)) $urls = array_merge($urls, $m[1]);
    if (preg_match_all('/https?:\/\/[^\s"\'<>\)]+\.(?:jpg|jpeg|png|gif|webp|bmp)/i', $content, $m)) $urls = array_merge($urls, $m[0]);
    if (preg_match_all('/\/usr\/uploads\/[^\s"\'<>\)]+/i', $content, $m)) $urls = array_merge($urls, $m[0]);
    $urls = array_filter($urls, function($u) {
        return strpos($u, 'data:') !== 0 && !preg_match('/spacer|blank|placeholder|pixel/i', $u);
    });
    // 去重前统一路径格式（去掉域名前缀，避免同张图因带不带域名被当成两张）
    $siteUrl = rtrim(\Helper::options()->siteUrl, '/');
    $urls = array_map(function($u) use ($siteUrl) {
        if (strpos($u, $siteUrl) === 0) return substr($u, strlen($siteUrl));
        return $u;
    }, $urls);
    $urls = array_values(array_unique($urls));
    return $limit > 0 ? array_slice($urls, 0, $limit) : $urls;
}
function getExcerpt($content, $length = 200) {
    $text = preg_replace('/\s+/', ' ', strip_tags($content));
    return mb_strlen($text) > $length ? mb_substr($text, 0, $length) . '...' : $text;
}

function humanTimeDiff($timestamp) {
    $diff = time() - $timestamp;
    if ($diff < 60) return '刚刚';
    if ($diff < 3600) return floor($diff / 60) . '分钟前';
    if ($diff < 86400) return floor($diff / 3600) . '小时前';
    if ($diff < 2592000) return floor($diff / 86400) . '天前';
    return date('Y-m-d', $timestamp);
}

function getPostViews($cid) {
    static $cache = array();
    if (isset($cache[$cid])) return $cache[$cid];
    $db = \Typecho\Db::get();
    $row = $db->fetchRow($db->select('str_value')->from('table.fields')->where('cid = ?', $cid)->where('name = ?', 'views'));
    $cache[$cid] = $row ? intval($row['str_value']) : 0;
    return $cache[$cid];
}

function incrementPostViews($cid) {
    $cookieKey = 'robes_views_' . $cid;
    if (isset($_COOKIE[$cookieKey])) return;
    $db = \Typecho\Db::get();
    $row = $db->fetchRow($db->select('str_value')->from('table.fields')->where('cid = ?', $cid)->where('name = ?', 'views'));
    $views = $row ? intval($row['str_value']) : 0;
    if ($row) {
        $db->query($db->update('table.fields')->rows(array('str_value' => $views + 1))->where('cid = ?', $cid)->where('name = ?', 'views'));
    } else {
        $db->query($db->insert('table.fields')->rows(array('cid' => $cid, 'name' => 'views', 'type' => 'str', 'str_value' => 1)));
    }
    setcookie($cookieKey, '1', time() + 300, '/');
}

/**
 * 批量获取文章浏览量（消除 N+1 查询）
 */
function getBatchViews($cids) {
    if (empty($cids)) return array();
    $db = \Typecho\Db::get();
    $rows = $db->fetchAll(
        $db->select('cid', 'str_value')->from('table.fields')
            ->where('name = ?', 'views')
            ->where('cid IN ?', $cids)
    );
    $result = array();
    foreach ($rows as $row) $result[intval($row['cid'])] = intval($row['str_value']);
    foreach ($cids as $cid) if (!isset($result[$cid])) $result[$cid] = 0;
    return $result;
}
/**
 * 输出 OG / Twitter Card / RSS meta 标签
 */
function themeSEO() {
    $options = \Helper::options();
    $request = \Typecho\Request::getInstance();
    $isPost = $request->is('post');
    $isPage = $request->is('page');

    $title = htmlspecialchars($options->title);
    $siteUrl = rtrim($options->siteUrl, '/');
    $description = htmlspecialchars($options->description ?: $title);
    $defaultImage = themeOption('ogImage', '');

    if ($isPost || $isPage) {
        $db = \Typecho\Db::get();
        $cid = $request->get('cid');
        if ($cid) {
            $row = $db->fetchRow($db->select('title', 'text', 'created', 'modified')->from('table.contents')->where('cid = ?', intval($cid)));
            if ($row) {
                $title = htmlspecialchars($row['title']) . ' - ' . $title;
                $desc = getExcerpt($row['text'], 160);
                $description = htmlspecialchars($desc);
                $images = getPostImages($row['text'], 1);
                $image = !empty($images) ? $images[0] : $defaultImage;
                $url = $siteUrl . $request->getRequestUrl();
                $published = date('c', $row['created']);
                $modified = date('c', $row['modified'] ?: $row['created']);

                echo '<meta property="og:type" content="article">' . "\n";
                echo '<meta property="og:title" content="' . $title . '">' . "\n";
                echo '<meta property="og:description" content="' . $description . '">' . "\n";
                echo '<meta property="og:url" content="' . $url . '">' . "\n";
                if ($image) echo '<meta property="og:image" content="' . htmlspecialchars($image) . '">' . "\n";
                echo '<meta property="article:published_time" content="' . $published . '">' . "\n";
                echo '<meta property="article:modified_time" content="' . $modified . '">' . "\n";
                echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
                echo '<meta name="twitter:title" content="' . $title . '">' . "\n";
                echo '<meta name="twitter:description" content="' . $description . '">' . "\n";
                if ($image) echo '<meta name="twitter:image" content="' . htmlspecialchars($image) . '">' . "\n";
                return;
            }
        }
    }

    // 首页/归档/分类等
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:title" content="' . $title . '">' . "\n";
    echo '<meta property="og:description" content="' . $description . '">' . "\n";
    echo '<meta property="og:url" content="' . $siteUrl . '/">' . "\n";
    if ($defaultImage) echo '<meta property="og:image" content="' . htmlspecialchars($defaultImage) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary">' . "\n";
    echo '<meta name="twitter:title" content="' . $title . '">' . "\n";
    echo '<meta name="twitter:description" content="' . $description . '">' . "\n";
    if ($defaultImage) echo '<meta name="twitter:image" content="' . htmlspecialchars($defaultImage) . '">' . "\n";
}

/**
 * 输出 JSON-LD 结构化数据
 */
function themeJSONLD() {
    $options = \Helper::options();
    $request = \Typecho\Request::getInstance();
    $siteUrl = rtrim($options->siteUrl, '/');
    $isPost = $request->is('post');

    if ($isPost) {
        $db = \Typecho\Db::get();
        $cid = $request->get('cid');
        if (!$cid) return;
        $row = $db->fetchRow($db->select('title', 'text', 'created', 'modified', 'author')->from('table.contents')->where('cid = ?', intval($cid)));
        if (!$row) return;
        $author = $db->fetchRow($db->select('screenName')->from('table.users')->where('uid = ?', intval($row['author'])));
        $images = getPostImages($row['text'], 1);
        $image = !empty($images) ? $images[0] : '';

        $ld = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $row['title'],
            'description' => getExcerpt($row['text'], 160),
            'datePublished' => date('c', $row['created']),
            'dateModified' => date('c', $row['modified'] ?: $row['created']),
            'mainEntityOfPage' => array('@type' => 'WebPage', '@id' => $siteUrl . $request->getRequestUrl()),
            'author' => array('@type' => 'Person', 'name' => $author ? $author['screenName'] : $options->title),
            'publisher' => array('@type' => 'Organization', 'name' => $options->title),
        );
        if ($image) $ld['image'] = $image;

        echo '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    } else {
        $ld = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $options->title,
            'url' => $siteUrl . '/',
            'description' => $options->description ?: $options->title,
        );
        echo '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}



/**
 * 字数统计
 */
function getWordCount($content) {
    $text = strip_tags($content);
    $text = preg_replace('/\s+/', '', $text);
    return mb_strlen($text);
}

/**
 * 获取文章最后修改时间
 */
function getLastModified($cid) {
    $row = \Typecho\Db::get()->fetchRow(\Typecho\Db::get()->select('modified', 'created')->from('table.contents')->where('cid = ?', intval($cid)));
    if (!$row) return 0;
    return ($row['modified'] && $row['modified'] > $row['created']) ? $row['modified'] : $row['created'];
}

/**
 * 获取上一篇/下一篇文章
 */
function getAdjacentPost($cid, $direction) {
    $db = \Typecho\Db::get();
    if ($direction === 'prev') {
        $row = $db->fetchRow($db->select('cid', 'slug', 'title')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->where('cid < ?', intval($cid))->order('cid', \Typecho\Db::SORT_DESC)->limit(1));
    } else {
        $row = $db->fetchRow($db->select('cid', 'slug', 'title')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->where('cid > ?', intval($cid))->order('cid', \Typecho\Db::SORT_ASC)->limit(1));
    }
    return $row;
}

/**
 * 获取随机文章（用于 404 等场景）
 */
function getRandomPosts($limit = 4) {
    $db = \Typecho\Db::get();
    return $db->fetchAll(
        $db->select('cid', 'title', 'slug', 'text', 'created')
            ->from('table.contents')
            ->where('type = ?', 'post')
            ->where('status = ?', 'publish')
            ->order('RAND()')
            ->limit($limit)
    );
}


// ══════════════════════════════════════
// 内容钩子：短代码处理
// ══════════════════════════════════════

function themeInit($form) {
    $request = \Typecho\Request::getInstance();
    if ($request->isPost() && $request->get('likeup') && $request->get('action')) {
        likeup($request->get('likeup'), $request->get('action'));
    }
    \Typecho\Plugin::factory('Widget_Abstract_Contents')->contentEx = array('themePhotoFilter', 'parsePhotos');
    if (class_exists('\\Typecho\\Widget\\Base\\Contents')) {
        \Typecho\Plugin::factory('Typecho_Widget_Base_Contents')->contentEx = array('themePhotoFilter', 'parsePhotos');
    }
}

class themePhotoFilter {
    public static function parsePhotos($content, $widget, $last) {
        return self::processShortcodes($content);
    }

    public static function processShortcodes($content) {
        if (strpos($content, '[') !== false) {
            $content = preg_replace_callback('/\[photos\](.*?)\[\/photos\]/si', function($m) {
            $inner = strip_tags(trim($m[1]), '<img>');
            $urls = array();
            if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/', $inner, $im)) $urls = $im[1];
            if (empty($urls)) { preg_match_all('/https?:\/\/[^\s"\'<>]+\.(?:jpg|jpeg|png|gif|webp|svg|bmp)/i', $inner, $um); $urls = $um[0]; }
            if (empty($urls)) return $m[0];
            $h = '<div class="photo-grid g' . min(count($urls), 9) . '">';
            foreach ($urls as $u) $h .= '<div class="photo-item"><img src="' . htmlspecialchars($u) . '" alt="" loading="lazy"></div>';
            return $h . '</div>';
        }, $content);

        $content = preg_replace_callback('/\[photo\](.*?)\[\/photo\]/si', function($m) {
            $inner = trim($m[1]);
            if (empty($inner)) return '';
            if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $inner, $im)) return '<div class="photo-single"><img src="' . htmlspecialchars($im[1]) . '" alt="" loading="lazy"></div>';
            if (preg_match('/^https?:\/\/[^\s]+$/i', $inner)) return '<div class="photo-single"><img src="' . htmlspecialchars($inner) . '" alt="" loading="lazy"></div>';
            return '<div class="photo-single">' . $inner . '</div>';
        }, $content);
        }

        // 无论有无短代码，都对连续图片做九宫格归组
        return self::groupConsecutiveImages($content);
    }

    /**
     * 把文章中连续的 <img>（不在短代码内）自动归组为九宫格
     */
    public static function groupConsecutiveImages($content) {
        // 匹配连续的 <p><img></p> 或独立 <img>
        $pattern = '/(?:\s*<p>\s*<img[^>]+>\s*<\/p>\s*|\s*<img[^>]+>\s*){2,}/si';
        return preg_replace_callback($pattern, function($m) {
            $block = $m[0];
            $urls = array();
            if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/', $block, $im)) $urls = $im[1];
            if (count($urls) < 2) return $block;
            $urls = array_values(array_unique($urls));
            $count = min(count($urls), 9);
            $h = '<div class="photo-grid g' . $count . '">';
            foreach (array_slice($urls, 0, 9) as $u) {
                $h .= '<div class="photo-item"><img src="' . htmlspecialchars($u) . '" alt="" loading="lazy"></div>';
            }
            return $h . '</div>';
        }, $content);
    }
}
// ══════════════════════════════════════
// 点赞系统
// ══════════════════════════════════════

function likeup($cid, $action) {
    $cid = intval($cid);
    if ($cid <= 0) return;
    $db = \Typecho\Db::get();
    $prefix = $db->getPrefix();
    try {
        $row = $db->fetchRow($db->select('likes')->from('table.contents')->where('cid = ?', $cid));
    } catch (Exception $e) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `likes` INT(10) DEFAULT 0');
        $row = array('likes' => 0);
    }
    $likes = isset($row['likes']) ? intval($row['likes']) : 0;
    if ($action === 'do') {
        $likes++;
        $db->query($db->update('table.contents')->rows(array('likes' => $likes))->where('cid = ?', $cid));
        setcookie('like_' . $cid, '1', time() + 31536000, '/');
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('likes' => $likes));
    exit;
}
