<aside class="sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <span class="sidebar-logo">🐎</span>
        <h2>UmaCT Admin</h2>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <span class="nav-section-label">TỔNG QUAN</span>
            <ul class="nav-links">
                <li>
                    <a href="<?= BASE_URL ?>/admin/index.php"
                       class="<?= (strpos($current_url, '/admin/index.php') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">📊</span> Dashboard
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">HÀNG HÓA</span>
            <ul class="nav-links">
                <li>
                    <a href="<?= BASE_URL ?>/admin/products/index.php"
                       class="<?= (strpos($current_url, '/products/') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">📦</span> Sản phẩm
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/admin/categories/index.php"
                       class="<?= (strpos($current_url, '/categories/') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">🗂️</span> Danh mục
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/admin/suppliers/index.php"
                       class="<?= (strpos($current_url, '/suppliers/') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">🏭</span> Nhà cung cấp
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">BÁN HÀNG</span>
            <ul class="nav-links">
                <li>
                    <a href="<?= BASE_URL ?>/admin/orders/index.php"
                       class="<?= (strpos($current_url, '/orders/') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">🛒</span> Đơn hàng
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/admin/vouchers/index.php"
                       class="<?= (strpos($current_url, '/vouchers/') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">🎟️</span> Mã giảm giá
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/admin/reviews/index.php"
                       class="<?= (strpos($current_url, '/reviews/') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">⭐</span> Đánh giá
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">NGƯỜI DÙNG & NỘI DUNG</span>
            <ul class="nav-links">
                <li>
                    <a href="<?= BASE_URL ?>/admin/users/index.php"
                       class="<?= (strpos($current_url, '/users/') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">👥</span> Người dùng
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/admin/articles/index.php"
                       class="<?= (strpos($current_url, '/articles/') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">📝</span> Bài viết
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>/auth/logout.php" class="sidebar-logout">
            <span class="nav-icon">🚪</span> Đăng xuất
        </a>
    </div>
</aside>