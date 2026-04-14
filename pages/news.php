<?php
/**
 * pages/news.php — Trang tin tức
 */
$pageTitle = 'Tin tức — UmaCT Shop';
$activeNav = 'news';

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/article_model.php';

$articles = getAllArticles();
require_once __DIR__ . '/includes/header.php';
?>

<div style="background:var(--bg-surface); border-bottom:1px solid var(--border); padding:48px 0 32px">
    <div class="container" style="text-align:center">
        <span class="section-label">Tin tức & Sự kiện</span>
        <h1 class="section-title" style="margin-top:8px">Cộng đồng <span>Uma Musume</span></h1>
        <p style="color:var(--text-muted); margin-top:10px; max-width:500px; margin-left:auto; margin-right:auto">
            Cập nhật tin tức mới nhất về figure, event, và mọi thứ liên quan đến Uma Musume
        </p>
    </div>
</div>

<div class="container section">
    <?php if (empty($articles)): ?>
        <div class="empty-state">
            <div class="empty-icon">📰</div>
            <div class="empty-title">Chưa có bài viết nào</div>
            <div class="empty-desc">Hãy quay lại sau nhé!</div>
        </div>
    <?php else: ?>
        <!-- Featured first article -->
        <?php $first = $articles[0]; ?>
        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-xl); overflow:hidden; margin-bottom:32px; display:grid; grid-template-columns:1.2fr 1fr">
            <div style="background:linear-gradient(135deg,var(--bg-surface),#1a0a2e); display:flex; align-items:center; justify-content:center; font-size:6rem; min-height:280px; border-right:1px solid var(--border)">
                📰
            </div>
            <div style="padding:36px">
                <div style="display:flex; gap:10px; margin-bottom:14px">
                    <span class="news-tag">✨ Nổi bật</span>
                    <span class="news-date"><?= formatDateShort($first['created_at']) ?></span>
                </div>
                <h2 style="font-family:'Space Grotesk',sans-serif; font-size:1.4rem; font-weight:800; line-height:1.3; margin-bottom:14px">
                    <a href="<?= BASE_URL ?>/pages/news_detail.php?id=<?= $first['id'] ?>" style="transition:color .15s" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text)'">
                        <?= htmlspecialchars($first['title']) ?>
                    </a>
                </h2>
                <p style="color:var(--text-muted); line-height:1.7; margin-bottom:20px">
                    <?= htmlspecialchars(truncate(strip_tags($first['content'] ?? ''), 160)) ?>
                </p>
                <div style="display:flex; align-items:center; justify-content:space-between">
                    <span style="font-size:.78rem; color:var(--text-dim)">✍️ <?= htmlspecialchars($first['author_name'] ?? 'UmaCT') ?></span>
                    <a href="<?= BASE_URL ?>/pages/news_detail.php?id=<?= $first['id'] ?>" class="btn-hero-primary" style="font-size:.85rem; padding:9px 20px">Đọc bài →</a>
                </div>
            </div>
        </div>

        <!-- Rest of articles -->
        <div class="news-grid">
            <?php foreach (array_slice($articles, 1) as $a): ?>
            <article class="news-card">
                <div class="news-img" style="background:linear-gradient(135deg,var(--bg-surface),#0a1a2e); display:flex; align-items:center; justify-content:center; font-size:3.5rem">
                    📝
                </div>
                <div class="news-body">
                    <div class="news-meta">
                        <span class="news-tag">Tin tức</span>
                        <span class="news-date"><?= formatDateShort($a['created_at']) ?></span>
                    </div>
                    <h3 class="news-title">
                        <a href="<?= BASE_URL ?>/pages/news_detail.php?id=<?= $a['id'] ?>">
                            <?= htmlspecialchars($a['title']) ?>
                        </a>
                    </h3>
                    <p class="news-excerpt"><?= htmlspecialchars(truncate(strip_tags($a['content'] ?? ''), 100)) ?></p>
                    <div class="news-footer">
                        <span class="news-author">✍️ <?= htmlspecialchars($a['author_name'] ?? 'UmaCT') ?></span>
                        <a href="<?= BASE_URL ?>/pages/news_detail.php?id=<?= $a['id'] ?>" class="news-read-more">Đọc tiếp →</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
