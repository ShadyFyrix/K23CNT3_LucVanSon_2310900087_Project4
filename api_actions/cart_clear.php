<?php
/** api_actions/cart_clear.php */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../models/cart_model.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { echo json_encode(['status'=>'error']); exit; }
echo json_encode(clearCart(getCurrentUser()['user_id']));
