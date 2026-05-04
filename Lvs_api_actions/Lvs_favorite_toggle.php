<?php
/**
 * Lvs_api_actions/Lvs_favorite_toggle.php — AJAX: Toggle yêu thích
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 * Backend: POST /api/favorites/toggle — {user_id, product_id}
 * Response: {status, action: "added"|"removed", message}
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../models/Lvs_favorite_model.php';

header('Content-Type: application/json');

if (!Lvs_isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thêm yêu thích.']); exit;
}

$Lvs_body      = json_decode(file_get_contents('php://input'), true) ?? [];
$Lvs_productId = (int)($Lvs_body['product_id'] ?? 0);

if (!$Lvs_productId) {
    echo json_encode(['status' => 'error', 'message' => 'ID sản phẩm không hợp lệ.']); exit;
}

$Lvs_userId = Lvs_getCurrentUser()['user_id'];
$Lvs_res    = Lvs_toggleFavorite($Lvs_userId, $Lvs_productId);
echo json_encode($Lvs_res);
?>
