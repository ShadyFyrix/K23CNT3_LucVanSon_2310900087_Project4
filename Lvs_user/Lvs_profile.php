<?php
/**
 * Lvs_user/Lvs_profile.php — Hồ sơ cá nhân
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Hồ sơ cá nhân — UmaCT Shop';
$activeNav = '';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../models/Lvs_user_model.php';
require_once __DIR__ . '/../utils/api_client.php'; // isSuccess/getError

Lvs_requireLogin();
$Lvs_user    = Lvs_getCurrentUser();
$Lvs_detail  = Lvs_getUserDetail($Lvs_user['user_id']);
$Lvs_profile = $Lvs_detail ?? $Lvs_user;

$Lvs_error = $Lvs_success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Lvs_data = [
        'full_name' => trim($_POST['full_name'] ?? ''),
        'email'     => trim($_POST['email'] ?? ''),
        'phone'     => trim($_POST['phone'] ?? ''),
        'address'   => trim($_POST['address'] ?? ''),
    ];
    if (empty($Lvs_data['full_name'])) {
        $Lvs_error = 'Họ tên không được để trống.';
    } else {
        $Lvs_res = Lvs_updateUserProfile($Lvs_user['user_id'], $Lvs_data);
        if (ApiClient::isSuccess($Lvs_res)) {
            $Lvs_success = 'Cập nhật thông tin thành công!';
            $_SESSION['full_name'] = $Lvs_data['full_name'];
            $Lvs_profile = array_merge($Lvs_profile, $Lvs_data);
        } else {
            $Lvs_error = ApiClient::getError($Lvs_res);
        }
    }
}

require_once __DIR__ . '/../Lvs_pages/includes/Lvs_header.php';
?>
<div class="container section"><div class="user-layout">

    <!-- Sidebar -->
    <aside class="user-sidebar">
        <div class="user-info">
            <div class="user-avatar-lg" style="width:64px;height:64px;border-radius:50%;background:var(--gradient-btn);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:700;color:#fff;margin:0 auto 12px">
                <?= strtoupper(substr($Lvs_profile['full_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div style="font-weight:700;text-align:center"><?= htmlspecialchars($Lvs_profile['full_name'] ?? '') ?></div>
            <div style="font-size:.78rem;color:var(--text-muted);text-align:center">@<?= htmlspecialchars($Lvs_profile['username'] ?? '') ?></div>
        </div>
        <nav class="user-nav">
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_profile.php" class="user-nav-link active">👤 Hồ sơ</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_order_history.php" class="user-nav-link">📦 Đơn hàng</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_favorites.php" class="user-nav-link">❤️ Yêu thích</a>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_change_password.php" class="user-nav-link">🔐 Đổi mật khẩu</a>
            <a href="<?= BASE_URL ?>/auth/Lvs_logout.php" class="user-nav-link" style="color:#f87171">🚪 Đăng xuất</a>
        </nav>
    </aside>

    <!-- Main -->
    <div class="user-main-card">
        <div class="user-card-title">👤 Hồ sơ cá nhân</div>
        <?php if ($Lvs_error): ?><div class="alert alert-error"><?= htmlspecialchars($Lvs_error) ?></div><?php endif; ?>
        <?php if ($Lvs_success): ?><div class="alert alert-success"><?= htmlspecialchars($Lvs_success) ?></div><?php endif; ?>

        <div style="display:flex;align-items:center;gap:20px;margin-bottom:28px;padding-bottom:24px;border-bottom:1px solid var(--border)">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--gradient-btn);display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:700;color:#fff;border:3px solid rgba(139,92,246,.3)">
                <?= strtoupper(substr($Lvs_profile['full_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div>
                <div style="font-family:'Space Grotesk',sans-serif;font-size:1.1rem;font-weight:700"><?= htmlspecialchars($Lvs_profile['full_name'] ?? '') ?></div>
                <div style="font-size:.8rem;color:var(--text-muted)">@<?= htmlspecialchars($Lvs_profile['username'] ?? '') ?></div>
                <div style="font-size:.75rem;color:var(--accent);margin-top:4px;background:rgba(139,92,246,.1);display:inline-block;padding:2px 10px;border-radius:99px;border:1px solid rgba(139,92,246,.2)">
                    <?= htmlspecialchars($Lvs_profile['role_name'] ?? 'ROLE_USER') ?>
                </div>
            </div>
        </div>

        <form method="POST">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label>Họ và tên *</label>
                    <input type="text" name="full_name" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)"
                           value="<?= htmlspecialchars($Lvs_profile['full_name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)"
                           value="<?= htmlspecialchars($Lvs_profile['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel" name="phone" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)"
                           value="<?= htmlspecialchars($Lvs_profile['phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Ngày tham gia</label>
                    <input type="text" readonly class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text-muted);opacity:.7"
                           value="<?= Lvs_formatDateShort($Lvs_profile['created_at'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Địa chỉ</label>
                <textarea name="address" rows="2" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text);resize:none"
                          placeholder="Địa chỉ nhận hàng mặc định..."><?= htmlspecialchars($Lvs_profile['address'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn-hero-primary" style="border:none;margin-top:8px">💾 Lưu thay đổi</button>
        </form>
    </div>
</div></div>
<?php require_once __DIR__ . '/../Lvs_pages/includes/Lvs_footer.php'; ?>
