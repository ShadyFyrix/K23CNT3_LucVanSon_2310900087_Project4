<?php
/**
 * auth/Lvs_login.php — Đăng nhập
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 * Backend: POST /api/login — {username, password}
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_api_client.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../models/Lvs_auth_model.php';

if (Lvs_isLoggedIn()) {
    header('Location: ' . BASE_URL . '/Lvs_pages/Lvs_home.php'); exit;
}

$Lvs_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Lvs_username = trim($_POST['username'] ?? '');
    $Lvs_password = trim($_POST['password'] ?? '');
    if (!$Lvs_username || !$Lvs_password) {
        $Lvs_error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
    } else {
        $Lvs_response = Lvs_loginUser($Lvs_username, $Lvs_password);
        if (Lvs_ApiClient::isSuccess($Lvs_response) && !empty($Lvs_response['data'])) {
            Lvs_loginSession($Lvs_response['data']);
            $Lvs_redirect = $_GET['redirect'] ?? (Lvs_isAdmin() ? BASE_URL . '/admin/index.php' : BASE_URL . '/Lvs_pages/Lvs_home.php');
            header('Location: ' . $Lvs_redirect); exit;
        } else {
            $Lvs_error = Lvs_ApiClient::getError($Lvs_response);
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
        body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--bg-base)}
        .auth-page{width:100%;max-width:420px;padding:20px}
        .auth-box{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-xl);padding:40px 36px}
        .auth-logo-big{text-align:center;margin-bottom:32px}
        .auth-logo-big h1{font-family:'Space Grotesk',sans-serif;font-size:1.8rem;font-weight:800}
        .auth-logo-big p{color:var(--text-muted);font-size:.875rem;margin-top:6px}
        .Lvs_field{margin-bottom:16px}
        .Lvs_field label{display:block;font-size:.82rem;font-weight:600;margin-bottom:6px;color:var(--text-muted)}
        .Lvs_field-wrap{position:relative}
        .Lvs_field input{width:100%;padding:11px 42px 11px 14px;background:var(--bg-glass);border:1.5px solid var(--border);border-radius:10px;color:var(--text);font-size:.9rem;outline:none;transition:border-color .15s,box-shadow .15s;font-family:inherit}
        .Lvs_field input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow)}
        .Lvs_eye-btn{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;opacity:.5;font-size:1rem}
        .Lvs_eye-btn:hover{opacity:1}
        .Lvs_btn-submit{width:100%;padding:12px;margin-top:8px;background:var(--gradient-btn);color:#fff;border:none;border-radius:12px;font-size:.95rem;font-weight:700;cursor:pointer;box-shadow:0 4px 20px var(--accent-glow);transition:all .2s}
        .Lvs_btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 30px var(--accent-glow)}
        .Lvs_btn-submit:disabled{opacity:.6;transform:none;cursor:not-allowed}
        .auth-links{text-align:center;margin-top:20px;font-size:.855rem;color:var(--text-muted)}
        .auth-links a{color:var(--accent);font-weight:600}
    </style>
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo-big">
            <h1>🐎 <span style="color:var(--accent)">Uma</span><span style="color:var(--pink)">CT</span></h1>
            <p>Đăng nhập vào tài khoản của bạn</p>
        </div>

        <?php if ($Lvs_error): ?>
            <div class="alert alert-error" style="margin-bottom:20px">⚠️ <?= htmlspecialchars($Lvs_error) ?></div>
        <?php endif; ?>
        <?= Lvs_renderFlash() ?>

        <form method="POST" id="Lvs_loginForm" novalidate>
            <div class="Lvs_field">
                <label for="Lvs_username">Tên đăng nhập</label>
                <input type="text" id="Lvs_username" name="username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       placeholder="Nhập tên đăng nhập..." required autofocus>
            </div>
            <div class="Lvs_field">
                <label for="Lvs_password">Mật khẩu</label>
                <div class="Lvs_field-wrap">
                    <input type="password" id="Lvs_password" name="password" placeholder="Nhập mật khẩu..." required>
                    <button type="button" class="Lvs_eye-btn" onclick="Lvs_togglePwd('Lvs_password')">👁</button>
                </div>
            </div>
            <button type="submit" class="Lvs_btn-submit" id="Lvs_btnSubmit">Đăng nhập →</button>
        </form>

        <div class="auth-links">
            Chưa có tài khoản? <a href="<?= BASE_URL ?>/auth/Lvs_register.php">Đăng ký ngay</a>
        </div>
    </div>
    <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_home.php" style="display:block;text-align:center;margin-top:16px;font-size:.8rem;color:var(--text-dim)">← Về trang chủ</a>
</div>
<script>
function Lvs_togglePwd(Lvs_id) {
    const Lvs_el = document.getElementById(Lvs_id);
    Lvs_el.type = Lvs_el.type === 'password' ? 'text' : 'password';
}
document.getElementById('Lvs_loginForm').addEventListener('submit', function() {
    const Lvs_btn = document.getElementById('Lvs_btnSubmit');
    Lvs_btn.textContent = 'Đang xử lý...'; Lvs_btn.disabled = true;
});
</script>
</body>
</html>
