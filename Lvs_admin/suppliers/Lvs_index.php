<?php
$pageTitle = 'Quản lý Nhà cung cấp';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_supplier_model.php';
require_once __DIR__ . '/../../utils/api_client.php';

$Lvs_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Lvs_action'])) {
        if ($_POST['Lvs_action'] === 'create') {
            $Lvs_res = ApiClient::post('/suppliers', ['name' => trim($_POST['name'] ?? ''), 'description' => trim($_POST['description'] ?? ''), 'contact_info' => trim($_POST['contact_info'] ?? '')]);
            if (ApiClient::isSuccess($Lvs_res)) { Lvs_setFlash('success', 'Thêm nhà cung cấp thành công!'); header('Location: ' . BASE_URL . '/Lvs_admin/suppliers/Lvs_index.php'); exit; }
            $Lvs_error = ApiClient::getError($Lvs_res);
        } elseif ($_POST['Lvs_action'] === 'delete') {
            $Lvs_res = ApiClient::delete('/suppliers/' . (int)$_POST['id']);
            if (ApiClient::isSuccess($Lvs_res)) { Lvs_setFlash('success', 'Đã xóa nhà cung cấp!'); header('Location: ' . BASE_URL . '/Lvs_admin/suppliers/Lvs_index.php'); exit; }
        }
    }
}
$Lvs_suppliers = Lvs_getAllSuppliers();
?>
<div class="data-card" style="margin-bottom:20px">
    <div class="data-card-header"><h2>➕ Thêm nhà cung cấp</h2></div>
    <?php if($Lvs_error): ?><div class="alert alert-error"><?= htmlspecialchars($Lvs_error) ?></div><?php endif; ?>
    <form method="POST" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:12px;align-items:flex-end">
        <input type="hidden" name="Lvs_action" value="create">
        <div><label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Tên NCC *</label>
            <input type="text" name="name" required class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="Good Smile Company..."></div>
        <div><label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Mô tả</label>
            <input type="text" name="description" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)"></div>
        <div><label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Liên hệ</label>
            <input type="text" name="contact_info" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="email / SĐT"></div>
        <button type="submit" class="btn btn-primary btn-sm" style="white-space:nowrap">+ Thêm</button>
    </form>
</div>
<div class="data-card">
    <div class="data-card-header"><h2>🏭 Nhà cung cấp (<?= count($Lvs_suppliers) ?>)</h2></div>
    <div class="table-responsive"><table>
        <thead><tr><th>#ID</th><th>Tên</th><th>Mô tả</th><th>Liên hệ</th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php foreach($Lvs_suppliers as $Lvs_s): ?>
        <tr>
            <td>#<?= $Lvs_s['id'] ?></td>
            <td><?= htmlspecialchars($Lvs_s['name']) ?></td>
            <td><?= htmlspecialchars($Lvs_s['description'] ?? '—') ?></td>
            <td><?= htmlspecialchars($Lvs_s['contact_info'] ?? '—') ?></td>
            <td><form method="POST" style="display:inline" onsubmit="return confirm('Xóa?')">
                <input type="hidden" name="Lvs_action" value="delete"><input type="hidden" name="id" value="<?= $Lvs_s['id'] ?>">
                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2)">🗑 Xóa</button>
            </form></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
</div>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
