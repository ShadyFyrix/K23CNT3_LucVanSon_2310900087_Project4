<?php
/**
 * auth_helper.php — Quản lý Session & Phân quyền
 *
 * CÁCH DÙNG:
 *   require_once __DIR__ . '/../../utils/auth_helper.php';
 *   requireLogin();                    // Bắt buộc phải đăng nhập
 *   requireRole('ROLE_ADMIN');         // Bắt buộc phải là Admin
 *   $user = getCurrentUser();          // Lấy thông tin user hiện tại
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Lưu thông tin user vào session sau khi đăng nhập thành công
 */
function loginSession(array $userData): void {
    // FastAPI /api/login trả về field 'id' (không phải 'user_id')
    $_SESSION['user_id']   = $userData['user_id'] ?? $userData['id'] ?? null;
    $_SESSION['username']  = $userData['username']  ?? '';
    $_SESSION['full_name'] = $userData['full_name'] ?? '';

    // FastAPI SELECT * users chỉ có role_id (không JOIN roles)
    // Map role_id → role_name: 1=ADMIN, 2=USER, 3=STAFF
    if (!empty($userData['role_name'])) {
        $_SESSION['role_name'] = $userData['role_name'];
    } elseif (!empty($userData['role'])) {
        $_SESSION['role_name'] = $userData['role'];
    } else {
        $Lvs_roleMap = [1 => 'ROLE_ADMIN', 2 => 'ROLE_USER', 3 => 'ROLE_STAFF'];
        $_SESSION['role_name'] = $Lvs_roleMap[$userData['role_id'] ?? 2] ?? 'ROLE_USER';
    }

    $_SESSION['avatar_url'] = $userData['avatar_url'] ?? '';

    if (empty($_SESSION['user_id'])) {
        error_log('[loginSession] WARNING: user_id empty. Response keys: ' . implode(',', array_keys($userData)));
    }
}

/**
 * Xóa session khi đăng xuất
 */
function logoutSession(): void {
    session_unset();
    session_destroy();
}

/**
 * Kiểm tra đã đăng nhập chưa
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Lấy thông tin user đang đăng nhập
 */
function getCurrentUser(): array|null {
    if (!isLoggedIn()) return null;
    return [
        'user_id'   => $_SESSION['user_id'],
        'username'  => $_SESSION['username'],
        'full_name' => $_SESSION['full_name'],
        'role_name' => $_SESSION['role_name'],
        'avatar_url'=> $_SESSION['avatar_url'],
    ];
}

/**
 * Bắt buộc đăng nhập — Redirect về login nếu chưa đăng nhập
 * Đặt dòng này đầu mỗi trang cần bảo vệ
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Bắt buộc có quyền cụ thể — Redirect về 403 nếu không đủ quyền
 * @param string $role  VD: 'ROLE_ADMIN' hoặc 'ROLE_STAFF'
 */
function requireRole(string $role): void {
    requireLogin(); // Phải đăng nhập trước
    if ($_SESSION['role_name'] !== $role) {
        http_response_code(403);
        die('<h2>⛔ Bạn không có quyền truy cập trang này.</h2><a href="' . BASE_URL . '">← Về trang chủ</a>');
    }
}

/**
 * Kiểm tra xem user hiện tại có phải Admin không
 */
function isAdmin(): bool {
    return isLoggedIn() && $_SESSION['role_name'] === 'ROLE_ADMIN';
}

/**
 * Kiểm tra xem user hiện tại có phải Staff không
 */
function isStaff(): bool {
    return isLoggedIn() && in_array($_SESSION['role_name'], ['ROLE_ADMIN', 'ROLE_STAFF']);
}
?>
