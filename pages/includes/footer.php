<?php /* pages/includes/footer.php */ ?>

</div><!-- /.page-wrapper -->

<!-- ========== FOOTER ========== -->
<footer class="footer">
    <div class="container">
        <div class="footer-main">

            <!-- Brand -->
            <div class="footer-brand">
                <div class="footer-logo">
                    <span>🐎</span>
                    <span><span style="color:#8b5cf6">Uma</span><span style="color:#ec4899">CT</span> Shop</span>
                </div>
                <p class="footer-desc">
                    Cửa hàng chuyên mô hình figure, trang phục cosplay và phụ kiện
                    Uma Musume chính hãng hàng đầu Việt Nam. Cam kết hàng authentic —
                    ship toàn quốc.
                </p>
                <div class="social-links">
                    <a href="#" class="social-link" title="Facebook">📘</a>
                    <a href="#" class="social-link" title="TikTok">🎵</a>
                    <a href="#" class="social-link" title="Instagram">📷</a>
                    <a href="#" class="social-link" title="Discord">💬</a>
                </div>
            </div>

            <!-- Links -->
            <div>
                <div class="footer-col-title">Cửa hàng</div>
                <div class="footer-links">
                    <a href="<?= BASE_URL ?>/pages/shop.php">Tất cả sản phẩm</a>
                    <a href="<?= BASE_URL ?>/pages/shop.php?category_id=1">Mô hình / Figure</a>
                    <a href="<?= BASE_URL ?>/pages/shop.php?category_id=2">Trang phục Cosplay</a>
                    <a href="<?= BASE_URL ?>/pages/shop.php?category_id=3">Phụ kiện</a>
                    <a href="<?= BASE_URL ?>/pages/shop.php?sort=newest">Sản phẩm mới</a>
                </div>
            </div>

            <div>
                <div class="footer-col-title">Hỗ trợ</div>
                <div class="footer-links">
                    <a href="<?= BASE_URL ?>/pages/news.php">Tin tức</a>
                    <a href="<?= BASE_URL ?>/user/order_history.php">Tra cứu đơn hàng</a>
                    <a href="#">Chính sách đổi trả</a>
                    <a href="#">Hướng dẫn đặt hàng</a>
                    <a href="#">Liên hệ</a>
                </div>
            </div>

            <div>
                <div class="footer-col-title">Tài khoản</div>
                <div class="footer-links">
                    <?php if (isLoggedIn()): ?>
                        <a href="<?= BASE_URL ?>/user/profile.php">Hồ sơ cá nhân</a>
                        <a href="<?= BASE_URL ?>/user/order_history.php">Đơn hàng của tôi</a>
                        <a href="<?= BASE_URL ?>/user/favorites.php">Danh sách yêu thích</a>
                        <a href="<?= BASE_URL ?>/auth/logout.php" style="color:#f87171">Đăng xuất</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/auth/login.php">Đăng nhập</a>
                        <a href="<?= BASE_URL ?>/auth/register.php">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>

        </div><!-- /.footer-main -->

        <!-- Bottom bar -->
        <div class="footer-bottom">
            <span>© 2026 UmaCT Shop — K23CNT3 · Lục Văn Sơn (2310900087)</span>
            <div class="payment-icons">
                <span class="payment-icon">COD</span>
                <span class="payment-icon">MoMo</span>
                <span class="payment-icon">VNPay</span>
                <span class="payment-icon">PayOS</span>
            </div>
        </div>
    </div>
</footer>

<!-- Back to top button -->
<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        title="Lên đầu trang"
        style="display:none; position:fixed; bottom:24px; right:24px; z-index:900;
               width:44px; height:44px; border-radius:12px; background:#8b5cf6;
               color:#fff; border:none; font-size:1.1rem; cursor:pointer;
               box-shadow:0 4px 20px rgba(139,92,246,.4);
               transition:opacity .2s, transform .2s;">↑</button>

<script>
// Back to top
const btt = document.getElementById('backToTop');
window.addEventListener('scroll', () => {
    btt.style.display = window.scrollY > 400 ? 'block' : 'none';
}, { passive: true });
</script>
</body>
</html>
