<?php
/**
 * category_model.php
 * Refactored: Dùng ApiClient
 */
require_once __DIR__ . '/../utils/api_client.php';

function getAllCategories(): array {
    return ApiClient::get('/categories') ?? [];
}

function getCategoryById(int $id): array|null {
    return ApiClient::get("/categories/{$id}");
}

function addCategory(array $data): array {
    return ApiClient::post('/categories', $data);
}

function updateCategory(int $id, array $data): array {
    return ApiClient::put("/categories/{$id}", $data);
}

function deleteCategory(int $id): array {
    return ApiClient::delete("/categories/{$id}");
}
?>