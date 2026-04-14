<?php
/**
 * favorite_model.php — Danh sách yêu thích
 */
require_once __DIR__ . '/../utils/api_client.php';

function getFavorites(int $userId): array {
    return ApiClient::get("/favorites/{$userId}") ?? [];
}

function addFavorite(int $userId, int $productId): array {
    return ApiClient::post('/favorites', [
        'user_id'    => $userId,
        'product_id' => $productId,
    ]);
}

function removeFavorite(int $favoriteId): array {
    return ApiClient::delete("/favorites/{$favoriteId}");
}

/**
 * Kiểm tra xem sản phẩm đã trong danh sách yêu thích chưa
 */
function isFavorited(int $userId, int $productId): bool {
    $favorites = getFavorites($userId);
    foreach ($favorites as $fav) {
        if ($fav['product_id'] == $productId) return true;
    }
    return false;
}
?>
