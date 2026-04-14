<?php
/**
 * article_model.php
 * Refactored: Dùng ApiClient
 */
require_once __DIR__ . '/../utils/api_client.php';

function getAllArticles(): array {
    return ApiClient::get('/articles') ?? [];
}

function getArticleById(int $id): array|null {
    return ApiClient::get("/articles/{$id}");
}

function addArticle(array $data): array {
    return ApiClient::post('/articles', $data);
}

function updateArticle(int $id, array $data): array {
    return ApiClient::put("/articles/{$id}", $data);
}

function deleteArticle(int $id): array {
    return ApiClient::delete("/articles/{$id}");
}
?>