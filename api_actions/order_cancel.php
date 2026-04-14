<?php
/** api_actions/order_cancel.php — Hủy đơn hàng (POST form) */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/format_helper.php';
require_once __DIR__ . '/../models/order_model.php';

requireLogin();
$orderId = (int)($_POST['order_id'] ?? 0);

if ($orderId) {
    $res = cancelOrder($orderId);
    setFlash(
        ApiClient::isSuccess($res) ? 'success' : 'error',
        ApiClient::isSuccess($res) ? "Đã hủy đơn hàng #{$orderId}." : ApiClient::getError($res)
    );
}

header('Location: ' . BASE_URL . '/user/order_history.php');
exit;
