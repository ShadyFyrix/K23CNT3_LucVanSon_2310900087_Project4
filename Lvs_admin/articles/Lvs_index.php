<?php
$pageTitle = 'Quản lý Bài viết';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../models/Lvs_article_model.php';
require_once __DIR__ . '/../../utils/Lvs_format_helper.php';
require_once __DIR__ . '/../../utils/api_client.php';

$Lvs_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Lvs_action'])) {
    if ($_POST['Lvs_action'] === 'create') {
        $Lvs_res = ApiClient::post('/articles', [
            'title'   => trim($_POST['title'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
        ]);
        if (ApiClient::isSuccess($Lvs_res)) { Lvs_setFlash('success', 'Đăng bài viết thành công!'); header('Location: ' . BASE_URL . '/Lvs_admin/articles/Lvs_index.php'); exit; }
        $Lvs_error = ApiClient::getError($Lvs_res);
    } elseif ($_POST['Lvs_action'] === 'delete') {
        ApiClient::delete('/articles/' . (int)$_POST['id']);
        header('Location: ' . BASE_URL . '/Lvs_admin/articles/Lvs_index.php'); exit;
    }
}
$Lvs_articles = Lvs_getAllArticles();
?>
<div class="data-card" style="margin-bottom:20px">
    <div class="data-card-header"><h2>➕ Đăng bài viết mới</h2></div>
    <?php if($Lvs_error): ?><div class="alert alert-error"><?= htmlspecialchars($Lvs_error) ?></div><?php endif; ?>
    <form method="POST">
        <input type="hidden" name="Lvs_action" value="create">
        <div class="form-group"><label>Tiêu đề *</label><input type="text" name="title" required class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text)" placeholder="Tiêu đề bài viết..."></div>
        <div class="form-group"><label>Nội dung *</label>
            <textarea name="content" rows="6" required class="form-control" style="background:var(--bg-glass);border-color:var(--border);color:var(--text);resize:vertical" placeholder="Nội dung bài viết..."></textarea></div>
        <button type="submit" class="btn btn-primary btn-sm">📤 Đăng bài</button>
    </form>
</div>
<div class="data-card">
    <div class="data-card-header"><h2>📝 Bài viết (<?= count($Lvs_articles) ?>)</h2></div>
    <div class="table-responsive"><table>
        <thead><tr><th>#ID</th><th>Tiêu đề</th><th>Tác giả</th><th>Ngày đăng</th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php foreach($Lvs_articles as $Lvs_a): ?>
        <tr>
            <td>#<?= $Lvs_a['id'] ?></td>
            <td><?= htmlspecialchars($Lvs_a['title']) ?></td>
            <td><?= htmlspecialchars($Lvs_a['author_name'] ?? $Lvs_a['username'] ?? '—') ?></td>
            <td style="font-size:.8rem"><?= Lvs_formatDateShort($Lvs_a['created_at'] ?? '') ?></td>
            <td><form method="POST" style="display:inline" onsubmit="return confirm('Xóa bài viết?')">
                <input type="hidden" name="Lvs_action" value="delete"><input type="hidden" name="id" value="<?= $Lvs_a['id'] ?>">
                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2)">🗑 Xóa</button>
            </form></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
</div>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
