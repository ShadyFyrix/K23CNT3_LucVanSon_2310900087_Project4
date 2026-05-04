<?php
/**
 * auth/Lvs_register.php — Đăng ký tài khoản
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 * Backend: POST /api/register — {username, password, full_name, email}
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_api_client.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../models/Lvs_auth_model.php';

if (Lvs_isLoggedIn()) { header('Location: ' . BASE_URL . '/Lvs_pages/Lvs_home.php'); exit; }

$Lvs_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Lvs_d = [
        'username'  => trim($_POST['username'] ?? ''),
        'password'  => trim($_POST['password'] ?? ''),
        'full_name' => trim($_POST['full_name'] ?? ''),
        'email'     => trim($_POST['email'] ?? ''),
    ];
    $Lvs_cfm = trim($_POST['confirm_password'] ?? '');
    if (!$Lvs_d['username'] || !$Lvs_d['password'] || !$Lvs_d['full_name'] || !$Lvs_d['email']) {
        $Lvs_error = 'Vui lòng điền đầy đủ các trường bắt buộc (*).';
    } elseif ($Lvs_d['password'] !== $Lvs_cfm) {
        $Lvs_error = 'Mật khẩu xác nhận không khớp!';
    } elseif (strlen($Lvs_d['password']) < 6) {
        $Lvs_error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif (!filter_var($Lvs_d['email'], FILTER_VALIDATE_EMAIL)) {
        $Lvs_error = 'Email không hợp lệ.';
    } else {
        $Lvs_res = Lvs_registerUser($Lvs_d);
        if (Lvs_ApiClient::isSuccess($Lvs_res)) {
            Lvs_setFlash('success', '🎉 Đăng ký thành công! Hãy đăng nhập ngay.');
            header('Location: ' . BASE_URL . '/auth/Lvs_login.php'); exit;
        }
        $Lvs_error = Lvs_ApiClient::getError($Lvs_res);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký — UmaCT Shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐎</text></svg>">
    <style>
        body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--bg-base);padding:20px}
        .auth-page{width:100%;max-width:560px}
        .auth-box{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-xl);padding:40px 36px}
        .auth-logo-big{text-align:center;margin-bottom:28px}
        .auth-logo-big h1{font-family:'Space Grotesk',sans-serif;font-size:1.6rem;font-weight:800}
        .auth-logo-big p{color:var(--text-muted);font-size:.875rem;margin-top:6px}
        .Lvs_field{margin-bottom:14px}
        .Lvs_field label{display:block;font-size:.82rem;font-weight:600;margin-bottom:5px;color:var(--text-muted)}
        .Lvs_field input{width:100%;padding:10px 14px;background:var(--bg-glass);border:1.5px solid var(--border);border-radius:10px;color:var(--text);font-size:.875rem;outline:none;transition:border-color .15s;font-family:inherit}
        .Lvs_field input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow)}
        .Lvs_grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
        .Lvs_btn-submit{width:100%;padding:12px;margin-top:10px;background:var(--gradient-btn);color:#fff;border:none;border-radius:12px;font-size:.95rem;font-weight:700;cursor:pointer;box-shadow:0 4px 20px var(--accent-glow);transition:all .2s}
        .Lvs_btn-submit:hover{transform:translateY(-2px)}
        .Lvs_btn-submit:disabled{opacity:.6;cursor:not-allowed;transform:none}
        .auth-links{text-align:center;margin-top:18px;font-size:.855rem;color:var(--text-muted)}
        .auth-links a{color:var(--accent);font-weight:600}
        @media(max-width:520px){.Lvs_grid-2{grid-template-columns:1fr}.auth-box{padding:28px 20px}}
    </style>
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo-big">
            <h1>🐎 <span style="color:var(--accent)">Uma</span><span style="color:var(--pink)">CT</span></h1>
            <p>Tạo tài khoản mới — Miễn phí</p>
        </div>
        <?php if ($Lvs_error): ?>
            <div class="alert alert-error" style="margin-bottom:18px">⚠️ <?= htmlspecialchars($Lvs_error) ?></div>
        <?php endif; ?>
        <form method="POST" id="Lvs_regForm" novalidate>
            <div class="Lvs_grid-2">
                <div class="Lvs_field"><label>Tên đăng nhập *</label><input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" placeholder="vd: bokachan" required></div>
                <div class="Lvs_field"><label>Họ và tên *</label><input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" placeholder="Nguyễn Văn A" required></div>
            </div>
            <div class="Lvs_field"><label>Email *</label><input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="example@gmail.com" required></div>
            <div class="Lvs_grid-2">
                <div class="Lvs_field"><label>Mật khẩu * (≥6 ký tự)</label><input type="password" name="password" placeholder="••••••••" required></div>
                <div class="Lvs_field"><label>Xác nhận mật khẩu *</label><input type="password" name="confirm_password" placeholder="••••••••" required></div>
            </div>
            <button type="submit" class="Lvs_btn-submit" id="Lvs_btnReg">Đăng ký →</button>
        </form>
        <div class="auth-links">Đã có tài khoản? <a href="<?= BASE_URL ?>/auth/Lvs_login.php">Đăng nhập</a></div>
    </div>
    <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_home.php" style="display:block;text-align:center;margin-top:14px;font-size:.8rem;color:var(--text-dim)">← Về trang chủ</a>
</div>
<script>
document.getElementById('Lvs_regForm').addEventListener('submit', function() {
    const Lvs_btn = document.getElementById('Lvs_btnReg');
    Lvs_btn.textContent = 'Đang xử lý...'; Lvs_btn.disabled = true;
});
</script>
</body>
</html>
