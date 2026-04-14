<?php
/**
 * user_model.php
 * Refactored: Dùng ApiClient
 */
require_once __DIR__ . '/../utils/api_client.php';

function getAllUsersAndRoles(): array {
    return ApiClient::get('/users') ?? ['users' => [], 'roles' => []];
}

function getUserDetail(int $id): array|null {
    return ApiClient::get("/users/{$id}");
}

function updateUserStatus(int $id, string $status): array {
    return ApiClient::put("/users/{$id}/status", ['status' => $status]);
}

function updateUserRole(int $id, int $roleId): array {
    return ApiClient::put("/users/{$id}/role", ['role_id' => $roleId]);
}

function updateUserProfile(int $id, array $data): array {
    return ApiClient::put("/users/{$id}/profile", $data);
}

function changeUserPassword(int $id, string $oldPwd, string $newPwd): array {
    return ApiClient::put("/users/{$id}/password", [
        'old_password' => $oldPwd,
        'new_password' => $newPwd,
    ]);
}
?>