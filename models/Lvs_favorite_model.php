<?php
/**
 * models/Lvs_favorite_model.php
 * Định danh Lvs_ — Wrapper cho favorite_model (đã FIXED endpoints)
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/favorite_model.php';

function Lvs_getFavorites(int $userId): array                           { return getFavorites($userId); }
function Lvs_toggleFavorite(int $userId, int $productId): array         { return toggleFavorite($userId, $productId); }
function Lvs_isFavorited(int $userId, int $productId): bool             { return isFavorited($userId, $productId); }
?>
