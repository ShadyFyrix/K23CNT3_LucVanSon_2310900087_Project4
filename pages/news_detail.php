<?php
/**
 * pages/news_detail.php — Chi tiết bài viết
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/article_model.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: ' . BASE_URL . '/pages/news.php'); exit; }

$article = getArticleById($id);
if (!$article) { http_response_code(404); die('<p style="text-align:center;padding:80px;color:#94a3b8">Không tìm thấy bài viết.</p>'); }

// Bài viết liên quan (tất cả trừ bài hiện tại)
$related = array_filter(getAllArticles(), fn($a) => $a['id'] != $id);
$related = array_slice(array_values($related), 0, 3);

$pageTitle = htmlspecialchars($article['title']) . ' — UmaCT';
$activeNav = 'news';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container section" style="max-width:960px; margin:0 auto">

    <!-- Breadcrumb -->
    <nav style="font-size:.78rem; color:var(--text-dim); margin-bottom:32px">
        <a href="<?= BASE_URL ?>/pages/home.php" style="color:var(--text-muted)">Trang chủ</a>
        <span style="margin:0 6px">›</span>
        <a href="<?= BASE_URL ?>/pages/news.php" style="color:var(--text-muted)">Tin tức</a>
        <span style="margin:0 6px">›</span>
        <span><?= htmlspecialchars(truncate($article['title'], 50)) ?></span>
    </nav>

    <!-- Article -->
    <article style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-xl); overflow:hidden; margin-bottom:48px">
        <!-- Cover -->
        <div style="background:linear-gradient(135deg,#0f0f1a,#1a0a2e,#0a1a2e); height:240px; display:flex; align-items:center; justify-content:center; font-size:6rem; border-bottom:1px solid var(--border)">
            📰
        </div>

        <!-- Content -->
        <div style="padding:40px">
            <!-- Meta -->
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px; flex-wrap:wrap">
                <span class="news-tag">✨ Tin tức</span>
                <span style="font-size:.78rem; color:var(--text-dim)">📅 <?= formatDate($article['created_at']) ?></span>
                <span style="font-size:.78rem; color:var(--text-dim)">✍️ <?= htmlspecialchars($article['author_name'] ?? $article['username'] ?? 'UmaCT') ?></span>
            </div>

            <!-- Title -->
            <h1 style="font-family:'Space Grotesk',sans-serif; font-size:clamp(1.5rem,3vw,2rem); font-weight:800; line-height:1.25; margin-bottom:28px">
                <?= htmlspecialchars($article['title']) ?>
            </h1>

            <!-- Body -->
            <div style="font-size:.95rem; color:var(--text-muted); line-height:1.9; letter-spacing:.01em">
                <?= nl2br(htmlspecialchars($article['content'] ?? '')) ?>
            </div>

            <!-- Share -->
            <div style="margin-top:36px; padding-top:24px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px">
                <a href="<?= BASE_URL ?>/pages/news.php"
                   style="display:inline-flex; align-items:center; gap:8px; color:var(--text-muted); font-size:.875rem; transition:color .15s"
                   onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">
                    ← Về danh sách tin tức
                </a>
                <div style="display:flex; gap:8px">
                    <span style="font-size:.78rem; color:var(--text-dim); padding-top:4px">Chia sẻ:</span>
                    <a href="#" title="Facebook" class="social-link">📘</a>
                    <a href="#" title="Twitter"  class="social-link">🐦</a>
                </div>
            </div>
        </div>
    </article>

    <!-- Related articles -->
    <?php if (!empty($related)): ?>
    <div>
        <h2 style="font-family:'Space Grotesk',sans-serif; font-size:1.1rem; font-weight:700; margin-bottom:20px">
            📚 Bài viết liên quan
        </h2>
        <div class="news-grid">
            <?php foreach ($related as $a): ?>
            <article class="news-card">
                <div class="news-img" style="background:var(--bg-surface); display:flex; align-items:center; justify-content:center; font-size:3rem">📝</div>
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
                    <div class="news-footer">
                        <span class="news-author">✍️ <?= htmlspecialchars($a['author_name'] ?? 'UmaCT') ?></span>
                        <a href="<?= BASE_URL ?>/pages/news_detail.php?id=<?= $a['id'] ?>" class="news-read-more">Đọc →</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
