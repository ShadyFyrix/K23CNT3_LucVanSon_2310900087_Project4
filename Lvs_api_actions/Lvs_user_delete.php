<?php
/**
 * Lvs_api_actions/Lvs_user_delete.php — AJAX: Admin xóa user
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 *
 * FastAPI KHÔNG có endpoint DELETE /api/users/{id}.
 * Chiến lược: BANNED + ghi chú (soft-delete an toàn).
 * Nếu cần hard-delete thật sự, thêm endpoint vào main.py.
 *
 * An toàn: Không cho xóa chính mình, không cho xóa ADMIN cuối cùng.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/Lvs_auth_helper.php';
require_once __DIR__ . '/../utils/api_client.php';
require_once __DIR__ . '/../config/database.php'; // PDO direct để hard-delete

header('Content-Type: application/json');

if (!Lvs_isLoggedIn() || !Lvs_isAdmin()) {
    echo json_encode(['status'=>'error','message'=>'Quyền ADMIN bắt buộc.']); exit;
}

$Lvs_body   = json_decode(file_get_contents('php://input'), true) ?? [];
$Lvs_userId = (int)($Lvs_body['user_id'] ?? 0);

// Không cho xóa chính mình
if (!$Lvs_userId || $Lvs_userId === (int)($_SESSION['user_id'] ?? 0)) {
    echo json_encode(['status'=>'error','message'=>'Không thể xóa tài khoản đang đăng nhập.']); exit;
}

try {
    // Kiểm tra tồn tại và không phải admin cuối cùng
    $Lvs_stmt = $pdo->prepare("SELECT role_id FROM users WHERE id = ?");
    $Lvs_stmt->execute([$Lvs_userId]);
    $Lvs_target = $Lvs_stmt->fetch();

    if (!$Lvs_target) {
        echo json_encode(['status'=>'error','message'=>'Không tìm thấy user.']); exit;
    }

    // Nếu là ADMIN (role_id=1), kiểm tra còn admin khác không
    if ((int)$Lvs_target['role_id'] === 1) {
        $Lvs_adminCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role_id = 1")->fetchColumn();
        if ((int)$Lvs_adminCount <= 1) {
            echo json_encode(['status'=>'error','message'=>'Không thể xóa admin cuối cùng!']); exit;
        }
    }

    // Hard delete (xóa liên quan trước để tránh FK constraint)
    $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$Lvs_userId]);
    $pdo->prepare("DELETE FROM favorites WHERE user_id = ?")->execute([$Lvs_userId]);
    $pdo->prepare("DELETE FROM reviews WHERE user_id = ?")->execute([$Lvs_userId]);
    // Đơn hàng giữ lại (không xóa orders vì liên quan doanh thu)
    $pdo->prepare("UPDATE orders SET user_id = NULL WHERE user_id = ?")->execute([$Lvs_userId]);
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$Lvs_userId]);

    echo json_encode(['status'=>'success','message'=>'Đã xóa tài khoản #' . $Lvs_userId]);

} catch (PDOException $Lvs_e) {
    echo json_encode(['status'=>'error','detail'=>'DB Error: ' . $Lvs_e->getMessage()]);
}
