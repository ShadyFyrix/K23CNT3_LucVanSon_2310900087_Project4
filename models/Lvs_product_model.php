<?php
/**
 * models/Lvs_product_model.php
 * Định danh Lvs_ — Wrapper cho product_model
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/product_model.php';

function Lvs_getAllProducts(array $filters = []): array      { return getAllProducts($filters); }
function Lvs_getProductById(int $id): ?array                { return getProductById($id); }
function Lvs_addProduct(array $data): array                 { return addProduct($data); }
function Lvs_updateProduct(int $id, array $data): array     { return updateProduct($id, $data); }
function Lvs_deleteProduct(int $id): array                  { return deleteProduct($id); }
?>
