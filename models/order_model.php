<?php
/**
 * order_model.php
 * Refactored: Dùng ApiClient
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

function cancelOrder(int $id): array {
    return ApiClient::patch("/orders/{$id}/cancel");
}

function deleteOrder(int $id): array {
    return ApiClient::delete("/orders/{$id}");
}

function getOrdersByUser(int $userId): array {
    return ApiClient::get('/orders', ['user_id' => $userId]) ?? [];
}
?>