<?php
/**
 * auth/login.php — Cập nhật: require ApiClient
 * (Đảm bảo ApiClient được load qua auth_helper / model)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/api_client.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/auth_model.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/pages/home.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$username || !$password) {
        $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
    } else {
        $response = loginUser($username, $password);
        if (ApiClient::isSuccess($response) && !empty($response['data'])) {
            loginSession($response['data']);
            $redirect = $_GET['redirect'] ?? (isAdmin() ? BASE_URL . '/admin/index.php' : BASE_URL . '/pages/home.php');
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = ApiClient::getError($response);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập — UmaCT Shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐎</text></svg>">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--bg-base); }
        .auth-page { width:100%; max-width:420px; padding:20px; }
        .auth-box { background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-xl); padding:40px 36px; }
        .auth-logo-big { text-align:center; margin-bottom:32px; }
        .auth-logo-big h1 { font-family:'Space Grotesk',sans-serif; font-size:1.8rem; font-weight:800; }
        .auth-logo-big p  { color:var(--text-muted); font-size:.875rem; margin-top:6px; }
        .field { margin-bottom:16px; }
        .field label { display:block; font-size:.82rem; font-weight:600; margin-bottom:6px; color:var(--text-muted); }
        .field-wrap { position:relative; }
        .field input {
            width:100%; padding:11px 42px 11px 14px;
            background:var(--bg-glass); border:1.5px solid var(--border);
            border-radius:10px; color:var(--text); font-size:.9rem; outline:none;
            transition:border-color .15s, box-shadow .15s; font-family:inherit;
        }
        .field input:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); }
        .eye-btn { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; opacity:.5; font-size:1rem; }
        .eye-btn:hover { opacity:1; }
        .btn-submit {
            width:100%; padding:12px; margin-top:8px;
            background:var(--gradient-btn); color:#fff; border:none;
            border-radius:12px; font-size:.95rem; font-weight:700; cursor:pointer;
            box-shadow:0 4px 20px var(--accent-glow); transition:all .2s;
        }
        .btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 30px var(--accent-glow); }
        .btn-submit:disabled { opacity:.6; transform:none; cursor:not-allowed; }
        .auth-links { text-align:center; margin-top:20px; font-size:.855rem; color:var(--text-muted); }
        .auth-links a { color:var(--accent); font-weight:600; }
        .back-home { display:block; text-align:center; margin-top:16px; font-size:.8rem; color:var(--text-dim); transition:color .15s; }
        .back-home:hover { color:var(--text-muted); }
    </style>
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo-big">
            <h1>🐎 <span style="color:var(--accent)">Uma</span><span style="color:var(--pink)">CT</span></h1>
            <p>Đăng nhập vào tài khoản của bạn</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error" style="margin-bottom:20px">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?= renderFlash() ?>

        <form method="POST" id="loginForm" novalidate>
            <div class="field">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       placeholder="Nhập tên đăng nhập..." required autofocus>
            </div>
            <div class="field">
                <label for="password">Mật khẩu</label>
                <div class="field-wrap">
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu..." required>
                    <button type="button" class="eye-btn" onclick="togglePwd('password')">👁</button>
                </div>
            </div>
            <button type="submit" class="btn-submit" id="btnSubmit">Đăng nhập →</button>
        </form>

        <div class="auth-links">
            Chưa có tài khoản? <a href="<?= BASE_URL ?>/auth/register.php">Đăng ký ngay</a>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/pages/home.php" class="back-home">← Về trang chủ</a>
</div>
<script>
function togglePwd(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmit');
    btn.textContent = 'Đang xử lý...';
    btn.disabled = true;
});
</script>
</body>
</html>
