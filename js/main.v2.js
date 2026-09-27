/**
 * Robes Theme - Main JS
 */
function showToast(msg, type) {
    var el = document.querySelector('.toast-notification');
    if (el) el.remove();
    var t = document.createElement('div');
    t.className = 'toast-notification toast-' + (type || 'success');
    t.innerHTML = '<i class="fa-solid fa-' + (type === 'error' ? 'circle-exclamation' : 'circle-check') + '"></i><span>' + msg + '</span>';
    document.body.appendChild(t);
    requestAnimationFrame(function() { t.classList.add('toast-show'); });
    setTimeout(function() { t.classList.remove('toast-show'); setTimeout(function() { t.remove(); }, 400); }, 2000);
}

// ── 一言 ──
var hitokotoQuotes = [
    '「世界上只有一种英雄主义，就是看清生活的真相之后依然热爱生活。」—— 罗曼·罗兰',
    '「人生天地之间，若白驱过隙，忽然而已。」—— 庄子',
    '「不乱于心，不困于情，不畏将来，不念过往。」—— 丰子恺',
    '「我们听过无数的道理，却仍旧过不好这一生。」—— 韩寒',
    '「凡是过往，皆为序章。」—— 莎士比亚',
    '「万物皆有裂痕，那是光照进来的地方。」—— 莱昂纳德·科恩',
    '「浮世三千，吱爱有三：日、月与卿。」',
    '「纵有疾风起，人生不言弃。」—— 堀辰雄',
    '「吹灭读书灯，一身都是月。」—— 桂苓',
    '「我们都在阴沟里，但仍有人仰望星空。」—— 王尔德'
];

function initHitokoto() {
    var el = document.getElementById('hitokotoText');
    if (!el) return;
    var idx = Math.floor(Math.random() * hitokotoQuotes.length);
    el.textContent = hitokotoQuotes[idx];
    el.style.cursor = 'pointer';
    el.style.opacity = '1';
    var newEl = el.cloneNode(true);
    if (el.parentNode) el.parentNode.replaceChild(newEl, el);
    newEl.addEventListener('click', function() {
        var ni;
        do { ni = Math.floor(Math.random() * hitokotoQuotes.length); } while (ni === idx && hitokotoQuotes.length > 1);
        idx = ni;
        newEl.style.opacity = '0';
        setTimeout(function() { newEl.textContent = hitokotoQuotes[idx]; newEl.style.opacity = '1'; }, 200);
    });
}

// ── 主题切换 ──
function initTheme() {
    var btn = document.getElementById('themeToggle');
    if (!btn) return;
    var icon = btn.querySelector('i');
    var darkMode = (typeof ROBES !== 'undefined' && ROBES.darkMode) ? ROBES.darkMode : 'auto';

    // 深色模式开关按钮显示/隐藏
    if (darkMode === 'light' || darkMode === 'dark') {
        btn.style.display = 'none';
        // 强制设置主题
        if (darkMode === 'dark') {
            document.documentElement.classList.add('dark');
            if (icon) icon.className = 'fa-solid fa-sun';
        } else {
            document.documentElement.classList.remove('dark');
            if (icon) icon.className = 'fa-solid fa-moon';
        }
        localStorage.setItem('theme', darkMode);
        return;
    }

    btn.style.display = '';

    if (darkMode === 'switch') {
        // 用户切换模式：读取 localStorage，无则默认浅色
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            if (icon) icon.className = 'fa-solid fa-sun';
        } else {
            document.documentElement.classList.remove('dark');
            if (icon) icon.className = 'fa-solid fa-moon';
        }
    } else {
        // auto 模式：跟随系统偏好
        if (!localStorage.getItem('theme')) {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
                if (icon) icon.className = 'fa-solid fa-sun';
            }
        } else if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            if (icon) icon.className = 'fa-solid fa-sun';
        }
    }

    if (!btn.dataset.bound) {
        btn.dataset.bound = '1';
        btn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            var d = document.documentElement.classList.contains('dark');
            if (icon) icon.className = d ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            localStorage.setItem('theme', d ? 'dark' : 'light');
        });
    }
}


// ── 侧边栏区块折叠 ──
function initSectionToggle() {
    var btns = document.querySelectorAll('.section-toggle-btn');
    if (!btns.length) return;
    btns.forEach(function(btn) {
        var targetId = btn.getAttribute('data-target');
        var list = document.getElementById(targetId);
        if (!list) return;

        // 读取折叠状态
        var collapsed = localStorage.getItem('section_' + targetId) === '1';
        if (collapsed) {
            list.classList.add('collapsed');
            btn.classList.add('collapsed');
        }

        if (!btn.dataset.bound) {
            btn.dataset.bound = '1';
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var isCollapsed = list.classList.toggle('collapsed');
                btn.classList.toggle('collapsed', isCollapsed);
                localStorage.setItem('section_' + targetId, isCollapsed ? '1' : '0');
            });
        }
    });
}

// ── 滚动进度 ──
var CIRC = 87.96;
function updateScrollUI() {
    var el = document.getElementById('feed'), ft = document.getElementById('fadeTop'), fb = document.getElementById('fadeBottom');
    var bar = document.querySelector('.scroll-progress-bar'), pctEl = document.getElementById('scrollPct');
    var scrollBtn = document.getElementById('scrollTopBtn');
    if (!el) return;
    var st = el.scrollTop, max = el.scrollHeight - el.clientHeight;
    var pct = max > 0 ? Math.min(100, Math.round(st / max * 100)) : 0;
    if (ft) ft.classList.toggle('visible', st > 30);
    if (fb) fb.classList.toggle('visible', st + el.clientHeight < el.scrollHeight - 30);
    if (bar) bar.style.strokeDashoffset = max <= 0 ? CIRC : CIRC - (CIRC * pct / 100);
    if (pctEl) {
        if (max <= 0) { pctEl.innerHTML = '<i class="fa-solid fa-ellipsis" style="font-size:10px"></i>'; if (scrollBtn) scrollBtn.title = '内容较少'; }
        else if (st < 50) { pctEl.innerHTML = '<i class="fa-solid fa-arrow-down" style="font-size:10px"></i>'; if (scrollBtn) scrollBtn.title = '滚到底部'; }
        else if (pct >= 99) { pctEl.innerHTML = '<i class="fa-solid fa-arrow-up" style="font-size:10px"></i>'; if (scrollBtn) scrollBtn.title = '回到顶部'; }
        else { pctEl.textContent = pct; if (scrollBtn) scrollBtn.title = pct + '%'; }
    }
}

