<?php
/**
 * Lvs_api_actions/Lvs_cart_add.php — AJAX: Thêm vào giỏ hàng
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 * Endpoint backend: POST /api/cart/add — {user_id, product_id, quantity}
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../models/Lvs_cart_model.php';

header('Content-Type: application/json');

if (!Lvs_isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập.']);
    exit;
}

$Lvs_body      = json_decode(file_get_contents('php://input'), true) ?? [];
$Lvs_productId = (int)($Lvs_body['product_id'] ?? 0);
$Lvs_quantity  = max(1, (int)($Lvs_body['quantity'] ?? 1));

if (!$Lvs_productId) {
    echo json_encode(['status' => 'error', 'message' => 'ID sản phẩm không hợp lệ.']);
    exit;
}

$Lvs_user = Lvs_getCurrentUser();
$Lvs_res  = Lvs_addToCart($Lvs_user['user_id'], $Lvs_productId, $Lvs_quantity);
echo json_encode($Lvs_res);
?>
