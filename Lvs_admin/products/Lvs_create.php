<?php
/**
 * Lvs_admin/products/Lvs_create.php — Thêm sản phẩm mới
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Thêm sản phẩm';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_category_model.php';
require_once __DIR__ . '/../../models/Lvs_supplier_model.php';
require_once __DIR__ . '/../../utils/api_client.php';

$Lvs_categories = Lvs_getAllCategories();
$Lvs_suppliers  = Lvs_getAllSuppliers();
$Lvs_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Lvs_data = [
        'name'           => trim($_POST['name'] ?? ''),
        'price'          => (float)($_POST['price'] ?? 0),
        'discount_price' => (float)($_POST['discount_price'] ?? 0) ?: null,
        'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
        'category_id'    => (int)($_POST['category_id'] ?? 0) ?: null,
        'supplier_id'    => (int)($_POST['supplier_id'] ?? 0) ?: null,
        'description'    => trim($_POST['description'] ?? ''),
        'main_image'     => trim($_POST['main_image'] ?? ''),
    ];
    if (empty($Lvs_data['name']) || $Lvs_data['price'] <= 0) {
        $Lvs_error = 'Tên và giá sản phẩm là bắt buộc.';
    } else {
        $Lvs_res = ApiClient::post('/products', $Lvs_data);
        if (ApiClient::isSuccess($Lvs_res)) {
            Lvs_setFlash('success', 'Thêm sản phẩm thành công!');
            header('Location: ' . BASE_URL . '/Lvs_admin/products/Lvs_index.php'); exit;
        }
        $Lvs_error = ApiClient::getError($Lvs_res);
    }
}
?>
<div class="data-card">
    <div class="data-card-header">
        <h2>📦 Thêm sản phẩm mới</h2>
        <a href="<?= BASE_URL ?>/Lvs_admin/products/Lvs_index.php" class="btn btn-secondary btn-sm">← Quay lại</a>
    </div>
    <?php if($Lvs_error): ?><div class="alert alert-error"><?= htmlspecialchars($Lvs_error) ?></div><?php endif; ?>
    <form method="POST" style="max-width:700px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group"><label>Tên sản phẩm *</label><input type="text" name="name" required class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"></div>
            <div class="form-group"><label>Giá (₫) *</label><input type="number" name="price" required min="0" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>"></div>
            <div class="form-group"><label>Giá giảm (₫)</label><input type="number" name="discount_price" min="0" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" value="<?= htmlspecialchars($_POST['discount_price'] ?? '') ?>"></div>
            <div class="form-group"><label>Tồn kho</label><input type="number" name="stock_quantity" min="0" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" value="<?= htmlspecialchars($_POST['stock_quantity'] ?? '0') ?>"></div>
            <div class="form-group"><label>Danh mục</label>
                <select name="category_id" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)">
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach($Lvs_categories as $Lvs_c): ?>
                        <option value="<?= $Lvs_c['id'] ?>"><?= htmlspecialchars($Lvs_c['name']) ?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="form-group"><label>Nhà cung cấp</label>
                <select name="supplier_id" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)">
                    <option value="">-- Chọn NCC --</option>
                    <?php foreach($Lvs_suppliers as $Lvs_s): ?>
                        <option value="<?= $Lvs_s['id'] ?>"><?= htmlspecialchars($Lvs_s['name']) ?></option>
                    <?php endforeach; ?>
                </select></div>
        </div>
        <div class="form-group"><label>URL Ảnh chính</label><input type="text" name="main_image" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="https://..." value="<?= htmlspecialchars($_POST['main_image'] ?? '') ?>"></div>
        <div class="form-group"><label>Mô tả</label><textarea name="description" rows="5" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text);resize:vertical"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea></div>
        <button type="submit" class="btn btn-primary">💾 Lưu sản phẩm</button>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
