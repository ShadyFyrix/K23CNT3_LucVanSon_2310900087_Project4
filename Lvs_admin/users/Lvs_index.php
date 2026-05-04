<?php
/**
 * Lvs_admin/users/Lvs_index.php — Quản lý người dùng (Full CRUD)
 * Định danh Lvs_ | Tác giả: Lục Văn Sơn (2310900087)
 *
 * API mapping (main.py):
 *   GET    /api/users             → list {data:{users:[], roles:[]}}
 *   GET    /api/users/{id}        → detail
 *   POST   /api/register          → create (reuse register endpoint)
 *   PUT    /api/users/{id}/status → ban/unban
 *   PUT    /api/users/{id}/role   → change role
 */
$pageTitle = 'Quản lý Người dùng';
require_once __DIR__ . '/../includes/Lvs_header.php';
require_once __DIR__ . '/../../utils/api_client.php';
require_once __DIR__ . '/../../utils/Lvs_format_helper.php';

// Lấy danh sách users + roles từ /api/users (trả về data.users + data.roles)
$Lvs_apiData = ApiClient::get('/users');
$Lvs_users   = $Lvs_apiData['users'] ?? [];
$Lvs_roles   = $Lvs_apiData['roles'] ?? [['id'=>1,'role_name'=>'ROLE_ADMIN'],['id'=>2,'role_name'=>'ROLE_USER'],['id'=>3,'role_name'=>'ROLE_STAFF']];

// Xử lý tạo user mới (POST /api/register)
$Lvs_createError = '';
$Lvs_createOk    = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['Lvs_action'] ?? '') === 'create') {
    $Lvs_d = [
        'username'  => trim($_POST['username'] ?? ''),
        'password'  => trim($_POST['password'] ?? ''),
        'full_name' => trim($_POST['full_name'] ?? ''),
        'email'     => trim($_POST['email'] ?? ''),
    ];
    if (!$Lvs_d['username'] || !$Lvs_d['password'] || !$Lvs_d['email']) {
        $Lvs_createError = 'Username, password và email là bắt buộc.';
    } else {
        $Lvs_res = ApiClient::post('/register', $Lvs_d);
        if (ApiClient::isSuccess($Lvs_res)) {
            // Nếu role khác USER, update role sau khi tạo
            $Lvs_roleId = (int)($_POST['role_id'] ?? 2);
            if ($Lvs_roleId !== 2) {
                // Tìm user vừa tạo để lấy ID
                $Lvs_allAfter = ApiClient::get('/users');
                foreach (($Lvs_allAfter['users'] ?? []) as $Lvs_u) {
                    if ($Lvs_u['username'] === $Lvs_d['username']) {
                        ApiClient::put('/users/' . $Lvs_u['id'] . '/role', ['role_id' => $Lvs_roleId]);
                        break;
                    }
                }
            }
            Lvs_setFlash('success', '✅ Tạo tài khoản ' . htmlspecialchars($Lvs_d['username']) . ' thành công!');
            header('Location: ' . BASE_URL . '/Lvs_admin/users/Lvs_index.php'); exit;
        }
        $Lvs_createError = ApiClient::getError($Lvs_res);
    }
}
?>
<div class="data-card">
    <div class="data-card-header">
        <h2>👥 Người dùng (<?= count($Lvs_users) ?>)</h2>
        <button onclick="document.getElementById('Lvs_modalCreate').style.display='flex'"
                class="btn btn-primary btn-sm">+ Tạo tài khoản</button>
    </div>

    <?= Lvs_renderFlash() ?>

    <div class="table-responsive"><table>
        <thead><tr>
            <th>#ID</th><th>Tên đăng nhập</th><th>Họ tên</th><th>Email</th>
            <th>Role</th><th>Trạng thái</th><th>Thao tác</th>
        </tr></thead>
        <tbody>
        <?php if(empty($Lvs_users)): ?>
            <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">Không có người dùng</td></tr>
        <?php else: ?>
            <?php foreach($Lvs_users as $Lvs_u): ?>
            <tr id="Lvs_row_<?= $Lvs_u['id'] ?>">
                <td><strong>#<?= $Lvs_u['id'] ?></strong></td>
                <td><?= htmlspecialchars($Lvs_u['username']) ?></td>
                <td><?= htmlspecialchars($Lvs_u['full_name'] ?? '—') ?></td>
                <td style="font-size:.8rem"><?= htmlspecialchars($Lvs_u['email'] ?? '—') ?></td>
                <td>
                    <!-- Dropdown đổi role inline -->
                    <select onchange="Lvs_changeRole(<?= $Lvs_u['id'] ?>, this.value)"
                            style="font-size:.75rem;padding:3px 6px;background:var(--bg-glass);border:1px solid var(--border);border-radius:6px;color:var(--text);cursor:pointer">
                        <?php foreach($Lvs_roles as $Lvs_r): ?>
                            <option value="<?= $Lvs_r['id'] ?>"
                                <?= ($Lvs_r['role_name'] === ($Lvs_u['role_name'] ?? '')) ? 'selected' : '' ?>>
                                <?= $Lvs_r['role_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <?php if(($Lvs_u['status'] ?? '') === 'BANNED'): ?>
                        <span style="color:#f87171;font-size:.8rem">🚫 Bị khóa</span>
                    <?php else: ?>
                        <span style="color:#4ade80;font-size:.8rem">✓ Hoạt động</span>
                    <?php endif; ?>
                </td>
                <td style="white-space:nowrap;display:flex;gap:6px;align-items:center">
                    <!-- Xem chi tiết -->
                    <a href="<?= BASE_URL ?>/Lvs_admin/users/Lvs_detail.php?id=<?= $Lvs_u['id'] ?>"
                       class="btn btn-sm" style="background:rgba(99,102,241,.1);color:var(--accent);border:1px solid rgba(99,102,241,.2);font-size:.75rem">
                        👤 Chi tiết
                    </a>
                    <!-- Ban / Unban -->
                    <?php if(($Lvs_u['status'] ?? '') === 'BANNED'): ?>
                        <button onclick="Lvs_toggleBan(<?= $Lvs_u['id'] ?>, 'ACTIVE')"
                                class="btn btn-sm" style="background:rgba(34,197,94,.1);color:#4ade80;border:1px solid rgba(34,197,94,.2);font-size:.75rem">
                            Mở khóa
                        </button>
                    <?php else: ?>
                        <button onclick="Lvs_toggleBan(<?= $Lvs_u['id'] ?>, 'BANNED')"
                                class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2);font-size:.75rem">
                            Khóa
                        </button>
                    <?php endif; ?>
                    <!-- Xóa (không cho xóa chính mình) -->
                    <?php if($Lvs_u['id'] != ($_SESSION['user_id'] ?? 0)): ?>
                        <button onclick="Lvs_deleteUser(<?= $Lvs_u['id'] ?>, '<?= htmlspecialchars($Lvs_u['username']) ?>')"
                                class="btn btn-sm" style="background:rgba(239,68,68,.08);color:#f87171;border:1px solid rgba(239,68,68,.15);font-size:.75rem">
                            🗑
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table></div>
</div>

