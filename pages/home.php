<?php
/**
 * pages/home.php — Trang chủ UmaCT Shop
 */
$pageTitle = 'Trang chủ — UmaCT Shop';
$activeNav = 'home';

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/product_model.php';
require_once __DIR__ . '/../models/category_model.php';
require_once __DIR__ . '/../models/article_model.php';

$featuredProducts = getAllProducts(['is_active' => 'true']);
$featuredProducts = array_slice($featuredProducts, 0, 8);

$newProducts = getAllProducts(['sort' => 'newest', 'is_active' => 'true']);
$newProducts = array_slice($newProducts, 0, 4);

$categories = getAllCategories();
$articles   = array_slice(getAllArticles(), 0, 3);

require_once __DIR__ . '/includes/header.php';
?>

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="hero-bg-orb hero-bg-orb--1"></div>
    <div class="hero-bg-orb hero-bg-orb--2"></div>
    <div class="hero-bg-orb hero-bg-orb--3"></div>

    <div class="hero-container">
        <div class="hero-content">
            <div class="hero-label">✨ BST Mùa Hè 2026 đã có mặt!</div>
            <h1 class="hero-title">
                Thế giới<br>
                <span class="highlight">Uma Musume</span><br>
                trong tầm tay bạn
            </h1>
            <p class="hero-desc">
                Khám phá hơn <?= count(getAllProducts()) ?>+ sản phẩm mô hình figure, trang phục cosplay
                và phụ kiện chính hãng. Hàng authentic — cam kết không hàng nhái.
            </p>
            <div class="hero-actions">
                <a href="<?= BASE_URL ?>/pages/shop.php" class="btn-hero-primary">
                    🛍 Mua ngay
                </a>
                <a href="<?= BASE_URL ?>/pages/shop.php?sort=newest" class="btn-hero-secondary">
                    🆕 Sản phẩm mới
                </a>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num" style="background:var(--gradient-btn);-webkit-background-clip:text;-webkit-text-fill-color:transparent">500+</div>
                    <div class="hero-stat-label">Sản phẩm</div>
                </div>
                <div>
                    <div class="hero-stat-num" style="background:var(--gradient-pink);-webkit-background-clip:text;-webkit-text-fill-color:transparent">1,200+</div>
                    <div class="hero-stat-label">Khách hàng</div>
                </div>
                <div>
                    <div class="hero-stat-num" style="color:var(--gold)">100%</div>
                    <div class="hero-stat-label">Hàng chính hãng</div>
                </div>
            </div>
        </div>

        <!-- Hero Images Grid -->
        <div class="hero-image-grid">
            <div class="hero-img-card">
                <span class="hero-img-badge">🔥 Trending</span>
                <img src="https://images.goodsmile.info/cgm/images/product/20230901/13367/98773/large/8e0c07e0b65b89ef2c3d5ad3afd83f02.jpg"
                     alt="Nendoroid Uma Musume"
                     onerror="this.src='<?= BASE_URL ?>/assets/images/no-image.png'">
            </div>
            <div class="hero-img-card">
                <span class="hero-img-badge" style="background:var(--pink)">💫 Mới</span>
                <img src="https://images.goodsmile.info/cgm/images/product/20221101/12458/90837/large/b2be45fd0d7c04b7c0b8c20c9fd0c18a.jpg"
                     alt="Special Week Figure"
                     onerror="this.src='<?= BASE_URL ?>/assets/images/no-image.png'">
            </div>
        </div>
    </div>
</section>

<!-- ===== BENEFITS STRIP ===== -->
<section class="section-sm" style="background:var(--bg-surface); border-bottom:1px solid var(--border)">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">🚚</div>
                <div class="feature-title">Miễn ship toàn quốc</div>
                <div class="feature-desc">Đơn từ 500.000₫ được miễn phí vận chuyển toàn quốc</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🛡️</div>
                <div class="feature-title">Cam kết chính hãng</div>
                <div class="feature-desc">100% hàng authentic từ Good Smile, Cygames và các NCC uy tín</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🔄</div>
                <div class="feature-title">Đổi trả 7 ngày</div>
                <div class="feature-desc">Sản phẩm lỗi hoặc không như mô tả — đổi trả miễn phí</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">💬</div>
                <div class="feature-title">Hỗ trợ 24/7</div>
                <div class="feature-desc">Tư vấn qua Fanpage và Discord — phản hồi trong 30 phút</div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CATEGORIES ===== -->
