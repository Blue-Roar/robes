# Robes
一个 Typecho 主题，是基于个人需求对 [Robes](https://robes.xin/) 的修改版。

主要修改：
- 添加了新的`导航页面`和`简单页面`模板
- 允许在侧栏隐藏站点标题，只显示Logo
- 版权信息支持`{year}`动态年份
- 使用 weavatar 替代 cravatar
- 修复分页评论显示异常
- 弃用会缺失字形的ZQL字体
- 修改代码高亮相关代码，以兼容[MarkdownParse](https://github.com/mrgeneralgoo/typecho-markdown)提供的 Mermaid 解析（注：在插件设置里，不要开启 Mermaid 支持，只需要插件解析 Mermaid 即可，后续渲染由主题侧 js 调用）
- 外部链接补 target（配合 MarkdownParse 的 rel 标记）