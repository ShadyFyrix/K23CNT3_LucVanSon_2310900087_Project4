<?php
/** user/change_password.php — Đổi mật khẩu */
$pageTitle = 'Đổi mật khẩu — UmaCT Shop';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/user_model.php';

requireLogin();
$user = getCurrentUser();
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = trim($_POST['old_password'] ?? '');
    $new = trim($_POST['new_password'] ?? '');
    $cfm = trim($_POST['confirm_password'] ?? '');

    if (!$old || !$new || !$cfm) {
        $error = 'Vui lòng điền đầy đủ thông tin.';
    } elseif ($new !== $cfm) {
        $error = 'Mật khẩu mới và xác nhận không khớp!';
    } elseif (strlen($new) < 6) {
        $error = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
    } else {
        $res = changeUserPassword($user['user_id'], $old, $new);
        if (ApiClient::isSuccess($res)) {
            $success = '✅ Đổi mật khẩu thành công!';
        } else {
            $error = ApiClient::getError($res);
        }
    }
}
require_once __DIR__ . '/../pages/includes/header.php';
?>
<div class="container section">
    <div class="user-layout">
        <?php include __DIR__ . '/includes/user_sidebar.php'; ?>
        <div class="user-main-card" style="max-width:520px">
            <div class="user-card-title">🔐 Đổi mật khẩu</div>
            <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <form method="POST">
                <?php
                $fields = [
                    'old_password'     => 'Mật khẩu hiện tại',
                    'new_password'     => 'Mật khẩu mới',
                    'confirm_password' => 'Xác nhận mật khẩu mới',
                ];
                foreach ($fields as $name => $label):
                ?>
                <div class="form-group">
                    <label style="font-size:.85rem; font-weight:600; display:block; margin-bottom:6px"><?= $label ?></label>
                    <div style="position:relative">
                        <input type="password" name="<?= $name ?>" id="<?= $name ?>" required
                               placeholder="<?= $label ?>..."
                               style="width:100%; background:var(--bg-glass); border:1px solid var(--border); border-radius:10px; padding:10px 42px 10px 14px; color:var(--text); font-size:.875rem; outline:none; transition:border-color .15s; font-family:inherit"
                               onfocus="this.style.borderColor='var(--accent)'"
                               onblur="this.style.borderColor='var(--border)'">
                        <button type="button" onclick="toggleField('<?= $name ?>')"
                                style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; opacity:.5; font-size:1rem; padding:0">👁</button>
                    </div>
                </div>
                <?php endforeach; ?>
                <button type="submit" class="btn-hero-primary" style="border:none; width:100%; margin-top:8px">
                    🔒 Cập nhật mật khẩu
                </button>
            </form>
        </div>
    </div>
</div>
<script>
function toggleField(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}
</script>
<?php require_once __DIR__ . '/../pages/includes/footer.php'; ?>
