<?php
/**
 * utils/Lvs_format_helper.php
 * Định danh Lvs_ — Wrapper cho format_helper
 * Tác giả phần: Lục Văn Sơn (2310900087)
 */
require_once __DIR__ . '/format_helper.php';

function Lvs_formatPrice(float $amount): string             { return formatPrice($amount); }
function Lvs_formatDate(string $dt): string                 { return formatDate($dt); }
function Lvs_formatDateShort(string $dt): string            { return formatDateShort($dt); }
function Lvs_orderStatusBadge(string $status): string       { return orderStatusBadge($status); }
function Lvs_userStatusBadge(string $status): string        { return userStatusBadge($status); }
function Lvs_renderStars(int $rating): string               { return renderStars($rating); }
function Lvs_paymentMethodName(int $method): string         { return paymentMethodName($method); }
function Lvs_setFlash(string $type, string $msg): void      { setFlash($type, $msg); }
function Lvs_getFlash(): ?array                             { return getFlash(); }
function Lvs_renderFlash(): string                          { return renderFlash(); }
function Lvs_truncate(string $text, int $len = 80): string  { return truncate($text, $len); }
function Lvs_imgOrDefault(?string $url, string $alt = ''): string { return imgOrDefault($url, $alt); }
?>
