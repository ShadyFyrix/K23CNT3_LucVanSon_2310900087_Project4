<?php
/**
 * Lvs_admin/Lvs_index.php — Dashboard Tổng quan
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/Lvs_header.php';
require_once __DIR__ . '/../models/Lvs_product_model.php';
require_once __DIR__ . '/../models/Lvs_order_model.php';
require_once __DIR__ . '/../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../utils/api_client.php';

// Dashboard stats qua GET /api/dashboard/stats
$Lvs_statsRaw    = ApiClient::get('/dashboard/stats');
$Lvs_summary     = $Lvs_statsRaw['summary'] ?? [];
$Lvs_chartData   = $Lvs_statsRaw['chart'] ?? [];
$Lvs_recentOrders = $Lvs_statsRaw['recent_orders'] ?? [];
$Lvs_topProducts = Lvs_getAllProducts(['sort' => 'popular']);
?>

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card stat-card--purple">
        <span class="stat-label">Tổng đơn hàng</span>
        <span class="stat-value"><?= number_format($Lvs_summary['orders'] ?? 0) ?></span>
        <span class="stat-sub">📦 Tất cả đơn hàng</span>
    </div>
    <div class="stat-card stat-card--green">
        <span class="stat-label">Doanh thu</span>
        <span class="stat-value" style="font-size:1.4rem"><?= Lvs_formatPrice($Lvs_summary['revenue'] ?? 0) ?></span>
        <span class="stat-sub">💰 Đã thanh toán</span>
    </div>
    <div class="stat-card stat-card--blue">
        <span class="stat-label">Sản phẩm</span>
        <span class="stat-value"><?= number_format($Lvs_summary['products'] ?? 0) ?></span>
        <span class="stat-sub">🛍️ Đang kinh doanh</span>
    </div>
    <div class="stat-card stat-card--orange">
        <span class="stat-label">Người dùng</span>
        <span class="stat-value"><?= number_format($Lvs_summary['users'] ?? 0) ?></span>
        <span class="stat-sub">👥 Đã đăng ký</span>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid">
    <div class="chart-card"><h3>📈 Doanh thu theo tháng</h3><canvas id="Lvs_revenueChart" height="90"></canvas></div>
    <div class="chart-card"><h3>🏆 Top sản phẩm bán chạy</h3><canvas id="Lvs_topProductChart" height="200"></canvas></div>
</div>

<!-- Recent Orders -->
<div class="data-card">
    <div class="data-card-header">
        <h2>🛒 Đơn hàng gần đây</h2>
        <a href="<?= BASE_URL ?>/Lvs_admin/orders/Lvs_index.php" class="btn btn-secondary btn-sm">Xem tất cả</a>
    </div>
    <div class="table-responsive"><table>
        <thead><tr><th>#ID</th><th>Khách hàng</th><th>Tổng tiền</th><th>Trạng thái</th></tr></thead>
        <tbody>
        <?php if (empty($Lvs_recentOrders)): ?>
            <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:24px">Chưa có đơn hàng</td></tr>
        <?php else: ?>
            <?php foreach ($Lvs_recentOrders as $Lvs_o): ?>
            <tr>
                <td><strong>#<?= $Lvs_o['id'] ?></strong></td>
                <td><?= htmlspecialchars($Lvs_o['full_name'] ?? $Lvs_o['username'] ?? '—') ?></td>
                <td><?= Lvs_formatPrice($Lvs_o['total_price']) ?></td>
                <td><?= Lvs_orderStatusBadge($Lvs_o['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const Lvs_revData   = <?= json_encode($Lvs_chartData) ?>;
    const Lvs_revLabels = Lvs_revData.map(d => d.month ?? '');
    const Lvs_revVals   = Lvs_revData.map(d => d.revenue ?? 0);
    if (document.getElementById('Lvs_revenueChart')) {
        new Chart(document.getElementById('Lvs_revenueChart'), {
            type: 'line',
            data: { labels: Lvs_revLabels.length ? Lvs_revLabels : ['Chưa có'], datasets: [{ label: 'Doanh thu (₫)', data: Lvs_revVals.length ? Lvs_revVals : [0], borderColor:'#6366f1', backgroundColor:'rgba(99,102,241,.08)', tension:0.4, fill:true, pointRadius:4 }] },
            options: { responsive:true, plugins:{ legend:{ display:false } } }
        });
    }
    const Lvs_topData   = <?= json_encode(array_slice($Lvs_topProducts, 0, 5)) ?>;
    const Lvs_topLabels = Lvs_topData.map(d => d.name ?? '');
    const Lvs_topVals   = Lvs_topData.map((_, i) => 5 - i); // Mock data khi không có sold count
    if (document.getElementById('Lvs_topProductChart')) {
        new Chart(document.getElementById('Lvs_topProductChart'), {
            type: 'doughnut',
            data: { labels: Lvs_topLabels.length ? Lvs_topLabels : ['Chưa có'], datasets: [{ data: Lvs_topVals.length ? Lvs_topVals : [1], backgroundColor:['#6366f1','#22c55e','#f59e0b','#38bdf8','#ef4444'], borderWidth:0 }] },
            options: { responsive:true, plugins:{ legend:{ position:'bottom', labels:{ font:{ size:11 } } } } }
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/Lvs_footer.php'; ?>