// ── 侧边栏 ──
var sidebarOverlay = document.getElementById('sidebarOverlay');
function openSidebar() {
    var sb = document.querySelector('.sidebar');
    if (sb) sb.classList.add('open');
    if (sidebarOverlay) sidebarOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    var sb = document.querySelector('.sidebar');
    if (sb) sb.classList.remove('open');
    if (sidebarOverlay) sidebarOverlay.classList.remove('active');
    document.body.style.overflow = '';
}
function bindSidebarAutoClose() {
    document.querySelectorAll('.sidebar .nav-item').forEach(function(item) {
        if (item.dataset.sbBound) return;
        item.dataset.sbBound = '1';
        item.addEventListener('click', function() { if (window.innerWidth <= 768) closeSidebar(); });
    });
}

// ── 分享 ──
function sharePost(url, title) {
    if (navigator.share) navigator.share({ title: title, url: url });
    else { navigator.clipboard.writeText(url); alert('链接已复制'); }
}


// ── 灯箱 ──
function bindLightbox() {
    if (!ROBES.enableLightbox) return;
    var lb = document.getElementById('lightbox');
    if (!lb) return;
    var lbImg = document.getElementById('lightboxImg');
    var lbPrev = document.getElementById('lightboxPrev');
    var lbNext = document.getElementById('lightboxNext');
    var lbCtr = document.getElementById('lightboxCounter');
    var imgs = [], idx = 0;

    function show() {
        if (!imgs.length) return;
        var cell = imgs[idx];
        var img = (cell.tagName === 'IMG') ? cell : (cell.querySelector ? cell.querySelector('img') : cell);
        if (img && lbImg) lbImg.src = img.src || img.getAttribute('src');
        if (lbCtr) lbCtr.textContent = (idx + 1) + ' / ' + imgs.length;
        var showNav = imgs.length > 1;
        if (lbPrev) lbPrev.style.display = showNav ? 'flex' : 'none';
        if (lbNext) lbNext.style.display = showNav ? 'flex' : 'none';
        if (lbCtr) lbCtr.style.display = showNav ? 'block' : 'none';
    }
    function close() { lb.classList.remove('active'); if (lbImg) lbImg.src = ''; imgs = []; }

    var isIndex = document.body.dataset.page === 'index';
    if (!isIndex) {
        document.querySelectorAll('.img-cell:not([data-lb]), .photo-item:not([data-lb]), .photo-single:not([data-lb])').forEach(function(cell) {
            cell.dataset.lb = '1';
            cell.addEventListener('click', function() {
                var grid = cell.closest('.img-grid, .photo-grid');
                if (grid) { imgs = Array.from(grid.querySelectorAll('.img-cell, .photo-item, .photo-single')); idx = imgs.indexOf(cell); show(); lb.classList.add('active'); }
                else { imgs = [cell]; idx = 0; show(); lb.classList.add('active'); }
            });
        });
    }
    document.querySelectorAll('.article-content img:not([data-lb])').forEach(function(img) {
        if (img.closest('.img-cell, .photo-item, .photo-single, .img-grid, .photo-grid')) return;
        img.dataset.lb = '1';
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
            imgs = Array.from(document.querySelectorAll('.article-content img')).filter(function(i) { return !i.closest('.img-cell, .photo-item, .photo-single, .img-grid, .photo-grid'); });
            idx = imgs.indexOf(img); show(); lb.classList.add('active');
        });
    });

    if (lbPrev) lbPrev.onclick = function(e) { e.stopPropagation(); idx = (idx - 1 + imgs.length) % imgs.length; show(); };
    if (lbNext) lbNext.onclick = function(e) { e.stopPropagation(); idx = (idx + 1) % imgs.length; show(); };
    document.getElementById('lightboxClose').onclick = close;
    lb.onclick = function(e) { if (e.target === lb) close(); };
}

// ── 排序 ──
function bindSortTabs() {
    var tabs = document.querySelectorAll('#sortTabs .content-tab');
    if (!tabs.length) return;
    var ct = document.querySelector('.content-scroll');
    if (!ct) return;
    if (!ct.dataset.origOrder) {
        var cards0 = Array.from(ct.querySelectorAll('.post-card[data-views], .post-card-simple[data-views]'));
        ct.dataset.origOrder = cards0.map(function(c) { return c.dataset.views + ':' + c.dataset.comments; }).join(',');
    }
    tabs.forEach(function(tab) {
        if (tab.dataset.sortBound) return;
        tab.dataset.sortBound = '1';
        tab.addEventListener('click', function() {
            tabs.forEach(function(t) { t.classList.remove('active'); });
            tab.classList.add('active');
            var sort = tab.dataset.sort;
            var all = Array.from(ct.querySelectorAll('.post-card[data-views], .post-card-simple[data-views]'));
            if (!all.length) return;
            var pg = ct.querySelector('.pagination');
            if (sort === 'default') {
                var orig = ct.dataset.origOrder.split(',');
                all.sort(function(a, b) { return orig.indexOf(a.dataset.views + ':' + a.dataset.comments) - orig.indexOf(b.dataset.views + ':' + b.dataset.comments); });
            } else {
                all.sort(function(a, b) { return parseInt(b.dataset[sort]) - parseInt(a.dataset[sort]); });
            }
            all.forEach(function(c) { ct.insertBefore(c, pg); });
            retriggerWaterfall(ct);
        });
    });
}

// ── 评论表单（用事件委托，避免 PJAX 后绑定丢失） ──
var _commentSubmitting = false;

document.addEventListener('submit', function(e) {
    var form = e.target;
    if (!form || form.id !== 'comment-form') return;
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();
    doCommentSubmit(form);
}, true);

