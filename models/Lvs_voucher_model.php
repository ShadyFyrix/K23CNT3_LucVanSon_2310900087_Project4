<?php
/**
 * models/Lvs_voucher_model.php
 * Định danh Lvs_ — Wrapper cho voucher_model
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/voucher_model.php';

function Lvs_getAllVouchers(): array                                     { return getAllVouchers(); }
function Lvs_getVoucherById(int $id): ?array                            { return getVoucherById($id); }
function Lvs_addVoucher(array $data): array                             { return addVoucher($data); }
function Lvs_updateVoucher(int $id, array $data): array                 { return updateVoucher($id, $data); }
function Lvs_deleteVoucher(int $id): array                              { return deleteVoucher($id); }
function Lvs_checkVoucher(string $code, float $val): ?array             { return checkVoucher($code, $val); }
?>
