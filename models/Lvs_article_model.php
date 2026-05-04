<?php
/**
 * models/Lvs_article_model.php
 * Định danh Lvs_ — Wrapper cho article_model
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/article_model.php';

function Lvs_getAllArticles(): array                         { return getAllArticles(); }
function Lvs_getArticleById(int $id): ?array                { return getArticleById($id); }
function Lvs_addArticle(array $data): array                 { return addArticle($data); }
function Lvs_updateArticle(int $id, array $data): array     { return updateArticle($id, $data); }
function Lvs_deleteArticle(int $id): array                  { return deleteArticle($id); }
?>