/**
 * Typecho 评论提交流程（全面排查版）
 *
 * Typecho comment action 行为：
 *   成功 → 302 重定向到文章页
 *   失败 → 200 返回带错误信息的 HTML 页面
 *
 * 关键判断逻辑：
 *   1. response.url ≠ action → 浏览器跟了重定向 → Typecho 走了 302 → 评论成功
 *   2. response.url = action → Typecho 没有重定向 → 需要检查是否有错误
 *   3. 如果没有检测到任何错误信息但 Typecho 也没重定向 → 可能是评论被过滤/静默拒绝
 */
function doCommentSubmit(form) {
    if (_commentSubmitting) return;

    var ta = form.querySelector('textarea[name="text"]');
    var text = ta ? ta.value.trim() : '';
    if (!text) { showToast('请输入评论内容', 'error'); return; }

    _commentSubmitting = true;
    var savedText = text;
    var btn = form.querySelector('#comment-submit');
    var orig = btn ? btn.innerHTML : '';
    if (btn) { btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> 提交中…'; btn.disabled = true; }

    var fd = new FormData(form);
    var action = form.getAttribute('action');
    if (!action || action === '#') action = location.href;

    fetch(action, { method: 'POST', body: fd, credentials: 'same-origin' })
    .then(function(response) {

        /* ── ① 非 200 响应 → 一定失败 ── */
        if (!response.ok) {
            return response.text().then(function(html) {
                showToast(_extractErrMsg(html) || '评论提交失败 (HTTP ' + response.status + ')', 'error');
                if (ta) ta.value = savedText;
            });
        }

        /* ── ② JSON 响应（某些插件/API 场景） ── */
        var ct = (response.headers && response.headers.get) ? response.headers.get('content-type') || '' : '';
        if (ct.indexOf('json') !== -1) {
            return response.json().then(function(data) {
                var errMsg = data.message || data.error || data.msg || data.reason || '';
                if (!errMsg && (data.success === false || (data.code && data.code !== 0))) errMsg = '评论提交失败';
                if (errMsg) {
                    showToast(errMsg, 'error');
                    if (ta) ta.value = savedText;
                } else {
                    _commentSuccess(ta);
                }
            });
        }

        /* ── ③ HTML 响应 → 核心判断 ── */
        return response.text().then(function(html) {
            /*
             * 关键判断：Typecho 成功时会 302 重定向到文章页。
             * 浏览器跟随重定向后 response.url 会变成文章页 URL，
             * 而 action 是评论接口 URL（如 /index.php/action/contents-comment）。
             * 如果两者不同 → 说明 Typecho 走了 302 → 评论已成功写入。
             */
            var redirected = false;
            try {
                var respUrl = response.url || '';
                var actPath = _normalizeUrl(action);
                var respPath = _normalizeUrl(respUrl);
                redirected = respPath && actPath && respPath !== actPath;
            } catch(_e) {}

            if (redirected) {
                /* Typecho 302 了 → 评论已写入数据库 */
                _commentSuccess(ta);
                return;
            }

            /*
             * Typecho 没有重定向（response.url = action）→ 可能是失败。
             * 但也要考虑边界情况：action 本身就是文章页 URL（表单 action 异常时的 fallback）。
             * 此时 response.url = action 不能说明失败，需要看内容。
             */
            var isActionFallback = (action === location.href || action === location.pathname);
            var errMsg = _extractErrMsg(html);

            if (errMsg) {
                /* 检测到明确的错误信息 → 失败 */
                showToast(errMsg, 'error');
                if (ta) ta.value = savedText;
            } else if (isActionFallback) {
                /* action 是文章页 URL（fallback），无法通过 URL 判断，
                   但没检测到错误 → 大概率成功（Typecho 可能用了 meta refresh 等非标准重定向） */
                _commentSuccess(ta);
            } else {
                /*
                 * action 是评论接口 URL，Typecho 没重定向，也没错误信息。
                 * 这种情况可能是：评论被反垃圾/插件静默过滤、服务器配置问题等。
                 * 兜底：提示用户评论可能需要审核。
                 */
                showToast('评论已提交，可能需要审核后显示', 'success');
                if (ta) ta.value = '';
                setTimeout(function() {
                    if (typeof clearPageCache === 'function') clearPageCache(location.href);
                    location.reload();
                }, 1500);
            }
        });
    })
    .catch(function() {
        showToast('网络错误，请重试', 'error');
        if (ta) ta.value = savedText;
    })
    .finally(function() {
        if (btn) { btn.innerHTML = orig; btn.disabled = false; }
        _commentSubmitting = false;
    });
}

/** 评论成功统一处理 */
function _commentSuccess(ta) {
    showToast('评论已提交，感谢您的留言！', 'success');
    if (ta) ta.value = '';
    setTimeout(function() {
        if (typeof clearPageCache === 'function') clearPageCache(location.href);
        location.reload();
    }, 1500);
}

/** URL 归一化（去掉协议/域名/query/fragment，只比 path） */
function _normalizeUrl(url) {
    try {
        var u = new URL(url, location.origin);
        return u.pathname.replace(/\/+$/, '') || '/';
    } catch(_e) {
        return url.split('?')[0].split('#')[0].replace(/\/+$/, '') || '/';
    }
}

/**
 * 从 HTML 中提取错误信息（只在 Typecho 消息区域查找，
 * 避免正文内容中的"垃圾""禁止"等词导致误判）
 */
function _extractErrMsg(html) {
    try {
        var doc = new DOMParser().parseFromString(html, 'text/html');

        /* Typecho 标准消息区域 */
        var msgEl = doc.querySelector('.typecho-message span')
            || doc.querySelector('.typecho-message')
            || doc.querySelector('.message')
            || doc.querySelector('[class*="error"]')
            || doc.querySelector('.typecho-notice')
            || doc.querySelector('.notice')
            || doc.querySelector('[role="alert"]');

        if (msgEl) {
            var msgText = msgEl.textContent.trim();
            if (msgText) {
                var lower = msgText.toLowerCase();
                var errKw = ['失败','错误','error','禁止','spam','垃圾','已发表过','过快',
                             'flood','重复','不能为空','评论已关闭','不允许','denied','blocked',
                             'invalid','missing','required','too many','limit'];
                for (var i = 0; i < errKw.length; i++) {
                    if (lower.indexOf(errKw[i]) !== -1) return msgText;
                }
            }
        }

        /* 页面 title 含错误指示 */
        var titleEl = doc.querySelector('title');
        if (titleEl) {
            var t = titleEl.textContent.toLowerCase();
            if (t.indexOf('error') !== -1 || t.indexOf('错误') !== -1) return '评论提交失败，请重试';
        }
    } catch(_e) {}
    return '';
}

function bindArchiveTabs() {
    var tabs = document.getElementById('archTabs');
    var list = document.getElementById('archList');
    if (!tabs || !list) return;
    tabs.onclick = function(e) {
        var btn = e.target.closest('.pg-arch-tab');
        if (!btn) return;
        var year = btn.getAttribute('data-year');
        tabs.querySelectorAll('.pg-arch-tab').forEach(function(t) { t.classList.remove('active'); });
        btn.classList.add('active');
        list.querySelectorAll('.pg-arch-panel').forEach(function(p) {
            p.style.display = p.getAttribute('data-year') === year ? '' : 'none';
        });
        // 重新触发当前年份面板的瀑布流动画
        var panel = list.querySelector('.pg-arch-panel[data-year="' + year + '"]');
        if (panel) retriggerWaterfall(panel);
    };
}

function bindCommentForm() {
    var replyBar = document.getElementById('cmt-reply-bar');
    var replyInfo = document.getElementById('cmt-reply-info');
    var cancelBtn = document.getElementById('cmt-cancel-btn');
    var parentInput = document.getElementById('comment-parent');
    var commentForm = document.getElementById('comment-form');

    if (cancelBtn) {
        cancelBtn.onclick = function() {
            if (parentInput) parentInput.value = '';
            if (replyBar) replyBar.style.display = 'none';
            var ta = commentForm ? commentForm.querySelector('textarea') : null;
            if (ta) ta.placeholder = '说点什么吧…';
        };
    }

    // Emoji
    var emojiBtn = document.getElementById('cmt-emoji-btn');
    var emojiPanel = document.getElementById('cmt-emoji-panel');
    var emojiOpen = false;
    var emojis = ['😀','😂','🤣','😍','🥰','😘','😎','🤔','😱','😭','😤','👍','👎','❤️','🔥','🎉','💯','✅','⭐','🙏'];

    if (emojiPanel && !emojiPanel.dataset.built) {
        emojiPanel.innerHTML = emojis.map(function(em) {
            return '<span class="cmt-emoji-item">' + em + '</span>';
        }).join('');
        emojiPanel.dataset.built = '1';
    }

    if (emojiBtn) {
        emojiBtn.onclick = function(e) {
            e.stopPropagation();
            if (!emojiPanel) return;
            emojiOpen = !emojiOpen;
            emojiPanel.style.display = emojiOpen ? 'flex' : 'none';
        };
    }
    if (emojiPanel) {
        emojiPanel.onclick = function(ev) {
            var item = ev.target.closest('.cmt-emoji-item');
            if (!item || !commentForm) return;
            var ta = commentForm.querySelector('textarea');
            if (ta) {
                var s = ta.selectionStart;
                ta.value = ta.value.substring(0, s) + item.textContent + ta.value.substring(ta.selectionEnd);
                ta.selectionStart = ta.selectionEnd = s + item.textContent.length;
                ta.focus();
            }
            emojiPanel.style.display = 'none';
            emojiOpen = false;
        };
    }
}

document.addEventListener('click', function(e) {
    var btn = e.target.closest('.cmt-reply-btn');
    if (!btn) return;
    e.preventDefault();
    var cid = btn.getAttribute('data-cid');
    var coid = btn.getAttribute('data-coid');
    var parentInput = document.getElementById('comment-parent');
    var replyBar = document.getElementById('cmt-reply-bar');
    var replyInfo = document.getElementById('cmt-reply-info');
    var commentForm = document.getElementById('comment-form');
    if (parentInput) parentInput.value = coid;
    var card = document.getElementById(cid);
    var nameEl = card ? card.querySelector('.cmt-name') : null;
    var name = nameEl ? nameEl.textContent.trim() : '';
    if (replyInfo) replyInfo.innerHTML = '回复 <strong>' + name + '</strong>';
    if (replyBar) replyBar.style.display = 'flex';
    var ta = commentForm ? commentForm.querySelector('textarea') : null;
    if (ta) { ta.focus(); ta.placeholder = '回复 ' + name + '…'; }
    if (commentForm) commentForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
});

document.addEventListener('click', function(e) {
    var panel = document.getElementById('cmt-emoji-panel');
    if (panel && panel.style.display !== 'none' && !e.target.closest('.cmt-emoji-panel') && !e.target.closest('.cmt-emoji-btn')) {
        panel.style.display = 'none';
    }
});

function initTableWrap() {
    document.querySelectorAll('.article-content table').forEach(function(table) {
        if (table.parentNode.classList.contains('table-wrap')) return;
        var wrap = document.createElement('div');
        wrap.className = 'table-wrap';
        table.parentNode.insertBefore(wrap, table);
        wrap.appendChild(table);
    });
}

function initSearchHighlight() {
    var params = new URLSearchParams(location.search);
    var keyword = params.get('s');
    if (!keyword || keyword.length < 2) return;
    var content = document.querySelector('.content-scroll');
    if (!content) return;
    var walker = document.createTreeWalker(content, NodeFilter.SHOW_TEXT, null, false);
    var nodes = [];
    while (walker.nextNode()) nodes.push(walker.currentNode);
    var re = new RegExp('(' + keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
    nodes.forEach(function(node) {
        if (!node.nodeValue.trim() || node.parentNode.closest('.search-highlight')) return;
        if (!re.test(node.nodeValue)) return;
        var span = document.createElement('span');
        span.innerHTML = node.nodeValue.replace(re, '<span class="search-highlight">$1</span>');
        node.parentNode.replaceChild(span, node);
    });
}

// ── 代码块复制 ──
function initCodeCopy() {
    document.querySelectorAll('.article-content pre').forEach(function(pre) {
        if (pre.querySelector('.code-copy-btn')) return;
        var btn = document.createElement('button');
        btn.className = 'code-copy-btn';
        btn.innerHTML = '<i class="fa-regular fa-copy"></i>';
        btn.title = '复制代码';
        btn.addEventListener('click', function() {
            var code = pre.querySelector('code');
            var text = code ? code.textContent : pre.textContent;
            navigator.clipboard.writeText(text).then(function() {
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                btn.classList.add('copied');
                setTimeout(function() {
                    btn.innerHTML = '<i class="fa-regular fa-copy"></i>';
                    btn.classList.remove('copied');
                }, 1500);
            }).catch(function() {
                var ta = document.createElement('textarea');
                ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
                document.body.appendChild(ta); ta.select();
                try { document.execCommand('copy');
                    btn.innerHTML = '<i class="fa-solid fa-check"></i>'; btn.classList.add('copied');
                    setTimeout(function() { btn.innerHTML = '<i class="fa-regular fa-copy"></i>'; btn.classList.remove('copied'); }, 1500);
                } catch(e) {}
                document.body.removeChild(ta);
            });
        });
        pre.style.position = 'relative';
        pre.appendChild(btn);
    });
}


// Like（文章详情页 + 独立页面）
function bindLike() {
    var btn = document.getElementById('likeBtn');
    if (!btn || btn.dataset.bound) return;
    btn.dataset.bound = '1';
    if (btn.dataset.liked === '1') btn.classList.add('liked');
    btn.addEventListener('click', function() {
        var cid = btn.dataset.cid;
        var isLiked = btn.classList.contains('liked');
        var action = isLiked ? 'undo' : 'do';
        btn.style.pointerEvents = 'none';
        fetch(location.href, {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'likeup=' + cid + '&action=' + action
        })
        .then(function(r) { return r.text(); })
        .then(function(text) {
            var likes;
            try { likes = JSON.parse(text).likes; } catch(e) { likes = parseInt(text); }
            var countEl = btn.querySelector('.like-count');
            if (countEl) countEl.textContent = likes;
            if (action === 'do') {
                btn.classList.add('liked');
                btn.querySelector('i').className = 'fa-solid fa-heart';
                btn.querySelector('.like-text').textContent = '\u5df2\u559c\u6b22';
                btn.classList.add('animating');
                var fh = document.createElement('i');
                fh.className = 'fa-solid fa-heart like-float-heart';
                btn.appendChild(fh);
                setTimeout(function() { btn.classList.remove('animating'); fh.remove(); }, 800);
                showToast('\u70b9\u8d5e\u6210\u529f \u2764\ufe0f', 'success');
            } else {
                btn.classList.remove('liked');
                btn.querySelector('i').className = 'fa-regular fa-heart';
                btn.querySelector('.like-text').textContent = '\u559c\u6b22\u8fd9\u7bc7\u6587\u7ae0';
                showToast('\u5df2\u53d6\u6d88\u70b9\u8d5e', 'success');
            }
        })
        .catch(function() { showToast('\u70b9\u8d5e\u5931\u8d25', 'error'); })
        .finally(function() { btn.style.pointerEvents = ''; });
    });
}

// 首页卡片点赞
function bindIndexLike() {
    document.querySelectorAll('.like-action:not([data-bound])').forEach(function(btn) {
        btn.dataset.bound = '1';
        btn.addEventListener('click', function() {
            var cid = btn.dataset.cid;
            if (!cid) return;
            var countEl = btn.querySelector('span');
            var iconEl = btn.querySelector('i');
            var isLiked = btn.classList.contains('liked');
            var action = isLiked ? 'undo' : 'do';
            btn.style.pointerEvents = 'none';
            fetch(location.href, {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'likeup=' + cid + '&action=' + action
            })
            .then(function(r) { return r.text(); })
            .then(function(text) {
                var likes;
                try { likes = JSON.parse(text).likes; } catch(e) { likes = parseInt(text); }
                if (countEl) countEl.textContent = likes;
                if (action === 'do') {
                    btn.classList.add('liked');
                    if (iconEl) iconEl.className = 'fa-solid fa-thumbs-up';
                    showToast('\u70b9\u8d5e\u6210\u529f', 'success');
                } else {
                    btn.classList.remove('liked');
                    if (iconEl) iconEl.className = 'fa-regular fa-thumbs-up';
                    showToast('\u5df2\u53d6\u6d88\u70b9\u8d5e', 'success');
                }
            })
            .catch(function() { showToast('\u70b9\u8d5e\u5931\u8d25', 'error'); })
            .finally(function() { btn.style.pointerEvents = ''; });
        });
    });
}
// ── 文章目录 TOC ──
function initTOC() {
  var content = document.querySelector('.article-content');
  var tocWrap = document.getElementById('articleTOC');
  var inlineBtn = document.getElementById('tocInlineBtn');
  if (!content || !tocWrap) return;
  var headings = content.querySelectorAll('h2, h3');
  if (headings.length < 2) { if (inlineBtn) inlineBtn.style.display = 'none'; return; }
  if (inlineBtn) inlineBtn.style.display = '';

  // 构建目录列表
  var list = document.createElement('div');
  list.className = 'toc-list';
  headings.forEach(function(h, i) {
    if (!h.id) h.id = 'toc-' + i;
    var item = document.createElement('a');
    item.className = 'toc-item toc-' + h.tagName.toLowerCase();
    item.href = '#' + h.id;
    item.textContent = h.textContent;
    item.addEventListener('click', function(e) {
      e.preventDefault();
      h.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
    list.appendChild(item);
  });
  tocWrap.appendChild(list);

  // 按钮点击展开/收起
  if (inlineBtn) {
    inlineBtn.addEventListener('click', function() {
      var isOpen = tocWrap.classList.toggle('open');
      inlineBtn.classList.toggle('active', isOpen);
      inlineBtn.title = isOpen ? '收起目录' : '展开目录';
    });
  }

  // 滚动高亮
  var feed = document.getElementById('feed');
  if (!feed) return;
  var items = list.querySelectorAll('.toc-item');
  var ticking = false;
  feed.addEventListener('scroll', function() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(function() {
      var current = '';
      headings.forEach(function(h) {
        var rect = h.getBoundingClientRect();
        if (rect.top <= 120) current = h.id;
      });
      items.forEach(function(item) {
        item.classList.toggle('active', item.getAttribute('href') === '#' + current);
      });
      ticking = false;
    });
  });
}

// ── 滚动条自动显隐 ──
function initScrollAutoHide() {
  var feed = document.getElementById('feed');
  if (!feed) return;
  var scrollTimer = null;
  feed.addEventListener('scroll', function() {
    feed.classList.add('sb-active');
    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(function() {
      feed.classList.remove('sb-active');
    }, 800);
  });
}

// ── 通用瀑布流动画 ──
var WATERFALL_SELECTORS = [
  '.post-card', '.post-card-simple', '.friend-feed-card',
  '.cmt-item', '.cmt-card', '.photo-item',
  '.link-card', '.album-card', '.pg-arch-row', '.pg-lb-item'
].join(',');

function initWaterfall() {
  var cards = document.querySelectorAll(WATERFALL_SELECTORS);
  if (!cards.length) return;
  if (typeof IntersectionObserver !== 'undefined') {
    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          var card = entry.target;
          if (!card.classList.contains('card-visible')) {
            var parent = card.parentElement;
            var siblings = parent ? Array.from(parent.querySelectorAll(WATERFALL_SELECTORS + ':not(.card-visible)')) : [card];
            var idx = siblings.indexOf(card);
            card.style.animationDelay = (idx * 0.04) + 's';
            card.classList.add('card-visible');
          }
          observer.unobserve(card);
        }
      });
    }, { threshold: 0.05 });
    cards.forEach(function(card) { observer.observe(card); });
  } else {
    cards.forEach(function(card) { card.classList.add('card-visible'); });
  }
}

