<?php
/**
 * pages/shop.php — Trang cửa hàng với filter và search
 */
$pageTitle = 'Cửa hàng — UmaCT Shop';
$activeNav = 'shop';

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/product_model.php';
require_once __DIR__ . '/../models/category_model.php';
require_once __DIR__ . '/../models/supplier_model.php';

// Lấy filter từ URL
$filters = [];
if (!empty($_GET['search']))      $filters['search']      = $_GET['search'];
if (!empty($_GET['category_id'])) $filters['category_id'] = (int)$_GET['category_id'];
if (!empty($_GET['supplier_id'])) $filters['supplier_id'] = (int)$_GET['supplier_id'];
if (!empty($_GET['sort']))        $filters['sort']        = $_GET['sort'];
if (!empty($_GET['min_price']))   $filters['min_price']   = (float)$_GET['min_price'];
if (!empty($_GET['max_price']))   $filters['max_price']   = (float)$_GET['max_price'];

$products   = getAllProducts($filters);
$categories = getAllCategories();
$suppliers  = getAllSuppliers();

$sortOptions = [
    ''           => '⭐ Mặc định',
    'newest'     => '🆕 Mới nhất',
    'price_asc'  => '💰 Giá tăng dần',
    'price_desc' => '💎 Giá giảm dần',
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<div style="background:var(--bg-surface); border-bottom:1px solid var(--border); padding:28px 0">
    <div class="container">
        <nav style="font-size:.78rem; color:var(--text-dim); margin-bottom:8px">
            <a href="<?= BASE_URL ?>/pages/home.php" style="color:var(--text-muted); text-decoration:none">Trang chủ</a>
            <span style="margin:0 6px">›</span>
            <span>Cửa hàng</span>
            <?php if (!empty($_GET['search'])): ?>
                <span style="margin:0 6px">›</span>
                <span>Kết quả: "<?= htmlspecialchars($_GET['search']) ?>"</span>
            <?php endif; ?>
        </nav>
        <h1 style="font-family:'Space Grotesk',sans-serif; font-size:1.5rem; font-weight:800">
            <?php if (!empty($_GET['search'])): ?>
                🔍 Kết quả tìm kiếm: <span style="color:var(--accent)">"<?= htmlspecialchars($_GET['search']) ?>"</span>
            <?php elseif (!empty($_GET['category_id'])): ?>
                <?php $selCat = array_filter($categories, fn($c) => $c['id'] == $_GET['category_id']); ?>
                🗂 <?= htmlspecialchars(array_values($selCat)[0]['name'] ?? 'Danh mục') ?>
            <?php else: ?>
                🛍 Tất cả sản phẩm
            <?php endif; ?>
        </h1>
    </div>
</div>

<div class="container section">
    <div class="shop-layout">

        <!-- ===== FILTER SIDEBAR ===== -->
        <aside class="filter-sidebar">
            <form method="GET" id="filterForm">
                <!-- Search -->
                <div class="filter-group">
                    <div class="filter-title">🔍 Tìm kiếm</div>
                    <input type="text" name="search"
                           class="form-control" style="background:var(--bg-glass); border-color:var(--border); color:var(--text)"
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                           placeholder="Tên sản phẩm...">
                </div>

                <!-- Category -->
                <div class="filter-group">
                    <div class="filter-title">🗂 Danh mục</div>
                    <div class="filter-option">
                        <input type="radio" name="category_id" value="" id="catAll"
                               <?= empty($_GET['category_id']) ? 'checked' : '' ?>>
                        <label for="catAll">Tất cả</label>
                    </div>
                    <?php foreach ($categories as $cat): ?>
                    <div class="filter-option">
                        <input type="radio" name="category_id"
                               value="<?= $cat['id'] ?>"
                               id="cat<?= $cat['id'] ?>"
                               <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'checked' : '' ?>>
                        <label for="cat<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Supplier -->
                <div class="filter-group">
                    <div class="filter-title">🏭 Nhà cung cấp</div>
                    <div class="filter-option">
                        <input type="radio" name="supplier_id" value="" id="supAll"
                               <?= empty($_GET['supplier_id']) ? 'checked' : '' ?>>
                        <label for="supAll">Tất cả</label>
                    </div>
                    <?php foreach ($suppliers as $sup): ?>
                    <div class="filter-option">
                        <input type="radio" name="supplier_id"
                               value="<?= $sup['id'] ?>"
                               id="sup<?= $sup['id'] ?>"
                               <?= (isset($_GET['supplier_id']) && $_GET['supplier_id'] == $sup['id']) ? 'checked' : '' ?>>
                        <label for="sup<?= $sup['id'] ?>"><?= htmlspecialchars($sup['name']) ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Khoảng giá -->
                <div class="filter-group">
                    <div class="filter-title">💰 Khoảng giá</div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-top:8px">
                        <input type="number" name="min_price"
                               class="form-control" style="background:var(--bg-glass); border-color:var(--border); color:var(--text); font-size:.8rem;"
                               placeholder="Từ (₫)"
                               value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                        <input type="number" name="max_price"
                               class="form-control" style="background:var(--bg-glass); border-color:var(--border); color:var(--text); font-size:.8rem;"
                               placeholder="Đến (₫)"
                               value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                    </div>
                </div>

                <button type="submit" class="btn-checkout" style="margin-top:0">🔍 Lọc sản phẩm</button>
                <a href="<?= BASE_URL ?>/pages/shop.php"
                   style="display:block; text-align:center; margin-top:10px; font-size:.8rem; color:var(--text-muted)">
                   ✕ Xóa bộ lọc
                </a>
            </form>
        </aside>

        <!-- ===== PRODUCT LIST ===== -->
        <div class="shop-main">
            <!-- Top bar -->
            <div class="shop-main-header">
                <span class="shop-result-count">
                    Hiển thị <strong style="color:var(--text)"><?= count($products) ?></strong> sản phẩm
                </span>
                <select class="sort-select" onchange="applySort(this.value)">
                    <?php foreach ($sortOptions as $val => $label): ?>
                        <option value="<?= $val ?>" <?= (($_GET['sort'] ?? '') === $val) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category pills (quick filter) -->
            <div class="category-list" style="margin-bottom:24px">
                <a href="<?= BASE_URL ?>/pages/shop.php"
                   class="cat-pill <?= empty($_GET['category_id']) ? 'active' : '' ?>">
                    🏷 Tất cả
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= BASE_URL ?>/pages/shop.php?category_id=<?= $cat['id'] ?>"
                       class="cat-pill <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Products Grid -->
            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <div class="empty-icon">🔍</div>
                    <div class="empty-title">Không tìm thấy sản phẩm</div>
                    <div class="empty-desc">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</div>
                    <a href="<?= BASE_URL ?>/pages/shop.php" class="btn-hero-secondary" style="display:inline-flex; margin-top:20px">
                        Xem tất cả sản phẩm
                    </a>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $p): ?>
                        <?php include __DIR__ . '/includes/product_card.php'; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
