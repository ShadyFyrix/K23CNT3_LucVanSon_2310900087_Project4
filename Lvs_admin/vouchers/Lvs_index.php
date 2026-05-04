<?php
$pageTitle = 'Quản lý Mã giảm giá';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_voucher_model.php';
require_once __DIR__ . '/../../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../../utils/api_client.php';

$Lvs_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Lvs_action'])) {
    if ($_POST['Lvs_action'] === 'create') {
        $Lvs_res = ApiClient::post('/vouchers', [
            'code'           => strtoupper(trim($_POST['code'] ?? '')),
            'discount_type'  => $_POST['discount_type'] ?? 'FIXED',
            'discount_value' => (float)($_POST['discount_value'] ?? 0),
            'min_order'      => (float)($_POST['min_order'] ?? 0),
            'max_usage'      => (int)($_POST['max_usage'] ?? 1),
            'expires_at'     => trim($_POST['expires_at'] ?? ''),
        ]);
        if (ApiClient::isSuccess($Lvs_res)) { Lvs_setFlash('success', 'Tạo voucher thành công!'); header('Location: ' . BASE_URL . '/Lvs_admin/vouchers/Lvs_index.php'); exit; }
        $Lvs_error = ApiClient::getError($Lvs_res);
    } elseif ($_POST['Lvs_action'] === 'delete') {
        ApiClient::delete('/vouchers/' . (int)$_POST['id']);
        header('Location: ' . BASE_URL . '/Lvs_admin/vouchers/Lvs_index.php'); exit;
    }
}
$Lvs_vouchers = Lvs_getAllVouchers();
?>
<div class="data-card" style="margin-bottom:20px">
    <div class="data-card-header"><h2>➕ Tạo voucher mới</h2></div>
    <?php if($Lvs_error): ?><div class="alert alert-error"><?= htmlspecialchars($Lvs_error) ?></div><?php endif; ?>
    <form method="POST" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;align-items:flex-end">
        <input type="hidden" name="Lvs_action" value="create">
        <div><label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Mã voucher *</label><input type="text" name="code" required class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="SUMMER25"></div>
        <div><label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Loại giảm</label>
            <select name="discount_type" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)"><option value="FIXED">Số tiền cố định</option><option value="PERCENT">Phần trăm (%)</option></select></div>
        <div><label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Giá trị giảm *</label><input type="number" name="discount_value" required class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="50000"></div>
        <div><label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Đơn tối thiểu</label><input type="number" name="min_order" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" value="0"></div>
        <div><label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Hết hạn</label><input type="date" name="expires_at" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)"></div>
        <button type="submit" class="btn btn-primary btn-sm">+ Tạo</button>
    </form>
</div>
<div class="data-card">
    <div class="data-card-header"><h2>🎟️ Mã giảm giá (<?= count($Lvs_vouchers) ?>)</h2></div>
    <div class="table-responsive"><table>
        <thead><tr><th>Mã</th><th>Loại</th><th>Giảm</th><th>Đơn tối thiểu</th><th>Hết hạn</th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php foreach($Lvs_vouchers as $Lvs_v): ?>
        <tr>
            <td><strong><?= htmlspecialchars($Lvs_v['code']) ?></strong></td>
            <td><?= $Lvs_v['discount_type'] ?? 'FIXED' ?></td>
            <td><?= $Lvs_v['discount_type'] === 'PERCENT' ? $Lvs_v['discount_value'].'%' : Lvs_formatPrice($Lvs_v['discount_value']) ?></td>
            <td><?= Lvs_formatPrice($Lvs_v['min_order'] ?? 0) ?></td>
            <td style="font-size:.8rem"><?= Lvs_formatDateShort($Lvs_v['expires_at'] ?? '') ?></td>
            <td><form method="POST" style="display:inline" onsubmit="return confirm('Xóa voucher?')">
                <input type="hidden" name="Lvs_action" value="delete"><input type="hidden" name="id" value="<?= $Lvs_v['id'] ?>">
                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2)">🗑 Xóa</button>
            </form></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
</div>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