// 重新触发指定容器内卡片的瀑布流动画（切 tab 用）
function retriggerWaterfall(container) {
  var cards = container.querySelectorAll(WATERFALL_SELECTORS);
  cards.forEach(function(card, i) {
    card.classList.remove('card-visible');
    void card.offsetWidth;
    card.style.animationDelay = (i * 0.04) + 's';
    card.classList.add('card-visible');
  });
}

// ── 友链状态检测（服务端 + 客户端双重检测 + localStorage 持久缓存） ──
var _linkCheckCache = {};  // url -> { status: 'ok'|'fail', ts: timestamp }
var _linkCheckTTL = 3600000; // 缓存有效期 1 小时（毫秒）

/* 从 localStorage 加载缓存 */
function _loadLinkCache() {
  try {
    var raw = localStorage.getItem('robes_link_check');
    if (raw) {
      var data = JSON.parse(raw);
      var now = Date.now();
      // 过滤掉过期的条目
      var valid = {};
      for (var url in data) {
        if (data[url] && data[url].ts && (now - data[url].ts) < _linkCheckTTL) {
          valid[url] = data[url];
        }
      }
      _linkCheckCache = valid;
      _saveLinkCache(); // 回写，清除过期条目
    }
  } catch(_e) {}
}

/* 写入 localStorage */
function _saveLinkCache() {
  try {
    localStorage.setItem('robes_link_check', JSON.stringify(_linkCheckCache));
  } catch(_e) {}
}

