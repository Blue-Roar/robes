<?php
/**
 * 评论模板
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$GLOBALS['isLogin'] = $this->user->hasLogin();

/**
 * 获取友链URL列表（用于评论区好友检测）
 */
function getFriendUrls() {
    static $cache = null;
    if ($cache !== null) return $cache;
    $cache = array();
    try {
        $db = \Typecho\Db::get();
        $prefix = $db->getPrefix();
        try {
            $rows = $db->fetchAll($db->select('url')->from($prefix . 'links'));
        } catch (Exception $e) {
            $rows = $db->fetchAll($db->select('url')->from('table.links'));
        }
        foreach ($rows as $row) {
            if (!empty($row['url'])) {
                $cache[] = rtrim(trim($row['url']), '/');
            }
        }
    } catch (Exception $e) {
        $cache = array();
    }
    return $cache;
}

function isFriend($url) {
    if (empty($url)) return false;
    $normalized = rtrim(trim($url), '/');
    $friends = getFriendUrls();
    foreach ($friends as $f) {
        if (strcasecmp($f, $normalized) === 0) return true;
    }
    return false;
}

function getCommentLevel($mail) {
    if (empty($mail)) return 0;
    static $cache = array();
    if (isset($cache[$mail])) return $cache[$mail];
    try {
        $count = \Typecho\Db::get()->fetchObject(
            \Typecho\Db::get()->select(array('COUNT(coid)' => 'total'))
                ->from('table.comments')->where('mail = ?', $mail)->where('status = ?', 'approved')
        )->total;
        $cache[$mail] = $count >= 100 ? 6 : ($count >= 50 ? 5 : ($count >= 30 ? 4 : ($count >= 15 ? 3 : ($count >= 5 ? 2 : 1))));
    } catch (Exception $e) {
        $cache[$mail] = 1;
    }
    return $cache[$mail];
}

function getCommentAgent($agent) {
    if (empty($agent)) return '';
    $os = '';
    foreach (array('Windows','Android','iPhone','Mac','Linux') as $k) { if (strpos($agent, $k) !== false) { $os = $k; break; } }
    $br = '';
    foreach (array('Chrome','Firefox','Edge','Safari') as $k) { if (strpos($agent, $k) !== false) { $br = $k; break; } }
    if ($os && $br) return $os . ' · ' . $br;
    return $os . $br;
}

function threadedComments($comments, $options) {
    $level = getCommentLevel($comments->mail);
    $device = getCommentAgent($comments->agent);
    $author = $comments->url
        ? '<a href="' . $comments->url . '" target="_blank" rel="external nofollow">' . $comments->author . '</a>'
        : $comments->author;
    $mail = $comments->mail ?: 'guest@example.com';
    $hash = md5(strtolower(trim($mail)));
    $isAdmin = $comments->authorId && $comments->authorId == $comments->ownerId;
    $isFriend = !$isAdmin && isFriend($comments->url);
?>
<li id="li-<?php $comments->theId(); ?>" class="cmt-item<?php echo $isAdmin ? ' cmt-is-admin' : ''; ?><?php echo $comments->parent > 0 ? ' cmt-is-child' : ''; ?>">
    <div class="cmt-card" id="<?php $comments->theId(); ?>">
        <div class="cmt-avatar-col">
            <img class="cmt-avatar" src="https://weavatar.com/avatar/<?php echo $hash; ?>?s=80&d=mp" alt="" loading="lazy">
        </div>
        <div class="cmt-body">
            <div class="cmt-header">
                <div class="cmt-user">
                    <span class="cmt-name"><?php echo $author; ?></span>
                    <?php if ($isAdmin): ?><span class="cmt-tag cmt-tag-admin">站长</span><?php endif; ?>
                    <?php if ($isFriend): ?><span class="cmt-tag cmt-tag-friend">好友</span><?php endif; ?>
                    <?php if ($level > 0): ?><span class="cmt-tag cmt-tag-lv">Lv<?php echo $level; ?></span><?php endif; ?>
                </div>
                <time class="cmt-time"><?php echo humanTimeDiff($comments->created); ?></time>
            </div>
            <div class="cmt-text"><?php $comments->content(); ?></div>
            <div class="cmt-actions">
                <a href="javascript:;" class="cmt-reply-btn" data-cid="<?php $comments->theId(); ?>" data-coid="<?php echo $comments->coid; ?>"><i class="fa-regular fa-comment"></i> 回复</a>
                <?php if ($device): ?><span class="cmt-device"><i class="fa-solid fa-display"></i> <?php echo $device; ?></span><?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($comments->children) { ?>
    <div class="cmt-children"><?php $comments->threadedComments($options); ?></div>
    <?php } ?>
</li>
<?php } ?>

<?php
/**
 * 标题里的评论总数：必须现场统计，不能再用 $this->commentsNum()。
 * 那个方法读的是 contents.commentsNum 缓存列（var/Widget/Base/Contents.php:420），
 * 它只按 status/cid 统计、不含 type 过滤，也可能因直接改库、导入数据、
 * 插件批量审核而虚高 —— 于是出现"标题 30+ 条、列表只有几条"。
 * 这里统计的是"真正会被列出来的评论数"。
 */
