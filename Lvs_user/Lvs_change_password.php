<?php
/**
 * Lvs_user/Lvs_change_password.php — Đổi mật khẩu
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Đổi mật khẩu — UmaCT Shop';
$activeNav = '';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../models/Lvs_user_model.php';
require_once __DIR__ . '/../utils/api_client.php';

Lvs_requireLogin();
$Lvs_user    = Lvs_getCurrentUser();
$Lvs_error   = $Lvs_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Lvs_old = trim($_POST['old_password'] ?? '');
    $Lvs_new = trim($_POST['new_password'] ?? '');
    $Lvs_cfm = trim($_POST['confirm_password'] ?? '');

    if (!$Lvs_old || !$Lvs_new || !$Lvs_cfm) {
        $Lvs_error = 'Vui lòng điền đầy đủ tất cả các trường.';
    } elseif ($Lvs_new !== $Lvs_cfm) {
        $Lvs_error = 'Mật khẩu mới và xác nhận không khớp.';
    } elseif (strlen($Lvs_new) < 6) {
        $Lvs_error = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
    } else {
        $Lvs_res = Lvs_changeUserPassword($Lvs_user['user_id'], $Lvs_old, $Lvs_new);
        if (ApiClient::isSuccess($Lvs_res)) {
            $Lvs_success = '✅ Đổi mật khẩu thành công!';
        } else {
            $Lvs_error = ApiClient::getError($Lvs_res);
        }
    }
}

require_once __DIR__ . '/../Lvs_pages/includes/Lvs_header.php';
?>
<div class="container section"><div class="user-layout">
    <aside class="user-sidebar">
        <div class="user-info">
            <div style="width:64px;height:64px;border-radius:50%;background:var(--gradient-btn);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:700;color:#fff;margin:0 auto 12px">
                <?= strtoupper(substr($Lvs_user['full_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div style="font-weight:700;text-align:center"><?= htmlspecialchars($Lvs_user['full_name']) ?></div>
        </div>
        <nav class="user-nav">
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_profile.php" class="user-nav-link">👤 Hồ sơ</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_order_history.php" class="user-nav-link">📦 Đơn hàng</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_favorites.php" class="user-nav-link">❤️ Yêu thích</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_change_password.php" class="user-nav-link active">🔐 Đổi mật khẩu</a>
            <a href="<?= BASE_URL ?>/auth/Lvs_logout.php" class="user-nav-link" style="color:#f87171">🚪 Đăng xuất</a>
        </nav>
    </aside>

    <div class="user-main-card">
        <div class="user-card-title">🔐 Đổi mật khẩu</div>
        <?php if ($Lvs_error): ?><div class="alert alert-error"><?= htmlspecialchars($Lvs_error) ?></div><?php endif; ?>
        <?php if ($Lvs_success): ?><div class="alert alert-success"><?= htmlspecialchars($Lvs_success) ?></div><?php endif; ?>

        <form method="POST" style="max-width:460px">
            <div class="form-group">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px">Mật khẩu hiện tại *</label>
                <input type="password" name="old_password" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px">Mật khẩu mới * (≥6 ký tự)</label>
                <input type="password" name="new_password" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px">Xác nhận mật khẩu mới *</label>
                <input type="password" name="confirm_password" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-hero-primary" style="border:none;margin-top:8px">🔐 Đổi mật khẩu</button>
        </form>
    </div>
</div></div>
<?php require_once __DIR__ . '/../Lvs_pages/includes/Lvs_footer.php'; ?>
