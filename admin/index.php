<?php
/**
 * admin/index.php — Dashboard Tổng quan
 */
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../models/stats_model.php';
require_once __DIR__ . '/../models/order_model.php';
require_once __DIR__ . '/../models/product_model.php';

$overview    = getStatsOverview();
$topProducts = getTopProducts(5);
$recentOrders = array_slice(getAllOrders(), 0, 8);
$revenueData  = getRevenueStats();
?>

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card stat-card--purple">
        <span class="stat-label">Tổng đơn hàng</span>
        <span class="stat-value"><?= number_format($overview['total_orders'] ?? 0) ?></span>
        <span class="stat-sub">📦 <?= $overview['pending_orders'] ?? 0 ?> đơn chờ xử lý</span>
    </div>
    <div class="stat-card stat-card--green">
        <span class="stat-label">Doanh thu</span>
        <span class="stat-value" style="font-size:1.4rem"><?= formatPrice($overview['total_revenue'] ?? 0) ?></span>
        <span class="stat-sub">💰 Tổng doanh thu</span>
    </div>
    <div class="stat-card stat-card--blue">
        <span class="stat-label">Sản phẩm</span>
        <span class="stat-value"><?= number_format($overview['total_products'] ?? 0) ?></span>
        <span class="stat-sub">🛍️ Đang kinh doanh</span>
    </div>
    <div class="stat-card stat-card--orange">
        <span class="stat-label">Người dùng</span>
        <span class="stat-value"><?= number_format($overview['total_users'] ?? 0) ?></span>
        <span class="stat-sub">👥 Đã đăng ký</span>
    </div>
    <div class="stat-card stat-card--red">
        <span class="stat-label">Chờ xử lý</span>
        <span class="stat-value"><?= number_format($overview['pending_orders'] ?? 0) ?></span>
        <span class="stat-sub">⏳ Cần duyệt ngay</span>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid">
    <div class="chart-card">
        <h3>📈 Doanh thu theo tháng</h3>
        <canvas id="revenueChart" height="90"></canvas>
    </div>
    <div class="chart-card">
        <h3>🏆 Top sản phẩm bán chạy</h3>
        <canvas id="topProductChart" height="200"></canvas>
    </div>
</div>

<!-- Recent Orders + Top Products side by side -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">

    <!-- Đơn hàng mới nhất -->
    <div class="data-card">
        <div class="data-card-header">
            <h2>🛒 Đơn hàng gần đây</h2>
            <a href="<?= BASE_URL ?>/admin/orders/index.php" class="btn btn-secondary btn-sm">Xem tất cả</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:24px">
                        Chưa có đơn hàng nào
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $o): ?>
                    <tr>
                        <td><strong>#<?= $o['id'] ?></strong></td>
                        <td><?= htmlspecialchars($o['full_name'] ?? $o['username'] ?? '—') ?></td>
                        <td><?= formatPrice($o['total_price']) ?></td>
                        <td><?= orderStatusBadge($o['status']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top sản phẩm -->
    <div class="data-card">
        <div class="data-card-header">
            <h2>🏆 Top sản phẩm bán chạy</h2>
            <a href="<?= BASE_URL ?>/admin/products/index.php" class="btn btn-secondary btn-sm">Xem tất cả</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đã bán</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($topProducts)): ?>
                    <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:24px">
                        Chưa có dữ liệu
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($topProducts as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['product_name']) ?></td>
                        <td><strong><?= $p['sold'] ?></strong></td>
                        <td><?= formatPrice($p['revenue']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Chart.js Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revData = <?= json_encode($revenueData) ?>;
    const revLabels  = revData.map(d => d.month ?? '');
    const revValues  = revData.map(d => d.revenue ?? 0);
    if (document.getElementById('revenueChart')) {
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: revLabels.length ? revLabels : ['Chưa có dữ liệu'],
                datasets: [{
                    label: 'Doanh thu (₫)',
                    data: revValues.length ? revValues : [0],
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,.08)',
                    tension: 0.4, fill: true, pointRadius: 4,
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }

    // Top Product Chart
    const topData    = <?= json_encode($topProducts) ?>;
    const topLabels  = topData.map(d => d.product_name ?? '');
    const topValues  = topData.map(d => d.sold ?? 0);
    if (document.getElementById('topProductChart')) {
        new Chart(document.getElementById('topProductChart'), {
            type: 'doughnut',
            data: {
                labels: topLabels.length ? topLabels : ['Chưa có dữ liệu'],
                datasets: [{
                    data: topValues.length ? topValues : [1],
                    backgroundColor: ['#6366f1','#22c55e','#f59e0b','#38bdf8','#ef4444'],
                    borderWidth: 0,
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>