<?php
/**
 * api_actions/cart_add.php — AJAX: Thêm vào giỏ hàng
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../models/cart_model.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập.']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$productId = (int)($body['product_id'] ?? 0);
$quantity  = max(1, (int)($body['quantity'] ?? 1));

if (!$productId) {
    echo json_encode(['status' => 'error', 'message' => 'ID sản phẩm không hợp lệ.']);
    exit;
}

$res = addToCart(getCurrentUser()['user_id'], $productId, $quantity);
echo json_encode($res);
?>
