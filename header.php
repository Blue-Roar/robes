<?php
/**
 * 头部模板
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php $this->archiveTitle(' - ', '', ' - '); ?><?php $this->options->title(); ?></title>
<meta name="description" content="<?php $this->options->description(); ?>">
<meta name="theme-color" content="<?php echo themeOption('accentColor', '#6366F1'); ?>" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#1a1d27" media="(prefers-color-scheme: dark)">

<?php /* Favicon */ ?>
<?php $favicon = themeOption('faviconUrl', ''); if ($favicon): ?>
<link rel="icon" href="<?php echo htmlspecialchars($favicon); ?>" type="image/png">
<?php endif; ?>

<?php /* RSS Auto-discovery */ ?>
<link rel="alternate" type="application/rss+xml" title="<?php $this->options->title(); ?> RSS" href="<?php $this->options->feedUrl(); ?>">

<?php /* SEO: OG / Twitter Card / JSON-LD */ ?>
<?php themeSEO(); ?>
<?php themeJSONLD(); ?>

<?php /* 资源加载优化 */ ?>
<link rel="dns-prefetch" href="//cravatar.cn">
<link rel="dns-prefetch" href="//cdn.bootcdn.net">
<link rel="preconnect" href="https://cdn.bootcdn.net" crossorigin>
<link rel="preload" href="<?php $this->options->themeUrl('css/main.css'); ?>" as="style">
<link rel="preload" href="<?php $this->options->themeUrl('js/main.v2.js'); ?>" as="script">
<link rel="preload" href="<?php $this->options->themeUrl('fonts/zql-v3-subset.woff2'); ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
<?php if ($this->is('post') || $this->is('page')): ?>
<link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/highlight.js/11.9.0/styles/github.min.css" media="print" onload="this.media='all'">
<?php endif; ?>
<link rel="stylesheet" href="<?php $this->options->themeUrl('css/main.css'); ?>">
<?php themeCSSVars(); ?>
<?php $this->header('atom=&rss2=&commentReply=&pingback=&xmlrpc='); ?>
</head>
<body data-page="<?php echo $this->is('index') ? 'index' : 'inner'; ?>"<?php if ($this->user->hasLogin() && ($this->is('post') || $this->is('page'))): ?> data-edit-url="<?php $this->options->adminUrl($this->is('post') ? 'write-post.php?cid=' . $this->cid : 'write-page.php?cid=' . $this->cid); ?>"<?php endif; ?>>

<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="page">
  <div class="shell glass">
    
    <header class="topbar">
      <button class="hamburger" id="hamburgerBtn"><i class="fa-solid fa-bars"></i></button>
      <div class="topbar-brand">
        <?php $logoImg = themeOption('logoImage', ''); if ($logoImg): ?>
          <img class="brand-logo-img" src="<?php echo $logoImg; ?>" alt="<?php echo themeOption('blogName', $this->options->title); ?>">
        <?php else: ?>
          <div class="brand-icon"><?php echo mb_substr($this->options->title, 0, 1); ?></div>
        <?php endif; ?>
        <span class="brand-name"><?php echo themeOption('blogName', $this->options->title); ?></span>
      </div>
      <div class="topbar-center">
        <i class="fa-solid fa-magnifying-glass"></i>
        <form action="<?php $this->options->siteUrl(); ?>" method="get" style="display:contents;">
          <input class="topbar-search" type="text" name="s" placeholder="搜索文章…" value="<?php if($this->is('search')) $this->archiveTitle(' - ', '', ''); ?>">
        </form>
        
        <?php if ($logoImg): ?>
          <img class="m-brand-logo-img" src="<?php echo $logoImg; ?>" alt="">
        <?php else: ?>
          <div class="m-brand-icon"><?php echo mb_substr($this->options->title, 0, 1); ?></div>
        <?php endif; ?>
        <span class="m-brand-name"><?php echo themeOption('blogName', $this->options->title); ?></span>
      </div>
      <div class="topbar-spacer"></div>
      <div class="topbar-actions">
        <a class="topbar-btn" id="editPostBtn" href="#" title="编辑文章" target="_blank" style="display:none"><i class="fa-regular fa-pen-to-square"></i></a>
        <button class="topbar-btn scroll-progress-btn" id="scrollTopBtn" title="回到顶部">
            <svg class="scroll-progress-ring" viewBox="0 0 34 34">
                <circle class="scroll-progress-bg" cx="17" cy="17" r="14" />
                <circle class="scroll-progress-bar" cx="17" cy="17" r="14" />
            </svg>
            <span class="scroll-progress-text" id="scrollPct">0</span>
        </button>
        <button class="topbar-btn" id="themeToggle" title="切换深色模式"><i class="fa-solid fa-moon"></i></button>
      </div>
    </header>
