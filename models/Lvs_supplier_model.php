<?php
/**
 * models/Lvs_supplier_model.php
 * Định danh Lvs_ — Wrapper cho supplier_model
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/supplier_model.php';

function Lvs_getAllSuppliers(): array                        { return getAllSuppliers(); }
function Lvs_getSupplierById(int $id): ?array               { return getSupplierById($id); }
function Lvs_addSupplier(array $data): array                { return addSupplier($data); }
function Lvs_updateSupplier(int $id, array $data): array    { return updateSupplier($id, $data); }
function Lvs_deleteSupplier(int $id): array                 { return deleteSupplier($id); }
?>
