<?php
/**
 * user/profile.php — Hồ sơ cá nhân
 */
$pageTitle = 'Hồ sơ cá nhân — UmaCT Shop';
$activeNav = '';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/user_model.php';

requireLogin();
$user = getCurrentUser();
$detail = getUserDetail($user['user_id']); // Lấy chi tiết từ API
$profile = $detail['info'] ?? $user;

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => trim($_POST['full_name'] ?? ''),
        'email'     => trim($_POST['email'] ?? ''),
        'phone'     => trim($_POST['phone'] ?? ''),
        'address'   => trim($_POST['address'] ?? ''),
    ];
    if (empty($data['full_name'])) {
        $error = 'Họ tên không được để trống.';
    } else {
        $res = updateUserProfile($user['user_id'], $data);
        if (ApiClient::isSuccess($res)) {
            $success = 'Cập nhật thông tin thành công!';
            $_SESSION['full_name'] = $data['full_name'];
            $profile = array_merge($profile, $data);
        } else {
            $error = ApiClient::getError($res);
        }
    }
}

require_once __DIR__ . '/../pages/includes/header.php';
?>

<div class="container section">
    <div class="user-layout">

        <!-- Sidebar -->
        <?php include __DIR__ . '/includes/user_sidebar.php'; ?>

        <!-- Main -->
        <div class="user-main-card">
            <div class="user-card-title">👤 Hồ sơ cá nhân</div>

            <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

            <!-- Avatar preview -->
            <div style="display:flex; align-items:center; gap:20px; margin-bottom:28px; padding-bottom:24px; border-bottom:1px solid var(--border)">
                <div style="width:72px; height:72px; border-radius:50%; background:var(--gradient-btn); display:flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:700; color:#fff; overflow:hidden; border:3px solid rgba(139,92,246,.3)">
                    <?php if (!empty($profile['avatar_url'])): ?>
                        <img src="<?= htmlspecialchars($profile['avatar_url']) ?>" style="width:100%;height:100%;object-fit:cover" alt="">
                    <?php else: ?>
                        <?= strtoupper(substr($profile['full_name'] ?? 'U', 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <div>
                    <div style="font-family:'Space Grotesk',sans-serif; font-size:1.1rem; font-weight:700"><?= htmlspecialchars($profile['full_name'] ?? '') ?></div>
                    <div style="font-size:.8rem; color:var(--text-muted)">@<?= htmlspecialchars($profile['username'] ?? '') ?></div>
                    <div style="font-size:.75rem; color:var(--accent); margin-top:4px; background:rgba(139,92,246,.1); display:inline-block; padding:2px 10px; border-radius:99px; border:1px solid rgba(139,92,246,.2)">
                        <?= htmlspecialchars($profile['role_name'] ?? 'ROLE_USER') ?>
                    </div>
                </div>
            </div>

            <form method="POST">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                    <div class="form-group">
                        <label>Họ và tên *</label>
                        <input type="text" name="full_name" class="form-control" style="background:var(--bg-glass); border-color:var(--border); color:var(--text)"
                               value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" style="background:var(--bg-glass); border-color:var(--border); color:var(--text)"
                               value="<?= htmlspecialchars($profile['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="tel" name="phone" class="form-control" style="background:var(--bg-glass); border-color:var(--border); color:var(--text)"
                               value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Ngày tham gia</label>
                        <input type="text" readonly class="form-control" style="background:var(--bg-glass); border-color:var(--border); color:var(--text-muted); opacity:.7"
                               value="<?= formatDateShort($profile['created_at'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Địa chỉ</label>
                    <textarea name="address" rows="2" class="form-control" style="background:var(--bg-glass); border-color:var(--border); color:var(--text); resize:none"
                              placeholder="Địa chỉ nhận hàng mặc định..."><?= htmlspecialchars($profile['address'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn-hero-primary" style="border:none; margin-top:8px">
                    💾 Lưu thay đổi
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../pages/includes/footer.php'; ?>
