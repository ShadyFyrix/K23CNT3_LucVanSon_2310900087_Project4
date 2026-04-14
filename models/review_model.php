<?php
/**
 * review_model.php — Đánh giá sản phẩm
 */
require_once __DIR__ . '/../utils/api_client.php';

function getReviewsByProduct(int $productId): array {
    return ApiClient::get('/reviews', ['product_id' => $productId]) ?? [];
}

function getAllReviews(): array {
    return ApiClient::get('/reviews') ?? [];
}

function addReview(int $userId, int $productId, int $rating, string $comment): array {
    return ApiClient::post('/reviews', [
        'user_id'    => $userId,
        'product_id' => $productId,
        'rating'     => $rating,
        'comment'    => $comment,
    ]);
}

function deleteReview(int $id): array {
    return ApiClient::delete("/reviews/{$id}");
}

/**
 * Tính điểm trung bình từ mảng reviews
 */
function calcAverageRating(array $reviews): float {
    if (empty($reviews)) return 0;
    $sum = array_sum(array_column($reviews, 'rating'));
    return round($sum / count($reviews), 1);
}
?>