/* 初始化时加载缓存 */
_loadLinkCache();

function initLinkCheck() {
  /* 后台开关关闭时，直接标记所有卡片为“未检测”并跳过 */
  if (typeof ROBES !== 'undefined' && !ROBES.enableLinkCheck) {
    document.querySelectorAll('.link-status').forEach(function(dot) {
      dot.style.display = 'none';
    });
    return;
  }
  var cards = document.querySelectorAll('.link-card[data-url]');
  if (!cards.length) return;

  var pending = [];
  var now = Date.now();

  cards.forEach(function(card) {
    var url = card.getAttribute('data-url');
    var dot = card.querySelector('.link-status');
    if (!url || !dot) return;

    /* 检查 localStorage 缓存 */
    var cached = _linkCheckCache[url];
    if (cached && cached.ts && (now - cached.ts) < _linkCheckTTL) {
      _applyResult(dot, cached.status);
      return;
    }
    if (card.dataset.checking === '1') return;
    card.dataset.checking = '1';
    dot.className = 'link-status checking';
    dot.title = '检测中…';
    dot.textContent = '检测中';
    pending.push({ card: card, url: url, dot: dot });
  });

  if (!pending.length) return;
  _serverCheck(pending);
}

function _applyResult(dot, result) {
  if (result === 'ok') {
    dot.className = 'link-status online';
    dot.title = '正常';
    dot.textContent = '在线';
  } else {
    dot.className = 'link-status offline';
    dot.title = '无法访问';
    dot.textContent = '离线';
  }
}

