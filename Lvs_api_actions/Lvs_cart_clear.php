<?php
/**
 * Lvs_api_actions/Lvs_cart_clear.php — AJAX: Xóa toàn bộ giỏ hàng
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../models/Lvs_cart_model.php';

header('Content-Type: application/json');

if (!Lvs_isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập.']); exit;
}

$Lvs_userId = Lvs_getCurrentUser()['user_id'];
$Lvs_res    = Lvs_clearCart($Lvs_userId);
echo json_encode($Lvs_res);
?>