// Sort thay đổi → submit form
function applySort(val) {
    const url = new URL(window.location.href);
    if (val) url.searchParams.set('sort', val);
    else url.searchParams.delete('sort');
    window.location.href = url.toString();
}

// Add to cart (AJAX)
function addToCart(productId, btn) {
    const origText = btn.innerHTML;
    btn.innerHTML = '⏳ Đang thêm...';
    btn.disabled = true;

    fetch('<?= BASE_URL ?>/api_actions/cart_add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            btn.innerHTML = '✅ Đã thêm!';
            btn.style.background = 'rgba(34,197,94,.15)';
            btn.style.borderColor = '#22c55e';
            btn.style.color = '#4ade80';
            // Update badge
            const badge = document.getElementById('cartBadge');
            if (badge) badge.textContent = parseInt(badge.textContent || '0') + 1;
            setTimeout(() => {
                btn.innerHTML = origText;
                btn.style = '';
                btn.disabled = false;
            }, 2000);
        } else {
            alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
            btn.innerHTML = origText;
            btn.disabled = false;
        }
    })
    .catch(() => {
        btn.innerHTML = origText;
        btn.disabled = false;
    });
}

// Toggle favorite
function toggleFavorite(productId, btn) {
    fetch('<?= BASE_URL ?>/api_actions/favorite_toggle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            btn.classList.toggle('fav-active');
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