function _markResult(url, status) {
  _linkCheckCache[url] = { status: status, ts: Date.now() };
  _saveLinkCache();
}

function _serverCheck(pending) {
  var urls = pending.map(function(p) { return p.url; });

  var apiUrl = (typeof ROBES !== 'undefined' && ROBES.themeUrl ? ROBES.themeUrl : '/usr/themes/robes/') + 'api-link-check.php';
  fetch(apiUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: urls.map(function(u) { return 'urls[]=' + encodeURIComponent(u); }).join('&')
  })
  .then(function(r) { return r.json(); })
  .then(function(data) {
    pending.forEach(function(p) {
      var r = data[p.url];
      var status = (r && r.ok) ? 'ok' : 'fail';
      _markResult(p.url, status);
      _applyResult(p.dot, status);
      p.card.dataset.checking = '0';
    });
  })
  .catch(function() {
    _fallbackCheck(pending);
  });
}

function _fallbackCheck(pending) {
  pending.forEach(function(p) {
    var controller = new AbortController();
    var timer = setTimeout(function() { controller.abort(); }, 10000);
    fetch(p.url, { mode: 'no-cors', signal: controller.signal })
      .then(function() {
        _markResult(p.url, 'ok');
        _applyResult(p.dot, 'ok');
      })
      .catch(function() {
        _markResult(p.url, 'fail');
        _applyResult(p.dot, 'fail');
      })
      .finally(function() { clearTimeout(timer); p.card.dataset.checking = '0'; });
  });
}

// ── Mermaid 按需加载 + 渲染（PJAX 兼容） ──
var _mermaidLoading = null;   // Promise，防止重复加载
var _mermaidReady = false;

