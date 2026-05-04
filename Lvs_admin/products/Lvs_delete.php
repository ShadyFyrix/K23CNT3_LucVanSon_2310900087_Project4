<?php
/**
 * Lvs_admin/products/Lvs_delete.php — Xóa sản phẩm (action only)
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_product_model.php';

$Lvs_id = (int)($_GET['id'] ?? 0);
if ($Lvs_id) {
    $Lvs_res = Lvs_deleteProduct($Lvs_id);
    if (ApiClient::isSuccess($Lvs_res)) {
        Lvs_setFlash('success', '🗑 Đã xóa sản phẩm #' . $Lvs_id);
    } else {
        Lvs_setFlash('error', 'Xóa thất bại: ' . ApiClient::getError($Lvs_res));
    }
}
header('Location: ' . BASE_URL . '/Lvs_admin/products/Lvs_index.php'); exit;
