<?php
/**
 * utils/Lvs_auth_helper.php
 * Định danh Lvs_ — Wrapper cho auth_helper
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/auth_helper.php';

function Lvs_isLoggedIn(): bool             { return isLoggedIn(); }
function Lvs_isAdmin(): bool                { return isAdmin(); }
function Lvs_getCurrentUser(): ?array       { return getCurrentUser(); }
function Lvs_requireLogin(): void           { requireLogin(); }
function Lvs_requireRole(string $r): void   { requireRole($r); }
function Lvs_loginSession(array $d): void   { loginSession($d); }
?>
