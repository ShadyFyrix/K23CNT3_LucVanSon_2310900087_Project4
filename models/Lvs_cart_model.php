<?php
/**
 * models/Lvs_cart_model.php
 * Định danh Lvs_ — Wrapper cho cart_model (đã FIXED endpoints)
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/cart_model.php';

function Lvs_getCart(int $userId): array                                               { return getCart($userId); }
function Lvs_addToCart(int $userId, int $productId, int $qty = 1): array               { return addToCart($userId, $productId, $qty); }
function Lvs_updateCartItem(int $userId, int $productId, int $qty): array              { return updateCartItem($userId, $productId, $qty); }
function Lvs_removeCartItem(int $userId, int $productId): array                        { return removeCartItem($userId, $productId); }
function Lvs_clearCart(int $userId): array                                             { return clearCart($userId); }
function Lvs_calcCartTotal(array $items): float                                        { return calcCartTotal($items); }
?>
