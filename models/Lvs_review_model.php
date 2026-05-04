<?php
/**
 * models/Lvs_review_model.php
 * Định danh Lvs_ — Wrapper cho review_model
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/review_model.php';

function Lvs_getReviewsByProduct(int $productId): array                                         { return getReviewsByProduct($productId); }
function Lvs_getAllReviews(): array                                                              { return getAllReviews(); }
function Lvs_addReview(int $uid, int $pid, int $rating, string $comment): array                { return addReview($uid, $pid, $rating, $comment); }
function Lvs_deleteReview(int $id): array                                                       { return deleteReview($id); }
function Lvs_calcAverageRating(array $reviews): float                                           { return calcAverageRating($reviews); }
?>
