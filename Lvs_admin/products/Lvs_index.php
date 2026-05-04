<?php
/**
 * Lvs_admin/products/Lvs_index.php — Danh sách sản phẩm
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Quản lý Sản phẩm';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_product_model.php';
require_once __DIR__ . '/../../utils/Lvs_format_helper.php';

$Lvs_search   = trim($_GET['search'] ?? '');
$Lvs_filters  = $Lvs_search ? ['search' => $Lvs_search] : [];
$Lvs_products = Lvs_getAllProducts($Lvs_filters);
?>
<div class="data-card">
    <div class="data-card-header">
        <h2>📦 Sản phẩm (<?= count($Lvs_products) ?>)</h2>
        <a href="<?= BASE_URL ?>/Lvs_admin/products/Lvs_create.php" class="btn btn-primary btn-sm">+ Thêm sản phẩm</a>
    </div>
    <form method="GET" style="padding:0 0 16px">
        <div style="display:flex;gap:10px">
            <input type="text" name="search" class="form-control" placeholder="Tìm tên sản phẩm..."
                   value="<?= htmlspecialchars($Lvs_search) ?>"
                   style="flex:1;background:var(--bg-glass);border:1px solid var(--border);color:var(--text);padding:8px 12px;border-radius:8px">
            <button type="submit" class="btn btn-secondary btn-sm">🔍 Tìm</button>
            <?php if($Lvs_search): ?><a href="<?= BASE_URL ?>/Lvs_admin/products/Lvs_index.php" class="btn btn-secondary btn-sm">✕ Xóa lọc</a><?php endif; ?>
        </div>
    </form>
    <div class="table-responsive"><table>
        <thead><tr><th>#ID</th><th>Tên sản phẩm</th><th>Giá</th><th>Tồn kho</th><th>Danh mục</th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php if(empty($Lvs_products)): ?>
            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">Không có sản phẩm nào</td></tr>
        <?php else: ?>
            <?php foreach($Lvs_products as $Lvs_p): ?>
            <tr>
                <td><strong>#<?= $Lvs_p['id'] ?></strong></td>
                <td><?= htmlspecialchars($Lvs_p['name']) ?></td>
                <td><?= Lvs_formatPrice($Lvs_p['price']) ?></td>
                <td><?= $Lvs_p['stock_quantity'] ?? 0 ?></td>
                <td><?= htmlspecialchars($Lvs_p['category_name'] ?? '—') ?></td>
                <td style="white-space:nowrap">
                    <a href="<?= BASE_URL ?>/Lvs_admin/products/Lvs_edit.php?id=<?= $Lvs_p['id'] ?>" class="btn btn-secondary btn-sm">✏️ Sửa</a>
                    <a href="<?= BASE_URL ?>/Lvs_admin/products/Lvs_delete.php?id=<?= $Lvs_p['id'] ?>"
                       class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2)"
                       onclick="return confirm('Xóa sản phẩm này?')">🗑 Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table></div>
</div>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
