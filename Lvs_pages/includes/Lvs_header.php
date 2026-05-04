<?php
/**
 * Lvs_pages/includes/Lvs_header.php
 * Định danh Lvs_ — Header dùng chung cho tất cả trang user của Sơn
 * Tác giả phần: Lục Văn Sơn (2310900087)
 *
 * Vars cần set trước khi include:
 *   $pageTitle (string) — tiêu đề trang
 *   $activeNav (string) — 'home'|'shop'|'news'|...
 */
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../../utils/Lvs_format_helper.php';

$pageTitle   = $pageTitle ?? 'UmaCT Shop';
$activeNav   = $activeNav ?? '';
$Lvs_currentUser = Lvs_getCurrentUser();

// Đếm giỏ hàng
$Lvs_cartCount = 0;
if ($Lvs_currentUser) {
    require_once __DIR__ . '/../../models/Lvs_cart_model.php';
    $Lvs_cartItems = Lvs_getCart($Lvs_currentUser['user_id']);
    $Lvs_cartCount = count($Lvs_cartItems);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="UmaCT Shop — Cửa hàng mô hình, figure, cosplay và phụ kiện Uma Musume chính hãng tại Việt Nam.">
    <title><?= htmlspecialchars($pageTitle) ?> | UmaCT Shop 🐎</title>

    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐎</text></svg>">
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar" id="mainNav">
    <div class="nav-container">

        <!-- Logo — trỏ về Lvs_pages -->
        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_home.php" class="nav-logo">
            <span class="logo-icon">🐎</span>
            <span><span class="logo-uma">Uma</span><span class="logo-ct">CT</span></span>
        </a>

        <!-- Nav Links -->
        <div class="nav-links">
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_home.php"
               class="<?= $activeNav === 'home' ? 'active' : '' ?>">Trang chủ</a>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php"
               class="<?= $activeNav === 'shop' ? 'active' : '' ?>">Cửa hàng</a>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php?category_id=1"
               class="<?= $activeNav === 'figure' ? 'active' : '' ?>">Figure</a>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php?category_id=2"
               class="<?= $activeNav === 'cosplay' ? 'active' : '' ?>">Cosplay</a>
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_news.php"
               class="<?= $activeNav === 'news' ? 'active' : '' ?>">Tin tức</a>
        </div>

        <!-- Search -->
        <form class="nav-search" action="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" method="GET" role="search">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" name="search"
                   placeholder="Tìm mô hình, figure..."
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                   aria-label="Tìm kiếm sản phẩm">
        </form>

        <!-- Actions -->
        <div class="nav-actions">
            <!-- Wishlist -->
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_favorites.php"
               class="nav-btn" title="Yêu thích" id="wishlistBtn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </a>

            <!-- Cart -->
            <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_cart.php"
               class="nav-btn" title="Giỏ hàng" id="cartBtn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                <?php if ($Lvs_cartCount > 0): ?>
                    <span class="nav-badge" id="cartBadge"><?= $Lvs_cartCount ?></span>
                <?php endif; ?>
            </a>

            <!-- User Menu / Login -->
            <?php if ($Lvs_currentUser): ?>
                <div class="nav-user" tabindex="0" role="button" aria-haspopup="true">
                    <div class="nav-avatar">
                        <?php if (!empty($Lvs_currentUser['avatar_url'])): ?>
                            <img src="<?= htmlspecialchars($Lvs_currentUser['avatar_url']) ?>"
                                 alt="<?= htmlspecialchars($Lvs_currentUser['full_name']) ?>">
                        <?php else: ?>
                            <?= strtoupper(substr($Lvs_currentUser['full_name'] ?? $Lvs_currentUser['username'], 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <span class="nav-username"><?= htmlspecialchars($Lvs_currentUser['full_name'] ?? $Lvs_currentUser['username']) ?></span>

                    <!-- Dropdown -->
                    <div class="nav-dropdown" role="menu">
                        <a href="<?= BASE_URL ?>/Lvs_user/Lvs_profile.php" role="menuitem">👤 Hồ sơ cá nhân</a>
                        <a href="<?= BASE_URL ?>/Lvs_user/Lvs_order_history.php" role="menuitem">📦 Đơn hàng của tôi</a>
                        <a href="<?= BASE_URL ?>/Lvs_user/Lvs_favorites.php" role="menuitem">❤️ Yêu thích</a>
                        <a href="<?= BASE_URL ?>/Lvs_user/Lvs_change_password.php" role="menuitem">🔐 Đổi mật khẩu</a>
                        <?php if (Lvs_isAdmin()): ?>
                            <div class="divider"></div>
                            <a href="<?= BASE_URL ?>/admin/index.php" role="menuitem">⚙️ Quản trị</a>
                        <?php endif; ?>
                        <div class="divider"></div>
                        <a href="<?= BASE_URL ?>/auth/Lvs_logout.php" class="logout-link" role="menuitem">🚪 Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/Lvs_login.php" class="btn-login">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Đăng nhập
                </a>
            <?php endif; ?>

            <!-- Mobile Toggle -->
            <button class="nav-mobile-toggle" id="mobileToggle" aria-label="Menu">☰</button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu" style="display:none; border-top:1px solid var(--border); padding:14px 24px; gap:4px; flex-direction:column;">
        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_home.php" style="padding:9px 0; color:var(--text-muted); font-size:.9rem;">🏠 Trang chủ</a>
        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_shop.php" style="padding:9px 0; color:var(--text-muted); font-size:.9rem;">🛍 Cửa hàng</a>
        <a href="<?= BASE_URL ?>/Lvs_pages/Lvs_news.php" style="padding:9px 0; color:var(--text-muted); font-size:.9rem;">📰 Tin tức</a>
        <?php if ($Lvs_currentUser): ?>
            <a href="<?= BASE_URL ?>/Lvs_user/Lvs_profile.php" style="padding:9px 0; color:var(--text-muted); font-size:.9rem;">👤 Hồ sơ</a>
            <a href="<?= BASE_URL ?>/auth/Lvs_logout.php" style="padding:9px 0; color:#f87171; font-size:.9rem;">🚪 Đăng xuất</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/auth/Lvs_login.php" style="padding:9px 0; color:var(--accent); font-size:.9rem; font-weight:600;">→ Đăng nhập</a>
        <?php endif; ?>
    </div>
</nav>

<div class="page-wrapper">
<?php
// Render flash message một lần nếu có
$Lvs_flash = Lvs_renderFlash();
if ($Lvs_flash) echo '<div class="container" style="padding-top:16px">' . $Lvs_flash . '</div>';
?>

<script>
// Navbar scroll effect
const nav = document.getElementById('mainNav');
window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY > 10), { passive: true });
// Mobile menu toggle
const toggle = document.getElementById('mobileToggle');
const menu   = document.getElementById('mobileMenu');
if (toggle && menu) {
    toggle.addEventListener('click', () => {
        menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
    });
}
</script>
