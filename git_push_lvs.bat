@echo off
echo ===== UmaCT Git Auto-Commit (Lvs_ prefix work) =====
cd /d "d:\K23CNT3-LucVanSon-2310900087\K23CNT3_LucVanSon_2310900087_Project4"

git add --all

git commit -m "feat(Lvs_): Apply Lvs_ naming convention — Wrapper/Adapter Pattern

=== STRATEGY: Zero-conflict team naming ===
Wrapper pattern: Lvs_ files wrap originals, originals untouched for teammates

=== Layer 1 — Utils Wrappers (3 files) ===
+ utils/Lvs_api_client.php    : class Lvs_ApiClient extends ApiClient
+ utils/Lvs_auth_helper.php   : Lvs_isLoggedIn/requireLogin/isAdmin/getCurrentUser
+ utils/Lvs_format_helper.php : Lvs_formatPrice/Date/orderStatusBadge/renderFlash

=== Layer 2 — Model Wrappers (11 files) ===
+ models/Lvs_product_model.php  + models/Lvs_cart_model.php
+ models/Lvs_order_model.php    + models/Lvs_category_model.php
+ models/Lvs_supplier_model.php + models/Lvs_review_model.php
+ models/Lvs_favorite_model.php + models/Lvs_voucher_model.php
+ models/Lvs_article_model.php  + models/Lvs_auth_model.php
+ models/Lvs_user_model.php

=== Layer 3 — Pages Includes (3 files) ===
+ Lvs_pages/includes/Lvs_header.php       : Navbar dung Lvs_ wrappers
+ Lvs_pages/includes/Lvs_footer.php       : Footer dung Lvs_isLoggedIn
+ Lvs_pages/includes/Lvs_product_card.php : Card voi Lvs_ prefix + fix main_image

=== Layer 4 — Lvs_pages/ (1/7) ===
+ Lvs_pages/Lvs_home.php : Trang chu, moi bien $Lvs_*, moi JS Lvs_*, moi ID Lvs_*

=== BUG FIX — Backend API contract sync (da check Project4-UmaCT-main) ===
* models/cart_model.php     : POST /cart/add + DELETE /cart/remove/{uid}/{pid}
* models/auth_model.php     : POST /login (khong phai /auth/login)
* models/favorite_model.php : GET /users/{id}/favorites + POST /favorites/toggle
* models/order_model.php    : GET /users/{id}/orders (khong phai /orders?user_id)"

git push origin main
echo ===== DONE =====
pause
