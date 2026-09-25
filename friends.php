<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 朋友圈
 *
 * @package custom
 * @type page
 * @title 朋友圈
 */
$this->need('header.php');

$friends = array();
$db = \Typecho\Db::get();
$prefix = $db->getPrefix();
try {
    $friends = $db->fetchAll($db->select()->from($prefix . 'links'));
} catch (Exception $e) {
    try {
        $friends = $db->fetchAll($db->select()->from('table.links'));
    } catch (Exception $e2) {
        $friends = array();
    }
}
$friends = array_values(array_filter($friends, function($f) {
    return !empty($f['url']);
}));

function _friends_fetchUrl($url, $timeout = 6) {
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true, CURLOPT_MAXREDIRS => 3,
            CURLOPT_TIMEOUT => $timeout, CURLOPT_CONNECTTIMEOUT => 4,
            CURLOPT_SSL_VERIFYPEER => true, CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; GlassBlog/1.0)',
        ));
        $data = curl_exec($ch);
        curl_close($ch);
        return $data ? $data : '';
    }
    if (ini_get('allow_url_fopen')) {
        $ctx = stream_context_create(array(
            'http' => array('timeout' => $timeout, 'user_agent' => 'Mozilla/5.0'),
            'ssl'  => array('verify_peer' => false, 'verify_peer_name' => false),
        ));
        $data = @file_get_contents($url, false, $ctx);
        return $data ? $data : '';
    }
    return '';
}

function _friends_cacheDir() {
    $dir = __TYPECHO_ROOT_DIR__ . '/usr/cache/friends';
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    return $dir;
}

function _friends_getRssUrl($siteUrl) {
    $cacheFile = _friends_cacheDir() . '/rss_' . md5($siteUrl) . '.txt';
    if (file_exists($cacheFile) && time() - filemtime($cacheFile) < 604800) {
        return trim(file_get_contents($cacheFile));
    }
    $paths = array('/feed', '/feed.xml', '/rss', '/rss.xml', '/atom.xml', '/index.xml');
    $found = '';
    foreach ($paths as $p) {
        $body = _friends_fetchUrl(rtrim($siteUrl, '/') . $p, 5);
        if ($body && (stripos($body, '<rss') !== false || stripos($body, '<feed') !== false || stripos($body, '<channel') !== false)) {
            $found = rtrim($siteUrl, '/') . $p;
            break;
        }
    }
    @file_put_contents($cacheFile, $found);
    return $found;
}

function _friends_getPosts($siteUrl, $limit = 3) {
    $cacheFile = _friends_cacheDir() . '/posts_' . md5($siteUrl) . '.json';
    if (file_exists($cacheFile) && time() - filemtime($cacheFile) < 7200) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if (is_array($cached)) return $cached;
    }
    $rssUrl = _friends_getRssUrl($siteUrl);
    if (!$rssUrl) { @file_put_contents($cacheFile, '[]'); return array(); }
    $body = _friends_fetchUrl($rssUrl, 8);
    if (!$body) { @file_put_contents($cacheFile, '[]'); return array(); }
    libxml_use_internal_errors(true);
    $xml = @simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    libxml_clear_errors();
    if (!$xml) { @file_put_contents($cacheFile, '[]'); return array(); }
    $posts = array();
    if (isset($xml->channel->item)) {
        foreach ($xml->channel->item as $item) {
            $desc = isset($item->description) ? (string)$item->description : '';
            $posts[] = array('title'=>(string)$item->title, 'link'=>(string)$item->link, 'desc'=>mb_substr(strip_tags($desc),0,100), 'time'=>(string)$item->pubDate);
            if (count($posts) >= $limit) break;
        }
    } elseif (isset($xml->entry)) {
        foreach ($xml->entry as $item) {
            $link = isset($item->link['href']) ? (string)$item->link['href'] : '';
            $content = isset($item->summary) ? (string)$item->summary : (isset($item->content) ? (string)$item->content : '');
            $time = isset($item->published) ? (string)$item->published : (isset($item->updated) ? (string)$item->updated : '');
            $posts[] = array('title'=>(string)$item->title, 'link'=>$link, 'desc'=>mb_substr(strip_tags($content),0,100), 'time'=>$time);
            if (count($posts) >= $limit) break;
        }
    }
    @file_put_contents($cacheFile, json_encode($posts));
    return $posts;
}

