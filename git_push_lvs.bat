@echo off
echo ===== UmaCT Git Auto-Commit (Lvs_ prefix — ALL LAYERS) =====
cd /d "d:\K23CNT3-LucVanSon-2310900087\K23CNT3_LucVanSon_2310900087_Project4"

git add --all

git commit -m "fix(Lvs_): bugfix session, cart table, checkout flow, image rendering

--- BUG 1: Missing cart table in DB ---
  database/project4.sql    - ADD CREATE TABLE cart (UNIQUE KEY required for ON DUPLICATE KEY UPDATE)
  database/fix_missing_tables.sql  - standalone fix script

--- BUG 2: POST /api/orders missing endpoint ---
  Project4-UmaCT-main/uma_api/main.py
    + OrderItem / OrderCreate Pydantic models
    + POST /api/orders endpoint (transaction: orders + order_items)
    + except block on cart/add endpoint (was bare try/finally)

--- BUG 3: Checkout form POST not detected ---
  Lvs_pages/Lvs_checkout.php
    + <input type='hidden' name='Lvs_do_checkout'> — survives JS button.disabled
    + PHP check $_POST['Lvs_do_checkout'] instead of disabled button value
    + null guard for cartItems before array_map
    + sticky error banner (position:sticky) always visible
    + error_log debug lines for API call tracing

--- BUG 4: Product detail wrong image ---
  Lvs_pages/Lvs_product_detail.php
    + decode JSON 'images' field from GET /api/products/{id}
    + render all images as clickable thumbnails gallery

--- BUG 5: Old pages/cart.php crashing ---
  pages/cart.php  - redirect 301 to Lvs_pages/Lvs_cart.php
  models/Lvs_cart_model.php - null guard on Lvs_getCart / Lvs_calcCartTotal

--- BUG 6: Cart add silent error ---
  Lvs_api_actions/Lvs_cart_add.php - normalize detail->message field
  Lvs_pages/Lvs_shop.php           - alert shows d.message||d.detail||fallback
  Lvs_pages/Lvs_product_detail.php - same alert fix + catch(err) block

--- BUG 7: User CRUD admin ---
  Lvs_admin/users/Lvs_index.php     - full CRUD + modal + inline role + ban
  Lvs_admin/users/Lvs_detail.php    - profile + order history
  Lvs_api_actions/Lvs_user_change_role.php
  Lvs_api_actions/Lvs_user_delete.php"

git push origin main
echo.
echo ===== DONE — All Layers 1-7 pushed =====
pause
