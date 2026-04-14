<?php
/**
 * auth/register.php — Trang đăng ký
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/auth_model.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/pages/home.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'username'  => trim($_POST['username']  ?? ''),
        'password'  => trim($_POST['password']  ?? ''),
        'full_name' => trim($_POST['full_name'] ?? ''),
        'email'     => trim($_POST['email']     ?? ''),
        'phone'     => trim($_POST['phone']     ?? ''),
    ];
    $confirm = trim($_POST['confirm_password'] ?? '');

    // Validate cơ bản phía PHP
    if (in_array('', [$data['username'], $data['password'], $data['full_name'], $data['email']])) {
        $error = 'Vui lòng điền đầy đủ các trường bắt buộc (*).';
    } elseif ($data['password'] !== $confirm) {
        $error = 'Mật khẩu xác nhận không khớp!';
    } elseif (strlen($data['password']) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } else {
        $response = registerUser($data);
        if (ApiClient::isSuccess($response)) {
            setFlash('success', 'Đăng ký thành công! Hãy đăng nhập ngay.');
            header('Location: ' . BASE_URL . '/auth/login.php');
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
    <title>Đăng ký — UmaCT Shop</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">
</head>
<body class="auth-body">
<div class="auth-wrapper">
    <div class="auth-card auth-card--wide">
        <div class="auth-logo">
            <h1>🐎 UmaCT Shop</h1>
            <p>Tạo tài khoản mới</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form" id="registerForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Tên đăng nhập *</label>
                    <input type="text" id="username" name="username"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           placeholder="Tối thiểu 4 ký tự" required>
                </div>
                <div class="form-group">
                    <label for="full_name">Họ và tên *</label>
                    <input type="text" id="full_name" name="full_name"
                           value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                           placeholder="Nguyễn Văn A" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="example@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                       placeholder="0901234567">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Mật khẩu *</label>
                    <input type="password" id="password" name="password" placeholder="Tối thiểu 6 ký tự" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu *</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
                </div>
            </div>

            <button type="submit" class="btn-auth" id="btnRegister">Đăng ký</button>
        </form>

        <div class="auth-footer">
            <p>Đã có tài khoản? <a href="<?= BASE_URL ?>/auth/login.php">Đăng nhập</a></p>
        </div>
    </div>
</div>
<script>
document.getElementById('registerForm').addEventListener('submit', function() {
    document.getElementById('btnRegister').textContent = 'Đang xử lý...';
    document.getElementById('btnRegister').disabled = true;
});
</script>
</body>
</html>
