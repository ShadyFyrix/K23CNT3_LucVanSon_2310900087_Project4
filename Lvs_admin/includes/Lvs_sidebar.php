<aside class="sidebar" id="Lvs_adminSidebar">
    <div class="sidebar-header">
        <span class="sidebar-logo">🐎</span>
        <h2>UmaCT Admin</h2>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">
            <span class="nav-section-label">TỔNG QUAN</span>
            <ul class="nav-links">
                <li><a href="<?= BASE_URL ?>/Lvs_admin/Lvs_index.php" class="<?= (strpos($Lvs_currentUrl, '/Lvs_admin/Lvs_index.php') !== false) ? 'active' : '' ?>"><span class="nav-icon">📊</span> Dashboard</a></li>
            </ul>
        </div>
        <div class="nav-section">
            <span class="nav-section-label">HÀNG HÓA</span>
            <ul class="nav-links">
                <li><a href="<?= BASE_URL ?>/Lvs_admin/products/Lvs_index.php" class="<?= (strpos($Lvs_currentUrl, '/Lvs_admin/products/') !== false) ? 'active' : '' ?>"><span class="nav-icon">📦</span> Sản phẩm</a></li>
                <li><a href="<?= BASE_URL ?>/Lvs_admin/categories/Lvs_index.php" class="<?= (strpos($Lvs_currentUrl, '/Lvs_admin/categories/') !== false) ? 'active' : '' ?>"><span class="nav-icon">🗂️</span> Danh mục</a></li>
                <li><a href="<?= BASE_URL ?>/Lvs_admin/suppliers/Lvs_index.php" class="<?= (strpos($Lvs_currentUrl, '/Lvs_admin/suppliers/') !== false) ? 'active' : '' ?>"><span class="nav-icon">🏭</span> Nhà cung cấp</a></li>
            </ul>
        </div>
        <div class="nav-section">
            <span class="nav-section-label">BÁN HÀNG</span>
            <ul class="nav-links">
                <li><a href="<?= BASE_URL ?>/Lvs_admin/orders/Lvs_index.php" class="<?= (strpos($Lvs_currentUrl, '/Lvs_admin/orders/') !== false) ? 'active' : '' ?>"><span class="nav-icon">🛒</span> Đơn hàng</a></li>
                <li><a href="<?= BASE_URL ?>/Lvs_admin/vouchers/Lvs_index.php" class="<?= (strpos($Lvs_currentUrl, '/Lvs_admin/vouchers/') !== false) ? 'active' : '' ?>"><span class="nav-icon">🎟️</span> Mã giảm giá</a></li>
                <li><a href="<?= BASE_URL ?>/Lvs_admin/reviews/Lvs_index.php" class="<?= (strpos($Lvs_currentUrl, '/Lvs_admin/reviews/') !== false) ? 'active' : '' ?>"><span class="nav-icon">⭐</span> Đánh giá</a></li>
            </ul>
        </div>
        <div class="nav-section">
            <span class="nav-section-label">NGƯỜI DÙNG & NỘI DUNG</span>
            <ul class="nav-links">
                <li><a href="<?= BASE_URL ?>/Lvs_admin/users/Lvs_index.php" class="<?= (strpos($Lvs_currentUrl, '/Lvs_admin/users/') !== false) ? 'active' : '' ?>"><span class="nav-icon">👥</span> Người dùng</a></li>
                <li><a href="<?= BASE_URL ?>/Lvs_admin/articles/Lvs_index.php" class="<?= (strpos($Lvs_currentUrl, '/Lvs_admin/articles/') !== false) ? 'active' : '' ?>"><span class="nav-icon">📝</span> Bài viết</a></li>
            </ul>
        </div>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>/auth/Lvs_logout.php" class="sidebar-logout"><span class="nav-icon">🚪</span> Đăng xuất</a>
    </div>
</aside>
