<?php
/**
 * models/auth_model.php — Đăng nhập / Đăng ký
 * FIXED: Sync với backend endpoints thực tế (Project4-UmaCT-main/uma_api/main.py)
 *   POST /api/login     body: {username, password}
 *   POST /api/register  body: {username, password, full_name, email}
 *
 * Response login: {"status":"success","data": {id, username, full_name, role_name, ...}}
 */
require_once __DIR__ . '/../utils/api_client.php';

function loginUser(string $username, string $password): array {
    return ApiClient::post('/login', [
        'username' => $username,
        'password' => $password,
    ]);
}

function registerUser(array $data): array {
    // $data phải có: username, password, full_name, email
    return ApiClient::post('/register', $data);
}
?>
