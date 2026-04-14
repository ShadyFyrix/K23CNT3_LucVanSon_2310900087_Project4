<?php
/** api_actions/voucher_check.php — Kiểm tra mã voucher */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/voucher_model.php';
header('Content-Type: application/json');

$b = json_decode(file_get_contents('php://input'), true) ?? [];
$code  = trim($b['code'] ?? '');
$value = (float)($b['order_value'] ?? 0);

if (!$code) { echo json_encode(['status'=>'error','message'=>'Thiếu mã voucher']); exit; }

$result = checkVoucher($code, $value);

if (!$result) {
    echo json_encode(['status'=>'error','message'=>'Mã không hợp lệ hoặc đã hết hạn']);
    exit;
}

echo json_encode([
    'status'     => 'success',
    'voucher_id' => $result['voucher_id'] ?? null,
    'discount'   => $result['discount_amount'] ?? 0,
    'message'    => 'Áp dụng thành công!',
]);
