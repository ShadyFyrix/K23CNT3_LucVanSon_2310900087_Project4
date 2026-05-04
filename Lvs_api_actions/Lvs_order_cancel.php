<?php
/**
 * Lvs_api_actions/Lvs_order_cancel.php — AJAX: Hủy đơn hàng
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../models/Lvs_order_model.php';

header('Content-Type: application/json');

if (!Lvs_isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập.']); exit;
}

$Lvs_body    = json_decode(file_get_contents('php://input'), true) ?? [];
$Lvs_orderId = (int)($Lvs_body['order_id'] ?? 0);

if (!$Lvs_orderId) {
    echo json_encode(['status' => 'error', 'message' => 'ID đơn hàng không hợp lệ.']); exit;
}

// Chỉ cho phép hủy đơn PENDING
$Lvs_order = Lvs_getOrderDetail($Lvs_orderId);
if (!$Lvs_order) {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng.']); exit;
}
if ($Lvs_order['user_id'] != Lvs_getCurrentUser()['user_id']) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền hủy đơn này.']); exit;
}
if (!in_array($Lvs_order['status'], ['PENDING', 'CONFIRMED'])) {
    echo json_encode(['status' => 'error', 'message' => 'Không thể hủy đơn hàng ở trạng thái này.']); exit;
}

$Lvs_res = Lvs_updateOrderStatus($Lvs_orderId, 'CANCELLED');
echo json_encode($Lvs_res);
?>
