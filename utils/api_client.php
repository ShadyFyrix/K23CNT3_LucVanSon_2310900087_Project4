<?php
/**
 * ApiClient.php — Lớp trung gian giao tiếp giữa PHP Frontend và FastAPI Backend
 * 
 * MỤC ĐÍCH: Tập trung toàn bộ logic gọi API vào 1 chỗ.
 * Nếu backend thay đổi endpoint hay format → chỉ sửa file này, KHÔNG sửa từng model.
 *
 * CÁCH DÙNG (trong các file model):
 *   require_once __DIR__ . '/../utils/api_client.php';
 *   $products = ApiClient::get('/products');
 *   $result   = ApiClient::post('/products', $data);
 */

class ApiClient {

    // =========================================================================
    //   CẤU HÌNH TRUNG TÂM — Chỉ sửa ở đây khi backend thay đổi địa chỉ
    // =========================================================================
    private static string $baseUrl = 'http://127.0.0.1:8000/api';
    private static int    $timeout = 10; // seconds

    // =========================================================================
    //   CÁC PHƯƠNG THỨC HTTP CÔNG KHAI
    // =========================================================================

    /**
     * Gọi GET request
     * @param  string $endpoint   VD: '/products' hoặc '/products/5'
     * @param  array  $params     Query parameters VD: ['search' => 'nendo', 'category_id' => 1]
     * @return array|null         Phần 'data' trong response, hoặc null nếu lỗi
     */
    public static function get(string $endpoint, array $params = []): array|null {
        $url = self::$baseUrl . $endpoint;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        $response = self::sendRequest('GET', $url);
        return self::extractData($response);
    }

    /**
     * Gọi POST request
     * @param  string $endpoint
     * @param  array  $body      Dữ liệu gửi lên (sẽ tự encode JSON)
     * @return array             Toàn bộ response ['status', 'data'/'message']
     */
    public static function post(string $endpoint, array $body = []): array {
        $url = self::$baseUrl . $endpoint;
        return self::sendRequest('POST', $url, $body);
    }

    /**
     * Gọi PUT request (cập nhật toàn bộ)
     */
    public static function put(string $endpoint, array $body = []): array {
        $url = self::$baseUrl . $endpoint;
        return self::sendRequest('PUT', $url, $body);
    }

    /**
     * Gọi PATCH request (cập nhật 1 trường: status, role, password...)
     */
    public static function patch(string $endpoint, array $body = []): array {
        $url = self::$baseUrl . $endpoint;
        return self::sendRequest('PATCH', $url, $body);
    }

    /**
     * Gọi DELETE request
     */
    public static function delete(string $endpoint): array {
        $url = self::$baseUrl . $endpoint;
        return self::sendRequest('DELETE', $url);
    }

    // =========================================================================
    //   UPLOAD FILE ẢNH (Multipart Form — gửi lên FastAPI rồi Cloudinary)
    // =========================================================================

    /**
     * Upload file ảnh qua API
     * @param  string $filePath   Đường dẫn file tạm ($_FILES['image']['tmp_name'])
     * @param  string $fileName   Tên file gốc ($_FILES['image']['name'])
     * @return string|null        URL ảnh trên Cloudinary sau khi upload thành công
     */
    public static function uploadImage(string $filePath, string $fileName): string|null {
        $cfile = new CURLFile($filePath, mime_content_type($filePath), $fileName);
        $ch = curl_init(self::$baseUrl . '/upload');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => ['file' => $cfile],
            CURLOPT_TIMEOUT        => 30,
        ]);
        $raw      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) return null;
        $result = json_decode($raw, true);
        return $result['url'] ?? null;
    }

    // =========================================================================
    //   HELPER: Kiểm tra response có thành công không
    // =========================================================================

    /**
     * Trả về true nếu response là thành công (status = "success")
     */
    public static function isSuccess(array $response): bool {
        return ($response['status'] ?? '') === 'success';
    }

    /**
     * Lấy thông báo lỗi từ response
     */
    public static function getError(array $response): string {
        return $response['detail'] ?? $response['message'] ?? 'Đã xảy ra lỗi không xác định.';
    }

    // =========================================================================
    //   CORE: Hàm gửi request thực tế (private)
    // =========================================================================

    private static function sendRequest(string $method, string $url, array $body = []): array {
        $ch = curl_init($url);
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_TIMEOUT        => self::$timeout,
            CURLOPT_SSL_VERIFYPEER => false,
        ];

        // Gắn body JSON nếu có dữ liệu
        if (!empty($body)) {
            $json = json_encode($body, JSON_UNESCAPED_UNICODE);
            $options[CURLOPT_POSTFIELDS] = $json;
            $options[CURLOPT_HTTPHEADER] = [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json),
            ];
        }

        curl_setopt_array($ch, $options);
        $raw      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        // Xử lý lỗi kết nối (API server chưa chạy, timeout...)
        if ($raw === false || !empty($curlErr)) {
            return [
                'status' => 'error',
                'detail' => 'Không thể kết nối đến API Server. Vui lòng kiểm tra FastAPI đã chạy chưa. (' . $curlErr . ')',
            ];
        }

        $decoded = json_decode($raw, true);

        // Nếu backend trả về lỗi HTTP (4xx, 5xx)
        if ($httpCode >= 400) {
            return [
                'status' => 'error',
                'detail' => $decoded['detail'] ?? "Lỗi từ server (HTTP {$httpCode})",
                'http_code' => $httpCode,
            ];
        }

        // Trả về nguyên vẹn response từ API
        return $decoded ?? ['status' => 'error', 'detail' => 'Phản hồi không hợp lệ từ API'];
    }

    /**
     * Trích xuất phần 'data' từ một response thành công
     * @return array|null  null nếu không có data hoặc lỗi
     */
    private static function extractData(array $response): array|null {
        if (self::isSuccess($response)) {
            return $response['data'] ?? [];
        }
        return null;
    }
}
?>
