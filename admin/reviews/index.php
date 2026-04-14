<?php
/**
 * admin/reviews/index.php — Quản lý đánh giá sản phẩm
 */
$pageTitle = 'Quản lý Đánh giá';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../..//../models/review_model.php';

// Xử lý xóa
if (isset($_GET['delete'])) {
    $id  = (int)$_GET['delete'];
    $res = deleteReview($id);
    setFlash(ApiClient::isSuccess($res) ? 'success' : 'error',
             ApiClient::isSuccess($res) ? 'Đã xóa đánh giá!' : ApiClient::getError($res));
    header('Location: ' . BASE_URL . '/admin/reviews/index.php');
    exit;
}

$reviews = getAllReviews();
?>

<div class="page-header">
    <h1>⭐ Quản lý Đánh giá</h1>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h2>Danh sách đánh giá (<?= count($reviews) ?>)</h2>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Người dùng</th>
                    <th>Sản phẩm</th>
                    <th>Đánh giá</th>
                    <th>Nội dung</th>
                    <th>Ngày</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($reviews)): ?>
                <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted)">
                    Chưa có đánh giá nào.
                </td></tr>
            <?php else: ?>
                <?php foreach ($reviews as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['full_name'] ?? $r['username'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['product_name'] ?? 'SP #' . $r['product_id']) ?></td>
                    <td><?= renderStars((int)$r['rating']) ?></td>
                    <td><?= htmlspecialchars(truncate($r['comment'] ?? '', 60)) ?></td>
                    <td><?= formatDateShort($r['created_at']) ?></td>
                    <td>
                        <a href="?delete=<?= $r['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Xóa đánh giá này?')">🗑 Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
