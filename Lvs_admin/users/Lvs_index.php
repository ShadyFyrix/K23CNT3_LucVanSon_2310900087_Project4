<?php
/**
 * Lvs_admin/users/Lvs_index.php — Quản lý người dùng
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Quản lý Người dùng';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_user_model.php';
require_once __DIR__ . '/../../utils/Lvs_format_helper.php';

$Lvs_users = Lvs_getAllUsers();
?>
<div class="data-card">
    <div class="data-card-header"><h2>👥 Người dùng (<?= count($Lvs_users) ?>)</h2></div>
    <div class="table-responsive"><table>
        <thead><tr><th>#ID</th><th>Tên đăng nhập</th><th>Họ tên</th><th>Email</th><th>Role</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php if(empty($Lvs_users)): ?>
            <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">Không có người dùng</td></tr>
        <?php else: ?>
            <?php foreach($Lvs_users as $Lvs_u): ?>
            <tr>
                <td>#<?= $Lvs_u['id'] ?></td>
                <td><?= htmlspecialchars($Lvs_u['username']) ?></td>
                <td><?= htmlspecialchars($Lvs_u['full_name'] ?? '—') ?></td>
                <td><?= htmlspecialchars($Lvs_u['email'] ?? '—') ?></td>
                <td><span style="font-size:.72rem;padding:2px 8px;border-radius:4px;background:rgba(139,92,246,.1);color:var(--accent)"><?= $Lvs_u['role_name'] ?? 'ROLE_USER' ?></span></td>
                <td>
                    <?php if(($Lvs_u['status'] ?? '') === 'BANNED'): ?>
                        <span style="color:#f87171;font-size:.8rem">🚫 Bị khóa</span>
                    <?php else: ?>
                        <span style="color:#4ade80;font-size:.8rem">✓ Hoạt động</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if(($Lvs_u['status'] ?? '') === 'BANNED'): ?>
                        <button onclick="Lvs_toggleBan(<?= $Lvs_u['id'] ?>, 'ACTIVE')" class="btn btn-sm" style="background:rgba(34,197,94,.1);color:#4ade80;border:1px solid rgba(34,197,94,.2);font-size:.75rem">Mở khóa</button>
                    <?php else: ?>
                        <button onclick="Lvs_toggleBan(<?= $Lvs_u['id'] ?>, 'BANNED')" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2);font-size:.75rem">Khóa</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table></div>
</div>
<script>
function Lvs_toggleBan(Lvs_userId, Lvs_status) {
    const Lvs_msg = Lvs_status === 'BANNED' ? 'Khóa tài khoản này?' : 'Mở khóa tài khoản này?';
    if (!confirm(Lvs_msg)) return;
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_user_toggle_ban.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({user_id: Lvs_userId, status: Lvs_status})
    }).then(r=>r.json()).then(d=>{ if(d.status==='success') location.reload(); else alert(d.detail||'Lỗi'); });
}
</script>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
