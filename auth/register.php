<?php
/**
 * auth/register.php — Redesigned dark theme
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/api_client.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/auth_model.php';

if (isLoggedIn()) { header('Location: ' . BASE_URL . '/pages/home.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = [
        'username'  => trim($_POST['username'] ?? ''),
        'password'  => trim($_POST['password'] ?? ''),
        'full_name' => trim($_POST['full_name'] ?? ''),
        'email'     => trim($_POST['email'] ?? ''),
        'phone'     => trim($_POST['phone'] ?? ''),
    ];
    $cfm = trim($_POST['confirm_password'] ?? '');

    if (!$d['username'] || !$d['password'] || !$d['full_name'] || !$d['email']) {
        $error = 'Vui lòng điền đầy đủ các trường bắt buộc (*).';
    } elseif ($d['password'] !== $cfm) {
        $error = 'Mật khẩu xác nhận không khớp!';
    } elseif (strlen($d['password']) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } else {
        $res = registerUser($d);
        if (ApiClient::isSuccess($res)) {
            setFlash('success', '🎉 Đăng ký thành công! Hãy đăng nhập ngay.');
            header('Location: ' . BASE_URL . '/auth/login.php');
            exit;
        }
        $error = ApiClient::getError($res);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký — UmaCT Shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐎</text></svg>">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--bg-base); padding:20px; }
        .auth-page { width:100%; max-width:560px; }
        .auth-box { background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-xl); padding:40px 36px; }
        .auth-logo-big { text-align:center; margin-bottom:28px; }
        .auth-logo-big h1 { font-family:'Space Grotesk',sans-serif; font-size:1.6rem; font-weight:800; }
        .auth-logo-big p  { color:var(--text-muted); font-size:.875rem; margin-top:6px; }
        .field { margin-bottom:14px; }
        .field label { display:block; font-size:.82rem; font-weight:600; margin-bottom:5px; color:var(--text-muted); }
        .field input {
            width:100%; padding:10px 14px; background:var(--bg-glass);
            border:1.5px solid var(--border); border-radius:10px;
            color:var(--text); font-size:.875rem; outline:none;
            transition:border-color .15s; font-family:inherit;
        }
        .field input:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); }
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .btn-submit {
            width:100%; padding:12px; margin-top:10px;
            background:var(--gradient-btn); color:#fff; border:none;
            border-radius:12px; font-size:.95rem; font-weight:700; cursor:pointer;
            box-shadow:0 4px 20px var(--accent-glow); transition:all .2s;
        }
        .btn-submit:hover { transform:translateY(-2px); }
        .btn-submit:disabled { opacity:.6; cursor:not-allowed; transform:none; }
        .auth-links { text-align:center; margin-top:18px; font-size:.855rem; color:var(--text-muted); }
        .auth-links a { color:var(--accent); font-weight:600; }
        .back-home { display:block; text-align:center; margin-top:14px; font-size:.8rem; color:var(--text-dim); }
        @media(max-width:520px) { .grid-2 { grid-template-columns:1fr; } .auth-box { padding:28px 20px; } }
    </style>
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo-big">
            <h1>🐎 <span style="color:var(--accent)">Uma</span><span style="color:var(--pink)">CT</span></h1>
            <p>Tạo tài khoản mới — Miễn phí</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error" style="margin-bottom:18px">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" id="regForm" novalidate>
            <div class="grid-2">
                <div class="field">
                    <label>Tên đăng nhập *</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" placeholder="vd: bokachan" required>
                </div>
                <div class="field">
                    <label>Họ và tên *</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" placeholder="Nguyễn Văn A" required>
                </div>
            </div>
            <div class="field">
                <label>Email *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="example@gmail.com" required>
            </div>
            <div class="field">
                <label>Số điện thoại</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" placeholder="0901234567">
            </div>
            <div class="grid-2">
                <div class="field">
                    <label>Mật khẩu * (≥6 ký tự)</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <div class="field">
                    <label>Xác nhận mật khẩu *</label>
                    <input type="password" name="confirm_password" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn-submit" id="btnReg">Đăng ký →</button>
        </form>

        <div class="auth-links">Đã có tài khoản? <a href="<?= BASE_URL ?>/auth/login.php">Đăng nhập</a></div>
    </div>
    <a href="<?= BASE_URL ?>/pages/home.php" class="back-home">← Về trang chủ</a>
</div>
<script>
document.getElementById('regForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnReg');
    btn.textContent = 'Đang xử lý...';
    btn.disabled = true;
});
</script>
</body>
</html>
