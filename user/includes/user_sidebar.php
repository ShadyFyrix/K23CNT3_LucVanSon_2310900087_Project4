<?php
/**
 * user/includes/user_sidebar.php
 * Sidebar menu cho các trang user (profile, orders, favorites...)
 * Yêu cầu $user đã được define
 */
$navUser = getCurrentUser();
$currentPath = $_SERVER['REQUEST_URI'];
$isActive = function($path) use ($currentPath) {
    return strpos($currentPath, $path) !== false ? 'active' : '';
};
?>
<aside class="user-sidebar">
    <div class="user-sidebar-header">
        <div class="user-sidebar-avatar">
            <?php if (!empty($navUser['avatar_url'])): ?>
                <img src="<?= htmlspecialchars($navUser['avatar_url']) ?>" alt="" style="width:100%;height:100%;object-fit:cover">
            <?php else: ?>
                <?= strtoupper(substr($navUser['full_name'] ?? 'U', 0, 1)) ?>
            <?php endif; ?>
        </div>
        <div class="user-sidebar-name"><?= htmlspecialchars($navUser['full_name'] ?? $navUser['username']) ?></div>
        <div class="user-sidebar-email">@<?= htmlspecialchars($navUser['username']) ?></div>
    </div>
    <nav class="user-sidebar-nav">
        <ul>
            <li><a href="<?= BASE_URL ?>/user/profile.php"       class="<?= $isActive('/user/profile') ?>">      👤 Hồ sơ cá nhân</a></li>
            <li><a href="<?= BASE_URL ?>/user/order_history.php"  class="<?= $isActive('/user/order') ?>">        📦 Đơn hàng của tôi</a></li>
            <li><a href="<?= BASE_URL ?>/user/favorites.php"      class="<?= $isActive('/user/favorites') ?>">    ❤️ Yêu thích</a></li>
            <li><a href="<?= BASE_URL ?>/user/change_password.php" class="<?= $isActive('/user/change_password') ?>">🔐 Đổi mật khẩu</a></li>
            <li style="border-top:1px solid var(--border); margin-top:6px; padding-top:6px">
                <a href="<?= BASE_URL ?>/auth/logout.php" style="color:var(--red) !important">🚪 Đăng xuất</a>
            </li>
        </ul>
    </nav>
</aside>
