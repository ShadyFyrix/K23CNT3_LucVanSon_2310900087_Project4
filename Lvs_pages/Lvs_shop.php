<?php
/**
 * Lvs_pages/Lvs_shop.php — Trang cửa hàng
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Cửa hàng — UmaCT Shop';
$activeNav = 'shop';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Lvs_product_model.php';
require_once __DIR__ . '/../models/Lvs_category_model.php';
require_once __DIR__ . '/../models/Lvs_supplier_model.php';

$Lvs_filters = [];
if (!empty($_GET['search']))      $Lvs_filters['search']      = htmlspecialchars(strip_tags($_GET['search']));
if (!empty($_GET['category_id'])) $Lvs_filters['category_id'] = (int)$_GET['category_id'];
if (!empty($_GET['supplier_id'])) $Lvs_filters['supplier_id'] = (int)$_GET['supplier_id'];
if (!empty($_GET['sort']))        $Lvs_filters['sort']        = $_GET['sort'];
if (!empty($_GET['min_price']))   $Lvs_filters['min_price']   = (float)$_GET['min_price'];
if (!empty($_GET['max_price']))   $Lvs_filters['max_price']   = (float)$_GET['max_price'];

$Lvs_products   = Lvs_getAllProducts($Lvs_filters);
$Lvs_categories = Lvs_getAllCategories();
$Lvs_suppliers  = Lvs_getAllSuppliers();
$Lvs_sortOpts   = ['' => '⭐ Mặc định', 'newest' => '🆕 Mới nhất', 'price_asc' => '💰 Tăng dần', 'price_desc' => '💎 Giảm dần'];

require_once __DIR__ . '/includes/Lvs_header.php';
?>
<div style="background:var(--bg-surface); border-bottom:1px solid var(--border); padding:28px 0">
    <div class="container">
        <nav style="font-size:.78rem; color:var(--text-dim); margin-bottom:8px">
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_home.php" style="color:var(--text-muted);text-decoration:none">Trang chủ</a>
            <span style="margin:0 6px">›</span><span>Cửa hàng</span>
        </nav>
        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:800">
            <?php if (!empty($Lvs_filters['search'])): ?>
                🔍 Kết quả: <span style="color:var(--accent)">"<?= htmlspecialchars($Lvs_filters['search']) ?>"</span>
            <?php else: ?> 🛍 Tất cả sản phẩm <?php endif; ?>
        </h1>
    </div>
</div>

<div class="container section"><div class="shop-layout">
    <aside class="filter-sidebar">
        <form method="GET" id="Lvs_filterForm">
            <div class="filter-group">
                <div class="filter-title">🔍 Tìm kiếm</div>
                <input type="text" name="search" class="form-control"
                       style="background:var(--bg-glass);border-color:var(--border);color:var(--text)"
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Tên sản phẩm...">
            </div>
            <div class="filter-group">
                <div class="filter-title">🗂 Danh mục</div>
                <div class="filter-option"><input type="radio" name="category_id" value="" id="Lvs_catAll" <?= empty($_GET['category_id']) ? 'checked' : '' ?>><label for="Lvs_catAll">Tất cả</label></div>
                <?php foreach ($Lvs_categories as $Lvs_c): ?>
                <div class="filter-option"><input type="radio" name="category_id" value="<?= $Lvs_c['id'] ?>" id="Lvs_cat<?= $Lvs_c['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $Lvs_c['id']) ? 'checked' : '' ?>><label for="Lvs_cat<?= $Lvs_c['id'] ?>"><?= htmlspecialchars($Lvs_c['name']) ?></label></div>
                <?php endforeach; ?>
            </div>
            <div class="filter-group">
                <div class="filter-title">🏭 Nhà cung cấp</div>
                <div class="filter-option"><input type="radio" name="supplier_id" value="" id="Lvs_supAll" <?= empty($_GET['supplier_id']) ? 'checked' : '' ?>><label for="Lvs_supAll">Tất cả</label></div>
                <?php foreach ($Lvs_suppliers as $Lvs_s): ?>
                <div class="filter-option"><input type="radio" name="supplier_id" value="<?= $Lvs_s['id'] ?>" id="Lvs_sup<?= $Lvs_s['id'] ?>" <?= (isset($_GET['supplier_id']) && $_GET['supplier_id'] == $Lvs_s['id']) ? 'checked' : '' ?>><label for="Lvs_sup<?= $Lvs_s['id'] ?>"><?= htmlspecialchars($Lvs_s['name']) ?></label></div>
                <?php endforeach; ?>
            </div>
            <div class="filter-group">
                <div class="filter-title">💰 Khoảng giá</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:8px">
                    <input type="number" name="min_price" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text);font-size:.8rem" placeholder="Từ (₫)" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                    <input type="number" name="max_price" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text);font-size:.8rem" placeholder="Đến (₫)" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                </div>
            </div>
            <button type="submit" class="btn-checkout" style="margin-top:0">🔍 Lọc sản phẩm</button>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" style="display:block;text-align:center;margin-top:10px;font-size:.8rem;color:var(--text-muted)">✕ Xóa bộ lọc</a>
        </form>
    </aside>

    <div class="shop-main">
        <div class="shop-main-header">
            <span class="shop-result-count">Hiển thị <strong style="color:var(--text)"><?= count($Lvs_products) ?></strong> sản phẩm</span>
            <select class="sort-select" id="Lvs_sortSelect" onchange="Lvs_applySort(this.value)">
                <?php foreach ($Lvs_sortOpts as $Lvs_v => $Lvs_l): ?>
                    <option value="<?= $Lvs_v ?>" <?= (($_GET['sort'] ?? '') === $Lvs_v) ? 'selected' : '' ?>><?= $Lvs_l ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="category-list" style="margin-bottom:24px">
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" class="cat-pill <?= empty($_GET['category_id']) ? 'active' : '' ?>">🏷 Tất cả</a>
            <?php foreach ($Lvs_categories as $Lvs_c): ?>
                <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php?category_id=<?= $Lvs_c['id'] ?>" class="cat-pill <?= (isset($_GET['category_id']) && $_GET['category_id'] == $Lvs_c['id']) ? 'active' : '' ?>"><?= htmlspecialchars($Lvs_c['name']) ?></a>
            <?php endforeach; ?>
        </div>
        <?php if (empty($Lvs_products)): ?>
            <div class="empty-state"><div class="empty-icon">🔍</div><div class="empty-title">Không tìm thấy sản phẩm</div><a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" class="btn-hero-secondary" style="display:inline-flex;margin-top:20px">Xem tất cả</a></div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($Lvs_products as $p): include __DIR__ . '/includes/Lvs_product_card.php'; endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div></div>

<script>
function Lvs_applySort(Lvs_val) {
    const Lvs_u = new URL(window.location.href);
    Lvs_val ? Lvs_u.searchParams.set('sort', Lvs_val) : Lvs_u.searchParams.delete('sort');
    window.location.href = Lvs_u.toString();
}
function Lvs_addToCart(Lvs_pid, Lvs_btn) {
    const Lvs_orig = Lvs_btn.innerHTML;
    Lvs_btn.innerHTML = '⏳'; Lvs_btn.disabled = true;
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_cart_add.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({product_id: Lvs_pid, quantity: 1})
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success'){
            Lvs_btn.innerHTML='✅ Đã thêm!';
            const b=document.getElementById('cartBadge');
            if(b) b.textContent=parseInt(b.textContent||0)+1;
            setTimeout(()=>{Lvs_btn.innerHTML=Lvs_orig;Lvs_btn.style='';Lvs_btn.disabled=false;},2000);
        } else { alert(d.message||'Lỗi'); Lvs_btn.innerHTML=Lvs_orig; Lvs_btn.disabled=false; }
    }).catch(()=>{Lvs_btn.innerHTML=Lvs_orig;Lvs_btn.disabled=false;});
}
function Lvs_toggleFavorite(Lvs_pid, Lvs_btn) {
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_favorite_toggle.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({product_id: Lvs_pid})
    }).then(r=>r.json()).then(d=>{if(d.status==='success') Lvs_btn.classList.toggle('fav-active');});
}
</script>
<?php require_once __DIR__ . '/includes/Lvs_footer.php'; ?>
