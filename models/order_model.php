<?php
/**
 * models/order_model.php
 * FIXED: Sync với backend endpoints thực tế (Project4-UmaCT-main/uma_api/main.py)
 *   GET    /api/orders                           (admin: tất cả đơn)
 *   GET    /api/orders/{order_id}                (chi tiết 1 đơn)
 *   GET    /api/users/{user_id}/orders           (đơn của 1 user)
 *   POST   /api/orders                           body: OrderCreate
 *   PUT    /api/orders/{order_id}/status         body: {status}
 *   DELETE /api/orders/{order_id}
 *
 * OrderCreate body: {user_id, total_price, shipping_address, payment_method, items[]}
 * items[]: [{id: product_id, quantity, price}]
 */
require_once __DIR__ . '/../utils/api_client.php';

function getAllOrders(array $filters = []): array {
    return ApiClient::get('/orders', $filters) ?? [];
}

function getOrderDetail(int $id): array|null {
    return ApiClient::get("/orders/{$id}");
}

function createOrder(array $data): array {
    return ApiClient::post('/orders', $data);
}

function updateOrderStatus(int $id, string $status): array {
    return ApiClient::put("/orders/{$id}/status", ['status' => $status]);
}

function deleteOrder(int $id): array {
    return ApiClient::delete("/orders/{$id}");
}

/** Lấy đơn hàng của 1 user — endpoint riêng theo backend */
function getOrdersByUser(int $userId): array {
    return ApiClient::get("/users/{$userId}/orders") ?? [];
}
?>