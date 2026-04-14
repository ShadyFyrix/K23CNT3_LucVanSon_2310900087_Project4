<?php
/**
 * auth/logout.php — Đăng xuất
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';

logoutSession();
setFlash('success', 'Bạn đã đăng xuất thành công.');
header('Location: ' . BASE_URL . '/auth/login.php');
exit;
?>