function _loadMermaid() {
    if (_mermaidReady || (typeof mermaid !== 'undefined' && window.mermaid)) {
        _mermaidReady = true;
        return Promise.resolve();
    }
    if (_mermaidLoading) return _mermaidLoading;

    _mermaidLoading = new Promise(function (resolve, reject) {
        var s = document.createElement('script');
        s.src = 'https://cdn.bootcdn.net/ajax/libs/mermaid/10.9.1/mermaid.min.js';
        s.onload = function () {
            if (window.mermaid) {
                mermaid.initialize({
                    startOnLoad: false,
                    theme: (typeof ROBES !== 'undefined' && ROBES.mermaidTheme) ? ROBES.mermaidTheme : 'default'
                });
                _mermaidReady = true;
                resolve();
            } else {
                reject(new Error('mermaid not found'));
            }
        };
        s.onerror = reject;
        document.head.appendChild(s);
    });
    return _mermaidLoading;
}

function renderMermaid() {
    // 页面上没有 Mermaid 相关内容，直接跳过，不加载脚本
    var hasMermaid = document.querySelector('.mermaid, .article-content pre code.language-mermaid, .article-content pre code.mermaid');
    if (!hasMermaid) return;

    _loadMermaid().then(function () {
        // 转换 pre code -> div.mermaid
        document.querySelectorAll('.article-content pre code.language-mermaid, .article-content pre code.mermaid').forEach(function (code) {
            var pre = code.parentElement;
            if (pre && pre.tagName === 'PRE' && pre.dataset.mermaidDone !== '1') {
                pre.dataset.mermaidDone = '1';
                var div = document.createElement('div');
                div.className = 'mermaid';
                div.textContent = code.textContent.trim();
                pre.replaceWith(div);
            }
        });

        // 只渲染还没有 SVG 的 .mermaid
        var pending = Array.from(document.querySelectorAll('.mermaid')).filter(function (el) {
            return !el.querySelector('svg');
        });
        if (!pending.length) return;

        try {
            mermaid.run({ nodes: pending });
        } catch (e) {
            if (typeof mermaid.init === 'function') mermaid.init(undefined, pending);
        }
    }).catch(function () {
        // 加载失败，静默处理
    });
}

// ── 代码高亮 ──
function initCodeHighlight() {
    var pres = document.querySelectorAll('.article-content pre code:not(.hljs)');
    if (!pres.length) return;

    // 过滤掉 Mermaid 代码块：类名排除 + 文本内容兜底
    var toHl = [];
    pres.forEach(function (block) {
        // 1) 类名排除
        if (block.classList.contains('mermaid') || block.classList.contains('language-mermaid')) return;

        // 2) 文本内容兜底（Mermaid 图类型关键字）
        var t = block.textContent.trim();
        if (/^(graph|flowchart|sequenceDiagram|classDiagram|stateDiagram|erDiagram|gantt|pie|gitGraph|mindmap|timeline|journey|quadrantChart|requirementDiagram|C4Context|sankey-beta|xychart-beta|block-beta|packet-beta|architecture-beta)\b/i.test(t)) {
            return;
        }

        toHl.push(block);
    });

    if (!toHl.length) return;

    if (typeof hljs === 'undefined') {
        var script = document.createElement('script');
        script.src = 'https://cdn.bootcdn.net/ajax/libs/highlight.js/11.9.0/highlight.min.js';
        script.onload = function () {
            toHl.forEach(function (block) { hljs.highlightElement(block); });
        };
        document.head.appendChild(script);
    } else {
        toHl.forEach(function (block) { hljs.highlightElement(block); });
    }
}

function initLazyLoad() {
    document.querySelectorAll('.article-content img:not([loading])').forEach(function(img) {
        img.setAttribute('loading', 'lazy');
    });
}

// ── 图片加载动画（shimmer + fade-in） ──
function initImgLoading() {
    var imgs = document.querySelectorAll('.post-cover img, .album-cover img, .friend-feed-avatar img, .link-avatar img, .img-cell img, .photo-item img, .article-content img, .sticky-img img');
    if (!imgs.length) return;
    imgs.forEach(function(img) {
        if (img.getAttribute('data-img-observed')) return;
        img.setAttribute('data-img-observed', '1');
        if (img.complete && img.naturalWidth > 0) {
            img.classList.add('loaded');
            return;
        }
        /* 对文章内容里的图片，用专用包裹元素代替 .article-content，
           避免整个文章区域都变成水波纹 */
        var wrap = img.closest('.post-cover, .album-cover, .friend-feed-avatar, .link-avatar, .img-cell, .photo-item, .sticky-img');
        if (!wrap && img.closest('.article-content')) {
            wrap = document.createElement('span');
            wrap.className = 'img-shimmer-wrap';
            img.parentNode.insertBefore(wrap, img);
            wrap.appendChild(img);
        }
        if (wrap) wrap.classList.add('img-loading');
        img.addEventListener('load', function() {
            img.classList.add('loaded');
            if (wrap) wrap.classList.remove('img-loading');
        }, { once: true });
        img.addEventListener('error', function() {
            img.classList.add('loaded');
            if (wrap) wrap.classList.remove('img-loading');
        }, { once: true });
    });
}

function bindLinksTabs() {
    var tabs = document.querySelectorAll('#linksCategoryTabs .links-cat-tab');
    if (!tabs.length) return;
    var groups = document.querySelectorAll('.links-category-group');
    tabs.forEach(function(tab) {
        if (tab.dataset.bound) return;
        tab.dataset.bound = '1';
        tab.addEventListener('click', function() {
            var cat = this.getAttribute('data-category');
            tabs.forEach(function(t) { t.classList.remove('active'); });
            this.classList.add('active');
            groups.forEach(function(g) {
                g.style.display = (g.getAttribute('data-category') === cat) ? '' : 'none';
            });
            // 切换分类后，重新触发瀑布流动画 + 补充检测
            var activeGroup = document.querySelector('.links-category-group[data-category="' + cat + '"]');
            if (activeGroup) retriggerWaterfall(activeGroup);
            initLinkCheck();
        });
    });
}

function syncEditBtn() {
    var btn = document.getElementById('editPostBtn');
    if (!btn) return;
    var url = document.body.getAttribute('data-edit-url');
    if (url) { btn.href = url; btn.style.display = ''; }
    else { btn.style.display = 'none'; }
}

