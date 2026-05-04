<?php
/**
 * setup_assets.php — Tự tạo assets/images/ và file no-image.png
 * Chạy 1 lần: http://localhost:8080/setup_assets.php
 * Xóa file này sau khi chạy xong!
 */
$Lvs_imgDir = __DIR__ . '/assets/images';

// Tạo thư mục nếu chưa có
if (!is_dir($Lvs_imgDir)) {
    mkdir($Lvs_imgDir, 0775, true);
    echo "✅ Đã tạo thư mục: assets/images/<br>";
} else {
    echo "ℹ️ Thư mục assets/images/ đã tồn tại.<br>";
}

// Tạo no-image.png bằng GD
$Lvs_file = $Lvs_imgDir . '/no-image.png';
if (!file_exists($Lvs_file)) {
    if (function_exists('imagecreate')) {
        $Lvs_img = imagecreatetruecolor(400, 400);
        $Lvs_bg  = imagecolorallocate($Lvs_img, 26, 26, 46);     // #1a1a2e
        $Lvs_fg  = imagecolorallocate($Lvs_img, 139, 92, 246);   // #8b5cf6 purple
        $Lvs_txt = imagecolorallocate($Lvs_img, 180, 180, 200);  // muted white
        imagefill($Lvs_img, 0, 0, $Lvs_bg);

        // Vẽ icon hộp đơn giản
        imagefilledrectangle($Lvs_img, 140, 120, 260, 240, $Lvs_fg);
        imagefilledrectangle($Lvs_img, 148, 128, 252, 232, $Lvs_bg);
        imagefilledrectangle($Lvs_img, 155, 135, 245, 225, $Lvs_fg);
        imagefilledrectangle($Lvs_img, 162, 142, 238, 218, $Lvs_bg);

        // Text
        imagestring($Lvs_img, 4, 148, 270, 'No Image', $Lvs_txt);

        imagepng($Lvs_img, $Lvs_file);
        imagedestroy($Lvs_img);
        echo "✅ Đã tạo: assets/images/no-image.png (via GD)<br>";
    } else {
        // Fallback: tạo PNG tối thiểu 1x1px bằng binary
        $Lvs_minPng = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        );
        file_put_contents($Lvs_file, $Lvs_minPng);
        echo "✅ Đã tạo: assets/images/no-image.png (minimal fallback, GD không có)<br>";
    }
} else {
    echo "ℹ️ no-image.png đã tồn tại.<br>";
}

// Tạo thêm favicon nếu chưa có
echo "<br><strong>Setup hoàn tất!</strong> Hãy xóa file setup_assets.php sau khi chạy.";
echo "<br><a href='/Lvs_pages/Lvs_shop.php'>→ Vào trang shop</a>";
?>
