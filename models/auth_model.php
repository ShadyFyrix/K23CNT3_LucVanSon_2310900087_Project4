<?php
/**
 * auth_model.php — Xử lý Đăng nhập / Đăng ký
 */
require_once __DIR__ . '/../utils/api_client.php';

function loginUser(string $username, string $password): array {
    return ApiClient::post('/auth/login', [
        'username' => $username,
        'password' => $password,
    ]);
}

function registerUser(array $data): array {
    // $data: username, password, full_name, email, phone
    return ApiClient::post('/auth/register', $data);
}
?>
