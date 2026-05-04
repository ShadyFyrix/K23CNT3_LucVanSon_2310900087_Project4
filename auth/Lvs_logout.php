<?php
/**
 * auth/Lvs_logout.php — Đăng xuất
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';

if (session_status() === PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();

Lvs_setFlash('success', '👋 Đăng xuất thành công. Hẹn gặp lại!');
header('Location: ' . BASE_URL . '/auth/Lvs_login.php');
exit;
?>
