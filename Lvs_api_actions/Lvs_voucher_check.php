<?php
/**
 * Lvs_api_actions/Lvs_voucher_check.php — AJAX: Kiểm tra mã voucher
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../models/Lvs_voucher_model.php';
require_once __DIR__ . '/../models/Lvs_cart_model.php';

header('Content-Type: application/json');

if (!Lvs_isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập.']); exit;
}

$Lvs_body  = json_decode(file_get_contents('php://input'), true) ?? [];
$Lvs_code  = trim($Lvs_body['code'] ?? '');

if (!$Lvs_code) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập mã voucher.']); exit;
}

// Tính tổng giỏ hàng để check điều kiện tối thiểu
$Lvs_userId = Lvs_getCurrentUser()['user_id'];
$Lvs_items  = Lvs_getCart($Lvs_userId);
$Lvs_total  = Lvs_calcCartTotal($Lvs_items);

$Lvs_voucher = Lvs_checkVoucher($Lvs_code, $Lvs_total);

if (!$Lvs_voucher) {
    echo json_encode(['status' => 'error', 'message' => 'Mã voucher không hợp lệ hoặc đã hết hạn.']); exit;
}

echo json_encode([
    'status'     => 'success',
    'discount'   => $Lvs_voucher['discount_value'] ?? 0,
    'voucher_id' => $Lvs_voucher['id'] ?? null,
    'message'    => 'Áp dụng thành công!',
]);
?>