$feedItems = array();
foreach ($friends as $f) {
    $url = $f['url'] ?? '';
    if (!$url) continue;
    $name = $f['name'] ?? $f['title'] ?? '好友';
    $av   = $f['image'] ?? $f['logo'] ?? '';
    $desc = $f['description'] ?? $f['desc'] ?? '';
    $posts = _friends_getPosts($url, 3);
    $feedItems[] = array('name'=>$name, 'url'=>$url, 'avatar'=>$av, 'desc'=>$desc, 'posts'=>$posts);
}

usort($feedItems, function($a, $b) {
    $ta = !empty($a['posts']) ? strtotime($a['posts'][0]['time']) : 0;
    $tb = !empty($b['posts']) ? strtotime($b['posts'][0]['time']) : 0;
    if ($ta === false) $ta = 0;
    if ($tb === false) $tb = 0;
    return $tb - $ta;
});
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

                    <div class="friends-feed">
                        <?php if (empty($friends)): ?>
                            <div class="friends-empty">
                                <i class="fa-solid fa-link-slash" style="font-size:32px;margin-bottom:12px;display:block;color:var(--text-light);"></i>
                                <p>暂无友情链接</p>
                                <p style="font-size:12px;color:var(--text-light);margin-top:4px;">请在后台「友情链接」插件中添加好友链接</p>
                            </div>
                        <?php elseif (empty($feedItems)): ?>
                            <div class="friends-empty">
                                <i class="fa-solid fa-rss" style="font-size:32px;margin-bottom:12px;display:block;color:var(--text-light);"></i>
                                <p>暂无好友动态</p>
                                <p style="font-size:12px;color:var(--text-light);margin-top:4px;">已收录 <?php echo count($friends); ?> 位好友，暂时没有获取到最新文章</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($feedItems as $friend): ?>
                                <div class="friend-feed-card">
                                    <div class="friend-feed-header">
                                        <div class="friend-feed-avatar">
                                            <?php if ($friend['avatar']): ?>
                                                <img src="<?php echo htmlspecialchars($friend['avatar']); ?>" class="friend-feed-avatar-img" loading="lazy" onerror="this.style.display='none'">
                                            <?php else: ?>
                                                <div class="friend-feed-avatar-text"><?php echo mb_substr($friend['name'], 0, 1); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="friend-feed-info">
                                            <span class="friend-feed-name"><?php echo htmlspecialchars($friend['name']); ?></span>
                                            <?php if ($friend['desc']): ?>
                                                <div class="friend-feed-desc"><?php echo mb_substr($friend['desc'], 0, 40); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <a class="friend-feed-visit" href="<?php echo htmlspecialchars($friend['url']); ?>" target="_blank" rel="noopener noreferrer">访问</a>
                                    </div>

                                    <?php if (!empty($friend['posts'])): ?>
                                        <div class="friend-feed-posts">
                                            <?php foreach ($friend['posts'] as $j => $p): ?>
                                                <?php if ($j > 0): ?><div class="friend-feed-divider"></div><?php endif; ?>
                                                <a class="friend-feed-item" href="<?php echo htmlspecialchars($p['link']); ?>" target="_blank" rel="noopener noreferrer">
                                                    <span class="friend-feed-dot"></span>
                                                    <div class="friend-feed-item-content">
                                                        <span class="friend-feed-item-title"><?php echo htmlspecialchars($p['title']); ?></span>
                                                        <?php if (!empty($p['desc'])): ?>
                                                            <span class="friend-feed-item-desc"><?php echo htmlspecialchars($p['desc']); ?></span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($p['time'])): ?>
                                                            <?php $d = strtotime($p['time']); ?>
                                                            <?php if ($d): ?>
                                                                <span class="friend-feed-item-date"><?php echo date('n/j', $d); ?></span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="friend-feed-no-posts">暂无文章</div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
