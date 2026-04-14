<?php
/**
 * index.php — Root entry point
 * Redirect: Nếu là admin → admin panel, ngược lại → trang chủ user
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/utils/auth_helper.php';

if (isAdmin()) {
    header('Location: ' . BASE_URL . '/admin/index.php');
} else {
    header('Location: ' . BASE_URL . '/pages/home.php');
}
exit;
