<?php
/**
 * cart_model.php — Quản lý giỏ hàng
 */
require_once __DIR__ . '/../utils/api_client.php';

function getCart(int $userId): array {
    return ApiClient::get("/cart/{$userId}") ?? [];
}

function addToCart(int $userId, int $productId, int $quantity = 1): array {
    return ApiClient::post('/cart', [
        'user_id'    => $userId,
        'product_id' => $productId,
        'quantity'   => $quantity,
    ]);
}

function updateCartItem(int $cartId, int $quantity): array {
    return ApiClient::put("/cart/{$cartId}", ['quantity' => $quantity]);
}

function removeCartItem(int $cartId): array {
    return ApiClient::delete("/cart/{$cartId}");
}

function clearCart(int $userId): array {
    return ApiClient::delete("/cart/clear/{$userId}");
}

/**
 * Tính tổng tiền giỏ hàng từ mảng items
 */
function calcCartTotal(array $items): float {
    return array_sum(array_column($items, 'subtotal'));
}
?>
