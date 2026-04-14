<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/auth_helper.php';
require_once __DIR__ . '/../../utils/format_helper.php';

// Bảo vệ toàn bộ khu vực Admin
requireRole('ROLE_ADMIN');

$currentUser = getCurrentUser();
$pageTitle   = $pageTitle ?? 'Admin Panel';
$current_url = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — UmaCT Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
</head>
<body>
<div class="admin-layout">

    <?php require_once 'sidebar.php'; ?>

    <div class="main-wrapper">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" id="sidebarToggle" title="Thu/mở menu">☰</button>
                <span class="topbar-title"><?= htmlspecialchars($pageTitle) ?></span>
            </div>
            <div class="topbar-right">
                <a href="<?= BASE_URL ?>/pages/home.php" class="btn-topbar" target="_blank" title="Xem trang web">🌐 Trang web</a>
                <div class="topbar-user">
                    <span>👤 <?= htmlspecialchars($currentUser['full_name'] ?? $currentUser['username']) ?></span>
                    <a href="<?= BASE_URL ?>/auth/logout.php" class="btn-logout">Đăng xuất</a>
                </div>
            </div>
        </header>

        <main class="main-content">
            <?= renderFlash() ?>