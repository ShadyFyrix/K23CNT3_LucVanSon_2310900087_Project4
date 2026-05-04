<?php
/**
 * Lvs_admin/includes/Lvs_header.php — Admin header + bảo vệ ROLE_ADMIN
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../../utils/Lvs_format_helper.php';

Lvs_requireRole('ROLE_ADMIN');

$Lvs_currentUser = Lvs_getCurrentUser();
$pageTitle       = $pageTitle ?? 'Admin Panel';
$Lvs_currentUrl  = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — UmaCT Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
</head>
<body>
<div class="admin-layout">
    <?php require_once __DIR__ . '/Lvs_sidebar.php'; ?>
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" id="Lvs_sidebarToggle">☰</button>
                <span class="topbar-title"><?= htmlspecialchars($pageTitle) ?></span>
            </div>
            <div class="topbar-right">
                <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_home.php" class="btn-topbar" target="_blank">🌐 Trang web</a>
                <div class="topbar-user">
                    <span>👤 <?= htmlspecialchars($Lvs_currentUser['full_name'] ?? $Lvs_currentUser['username']) ?></span>
                    <a href="<?= BASE_URL ?>/auth/Lvs_logout.php" class="btn-logout">Đăng xuất</a>
                </div>
            </div>
        </header>
        <main class="main-content">
            <?= Lvs_renderFlash() ?>
