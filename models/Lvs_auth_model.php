<?php
/**
 * models/Lvs_auth_model.php
 * Định danh Lvs_ — Wrapper cho auth_model (đã FIXED endpoints)
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/auth_model.php';

function Lvs_loginUser(string $username, string $password): array   { return loginUser($username, $password); }
function Lvs_registerUser(array $data): array                       { return registerUser($data); }
?>
