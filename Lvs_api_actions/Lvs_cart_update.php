<?php
/**
 * Lvs_api_actions/Lvs_cart_update.php — AJAX: Cập nhật số lượng
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../models/Lvs_cart_model.php';

header('Content-Type: application/json');

if (!Lvs_isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập.']); exit;
}

$Lvs_body      = json_decode(file_get_contents('php://input'), true) ?? [];
$Lvs_productId = (int)($Lvs_body['product_id'] ?? 0);
$Lvs_quantity  = max(1, (int)($Lvs_body['quantity'] ?? 1));

if (!$Lvs_productId) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']); exit;
}

$Lvs_userId = Lvs_getCurrentUser()['user_id'];
$Lvs_res    = Lvs_updateCartItem($Lvs_userId, $Lvs_productId, $Lvs_quantity);
echo json_encode($Lvs_res);
?>
