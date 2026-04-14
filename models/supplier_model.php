<?php
/**
 * supplier_model.php
 * Refactored: Dùng ApiClient
 */
require_once __DIR__ . '/../utils/api_client.php';

function getAllSuppliers(): array {
    return ApiClient::get('/suppliers') ?? [];
}

function getSupplierById(int $id): array|null {
    return ApiClient::get("/suppliers/{$id}");
}

function addSupplier(array $data): array {
    return ApiClient::post('/suppliers', $data);
}

function updateSupplier(int $id, array $data): array {
    return ApiClient::put("/suppliers/{$id}", $data);
}

function deleteSupplier(int $id): array {
    return ApiClient::delete("/suppliers/{$id}");
}
?>