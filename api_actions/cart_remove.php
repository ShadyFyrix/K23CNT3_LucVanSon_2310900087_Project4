<?php
/** api_actions/cart_remove.php */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../models/cart_model.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { echo json_encode(['status'=>'error','message'=>'Chưa đăng nhập']); exit; }
$b = json_decode(file_get_contents('php://input'), true) ?? [];
echo json_encode(removeCartItem((int)($b['cart_id'] ?? 0)));
