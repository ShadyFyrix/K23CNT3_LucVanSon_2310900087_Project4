<?php
/**
 * models/cart_model.php — Quản lý giỏ hàng
 * FIXED: Sync với backend endpoints thực tế (Project4-UmaCT-main/uma_api/main.py)
 *   GET    /api/cart/{user_id}
 *   POST   /api/cart/add        body: {user_id, product_id, quantity}
 *   DELETE /api/cart/remove/{user_id}/{product_id}
 */
require_once __DIR__ . '/../utils/api_client.php';

function getCart(int $userId): array {
    return ApiClient::get("/cart/{$userId}") ?? [];
}

function addToCart(int $userId, int $productId, int $quantity = 1): array {
    return ApiClient::post('/cart/add', [
        'user_id'    => $userId,
        'product_id' => $productId,
        'quantity'   => $quantity,
    ]);
}

/** Backend không có endpoint update quantity riêng — xóa + thêm lại */
function updateCartItem(int $userId, int $productId, int $quantity): array {
    // Xóa item cũ
    ApiClient::delete("/cart/remove/{$userId}/{$productId}");
    // Thêm lại với quantity mới
    return ApiClient::post('/cart/add', [
        'user_id'    => $userId,
        'product_id' => $productId,
        'quantity'   => $quantity,
    ]);
}

function removeCartItem(int $userId, int $productId): array {
    return ApiClient::delete("/cart/remove/{$userId}/{$productId}");
}

/** Backend chưa có /cart/clear — xóa từng item */
function clearCart(int $userId): array {
    $items = getCart($userId);
    foreach ($items as $item) {
        ApiClient::delete("/cart/remove/{$userId}/{$item['id']}");
    }
    return ['status' => 'success'];
}

function calcCartTotal(array $items): float {
    return array_sum(array_map(fn($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 1), $items));
}
?>
