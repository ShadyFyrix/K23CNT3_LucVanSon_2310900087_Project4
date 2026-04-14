<?php
/** api_actions/cart_update.php — AJAX: Cập nhật số lượng */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../models/cart_model.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { echo json_encode(['status'=>'error','message'=>'Chưa đăng nhập']); exit; }
$body = json_decode(file_get_contents('php://input'), true) ?? [];
echo json_encode(updateCartItem((int)$body['cart_id'], (int)$body['quantity']));
?>
