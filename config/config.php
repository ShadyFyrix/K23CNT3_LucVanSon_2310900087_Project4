<?php
// ================================================================
// config.php — Cấu hình kết nối trung tâm
// PHP built-in server: php -S localhost:8080
// FastAPI backend:     uvicorn main:app --port 8000
// ================================================================

// URL gốc của Frontend PHP (PHP built-in server)
define('BASE_URL', 'http://localhost:8080');

// URL gốc của Backend FastAPI
define('API_URL', 'http://127.0.0.1:8000/api');

// Khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>