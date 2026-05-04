<?php
/**
 * Lvs_pages/Lvs_home.php — Trang chủ UmaCT Shop
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Trang chủ — UmaCT Shop';
$activeNav = 'home';

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Lvs_product_model.php';
require_once __DIR__ . '/../models/Lvs_category_model.php';
require_once __DIR__ . '/../models/Lvs_article_model.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';

$Lvs_featuredProducts = array_slice(Lvs_getAllProducts(['is_active' => 'true']), 0, 8);
$Lvs_newProducts      = array_slice(Lvs_getAllProducts(['sort' => 'newest', 'is_active' => 'true']), 0, 4);
$Lvs_categories       = Lvs_getAllCategories();
$Lvs_articles         = array_slice(Lvs_getAllArticles(), 0, 3);
$Lvs_totalProducts    = count(Lvs_getAllProducts());

require_once __DIR__ . '/includes/Lvs_header.php';
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
                Khám phá hơn <?= $Lvs_totalProducts ?>+ sản phẩm mô hình figure, trang phục cosplay
                và phụ kiện chính hãng. Hàng authentic — cam kết không hàng nhái.
            </p>
            <div class="hero-actions">
                <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" class="btn-hero-primary">
                    🛍 Mua ngay
                </a>
                <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php?sort=newest" class="btn-hero-secondary">
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
<?php if (!empty($Lvs_categories)): ?>
<section class="section">
    <div class="container">
        <div class="section-header section-header-row">
            <div>
                <span class="section-label">Danh mục sản phẩm</span>
                <h2 class="section-title">Chọn theo <span>sở thích</span> của bạn</h2>
            </div>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" class="link-all">Xem tất cả →</a>
        </div>
        <div class="categories-grid">
            <?php
            $Lvs_catIcons = ['🏆','👘','✨','🎀','🌟'];
            foreach (array_slice($Lvs_categories, 0, 3) as $Lvs_i => $Lvs_cat):
            ?>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php?category_id=<?= $Lvs_cat['id'] ?>"
               class="category-card">
                <div style="width:100%; height:100%; background:linear-gradient(135deg,<?= ['#1a0a2e','#0a1a2e','#1a1a0a'][$Lvs_i % 3] ?>,var(--bg-card)); display:flex; align-items:center; justify-content:center; font-size:5rem;">
                    <?= $Lvs_catIcons[$Lvs_i % count($Lvs_catIcons)] ?>
                </div>
                <div class="category-card-overlay">
                    <div>
                        <div class="category-card-name"><?= htmlspecialchars($Lvs_cat['name']) ?></div>
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
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" class="link-all">Xem tất cả →</a>
        </div>

        <?php if (empty($Lvs_featuredProducts)): ?>
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <div class="empty-title">Chưa có sản phẩm nào</div>
                <div class="empty-desc">Hãy quay lại sau nhé!</div>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($Lvs_featuredProducts as $p): ?>
                    <?php include __DIR__ . '/includes/Lvs_product_card.php'; ?>
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
                <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" class="btn-hero-primary" style="margin-top:18px; display:inline-flex">
                    Mua ngay 🔥
                </a>
            </div>
            <div class="promo-countdown" id="Lvs_countdown">
                <div class="countdown-item">
                    <div class="countdown-num" id="Lvs_cdHours">23</div>
                    <div class="countdown-unit">Giờ</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-num" id="Lvs_cdMins">59</div>
                    <div class="countdown-unit">Phút</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-num" id="Lvs_cdSecs">00</div>
                    <div class="countdown-unit">Giây</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== NEW ARRIVALS ===== -->
<?php if (!empty($Lvs_newProducts)): ?>
<section class="section" style="background:var(--bg-surface); border-top:1px solid var(--border)">
    <div class="container">
        <div class="section-header section-header-row">
            <div>
                <span class="section-label">Mới về</span>
                <h2 class="section-title">Hàng mới <span>nhất</span></h2>
            </div>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php?sort=newest" class="link-all">Xem thêm →</a>
        </div>
        <div class="products-grid">
            <?php foreach ($Lvs_newProducts as $p): ?>
                <?php include __DIR__ . '/includes/Lvs_product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== NEWS ===== -->
<?php if (!empty($Lvs_articles)): ?>
<section class="section">
    <div class="container">
        <div class="section-header section-header-row">
            <div>
                <span class="section-label">Tin tức</span>
                <h2 class="section-title">Cộng đồng <span>Uma</span></h2>
            </div>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_news.php" class="link-all">Tất cả bài viết →</a>
        </div>
        <div class="news-grid">
            <?php foreach ($Lvs_articles as $Lvs_a): ?>
            <article class="news-card">
                <div class="news-img" style="background:var(--bg-surface); display:flex; align-items:center; justify-content:center; font-size:3rem;">📰</div>
                <div class="news-body">
                    <div class="news-meta">
                        <span class="news-tag">Tin tức</span>
                        <span class="news-date"><?= Lvs_formatDateShort($Lvs_a['created_at']) ?></span>
                    </div>
                    <h3 class="news-title">
                        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_news_detail.php?id=<?= $Lvs_a['id'] ?>">
                            <?= htmlspecialchars($Lvs_a['title']) ?>
                        </a>
                    </h3>
                    <p class="news-excerpt"><?= htmlspecialchars(Lvs_truncate(strip_tags($Lvs_a['content'] ?? ''), 100)) ?></p>
                    <div class="news-footer">
                        <span class="news-author">✍️ <?= htmlspecialchars($Lvs_a['author_name'] ?? $Lvs_a['username'] ?? 'UmaCT') ?></span>
                        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_news_detail.php?id=<?= $Lvs_a['id'] ?>" class="news-read-more">Đọc tiếp →</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Countdown Script — ID dùng Lvs_ để không conflict -->
<script>
(function() {
    const Lvs_end = new Date();
    Lvs_end.setHours(23, 59, 59, 0);
    function Lvs_tick() {
        const diff = Lvs_end - new Date();
        if (diff <= 0) return;
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        document.getElementById('Lvs_cdHours').textContent = String(h).padStart(2,'0');
        document.getElementById('Lvs_cdMins').textContent  = String(m).padStart(2,'0');
        document.getElementById('Lvs_cdSecs').textContent  = String(s).padStart(2,'0');
    }
    Lvs_tick();
    setInterval(Lvs_tick, 1000);
})();

// AJAX helper — Lvs_ prefix cho JS function để không conflict
function Lvs_addToCart(Lvs_productId, Lvs_btn) {
    Lvs_btn.disabled = true;
    Lvs_btn.textContent = '⏳ Đang thêm...';
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_cart_add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: Lvs_productId, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Lvs_btn.textContent = '✅ Đã thêm!';
            const badge = document.getElementById('cartBadge');
            if (badge) badge.textContent = parseInt(badge.textContent || 0) + 1;
        } else {
            Lvs_btn.textContent = '❌ Thất bại';
        }
        setTimeout(() => { Lvs_btn.disabled = false; Lvs_btn.textContent = '🛒 Thêm vào giỏ'; }, 2000);
    });
}

function Lvs_toggleFavorite(Lvs_productId, Lvs_btn) {
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_favorite_toggle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: Lvs_productId })
    })
    .then(r => r.json())
    .then(data => {
        Lvs_btn.style.opacity = data.action === 'added' ? '1' : '0.5';
    });
}
</script>

<?php require_once __DIR__ . '/includes/Lvs_footer.php'; ?>
