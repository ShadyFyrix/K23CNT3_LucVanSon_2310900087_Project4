<?php
/**
 * product_model.php
 * Refactored: Dùng ApiClient — KHÔNG viết cURL trực tiếp nữa
 */
require_once __DIR__ . '/../utils/api_client.php';

function getAllProducts(array $filters = []): array {
    return ApiClient::get('/products', $filters) ?? [];
}

function getProductById(int $id): array|null {
    return ApiClient::get("/products/{$id}");
}

function addProduct(array $data): array {
    return ApiClient::post('/products', $data);
}

function updateProduct(int $id, array $data): array {
    return ApiClient::put("/products/{$id}", $data);
}

function deleteProduct(int $id): array {
    return ApiClient::delete("/products/{$id}");
}
?>