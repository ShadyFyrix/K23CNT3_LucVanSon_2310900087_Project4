<?php
/**
 * Lvs_api_actions/Lvs_user_toggle_ban.php — AJAX: Admin khóa/mở tài khoản
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/api_client.php';

header('Content-Type: application/json');

if (!Lvs_isLoggedIn() || !Lvs_isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Quyền ADMIN bắt buộc.']); exit;
}

$Lvs_body   = json_decode(file_get_contents('php://input'), true) ?? [];
$Lvs_userId = (int)($Lvs_body['user_id'] ?? 0);
$Lvs_status = $Lvs_body['status'] ?? '';

if (!$Lvs_userId || !in_array($Lvs_status, ['ACTIVE', 'BANNED'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']); exit;
}

$Lvs_res = ApiClient::put('/users/' . $Lvs_userId . '/status', ['status' => $Lvs_status]);
echo json_encode(ApiClient::isSuccess($Lvs_res) ? ['status' => 'success'] : ['status' => 'error', 'detail' => ApiClient::getError($Lvs_res)]);
?>
