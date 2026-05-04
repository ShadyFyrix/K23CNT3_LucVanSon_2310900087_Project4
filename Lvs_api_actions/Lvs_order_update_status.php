<?php
/**
 * Lvs_api_actions/Lvs_order_update_status.php — AJAX: Admin cập nhật trạng thái đơn
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../models/Lvs_order_model.php';

header('Content-Type: application/json');

if (!Lvs_isLoggedIn() || !Lvs_isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Quyền ADMIN bắt buộc.']); exit;
}

$Lvs_body    = json_decode(file_get_contents('php://input'), true) ?? [];
$Lvs_orderId = (int)($Lvs_body['order_id'] ?? 0);
$Lvs_status  = $Lvs_body['status'] ?? '';
$Lvs_allowed = ['PENDING', 'CONFIRMED', 'SHIPPING', 'PAID', 'COMPLETED', 'CANCELLED'];

if (!$Lvs_orderId || !in_array($Lvs_status, $Lvs_allowed)) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']); exit;
}

$Lvs_res = Lvs_updateOrderStatus($Lvs_orderId, $Lvs_status);
echo json_encode($Lvs_res);
?>
