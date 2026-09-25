<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 关于页面
 *
 * @package custom
 * @type page
 * @title 关于
 *
 */
$this->need('header.php');

$db = \Typecho\Db::get();
$postCount = intval($db->fetchObject($db->select(array('COUNT(cid)' => 'total'))->from('table.contents')->where('type = ?', 'post'))->total);
$commentCount = intval($db->fetchObject($db->select(array('COUNT(coid)' => 'total'))->from('table.comments'))->total);
$tagCount = intval($db->fetchObject($db->select(array('COUNT(mid)' => 'total'))->from('table.metas')->where('type = ?', 'tag'))->total);
$startDate = themeOption('siteStartDate', '');
$startTs = ($startDate && strtotime($startDate)) ? strtotime($startDate) : strtotime($this->options->timezone);
$days = max(0, floor((time() - $startTs) / 86400));
$years = floor($days / 365);
$remainDays = $days % 365;
$lastPost = $db->fetchRow($db->select('created')->from('table.contents')->where('type = ?', 'post')->where('status = ?', 'publish')->order('created', \Typecho\Db::SORT_DESC)->limit(1));
$lastUpdate = $lastPost ? date('Y-m-d', $lastPost['created']) : '暂无';
?>

<div class="main-body">
    <?php $this->need('sidebar.php'); ?>

    <div class="content-area">
        <div class="fade-top" id="fadeTop"></div>
        <div class="content-inner">
            <div class="content-scroll" id="feed">
                <article class="article standalone-page">

                    <!-- 个人资料卡片 -->
                    <div class="pg-card pg-profile-card">
                        <div class="pg-profile-top">
                            <div class="pg-profile-avatar">
                                <?php $logoImg = themeOption('postAvatar', '') ?? themeOption('logoImage', ''); if ($logoImg): ?>
                                    <img src="<?php echo $logoImg; ?>" alt="">
                                <?php else: ?>
                                    <span><?php echo mb_substr($this->options->title, 0, 1); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="pg-profile-info">
                                <h1 class="pg-profile-name"><?php echo themeOption('blogName', $this->options->title); ?></h1>
                                <p class="pg-profile-bio"><?php echo $this->options->description ?: '这个人很懒，什么都没写~'; ?></p>
                            </div>
                        </div>
                        <div class="pg-profile-bottom">
                            <div class="pg-profile-links">
                                <a href="<?php $this->options->siteUrl(); ?>" title="首页"><i class="fa-solid fa-house"></i> 首页</a>
                                <a href="<?php $this->options->siteUrl(); ?>feed/" title="RSS"><i class="fa-solid fa-rss"></i> RSS</a>
                                <?php
                                $socials = array(
                                    'socialGithub' => array('fa-brands fa-github', 'GitHub'),
                                    'socialTwitter' => array('fa-brands fa-x-twitter', 'Twitter'),
                                    'socialWeibo' => array('fa-brands fa-weibo', '微博'),
                                    'socialWechat' => array('fa-brands fa-weixin', '微信'),
                                    'socialBilibili' => array('fa-brands fa-bilibili', 'Bilibili'),
                                    'socialTelegram' => array('fa-brands fa-telegram', 'Telegram'),
                                    'socialEmail' => array('fa-solid fa-envelope', '邮箱'),
                                    'socialRss' => array('fa-solid fa-rss', 'RSS'),
                                );
                                foreach ($socials as $key => $info) {
                                    $val = trim(themeOption($key, ''));
                                    if (empty($val)) continue;
                                    if ($key === 'socialEmail') $val = 'mailto:' . $val;
                                    elseif ($key === 'socialRss' && strpos($val, 'http') !== 0) $val = $this->options->feedUrl();
                                    elseif (strpos($val, 'http') !== 0 && strpos($val, 'mailto:') !== 0) {
                                        $prefixes = array(
                                            'socialGithub' => 'https://github.com/',
                                            'socialTwitter' => 'https://x.com/',
                                            'socialWeibo' => 'https://weibo.com/',
                                            'socialBilibili' => 'https://space.bilibili.com/',
                                            'socialTelegram' => 'https://t.me/',
                                        );
                                        $val = (isset($prefixes[$key]) ? $prefixes[$key] : '') . $val;
                                    }
                                    echo '<a href="' . htmlspecialchars($val) . '" target="_blank" rel="noopener noreferrer" title="' . $info[1] . '"><i class="' . $info[0] . '"></i> ' . $info[1] . '</a>' . "\n";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- 数据统计卡片 -->
                    <div class="pg-card pg-stats-card">
                        <div class="pg-stat"><b><?php echo $postCount; ?></b><span>文章</span></div>
                        <div class="pg-stat-divider"></div>
                        <div class="pg-stat"><b><?php echo $commentCount; ?></b><span>评论</span></div>
                        <div class="pg-stat-divider"></div>
                        <div class="pg-stat"><b><?php echo $tagCount; ?></b><span>标签</span></div>
                        <div class="pg-stat-divider"></div>
                        <div class="pg-stat"><b><?php echo $days; ?></b><span>天</span></div>
                    </div>

                    <!-- 站点信息卡片 -->
                    <div class="pg-card pg-info-card">
                        <div class="pg-card-title"><i class="fa-solid fa-circle-info"></i> 站点信息</div>
                        <div class="pg-info-grid">
                            <div class="pg-info-item">
                                <div class="pg-info-icon"><i class="fa-solid fa-clock"></i></div>
                                <div class="pg-info-text">
                                    <span class="pg-info-label">运行时间</span>
                                    <span class="pg-info-value"><?php echo $years; ?> 年 <?php echo $remainDays; ?> 天</span>
                                </div>
                            </div>
                            <div class="pg-info-item">
                                <div class="pg-info-icon"><i class="fa-solid fa-calendar-check"></i></div>
                                <div class="pg-info-text">
                                    <span class="pg-info-label">建站日期</span>
                                    <span class="pg-info-value"><?php echo date('Y-m-d', $startTs); ?></span>
                                </div>
                            </div>
                            <div class="pg-info-item">
                                <div class="pg-info-icon"><i class="fa-solid fa-pen-nib"></i></div>
                                <div class="pg-info-text">
                                    <span class="pg-info-label">最近更新</span>
                                    <span class="pg-info-value"><?php echo $lastUpdate; ?></span>
                                </div>
                            </div>
                            <div class="pg-info-item">
                                <div class="pg-info-icon"><i class="fa-solid fa-code"></i></div>
                                <div class="pg-info-text">
                                    <span class="pg-info-label">博客主题</span>
                                    <span class="pg-info-value">Robes</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 建站历程 -->
                    <?php $pageContent = $this->content; ?>
                    <?php if (!empty(trim(strip_tags($pageContent)))): ?>
                    <div class="pg-card pg-timeline-card">
                        <div class="pg-card-title"><i class="fa-solid fa-timeline"></i> 建站历程</div>
                        <div class="pg-tl">
                            <?php
                            $dom = new DOMDocument();
                            @$dom->loadHTML('<?xml encoding="utf-8"?>' . $pageContent);
                            $body = $dom->getElementsByTagName('body')->item(0);
                            $nodes = $body->childNodes;
                            $hasEntry = false;
                            for ($i = 0; $i < $nodes->length; $i++) {
                                $node = $nodes->item($i);
                                if ($node->nodeName === 'h3') {
                                    if ($hasEntry) echo '</div>';
                                    echo '<div class="pg-tl-item"><div class="pg-tl-head">' . $node->textContent . '</div>';
                                    $hasEntry = true;
                                } else {
                                    if (!$hasEntry) {
                                        echo '<div class="pg-tl-item">';
                                        $hasEntry = true;
                                    }
                                    echo $dom->saveHTML($node);
                                }
                            }
                            if ($hasEntry) echo '</div>';
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </article>
            </div>
        </div>
        <div class="fade-bottom" id="fadeBottom"></div>
    </div>

<?php $this->need('footer.php'); ?>
