<?php
/**
 * Lvs_admin/users/Lvs_detail.php — Chi tiết người dùng + lịch sử đơn
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 * Backend: GET /api/users/{id} → {data: {info:{...}, orders:[...]}}
 */
$pageTitle = 'Chi tiết tài khoản';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../utils/api_client.php';
require_once __DIR__ . '/../../utils/Lvs_format_helper.php';

$Lvs_id  = (int)($_GET['id'] ?? 0);
if (!$Lvs_id) { header('Location: ' . BASE_URL . '/Lvs_admin/users/Lvs_index.php'); exit; }

$Lvs_raw    = ApiClient::get('/users/' . $Lvs_id);
$Lvs_info   = $Lvs_raw['info']   ?? null;
$Lvs_orders = $Lvs_raw['orders'] ?? [];

if (!$Lvs_info) {
    echo '<div class="data-card"><p style="color:var(--text-muted)">Không tìm thấy người dùng #' . $Lvs_id . '</p></div>';
    require_once __DIR__ . '/../includes/Lvs_footer.php'; exit;
}

// Badge màu status
$Lvs_statusBadge = ($Lvs_info['status'] ?? '') === 'BANNED'
    ? '<span style="color:#f87171;font-size:.8rem">🚫 Bị khóa</span>'
    : '<span style="color:#4ade80;font-size:.8rem">✓ Hoạt động</span>';
?>
<div class="data-card">
    <div class="data-card-header">
        <h2>👤 <?= htmlspecialchars($Lvs_info['username']) ?> <span style="font-size:.8rem;color:var(--text-muted)">#<?= $Lvs_id ?></span></h2>
        <a href="<?= BASE_URL ?>/Lvs_admin/users/Lvs_index.php" class="btn btn-secondary btn-sm">← Quay lại</a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px">
        <?php
        $Lvs_fields = [
            'Họ và tên'    => $Lvs_info['full_name']  ?? '—',
            'Email'        => $Lvs_info['email']       ?? '—',
            'Điện thoại'   => $Lvs_info['phone']       ?? '—',
            'Địa chỉ'      => $Lvs_info['address']     ?? '—',
            'Quyền'        => '<span style="font-size:.75rem;padding:3px 8px;border-radius:4px;background:rgba(139,92,246,.1);color:var(--accent)">' . ($Lvs_info['role_name'] ?? '—') . '</span>',
            'Trạng thái'   => $Lvs_statusBadge,
            'Ngày tạo'     => Lvs_formatDate($Lvs_info['created_at'] ?? ''),
        ];
        foreach($Lvs_fields as $Lvs_k => $Lvs_v):
        ?>
        <div style="background:var(--bg-glass);border:1px solid var(--border);border-radius:10px;padding:14px">
            <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:4px"><?= $Lvs_k ?></div>
            <div style="font-size:.9rem;font-weight:500"><?= $Lvs_v ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Lịch sử đơn hàng -->
    <h3 style="font-size:.9rem;font-weight:700;margin-bottom:12px">📦 Lịch sử đơn hàng (<?= count($Lvs_orders) ?>)</h3>
    <?php if(empty($Lvs_orders)): ?>
        <p style="color:var(--text-muted);font-size:.875rem">Chưa có đơn hàng nào.</p>
    <?php else: ?>
    <div class="table-responsive"><table>
        <thead><tr><th>#Đơn</th><th>Tổng tiền</th><th>Thanh toán</th><th>Trạng thái</th><th>Ngày đặt</th></tr></thead>
        <tbody>
        <?php foreach($Lvs_orders as $Lvs_o): ?>
            <tr>
                <td><strong>#<?= $Lvs_o['id'] ?></strong></td>
                <td><?= Lvs_formatPrice($Lvs_o['total_price']) ?></td>
                <td><?= $Lvs_o['payment_method'] == 1 ? 'COD' : 'Chuyển khoản' ?></td>
                <td><?= Lvs_orderStatusBadge($Lvs_o['status']) ?></td>
                <td style="font-size:.8rem"><?= Lvs_formatDate($Lvs_o['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
