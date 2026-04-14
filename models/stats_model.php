<?php
/**
 * stats_model.php — Thống kê Dashboard Admin
 */
require_once __DIR__ . '/../utils/api_client.php';

function getStatsOverview(): array {
    return ApiClient::get('/stats/overview') ?? [
        'total_orders'   => 0,
        'total_revenue'  => 0,
        'total_products' => 0,
        'total_users'    => 0,
        'pending_orders' => 0,
    ];
}

function getRevenueStats(int $month = 0, int $year = 0): array {
    $params = [];
    if ($month) $params['month'] = $month;
    if ($year)  $params['year']  = $year;
    return ApiClient::get('/stats/revenue', $params) ?? [];
}

function getTopProducts(int $limit = 5): array {
    return ApiClient::get('/stats/top-products', ['limit' => $limit]) ?? [];
}
?>
