<?php
/**
 * models/favorite_model.php — Danh sách yêu thích
 * FIXED: Sync với backend endpoints thực tế (Project4-UmaCT-main/uma_api/main.py)
 *   GET  /api/users/{user_id}/favorites
 *   POST /api/favorites/toggle  body: {user_id, product_id}
 *     → response: {status, action: "added"|"removed", message}
 */
require_once __DIR__ . '/../utils/api_client.php';

function getFavorites(int $userId): array {
    return ApiClient::get("/users/{$userId}/favorites") ?? [];
}

/**
 * Toggle yêu thích — thêm nếu chưa có, bỏ nếu đã có
 * @return array {status, action: "added"|"removed", message}
 */
function toggleFavorite(int $userId, int $productId): array {
    return ApiClient::post('/favorites/toggle', [
        'user_id'    => $userId,
        'product_id' => $productId,
    ]);
}

/** Kiểm tra sản phẩm đã trong danh sách yêu thích chưa */
function isFavorited(int $userId, int $productId): bool {
    $favorites = getFavorites($userId);
    foreach ($favorites as $fav) {
        if (($fav['id'] ?? $fav['product_id']) == $productId) return true;
    }
    return false;
}
?>