$cmtTotal = 0;
try {
    $cmtDb = \Typecho\Db::get();
    $cmtQuery = $cmtDb->select(array('COUNT(coid)' => 'num'))
        ->from('table.comments')
        ->where('cid = ?', $this->cid)
        ->where('status = ?', 'approved');
    if ($this->options->commentsShowCommentOnly) {
        $cmtQuery->where('type = ?', 'comment');
    }
    $cmtTotal = intval($cmtDb->fetchObject($cmtQuery)->num);
} catch (Exception $e) {
    $cmtTotal = intval($this->commentsNum);
}
?>
<div class="cmt-section" id="comments">
    <div class="cmt-section-head">
        <h3 class="cmt-title"><i class="fa-regular fa-comments"></i> <?php
            echo $cmtTotal > 0 ? $cmtTotal . '条评论' : '暂无评论';
        ?></h3>
    </div>

    <?php if ($this->allow('comment')): ?>
    <div class="cmt-form-area" id="<?php $this->respondId(); ?>">
        <div class="cmt-reply-bar" id="cmt-reply-bar" style="display:none;">
            <i class="fa-solid fa-reply"></i>
            <span id="cmt-reply-info"></span>
            <button type="button" class="cmt-cancel-btn" id="cmt-cancel-btn"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="cmt-form" id="cmt-form-wrap">
            <form method="post" action="<?php $this->commentUrl(); ?>" id="comment-form">
                <input type="hidden" name="parent" id="comment-parent" value="" />
                <?php if ($this->user->hasLogin()): ?>
                <div class="cmt-logged">已登录为 <strong><?php $this->user->screenName(); ?></strong> <a href="<?php $this->options->logoutUrl(); ?>" class="no-pjax">退出</a></div>
                <?php else: ?>
                <div class="cmt-fields">
                    <input type="text" name="author" placeholder="昵称 *" value="<?php $this->remember('author'); ?>" required maxlength="50" />
                    <input type="email" name="mail" placeholder="邮箱 *" value="<?php $this->remember('mail'); ?>" required maxlength="100" />
                    <input type="url" name="url" placeholder="网址" value="<?php $this->remember('url'); ?>" maxlength="100" />
                </div>
                <?php endif; ?>
                <div class="cmt-textarea-wrap">
                    <textarea name="text" rows="3" placeholder="说点什么吧…" required><?php $this->remember('text'); ?></textarea>
                </div>
                <div class="cmt-form-bar">
                    <div class="cmt-form-left">
                        <button type="button" class="cmt-emoji-btn" id="cmt-emoji-btn"><i class="fa-regular fa-face-smile"></i></button>
                        <span class="cmt-hint">Ctrl + Enter 发送</span>
                    </div>
                    <button type="submit" class="cmt-submit" id="comment-submit">提交评论</button>
                </div>
            </form>
        </div>
        <div class="cmt-emoji-panel" id="cmt-emoji-panel" style="display:none;"></div>
    </div>
    <?php else: ?>
    <p class="cmt-closed"><i class="fa-solid fa-lock"></i> 评论已关闭</p>
    <?php endif; ?>



    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
        <?php
        /**
         * 用 before/after 把外层容器交给内核输出。
         * 内核 listComments() 的默认 before 是 <ol class="comment-list">，主题外面再套一层
         * <ol class="cmt-list"> 会变成 <ol> 直接嵌 <ol> 的非法结构，而且 .comment-list 没有样式，
         * 子评论会带上浏览器默认的 40px 缩进和 1. 2. 3. 序号。
         *
         * 注意：这里的入参是 Config（before/after/beforeAuthor/...），不是 callback。
         * 全局函数 threadedComments() 由内核 Widget\Comments\Archive::threadedCommentsCallback()
         * 自动检测并调用（Archive.php:316 function_exists('threadedComments')），
         * 传 'callback' => 'threadedComments' 是没有作用的。
         */
        $comments->listComments(array(
            'before' => '<ol class="cmt-list">',
            'after'  => '</ol>',
        ));
        ?>

        <?php
        /**
         * 评论分页 —— 之前完全缺失，是"评论显示不全"的直接原因。
         * 内核在后台开启"启用分页"（commentsPageBreak）后会按 commentsPageSize 切片
         * （Widget/Comments/Archive.php:163-176），切掉的评论只有 pageNav() 能到达；
         * 而 commentsPageDisplay 默认是 last（install.php:296），默认显示的是最后一页。
         * 分页链接形如 /文章路径/comment-page-2#comments，未开启分页时此调用不输出任何内容。
         */
        $comments->pageNav(
            '<i class="fa-solid fa-chevron-left"></i>',
            '<i class="fa-solid fa-chevron-right"></i>',
            3,
            '…',
            array('wrapClass' => 'cmt-pagenav')
        );
        ?>
    <?php else: ?>
    <div class="cmt-empty">
        <i class="fa-regular fa-message"></i>
        <p>还没有评论，来说点什么吧</p>
    </div>
    <?php endif; ?>
</div>
