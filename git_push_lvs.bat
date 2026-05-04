@echo off
echo ===== UmaCT Git Auto-Commit (Lvs_ prefix work) =====
cd /d "d:\K23CNT3-LucVanSon-2310900087\K23CNT3_LucVanSon_2310900087_Project4"

git add --all

git commit -m "feat(Lvs_): Layer 1-6 complete — utils, models, pages, AJAX actions

STRATEGY: Zero-conflict team naming — Wrapper/Adapter Pattern
Lvs_ files wrap originals, originals untouched for teammates

Layer 1 — Utils Wrappers (3 files):
  + utils/Lvs_api_client.php    class Lvs_ApiClient extends ApiClient
  + utils/Lvs_auth_helper.php   Lvs_isLoggedIn/requireLogin/isAdmin/getCurrentUser
  + utils/Lvs_format_helper.php Lvs_formatPrice/Date/orderStatusBadge/renderFlash

Layer 2 — Model Wrappers (11 files):
  + models/Lvs_product/cart/order/category/supplier/review/favorite/voucher/article/auth/user

Layer 3 — Lvs_pages/includes (3 files):
  + Lvs_header.php Lvs_footer.php Lvs_product_card.php

Layer 4 — Lvs_pages/ (3/7 files):
  + Lvs_home.php Lvs_shop.php Lvs_cart.php

Layer 6 — Lvs_api_actions/ (7 files):
  + Lvs_cart_add/remove/update/clear
  + Lvs_favorite_toggle Lvs_voucher_check Lvs_order_cancel

BUG FIX — Backend API sync (checked Project4-UmaCT-main/uma_api/main.py):
  * POST /cart/add + DELETE /cart/remove/{uid}/{pid}
  * POST /login (NOT /auth/login) + POST /register
  * GET /users/{id}/favorites + POST /favorites/toggle
  * GET /users/{id}/orders (NOT /orders?user_id=)"

git push origin main
echo.
echo ===== DONE — Layer 1-6 pushed to remote =====
pause
