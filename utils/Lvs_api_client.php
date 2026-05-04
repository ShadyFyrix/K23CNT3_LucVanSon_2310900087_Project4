<?php
/**
 * utils/Lvs_api_client.php
 * Định danh Lvs_ — Wrapper cho ApiClient
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/api_client.php';

class Lvs_ApiClient extends ApiClient {
    // Kế thừa toàn bộ phương thức từ ApiClient:
    // Lvs_ApiClient::get(), Lvs_ApiClient::post(),
    // Lvs_ApiClient::put(), Lvs_ApiClient::delete(),
    // Lvs_ApiClient::isSuccess(), Lvs_ApiClient::getError()
    // Không cần viết lại — PHP kế thừa tự động.
}
?>
