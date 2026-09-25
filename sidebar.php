<?php
/**
 *侧边栏模板
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<aside class="sidebar">
    <div class="sidebar-mobile-header">
        <button class="sidebar-close" id="sidebarCloseBtn"><i class="fa-solid fa-xmark"></i></button>
    </div>

    
    <div class="nav-group">
        <a class="nav-item<?php if($this->is('index')) echo ' active'; ?>" href="<?php $this->options->siteUrl(); ?>">
            <i class="fa-solid fa-star"></i>首页
        </a>
        <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
        <?php while ($pages->next()): ?>
            <?php
            $pageTitle = $pages->title;
            $icon = 'fa-file-lines';
            $iconMap = array(
                '关于' => 'fa-user', 'about' => 'fa-user',
                '友链' => 'fa-link', '友情链接' => 'fa-link', 'link' => 'fa-link', 'links' => 'fa-link',
                '留言' => 'fa-envelope', '留言板' => 'fa-envelope', 'guestbook' => 'fa-envelope',
                '相册' => 'fa-images', 'photo' => 'fa-images', 'gallery' => 'fa-images',
                '归档' => 'fa-box-archive', 'archive' => 'fa-box-archive',
                '朋友圈' => 'fa-rss', '动态' => 'fa-rss',
                '标签' => 'fa-tags', 'tag' => 'fa-tags', 'tags' => 'fa-tags',
                '项目' => 'fa-code', 'portfolio' => 'fa-code',
                '日志' => 'fa-book-open', 'log' => 'fa-book-open',
                '音乐' => 'fa-music', 'music' => 'fa-music',
                '说说' => 'fa-comment-dots', 'shuoshuo' => 'fa-comment-dots',
                '书签' => 'fa-bookmark', 'bookmark' => 'fa-bookmark',
                '追剧' => 'fa-film', 'movie' => 'fa-film',
                '建站' => 'fa-hammer', '工具' => 'fa-wrench',
            );
            foreach ($iconMap as $key => $ic) {
                if (mb_stripos($pageTitle, $key) !== false) { $icon = $ic; break; }
            }
            ?>
            <a class="nav-item<?php if($this->is('page', $pages->slug)) echo ' active'; ?>" href="<?php $pages->permalink(); ?>">
                <i class="fa-solid <?php echo $icon; ?>"></i><?php echo $pageTitle; ?>
            </a>
        <?php endwhile; ?>
    </div>

    <?php if (themeOption('showSidebarCategories', '1') === '1'): ?>
    <div class="nav-divider"></div>
    <div class="nav-group">
        <div class="nav-group-title">
            <span>分类</span>
            <button class="section-toggle-btn" data-target="categoryList" title="收起/展开">
                <i class="fa-solid fa-chevron-up"></i>
            </button>
        </div>
        <div class="section-list" id="categoryList">
        <?php $this->widget('Widget_Metas_Category_List')->to($categorys); ?>
        <?php while ($categorys->next()): ?>
            <?php if ($categorys->count == 0) continue; ?>
            <a class="nav-item<?php if ($this->is('category', $categorys->slug)) echo ' active'; ?>" href="<?php $categorys->permalink(); ?>">
                <i class="fa-solid fa-folder"></i><?php $categorys->name(); ?>
                <span class="nav-count"><?php $categorys->count(); ?></span>
            </a>
        <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (themeOption('showSidebarTags', '1') === '1'): ?>
    <div class="nav-divider"></div>
    <div class="nav-group">
        <div class="nav-group-title">
            <span>标签</span>
            <button class="section-toggle-btn" data-target="tagList" title="收起/展开">
                <i class="fa-solid fa-chevron-up"></i>
            </button>
        </div>
        <div class="section-list" id="tagList">
            <div class="sidebar-tags">
            <?php $this->widget('Widget_Metas_Tag_Cloud')->to($tags); ?>
            <?php while ($tags->next()): ?>
                <a class="sidebar-tag" href="<?php $tags->permalink(); ?>" title="<?php $tags->count(); ?> 篇文章">
                    <?php $tags->name(); ?>
                </a>
            <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="sidebar-footer">
        <span><?php echo themeOption('footerText', '© 2026 ' . $this->options->title); ?></span>
        <?php $icp = themeOption('icpText', ''); if ($icp): ?>
        <div class="dot"></div>
        <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($icp); ?></a>
        <?php endif; ?>
        <div class="dot"></div>
        <span>Theme <a href="https://robes.xin/" target="_blank" rel="noopener noreferrer">Robes</a></span>
    </div>
</aside>
