<?php
/**
 * Lvs_admin/orders/Lvs_index.php — Danh sách đơn hàng
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Quản lý Đơn hàng';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_order_model.php';
require_once __DIR__ . '/../../utils/Lvs_format_helper.php';

$Lvs_statusFilter = $_GET['status'] ?? '';
$Lvs_filters      = $Lvs_statusFilter ? ['status' => $Lvs_statusFilter] : [];
$Lvs_orders       = Lvs_getAllOrders($Lvs_filters);
$Lvs_statuses     = ['', 'PENDING', 'CONFIRMED', 'SHIPPING', 'PAID', 'COMPLETED', 'CANCELLED'];
?>
<div class="data-card">
    <div class="data-card-header">
        <h2>🛒 Đơn hàng (<?= count($Lvs_orders) ?>)</h2>
    </div>
    <!-- Filter tabs -->
    <div style="display:flex;gap:8px;flex-wrap:wrap;padding:0 0 16px">
        <?php foreach($Lvs_statuses as $Lvs_s): ?>
        <a href="<?= BASE_URL ?>/Lvs_admin/orders/Lvs_index.php<?= $Lvs_s ? '?status='.$Lvs_s : '' ?>"
           style="padding:4px 14px;border-radius:99px;font-size:.78rem;font-weight:600;border:1px solid var(--border);<?= $Lvs_statusFilter === $Lvs_s ? 'background:var(--accent);color:#fff;border-color:var(--accent)' : 'color:var(--text-muted)' ?>">
            <?= $Lvs_s ?: 'Tất cả' ?>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="table-responsive"><table>
        <thead><tr><th>#ID</th><th>Khách hàng</th><th>Tổng tiền</th><th>Địa chỉ</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php if(empty($Lvs_orders)): ?>
            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">Không có đơn hàng</td></tr>
        <?php else: ?>
            <?php foreach($Lvs_orders as $Lvs_o): ?>
            <tr>
                <td><strong>#<?= $Lvs_o['id'] ?></strong></td>
                <td><?= htmlspecialchars($Lvs_o['full_name'] ?? $Lvs_o['username'] ?? '—') ?></td>
                <td><?= Lvs_formatPrice($Lvs_o['total_price']) ?></td>
                <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($Lvs_o['shipping_address'] ?? '—') ?></td>
                <td><?= Lvs_orderStatusBadge($Lvs_o['status']) ?></td>
                <td>
                    <select onchange="Lvs_updateStatus(<?= $Lvs_o['id'] ?>, this.value)"
                            style="background:var(--bg-glass);border:1px solid var(--border);color:var(--text);padding:4px 8px;border-radius:6px;font-size:.78rem">
                        <?php foreach(['PENDING','CONFIRMED','SHIPPING','PAID','COMPLETED','CANCELLED'] as $Lvs_st): ?>
                            <option value="<?= $Lvs_st ?>" <?= $Lvs_o['status'] === $Lvs_st ? 'selected' : '' ?>><?= $Lvs_st ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table></div>
</div>
<script>
function Lvs_updateStatus(Lvs_orderId, Lvs_status) {
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_order_update_status.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({order_id: Lvs_orderId, status: Lvs_status})
    }).then(r=>r.json()).then(d=>{
        if(d.status!=='success') alert('Cập nhật thất bại: ' + (d.detail||''));
    });
}
</script>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
