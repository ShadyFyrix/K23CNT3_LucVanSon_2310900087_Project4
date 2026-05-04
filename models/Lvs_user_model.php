<?php
/**
 * models/Lvs_user_model.php
 * Định danh Lvs_ — Wrapper cho user_model
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/user_model.php';

function Lvs_getAllUsersAndRoles(): array                            { return getAllUsersAndRoles(); }
function Lvs_getUserDetail(int $id): ?array                         { return getUserDetail($id); }
function Lvs_updateUserStatus(int $id, string $status): array       { return updateUserStatus($id, $status); }
function Lvs_updateUserRole(int $id, int $roleId): array            { return updateUserRole($id, $roleId); }
function Lvs_updateUserProfile(int $id, array $data): array         { return updateUserProfile($id, $data); }
function Lvs_changeUserPassword(int $id, string $old, string $new): array { return changeUserPassword($id, $old, $new); }
?>
