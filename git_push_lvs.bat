@echo off
echo ===== UmaCT Git Auto-Commit (Lvs_ prefix — ALL LAYERS) =====
cd /d "d:\K23CNT3-LucVanSon-2310900087\K23CNT3_LucVanSon_2310900087_Project4"

git add --all

git commit -m "feat(Lvs_): COMPLETE Lvs_ naming convention: Layer 1 through 7

STRATEGY: Wrapper/Adapter Pattern — zero conflict with teammates
All Lvs_ files wrap originals. Originals untouched for team use.

--- Layer 1: Utils Wrappers (3 files) ---
  utils/Lvs_api_client.php    - class Lvs_ApiClient extends ApiClient
  utils/Lvs_auth_helper.php   - Lvs_isLoggedIn/requireLogin/getCurrentUser/isAdmin
  utils/Lvs_format_helper.php - Lvs_formatPrice/Date/orderStatusBadge/renderFlash

--- Layer 2: Model Wrappers (11 files) ---
  models/Lvs_product/cart/order/category/supplier
  models/Lvs_review/favorite/voucher/article/auth/user

--- Layer 3: Page Includes (3 files) ---
  Lvs_pages/includes/Lvs_header.php
  Lvs_pages/includes/Lvs_footer.php
  Lvs_pages/includes/Lvs_product_card.php

--- Layer 4: Lvs_pages/ (5/5 files) ---
  Lvs_pages/Lvs_home.php     - hero, categories, featured, flash sale, news
  Lvs_pages/Lvs_shop.php     - filter sidebar + category pills + sort
  Lvs_pages/Lvs_cart.php     - cart CRUD with correct cart_id+product_id
  Lvs_pages/Lvs_checkout.php - order form with correct backend items format

--- Layer 5: Lvs_user/ (4 files) ---
  Lvs_user/Lvs_profile.php         - edit name/email/phone/address
  Lvs_user/Lvs_order_history.php   - list orders + cancel button
  Lvs_user/Lvs_favorites.php       - grid of fav products
  Lvs_user/Lvs_change_password.php - validate + change pw

--- Layer 6: Lvs_api_actions/ AJAX (7 files) ---
  Lvs_cart_add/remove/update/clear
  Lvs_favorite_toggle + Lvs_voucher_check + Lvs_order_cancel

--- Layer 7: auth/ (3 files) ---
  auth/Lvs_login.php    - POST /api/login
  auth/Lvs_register.php - POST /api/register + validation
  auth/Lvs_logout.php   - session destroy + redirect

--- BUG FIX: Backend API contract sync ---
  POST /api/cart/add             (not /cart)
  DELETE /api/cart/remove/{u}/{p}(not /cart/{id})
  POST /api/login                (not /auth/login)
  POST /api/register             (not /auth/register)
  POST /api/favorites/toggle     (toggle, not add/remove)
  GET  /api/users/{id}/favorites (not /favorites?user_id)
  GET  /api/users/{id}/orders    (not /orders?user_id)
  POST /api/orders items format  [{id,quantity,price}]"

git push origin main
echo.
echo ===== DONE — All Layers 1-7 pushed =====
pause