// ── 外部链接补 target（配合 MarkdownParse 的 rel 标记） ──
function initLinkTarget() {
    var content = document.querySelector('.article-content');
    if (!content) return;
    content.querySelectorAll('a[rel~="noopener"]').forEach(function(a) {
        if (a.target === '_blank') return;
        a.setAttribute('target', '_blank');
    });
}

function rebindAll() {
    initTheme(); initSectionToggle(); initHitokoto(); bindLike(); bindIndexLike(); bindSidebarAutoClose(); bindSortTabs(); bindCommentForm(); bindArchiveTabs(); bindLightbox(); initCodeCopy(); initCodeHighlight(); initTableWrap(); initSearchHighlight(); initLinkCheck(); initWaterfall(); initTOC(); initLazyLoad(); initImgLoading(); bindLinksTabs(); syncEditBtn(); initScrollAutoHide(); renderMermaid(); initLinkTarget();
    var tagsEl = document.getElementById('articleTags');
    if (tagsEl && !tagsEl.querySelector('a')) tagsEl.style.display = 'none';
    var se = document.getElementById('feed');
    if (se) { se.addEventListener('scroll', updateScrollUI); updateScrollUI(); }
    var cb = document.getElementById('sidebarCloseBtn');
    if (cb) cb.addEventListener('click', closeSidebar);
}

(function() {
    var scrollEl = document.getElementById('feed');
    if (scrollEl) { scrollEl.addEventListener('scroll', updateScrollUI, {passive: true}); updateScrollUI(); }
    var scrollTopBtn = document.getElementById('scrollTopBtn');
    if (scrollTopBtn) scrollTopBtn.addEventListener('click', function() {
        var el = document.getElementById('feed');
        if (!el) return;
        if (el.scrollHeight - el.clientHeight <= 10) return;
        if (el.scrollTop < 50) el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' });
        else el.scrollTo({ top: 0, behavior: 'smooth' });
    });
    var hamburgerBtn = document.getElementById('hamburgerBtn');
    if (hamburgerBtn) hamburgerBtn.addEventListener('click', function() { var sb = document.querySelector('.sidebar'); (sb && sb.classList.contains('open')) ? closeSidebar() : openSidebar(); });
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);
    var sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeSidebar);

    bindSidebarAutoClose(); bindSortTabs(); bindCommentForm(); bindArchiveTabs(); bindIndexLike(); bindLightbox(); initCodeCopy(); initCodeHighlight(); initTableWrap(); initLinkCheck(); initWaterfall(); initTOC(); bindLinksTabs(); syncEditBtn(); initImgLoading(); initScrollAutoHide(); renderMermaid(); initLinkTarget();
    initTheme(); initSectionToggle(); initHitokoto();
    var tagsEl = document.getElementById('articleTags');
    if (tagsEl && !tagsEl.querySelector('a')) tagsEl.style.display = 'none';

    document.addEventListener('keydown', function(e) { var lb = document.getElementById('lightbox'); if (lb && lb.classList.contains('active') && e.key === 'Escape') lb.classList.remove('active'); });

    // PJAX
    if (!ROBES.enablePJAX) return;
    var container = document.querySelector('.main-body');
    if (!container) return;
    var isPjax = false, cache = {};
    window.clearPageCache = function(url) {
        if (url) { delete cache[url]; }
        else { cache = {}; }
    };

    function isPjaxLink(a) {
        if (!a || !a.href || a.target === '_blank') return false;
        if (a.href.indexOf(location.origin) !== 0) return false;
        if (a.href.indexOf('#') !== -1 && a.pathname === location.pathname) return false;
        if (a.classList.contains('no-pjax')) return false;
        return true;
    }

    loadPage = function(url, push) {
        if (isPjax) return;
        isPjax = true;
        var loading = document.getElementById('pjaxLoading');
        if (loading) loading.classList.add('active');

        function render(html) {
            var doc = new DOMParser().parseFromString(html, 'text/html');
            var nc = doc.querySelector('.main-body');
            var nt = doc.querySelector('title');
            var nb = doc.querySelector('body');
            if (nc) {
                container.innerHTML = nc.innerHTML;
                if (nt) document.title = nt.textContent;
                if (nb) {
                    document.body.dataset.page = nb.dataset.page || 'inner';
                    if (nb.dataset.editUrl) document.body.dataset.editUrl = nb.dataset.editUrl;
                    else delete document.body.dataset.editUrl;
                }
                if (push) history.pushState(null, '', url);
                rebindAll();
                var feed = document.getElementById('feed');
                if (feed) {
                    /* 评论分页链接带 #comments，PJAX 后要滚到评论区而不是回到顶部 */
                    var cmt = url.indexOf('#comments') !== -1 ? document.getElementById('comments') : null;
                    if (cmt) {
                        feed.scrollTop = Math.max(0, cmt.getBoundingClientRect().top
                            - feed.getBoundingClientRect().top + feed.scrollTop - 12);
                    } else {
                        feed.scrollTop = 0;
                    }
                }
            } else {
                window.location.href = url;
            }
            if (loading) loading.classList.remove('active');
            isPjax = false;
        }

        if (cache[url]) { render(cache[url]); }
        else {
            fetch(url).then(function(r) { return r.text(); }).then(function(html) { cache[url] = html; render(html); })
                .catch(function() {
            isPjax = false;
            if (loading) loading.classList.remove('active');
            window.location.href = url;
        });
        }
    };

    // 预取
    var prefetchTimer;
    document.addEventListener('mouseover', function(e) {
        var a = e.target.closest('a');
        if (!a || !isPjaxLink(a) || cache[a.href]) return;
        clearTimeout(prefetchTimer);
        prefetchTimer = setTimeout(function() { if(cache[a.href])return; fetch(a.href).then(function(r) { return r.text(); }).then(function(h) { cache[a.href] = h; }).catch(function(){}); }, 80);
    });

    document.addEventListener('click', function(e) {
        var a = e.target.closest('a');
        if (a && isPjaxLink(a)) { e.preventDefault(); loadPage(a.href, true); }
    });

    window.addEventListener('popstate', function() { loadPage(location.href, false); });
})();
