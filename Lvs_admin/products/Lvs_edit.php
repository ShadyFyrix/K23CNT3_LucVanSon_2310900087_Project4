<?php
/**
 * Lvs_admin/products/Lvs_edit.php — Chỉnh sửa sản phẩm
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 * 
 * Ảnh: Backend dùng Cloudinary. Có 2 cách:
 *   1. Upload file → API /api/upload → Cloudinary → trả URL → lưu vào product_images
 *   2. Paste URL trực tiếp (từ Cloudinary, Google Images, v.v.)
 */
$pageTitle = 'Sửa sản phẩm';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_category_model.php';
require_once __DIR__ . '/../../models/Lvs_supplier_model.php';
require_once __DIR__ . '/../../models/Lvs_product_model.php';
require_once __DIR__ . '/../../utils/api_client.php';

$Lvs_id = (int)($_GET['id'] ?? 0);
if (!$Lvs_id) {
    Lvs_setFlash('error', 'ID sản phẩm không hợp lệ.');
    header('Location: ' . BASE_URL . '/Lvs_admin/products/Lvs_index.php'); exit;
}

$Lvs_product    = Lvs_getProductById($Lvs_id);
if (!$Lvs_product) {
    Lvs_setFlash('error', 'Không tìm thấy sản phẩm #' . $Lvs_id);
    header('Location: ' . BASE_URL . '/Lvs_admin/products/Lvs_index.php'); exit;
}

// Lấy danh sách ảnh hiện tại từ product_images (qua field 'images' JSON trong response)
$Lvs_currentImages = [];
if (!empty($Lvs_product['images'])) {
    $Lvs_decoded = json_decode($Lvs_product['images'], true);
    if (is_array($Lvs_decoded)) $Lvs_currentImages = $Lvs_decoded;
} elseif (!empty($Lvs_product['main_image'])) {
    $Lvs_currentImages = [$Lvs_product['main_image']];
}

