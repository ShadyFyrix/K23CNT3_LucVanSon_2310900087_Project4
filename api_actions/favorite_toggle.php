<?php
/** api_actions/favorite_toggle.php — Thêm hoặc xóa yêu thích */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../models/favorite_model.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { echo json_encode(['status'=>'error','message'=>'Chưa đăng nhập']); exit; }

$b = json_decode(file_get_contents('php://input'), true) ?? [];
$userId    = getCurrentUser()['user_id'];
$productId = (int)($b['product_id'] ?? 0);
$favId     = (int)($b['favorite_id'] ?? 0);

// Nếu truyền favorite_id → xóa trực tiếp (trang favorites)
if ($favId) {
    $res = removeFavorite($favId);
    echo json_encode(array_merge($res, ['action' => 'removed']));
    exit;
}

// Nếu truyền product_id → toggle
if (!$productId) { echo json_encode(['status'=>'error','message'=>'Thiếu product_id']); exit; }

if (isFavorited($userId, $productId)) {
    // Tìm favorite_id để xóa
    $favs = getFavorites($userId);
    foreach ($favs as $f) {
        if ($f['product_id'] == $productId) {
            $res = removeFavorite($f['favorite_id']);
            echo json_encode(array_merge($res, ['action' => 'removed']));
            exit;
        }
    }
}

$res = addFavorite($userId, $productId);
echo json_encode(array_merge($res, ['action' => 'added']));
