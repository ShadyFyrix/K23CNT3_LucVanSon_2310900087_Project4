<?php
/**
 * auth/login.php — Trang đăng nhập
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/auth_model.php';

// Nếu đã đăng nhập → redirect
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/pages/home.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
    } else {
        $response = loginUser($username, $password);
        if (ApiClient::isSuccess($response) || isset($response['data'])) {
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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">
</head>
<body class="auth-body">

<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Logo -->
        <div class="auth-logo">
            <h1>🐎 UmaCT Shop</h1>
            <p>Đăng nhập vào tài khoản của bạn</p>
        </div>

        <!-- Flash / Error -->
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?= renderFlash() ?>

        <!-- Form -->
        <form method="POST" class="auth-form" id="loginForm">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       placeholder="Nhập tên đăng nhập..." required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <div class="input-password">
                    <input type="password" id="password" name="password"
                           placeholder="Nhập mật khẩu..." required>
                    <button type="button" class="toggle-pwd" onclick="togglePwd('password')">👁</button>
                </div>
            </div>

            <button type="submit" class="btn-auth" id="btnLogin">Đăng nhập</button>
        </form>

        <div class="auth-footer">
            <p>Chưa có tài khoản? <a href="<?= BASE_URL ?>/auth/register.php">Đăng ký ngay</a></p>
        </div>
    </div>
</div>

<script>
function togglePwd(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
document.getElementById('loginForm').addEventListener('submit', function() {
    document.getElementById('btnLogin').textContent = 'Đang xử lý...';
    document.getElementById('btnLogin').disabled = true;
});
</script>
</body>
</html>
