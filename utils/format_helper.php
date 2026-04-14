<?php
/**
 * format_helper.php — Các hàm format dữ liệu dùng chung cho toàn bộ view
 *
 * CÁCH DÙNG:
 *   require_once __DIR__ . '/../../utils/format_helper.php';
 *   echo formatPrice(1200000);       // → "1.200.000 ₫"
 *   echo formatDate('2026-04-14');   // → "14/04/2026"
 */

/**
 * Format tiền VNĐ
 * @param float $amount
 * @return string  VD: "1.200.000 ₫"
 */
function formatPrice(float $amount): string {
    return number_format($amount, 0, ',', '.') . ' ₫';
}

/**
 * Format ngày giờ dễ đọc
 * @param string $datetime  ISO string từ API: "2026-04-14T10:00:00"
 * @return string           "14/04/2026 10:00"
 */
function formatDate(string $datetime): string {
    if (empty($datetime)) return '—';
    $ts = strtotime($datetime);
    return date('d/m/Y H:i', $ts);
}

/**
 * Format ngày ngắn gọn
 * @return string  "14/04/2026"
 */
function formatDateShort(string $datetime): string {
    if (empty($datetime)) return '—';
    return date('d/m/Y', strtotime($datetime));
}

/**
 * Render badge trạng thái đơn hàng
 */
function orderStatusBadge(string $status): string {
    $map = [
        'PENDING'   => ['label' => 'Chờ xử lý',    'class' => 'badge-warning'],
        'PAID'      => ['label' => 'Đã thanh toán', 'class' => 'badge-info'],
        'SHIPPING'  => ['label' => 'Đang giao',     'class' => 'badge-primary'],
        'COMPLETED' => ['label' => 'Hoàn thành',    'class' => 'badge-success'],
        'CANCELLED' => ['label' => 'Đã hủy',        'class' => 'badge-danger'],
    ];
    $s = $map[$status] ?? ['label' => $status, 'class' => 'badge-secondary'];
    return "<span class=\"badge {$s['class']}\">{$s['label']}</span>";
}

/**
 * Render badge trạng thái tài khoản user
 */
function userStatusBadge(string $status): string {
    if ($status === 'ACTIVE') {
        return '<span class="badge badge-success">Hoạt động</span>';
    }
    return '<span class="badge badge-danger">Đã khóa</span>';
}

/**
 * Render sao đánh giá (rating)
 * @param int $rating  1–5
 */
function renderStars(int $rating): string {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $rating ? '★' : '☆';
    }
    return "<span class=\"stars\" title=\"{$rating}/5\">{$stars}</span>";
}

/**
 * Tên phương thức thanh toán
 */
function paymentMethodName(int $method): string {
    return match($method) {
        1 => 'COD (Tiền mặt)',
        2 => 'MoMo',
        3 => 'PayOS',
        4 => 'VNPay',
        default => 'Không xác định',
    };
}

/**
 * Tạo thông báo flash (lưu vào session, hiển thị 1 lần)
 * @param string $type  'success' | 'error' | 'warning' | 'info'
 */
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Lấy và xóa thông báo flash
 * @return array|null  ['type' => ..., 'message' => ...]
 */
function getFlash(): array|null {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Render thông báo flash thành HTML (pure CSS — không cần Bootstrap)
 */
function renderFlash(): string {
    $flash = getFlash();
    if (!$flash) return '';
    $type = htmlspecialchars($flash['type']);
    $msg  = htmlspecialchars($flash['message']);
    $icons = ['success' => '✅', 'error' => '⚠️', 'warning' => '⚡', 'info' => 'ℹ️'];
    $icon = $icons[$flash['type']] ?? '📢';
    return "<div class=\"alert alert-{$type}\" role=\"alert\" id=\"flashMsg\" style=\"position:relative\">
                <span>{$icon} {$msg}</span>
                <button type=\"button\" onclick=\"this.parentElement.remove()\" style=\"background:none;border:none;cursor:pointer;opacity:.5;font-size:1rem;position:absolute;right:12px;top:50%;transform:translateY(-50%)\">✕</button>
            </div>
            <script>setTimeout(()=>document.getElementById('flashMsg')?.remove(), 4000)</script>";
}


/**
 * Rút gọn văn bản dài
 */
function truncate(string $text, int $length = 80): string {
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '…';
}

/**
 * Tạo URL ảnh mặc định nếu không có ảnh
 */
function imgOrDefault(string|null $url, string $alt = ''): string {
    if (empty($url)) {
        $url = BASE_URL . '/assets/images/no-image.png';
    }
    $alt = htmlspecialchars($alt);
    return "<img src=\"{$url}\" alt=\"{$alt}\" loading=\"lazy\">";
}
?>