$Lvs_categories = Lvs_getAllCategories();
$Lvs_suppliers  = Lvs_getAllSuppliers();
$Lvs_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý upload ảnh mới (nếu có file được chọn)
    $Lvs_imageUrls = $Lvs_currentImages; // giữ ảnh cũ mặc định

    // Thêm URL ảnh mới từ input text
    $Lvs_newUrl = trim($_POST['new_image_url'] ?? '');
    if (!empty($Lvs_newUrl)) {
        $Lvs_imageUrls[] = $Lvs_newUrl;
    }

    // Upload file ảnh qua API /api/upload → Cloudinary
    if (!empty($_FILES['image_file']['tmp_name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $Lvs_uploadRes = ApiClient::uploadImage(
            $_FILES['image_file']['tmp_name'],
            $_FILES['image_file']['name']
        );
        if ($Lvs_uploadRes) {
            $Lvs_imageUrls[] = $Lvs_uploadRes;
        } else {
            $Lvs_error = 'Upload ảnh thất bại. Kiểm tra lại kết nối Cloudinary.';
        }
    }

    // Xóa ảnh được chọn xóa
    $Lvs_removeIdx = (int)($_POST['remove_img_idx'] ?? -1);
    if ($Lvs_removeIdx >= 0 && isset($Lvs_imageUrls[$Lvs_removeIdx])) {
        array_splice($Lvs_imageUrls, $Lvs_removeIdx, 1);
    }

    if (empty($Lvs_error)) {
        $Lvs_data = [
            'name'           => trim($_POST['name'] ?? ''),
            'price'          => (float)($_POST['price'] ?? 0),
            'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
            'category_id'    => (int)($_POST['category_id'] ?? 0),
            'supplier_id'    => (int)($_POST['supplier_id'] ?? 0),
            'description'    => trim($_POST['description'] ?? ''),
            'is_active'      => isset($_POST['is_active']) ? true : false,
            'images'         => json_encode(array_values($Lvs_imageUrls)),
        ];
        if (empty($Lvs_data['name']) || $Lvs_data['price'] <= 0) {
            $Lvs_error = 'Tên và giá sản phẩm là bắt buộc.';
        } else {
            $Lvs_res = Lvs_updateProduct($Lvs_id, $Lvs_data);
            if (ApiClient::isSuccess($Lvs_res)) {
                Lvs_setFlash('success', '✅ Cập nhật sản phẩm #' . $Lvs_id . ' thành công!');
                header('Location: ' . BASE_URL . '/Lvs_admin/products/Lvs_index.php'); exit;
            }
            $Lvs_error = ApiClient::getError($Lvs_res);
        }
    }
    // Reload ảnh mới sau khi POST
    $Lvs_currentImages = $Lvs_imageUrls;
}
?>
<div class="data-card">
    <div class="data-card-header">
        <h2>✏️ Sửa sản phẩm #<?= $Lvs_id ?></h2>
        <a href="<?= BASE_URL ?>/Lvs_admin/products/Lvs_index.php" class="btn btn-secondary btn-sm">← Quay lại</a>
    </div>

    <?php if($Lvs_error): ?>
        <div class="alert alert-error" style="margin-bottom:16px">⚠️ <?= htmlspecialchars($Lvs_error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="max-width:760px">
        <!-- Thông tin cơ bản -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div class="form-group">
                <label style="font-size:.82rem;color:var(--text-muted);display:block;margin-bottom:4px">Tên sản phẩm *</label>
                <input type="text" name="name" required
                       value="<?= htmlspecialchars($Lvs_product['name']) ?>"
                       class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)">
            </div>
            <div class="form-group">
                <label style="font-size:.82rem;color:var(--text-muted);display:block;margin-bottom:4px">Giá (₫) *</label>
                <input type="number" name="price" required min="0"
                       value="<?= $Lvs_product['price'] ?>"
                       class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)">
            </div>
            <div class="form-group">
                <label style="font-size:.82rem;color:var(--text-muted);display:block;margin-bottom:4px">Tồn kho</label>
                <input type="number" name="stock_quantity" min="0"
                       value="<?= $Lvs_product['stock_quantity'] ?? 0 ?>"
                       class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)">
            </div>
            <div class="form-group">
                <label style="font-size:.82rem;color:var(--text-muted);display:block;margin-bottom:4px">Trạng thái</label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:10px">
                    <input type="checkbox" name="is_active" value="1" <?= ($Lvs_product['is_active'] ?? 1) ? 'checked' : '' ?>>
                    <span style="font-size:.875rem">Đang bán</span>
                </label>
            </div>
            <div class="form-group">
                <label style="font-size:.82rem;color:var(--text-muted);display:block;margin-bottom:4px">Danh mục</label>
                <select name="category_id" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)">
                    <option value="">-- Chọn --</option>
                    <?php foreach($Lvs_categories as $Lvs_c): ?>
                        <option value="<?= $Lvs_c['id'] ?>" <?= ($Lvs_c['id'] == ($Lvs_product['category_id'] ?? 0)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($Lvs_c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label style="font-size:.82rem;color:var(--text-muted);display:block;margin-bottom:4px">Nhà cung cấp</label>
                <select name="supplier_id" class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)">
                    <option value="">-- Chọn --</option>
                    <?php foreach($Lvs_suppliers as $Lvs_s): ?>
                        <option value="<?= $Lvs_s['id'] ?>" <?= ($Lvs_s['id'] == ($Lvs_product['supplier_id'] ?? 0)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($Lvs_s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom:16px">
            <label style="font-size:.82rem;color:var(--text-muted);display:block;margin-bottom:4px">Mô tả</label>
            <textarea name="description" rows="4" class="form-control"
                      style="background:var(--bg-glass);border-color:var(--border);color:var(--text);resize:vertical"><?= htmlspecialchars($Lvs_product['description'] ?? '') ?></textarea>
        </div>

        <!-- ===== KHU VỰC ẢNH ===== -->
        <div style="background:var(--bg-glass);border:1px solid var(--border);border-radius:12px;padding:20px;margin-bottom:20px">
            <h3 style="font-size:.9rem;font-weight:700;margin-bottom:14px">🖼️ Quản lý ảnh sản phẩm</h3>

            <!-- Ảnh hiện tại -->
            <?php if(!empty($Lvs_currentImages)): ?>
            <div style="margin-bottom:16px">
                <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:8px">Ảnh hiện tại (click ✕ để xóa):</div>
                <div style="display:flex;flex-wrap:wrap;gap:10px">
                    <?php foreach($Lvs_currentImages as $Lvs_imgIdx => $Lvs_imgUrl): ?>
                    <div style="position:relative;display:inline-block">
                        <img src="<?= htmlspecialchars($Lvs_imgUrl) ?>" alt=""
                             style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid var(--border)"
                             onerror="this.style.background='#333';this.src='<?= BASE_URL ?>/assets/images/no-image.png'">
                        <button type="submit" name="remove_img_idx" value="<?= $Lvs_imgIdx ?>"
                                style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:#ef4444;color:#fff;border:none;cursor:pointer;font-size:.7rem;line-height:1;display:flex;align-items:center;justify-content:center"
                                title="Xóa ảnh này">✕</button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div style="font-size:.8rem;color:var(--text-dim);margin-bottom:12px">Sản phẩm chưa có ảnh nào.</div>
            <?php endif; ?>

            <!-- Thêm ảnh mới -->
            <div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:flex-end;margin-bottom:10px">
                <div>
                    <label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">
                        📎 Upload file ảnh từ máy → tự động lên Cloudinary
                    </label>
                    <input type="file" name="image_file" accept="image/*"
                           style="width:100%;background:var(--bg-glass);border:1px solid var(--border);border-radius:8px;padding:8px;color:var(--text);font-size:.85rem">
                </div>
                <button type="submit" class="btn btn-secondary btn-sm" style="white-space:nowrap">☁️ Upload</button>
            </div>

            <div>
                <label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">
                    🔗 Hoặc paste URL ảnh trực tiếp (Cloudinary, Google Images, v.v.)
                </label>
                <div style="display:grid;grid-template-columns:1fr auto;gap:10px">
                    <input type="text" name="new_image_url" placeholder="https://res.cloudinary.com/..."
                           style="background:var(--bg-glass);border:1px solid var(--border);border-radius:8px;padding:8px 12px;color:var(--text);font-size:.85rem">
                    <button type="submit" class="btn btn-secondary btn-sm">+ Thêm URL</button>
                </div>
            </div>

            <div style="margin-top:10px;padding:10px;background:rgba(99,102,241,.06);border-radius:8px;font-size:.75rem;color:var(--text-muted);line-height:1.6">
                💡 <strong>Cách lấy ảnh:</strong> Dùng Google Images → Chuột phải → "Copy image address" → paste vào ô URL ảnh.<br>
                Hoặc upload file từ máy → hệ thống tự gửi lên Cloudinary (cần FastAPI đang chạy).
            </div>
        </div>

        <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
