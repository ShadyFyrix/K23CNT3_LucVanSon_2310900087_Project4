<?php
$pageTitle = 'Quản lý Đánh giá';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_review_model.php';
require_once __DIR__ . '/../../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../../utils/api_client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Lvs_review_id'])) {
    $Lvs_res = ApiClient::delete('/reviews/' . (int)$_POST['Lvs_review_id']);
    if (ApiClient::isSuccess($Lvs_res)) { Lvs_setFlash('success', 'Đã xóa đánh giá!'); header('Location: ' . BASE_URL . '/Lvs_admin/reviews/Lvs_index.php'); exit; }
}

$Lvs_reviews = Lvs_getAllReviews();
?>
<div class="data-card">
    <div class="data-card-header"><h2>⭐ Đánh giá (<?= count($Lvs_reviews) ?>)</h2></div>
    <div class="table-responsive"><table>
        <thead><tr><th>#ID</th><th>Sản phẩm</th><th>Người dùng</th><th>Sao</th><th>Nội dung</th><th>Ngày</th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php if(empty($Lvs_reviews)): ?>
            <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">Chưa có đánh giá</td></tr>
        <?php else: ?>
            <?php foreach($Lvs_reviews as $Lvs_rv): ?>
            <tr>
                <td>#<?= $Lvs_rv['id'] ?></td>
                <td><?= htmlspecialchars($Lvs_rv['product_name'] ?? '#'.$Lvs_rv['product_id']) ?></td>
                <td><?= htmlspecialchars($Lvs_rv['username'] ?? '—') ?></td>
                <td style="color:#f59e0b"><?= str_repeat('★', $Lvs_rv['rating']) . str_repeat('☆', 5 - $Lvs_rv['rating']) ?></td>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($Lvs_rv['comment'] ?? '') ?></td>
                <td style="font-size:.78rem;color:var(--text-muted)"><?= Lvs_formatDateShort($Lvs_rv['created_at'] ?? '') ?></td>
                <td><form method="POST" style="display:inline" onsubmit="return confirm('Xóa đánh giá này?')">
                    <input type="hidden" name="Lvs_review_id" value="<?= $Lvs_rv['id'] ?>">
                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2)">🗑 Xóa</button>
                </form></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table></div>
</div>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
