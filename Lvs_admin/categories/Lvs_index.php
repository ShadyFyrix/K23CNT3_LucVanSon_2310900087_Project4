<?php
$pageTitle = 'Quản lý Danh mục';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_category_model.php';

$Lvs_categories = Lvs_getAllCategories();
$Lvs_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Lvs_action'])) {
    require_once __DIR__ . '/../../utils/api_client.php';
    if ($_POST['Lvs_action'] === 'create') {
        $Lvs_res = ApiClient::post('/categories', ['name' => trim($_POST['name'] ?? ''), 'description' => trim($_POST['description'] ?? '')]);
        if (ApiClient::isSuccess($Lvs_res)) { Lvs_setFlash('success', 'Thêm danh mục thành công!'); header('Location: ' . BASE_URL . '/Lvs_admin/categories/Lvs_index.php'); exit; }
        $Lvs_error = ApiClient::getError($Lvs_res);
    } elseif ($_POST['Lvs_action'] === 'delete') {
        $Lvs_res = ApiClient::delete('/categories/' . (int)$_POST['id']);
        if (ApiClient::isSuccess($Lvs_res)) { Lvs_setFlash('success', 'Đã xóa danh mục!'); header('Location: ' . BASE_URL . '/Lvs_admin/categories/Lvs_index.php'); exit; }
        $Lvs_error = ApiClient::getError($Lvs_res);
    }
}
?>
<div class="data-card" style="margin-bottom:20px">
    <div class="data-card-header"><h2>➕ Thêm danh mục mới</h2></div>
    <?php if($Lvs_error): ?><div class="alert alert-error"><?= htmlspecialchars($Lvs_error) ?></div><?php endif; ?>
    <form method="POST" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
        <input type="hidden" name="Lvs_action" value="create">
        <div style="flex:1"><label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Tên danh mục *</label>
            <input type="text" name="name" required class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="VD: Figure, Cosplay..."></div>
        <div style="flex:2"><label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Mô tả</label>
            <input type="text" name="description" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="Mô tả danh mục..."></div>
        <button type="submit" class="btn btn-primary btn-sm">+ Thêm</button>
    </form>
</div>
<div class="data-card">
    <div class="data-card-header"><h2>🗂️ Danh mục (<?= count($Lvs_categories) ?>)</h2></div>
    <div class="table-responsive"><table>
        <thead><tr><th>#ID</th><th>Tên danh mục</th><th>Mô tả</th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php foreach($Lvs_categories as $Lvs_c): ?>
        <tr>
            <td>#<?= $Lvs_c['id'] ?></td>
            <td><?= htmlspecialchars($Lvs_c['name']) ?></td>
            <td><?= htmlspecialchars($Lvs_c['description'] ?? '—') ?></td>
            <td>
                <form method="POST" style="display:inline" onsubmit="return confirm('Xóa danh mục này?')">
                    <input type="hidden" name="Lvs_action" value="delete">
                    <input type="hidden" name="id" value="<?= $Lvs_c['id'] ?>">
                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2)">🗑 Xóa</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
</div>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
