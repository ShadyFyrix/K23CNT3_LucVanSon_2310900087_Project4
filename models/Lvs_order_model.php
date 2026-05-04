<?php
/**
 * models/Lvs_order_model.php
 * Định danh Lvs_ — Wrapper cho order_model (đã FIXED endpoints)
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/order_model.php';

function Lvs_getAllOrders(array $filters = []): array        { return getAllOrders($filters); }
function Lvs_getOrderDetail(int $id): ?array                { return getOrderDetail($id); }
function Lvs_createOrder(array $data): array                { return createOrder($data); }
function Lvs_updateOrderStatus(int $id, string $s): array   { return updateOrderStatus($id, $s); }
function Lvs_deleteOrder(int $id): array                    { return deleteOrder($id); }
function Lvs_getOrdersByUser(int $userId): array            { return getOrdersByUser($userId); }
?>