<?php if (!empty($categories)): ?>
<section class="section">
    <div class="container">
        <div class="section-header section-header-row">
            <div>
                <span class="section-label">Danh mục sản phẩm</span>
                <h2 class="section-title">Chọn theo <span>sở thích</span> của bạn</h2>
            </div>
            <a href="<?= BASE_URL ?>/pages/shop.php" class="link-all">Xem tất cả →</a>
        </div>

        <div class="categories-grid">
            <?php
            $catIcons = ['🏆','👘','✨','🎀','🌟'];
            $catImages = [
                BASE_URL . '/assets/images/cat-figure.jpg',
                BASE_URL . '/assets/images/cat-cosplay.jpg',
                BASE_URL . '/assets/images/cat-accessory.jpg',
            ];
            foreach (array_slice($categories, 0, 3) as $i => $cat):
            ?>
            <a href="<?= BASE_URL ?>/pages/shop.php?category_id=<?= $cat['id'] ?>"
               class="category-card">
                <div style="width:100%; height:100%; background:linear-gradient(135deg,<?= ['#1a0a2e','#0a1a2e','#1a1a0a'][$i % 3] ?>,var(--bg-card)); display:flex; align-items:center; justify-content:center; font-size:5rem;">
                    <?= $catIcons[$i % count($catIcons)] ?>
                </div>
                <div class="category-card-overlay">
                    <div>
                        <div class="category-card-name"><?= htmlspecialchars($cat['name']) ?></div>
                        <div class="category-card-count">Khám phá ngay →</div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== FEATURED PRODUCTS ===== -->
<section class="section" style="background:var(--bg-surface); border-top:1px solid var(--border); border-bottom:1px solid var(--border)">
    <div class="container">
        <div class="section-header section-header-row">
            <div>
                <span class="section-label">Nổi bật</span>
                <h2 class="section-title">Sản phẩm <span>bán chạy</span></h2>
                <p class="section-subtitle">Được yêu thích nhất bởi cộng đồng Uma Musume</p>
            </div>
            <a href="<?= BASE_URL ?>/pages/shop.php" class="link-all">Xem tất cả →</a>
        </div>

        <?php if (empty($featuredProducts)): ?>
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <div class="empty-title">Chưa có sản phẩm nào</div>
                <div class="empty-desc">Hãy quay lại sau nhé!</div>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($featuredProducts as $p): ?>
                    <?php include __DIR__ . '/includes/product_card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ===== PROMO BANNER ===== -->
<section class="section">
    <div class="container">
        <div class="promo-strip">
            <div class="promo-content">
                <div class="promo-label">⚡ Flash Sale</div>
                <h2 class="promo-title">Giảm đến 30% toàn bộ<br>BST Nendoroid</h2>
                <p class="promo-desc">Sử dụng mã <strong style="color:var(--accent)">UMA100K</strong> — Giảm ngay 100.000₫ cho đơn từ 500K</p>
                <a href="<?= BASE_URL ?>/pages/shop.php" class="btn-hero-primary" style="margin-top:18px; display:inline-flex">
                    Mua ngay 🔥
                </a>
            </div>
            <div class="promo-countdown" id="countdown">
                <div class="countdown-item">
                    <div class="countdown-num" id="cdHours">23</div>
                    <div class="countdown-unit">Giờ</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-num" id="cdMins">59</div>
                    <div class="countdown-unit">Phút</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-num" id="cdSecs">00</div>
                    <div class="countdown-unit">Giây</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== NEW ARRIVALS ===== -->
<?php if (!empty($newProducts)): ?>
<section class="section" style="background:var(--bg-surface); border-top:1px solid var(--border)">
    <div class="container">
        <div class="section-header section-header-row">
            <div>
                <span class="section-label">Mới về</span>
                <h2 class="section-title">Hàng mới <span>nhất</span></h2>
            </div>
            <a href="<?= BASE_URL ?>/pages/shop.php?sort=newest" class="link-all">Xem thêm →</a>
        </div>
        <div class="products-grid">
            <?php foreach ($newProducts as $p): ?>
                <?php include __DIR__ . '/includes/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== NEWS ===== -->
<?php if (!empty($articles)): ?>
<section class="section">
    <div class="container">
        <div class="section-header section-header-row">
            <div>
                <span class="section-label">Tin tức</span>
                <h2 class="section-title">Cộng đồng <span>Uma</span></h2>
            </div>
            <a href="<?= BASE_URL ?>/pages/news.php" class="link-all">Tất cả bài viết →</a>
        </div>
        <div class="news-grid">
            <?php foreach ($articles as $a): ?>
            <article class="news-card">
                <div class="news-img" style="background:var(--bg-surface); display:flex; align-items:center; justify-content:center; font-size:3rem;">
                    📰
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
                        <span class="news-author">✍️ <?= htmlspecialchars($a['author_name'] ?? $a['username'] ?? 'UmaCT') ?></span>
                        <a href="<?= BASE_URL ?>/pages/news_detail.php?id=<?= $a['id'] ?>" class="news-read-more">Đọc tiếp →</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Countdown Script -->
<script>
(function() {
    const end = new Date();
    end.setHours(23, 59, 59, 0);

    function tick() {
        const diff = end - new Date();
        if (diff <= 0) return;
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        document.getElementById('cdHours').textContent = String(h).padStart(2,'0');
        document.getElementById('cdMins').textContent  = String(m).padStart(2,'0');
        document.getElementById('cdSecs').textContent  = String(s).padStart(2,'0');
    }
    tick();
    setInterval(tick, 1000);
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
