# Robes
一个 Typecho 主题，是本人基于个人需求对 [Robes](https://robes.xin/) 的修改版。

主要修改：
- 添加了新的`简单页面`模板，隐藏元信息和互动等组件
- 添加了新的`导航页面`模板，修改自友链页面，可添加到各个子站点的链接

    导航链接来自页面正文所存储的JSON（不要开启Markdown）：
    ```
    [
        {"title": "站点1", "sort": "分类1", "desc": "示例站点1", "link": "https://example1.com/", "icon": "fas fa-info"},
        {"title": "站点2", "sort": "分类2", "desc": "示例站点2", "link": "https://example2.com/", "avatar": "https://example2.com/favicon.png"}
    ]
    ```
    显示的图标按照`icon值`-`avatar值`-`url值对应目标站点的 favicon.ico`-`站点 favicon`的顺序逐级匹配

    `icon`字段支持主题自带FontAwesome免费图标；`avatar`字段支持图片URL
- 允许在侧栏隐藏站点标题，只显示Logo
- 版权信息支持`{year}`动态年份
- 修复分页评论显示异常
- 修复评论防垃圾开启时评论被静默丢弃的问题
- 修改代码高亮相关代码，以兼容[MarkdownParse](https://github.com/mrgeneralgoo/typecho-markdown)提供的 Mermaid 解析（注：在插件设置里，不要开启 Mermaid 支持，只需要插件解析 Mermaid 即可，后续渲染由主题侧 js 调用）
- 给外部链接补上`target="blank"`标记（依赖 MarkdownParse 或同类型插件给外部链接添加的`rel="noopener noreferrer"`标记）
- 使用 weavatar 替换各种不显示的 cravatar
- 使用 zstatic 替换时常不稳定的 bootcdn
- 添加自定义图片背景支持（会覆盖SVG背景图案）
- 弃用会缺失字形的ZQL字体