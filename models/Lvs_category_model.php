<?php
/**
 * models/Lvs_category_model.php
 * Định danh Lvs_ — Wrapper cho category_model
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/category_model.php';

function Lvs_getAllCategories(): array                       { return getAllCategories(); }
function Lvs_getCategoryById(int $id): ?array               { return getCategoryById($id); }
function Lvs_addCategory(array $data): array                { return addCategory($data); }
function Lvs_updateCategory(int $id, array $data): array    { return updateCategory($id, $data); }
function Lvs_deleteCategory(int $id): array                 { return deleteCategory($id); }
?>
