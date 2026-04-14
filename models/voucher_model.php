<?php
/**
 * voucher_model.php
 * Refactored: Dùng ApiClient
 */
require_once __DIR__ . '/../utils/api_client.php';

function getAllVouchers(): array {
    return ApiClient::get('/vouchers') ?? [];
}

function getVoucherById(int $id): array|null {
    return ApiClient::get("/vouchers/{$id}");
}

function addVoucher(array $data): array {
    return ApiClient::post('/vouchers', $data);
}

function updateVoucher(int $id, array $data): array {
    return ApiClient::put("/vouchers/{$id}", $data);
}

function deleteVoucher(int $id): array {
    return ApiClient::delete("/vouchers/{$id}");
}

/**
 * Kiểm tra mã voucher có hợp lệ không trước khi checkout
 * @param string $code         Mã voucher người dùng nhập
 * @param float  $orderValue   Tổng giá trị đơn hàng
 */
function checkVoucher(string $code, float $orderValue): array|null {
    return ApiClient::get("/vouchers/check/{$code}", ['order_value' => $orderValue]);
}
?>