<!-- ===== MODAL: Tạo tài khoản mới ===== -->
<div id="Lvs_modalCreate" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:28px;width:100%;max-width:480px;position:relative">
        <h3 style="margin:0 0 20px;font-size:1rem">➕ Tạo tài khoản mới</h3>
        <button onclick="document.getElementById('Lvs_modalCreate').style.display='none'"
                style="position:absolute;top:16px;right:16px;background:none;border:none;color:var(--text-muted);font-size:1.2rem;cursor:pointer">✕</button>

        <?php if($Lvs_createError): ?>
            <div class="alert alert-error" style="margin-bottom:14px">⚠️ <?= htmlspecialchars($Lvs_createError) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="Lvs_action" value="create">
            <?php
            $Lvs_fields = [
                ['username','text','Tên đăng nhập *','vd: bokachan99'],
                ['full_name','text','Họ và tên','Nguyễn Văn A'],
                ['email','email','Email *','example@gmail.com'],
                ['password','password','Mật khẩu *','••••••'],
            ];
            foreach($Lvs_fields as [$Lvs_n,$Lvs_t,$Lvs_l,$Lvs_ph]):
            ?>
            <div style="margin-bottom:12px">
                <label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px"><?= $Lvs_l ?></label>
                <input type="<?= $Lvs_t ?>" name="<?= $Lvs_n ?>" placeholder="<?= $Lvs_ph ?>"
                       value="<?= $Lvs_t !== 'password' ? htmlspecialchars($_POST[$Lvs_n] ?? '') : '' ?>"
                       style="width:100%;padding:9px 12px;background:var(--bg-glass);border:1px solid var(--border);border-radius:8px;color:var(--text);font-size:.875rem">
            </div>
            <?php endforeach; ?>
            <div style="margin-bottom:16px">
                <label style="font-size:.8rem;color:var(--text-muted);display:block;margin-bottom:4px">Quyền</label>
                <select name="role_id" style="width:100%;padding:9px 12px;background:var(--bg-glass);border:1px solid var(--border);border-radius:8px;color:var(--text);font-size:.875rem">
                    <?php foreach($Lvs_roles as $Lvs_r): ?>
                        <option value="<?= $Lvs_r['id'] ?>"><?= $Lvs_r['role_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">✅ Tạo tài khoản</button>
        </form>
    </div>
</div>

<script>
// Toggle ban/unban
function Lvs_toggleBan(Lvs_uid, Lvs_status) {
    if (!confirm(Lvs_status === 'BANNED' ? 'Khóa tài khoản này?' : 'Mở khóa tài khoản này?')) return;
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_user_toggle_ban.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({user_id: Lvs_uid, status: Lvs_status})
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success') location.reload();
        else alert('Lỗi: ' + (d.detail||d.message||'Unknown'));
    });
}

// Đổi role inline
function Lvs_changeRole(Lvs_uid, Lvs_roleId) {
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_user_change_role.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({user_id: Lvs_uid, role_id: parseInt(Lvs_roleId)})
    }).then(r=>r.json()).then(d=>{
        if(d.status!=='success') { alert('Đổi quyền thất bại: ' + (d.detail||'')); location.reload(); }
    });
}

// Xóa user (không có API DELETE → dùng soft-delete qua ban, hoặc AJAX action riêng)
function Lvs_deleteUser(Lvs_uid, Lvs_uname) {
    if (!confirm('Xóa tài khoản "' + Lvs_uname + '"?\nHành động này không thể hoàn tác!')) return;
    fetch('<?= BASE_URL ?>/Lvs_api_actions/Lvs_user_delete.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({user_id: Lvs_uid})
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success') {
            const Lvs_row = document.getElementById('Lvs_row_' + Lvs_uid);
            if(Lvs_row) Lvs_row.remove();
        } else {
            alert('Xóa thất bại: ' + (d.detail||d.message||''));
        }
    });
}

// Tự mở modal nếu có lỗi create (POST vừa fail)
<?php if($Lvs_createError): ?>
document.getElementById('Lvs_modalCreate').style.display = 'flex';
<?php endif; ?>
</script>
<?php require_once __DIR__ . '/../includes/Lvs_footer.php'; ?>
