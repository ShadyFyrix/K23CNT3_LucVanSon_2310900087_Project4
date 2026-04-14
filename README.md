# 🐎 UmaCT Shop — Hệ thống bán mô hình & đồ trang trí

> **Project 4 — K23CNT3 | Lục Văn Sơn — 2310900087**

---

## 📋 Tổng quan hệ thống

**UmaCT Shop** là website thương mại điện tử chuyên bán mô hình figure, cosplay và phụ kiện Uma Musume.  
Kiến trúc: **PHP Frontend** ↔ **FastAPI Backend (Python)** ↔ **MySQL**.

---

## 🗂 Cấu trúc dự án

```
Project4/
├── 📁 admin/               ← Khu vực quản trị (ROLE_ADMIN)
│   ├── index.php           ← Dashboard: stat cards + biểu đồ Chart.js
│   ├── products/           ← CRUD sản phẩm
│   ├── categories/         ← CRUD danh mục
│   ├── suppliers/          ← CRUD nhà cung cấp
│   ├── orders/             ← Quản lý đơn hàng
│   ├── vouchers/           ← Quản lý mã giảm giá
│   ├── users/              ← Quản lý người dùng
│   ├── articles/           ← Quản lý bài viết
│   ├── reviews/            ← Duyệt / xóa đánh giá
│   └── includes/           ← header.php | sidebar.php | footer.php
│
├── 📁 pages/               ← Trang user (public)
│   ├── home.php            ← Trang chủ: Hero + Categories + Products + News
│   ├── shop.php            ← Cửa hàng: Filter + Sort + Search
│   ├── product_detail.php  ← Chi tiết SP: Gallery + Reviews + Đánh giá
│   ├── cart.php            ← Giỏ hàng (AJAX update/remove/clear)
│   ├── checkout.php        ← Thanh toán: địa chỉ + payment + voucher
│   ├── news.php            ← Danh sách tin tức
│   ├── news_detail.php     ← Chi tiết bài viết
│   └── includes/           ← header.php | footer.php | product_card.php
│
├── 📁 user/                ← Khu vực tài khoản (requireLogin)
│   ├── profile.php         ← Cập nhật thông tin
│   ├── order_history.php   ← Lịch sử đơn (filter theo status)
│   ├── order_detail.php    ← Chi tiết 1 đơn hàng
│   ├── favorites.php       ← Danh sách yêu thích
│   ├── change_password.php ← Đổi mật khẩu
│   └── includes/           ← user_sidebar.php
│
├── 📁 auth/                ← Đăng nhập / Đăng ký / Đăng xuất
│   ├── login.php
│   ├── register.php
│   └── logout.php
│
├── 📁 api_actions/         ← AJAX endpoints (trả JSON)
│   ├── cart_add.php        ← POST: Thêm vào giỏ
│   ├── cart_update.php     ← POST: Cập nhật số lượng
│   ├── cart_remove.php     ← POST: Xóa 1 món
│   ├── cart_clear.php      ← POST: Xóa toàn bộ giỏ
│   ├── favorite_toggle.php ← POST: Toggle yêu thích
│   ├── voucher_check.php   ← POST: Kiểm tra mã giảm giá
│   └── order_cancel.php    ← POST: Hủy đơn PENDING
│
├── 📁 models/              ← Lớp truy cập dữ liệu qua ApiClient
│   ├── product_model.php   ← CRUD sản phẩm + filter + search
│   ├── category_model.php  ← CRUD danh mục
│   ├── supplier_model.php  ← CRUD nhà cung cấp
│   ├── order_model.php     ← Tạo / xem / hủy đơn
│   ├── voucher_model.php   ← CRUD + checkVoucher()
│   ├── user_model.php      ← Profile + đổi mật khẩu
│   ├── article_model.php   ← CRUD bài viết
│   ├── auth_model.php      ← loginUser / registerUser
│   ├── cart_model.php      ← CRUD giỏ hàng + calcCartTotal()
│   ├── review_model.php    ← CRUD + calcAverageRating()
│   ├── favorite_model.php  ← CRUD + isFavorited()
│   └── stats_model.php     ← Dashboard stats / revenue / top products
│
├── 📁 utils/               ← Helper dùng chung
│   ├── api_client.php      ← ⭐ Lớp trung gian gọi API (toàn bộ cURL ở đây)
│   ├── auth_helper.php     ← Session: requireLogin / requireRole / isAdmin
│   └── format_helper.php   ← formatPrice / formatDate / orderStatusBadge / renderFlash
│
├── 📁 assets/
│   └── css/
│       ├── admin.css       ← Dark sidebar admin panel
│       └── user.css        ← Dark anime theme — Inter + Space Grotesk
│
├── 📁 config/
│   └── config.php          ← BASE_URL + API_URL constants
│
├── 📁 database/
│   └── project4.sql        ← Schema + dữ liệu mẫu
│
├── 📁 uma_api/             ← FastAPI Backend (Python) — do Backend team quản lý
│   └── main.py
│
└── index.php               ← Root: redirect Admin → /admin | User → /pages/home
```

---

## ⚙️ Cài đặt & Chạy

### 1. Yêu cầu
| Công cụ | Version |
|---------|---------|
| PHP     | ≥ 8.1   |
| XAMPP / WAMP | Bất kỳ |
| Python  | ≥ 3.10  |
| FastAPI + Uvicorn | `pip install fastapi uvicorn` |
| MySQL   | ≥ 8.0   |

### 2. Setup Database
```sql
-- Import file vào phpMyAdmin hoặc MySQL CLI
source database/project4.sql;
```

### 3. Chạy Backend (FastAPI)
```bash
cd uma_api
pip install -r requirements.txt   # nếu có
uvicorn main:app --reload --port 8000
```
→ API chạy tại: `http://127.0.0.1:8000`

### 4. Chạy Frontend (PHP)
```
Đặt project vào: C:\xampp\htdocs\K23CNT3_LucVanSon_2310900087_Project4\
Mở: http://localhost/K23CNT3_LucVanSon_2310900087_Project4/
```

---

## 🔑 Kiến trúc quan trọng — ApiClient

**Mọi request đều đi qua `utils/api_client.php`** — không ai gọi cURL trực tiếp trong view hay model:

```
Browser → PHP View → Model → ApiClient::get/post/put/delete() → FastAPI → MySQL
```

**Lợi ích:** Backend thay đổi endpoint → chỉ sửa `api_client.php`, không sửa model.

---

## 👥 Phân chia nhiệm vụ

| Người | Vai trò | Phần việc |
|-------|---------|-----------|
| **Sơn** | Frontend Lead | Toàn bộ `pages/`, `user/`, `admin/`, `utils/`, `assets/css/` |
| **Backend Dev A** | Backend | Auth, Cart, Orders, Stats API |
| **Backend Dev B** | Backend | Products, Reviews, Favorites, Vouchers API |

### ⚠️ Backend phải tuân thủ API Contract:
Xem file: `docs/api_contract.md` *(hoặc hỏi Sơn)*

**Format bắt buộc:**
```json
{
  "status": "success",
  "message": "OK",
  "data": { ... }
}
```

---

## 🔒 Bảo mật

- Tất cả trang Admin có `requireRole('ROLE_ADMIN')` trong `header.php`
- Tất cả trang User có `requireLogin()` ở đầu file
- Tất cả input đều qua `htmlspecialchars()` trước khi render
- API token / credentials **không được commit** vào git

---

## ✅ Danh sách 60+ chức năng

### 🔐 Auth (5)
- [x] Đăng nhập
- [x] Đăng ký
- [x] Đăng xuất
- [x] Redirect theo role (Admin/User)
- [x] Bảo vệ trang bằng session

### 🛍 Cửa hàng (10)
- [x] Xem tất cả sản phẩm
- [x] Lọc theo danh mục
- [x] Lọc theo nhà cung cấp
- [x] Lọc theo khoảng giá
- [x] Tìm kiếm sản phẩm
- [x] Sắp xếp (mới nhất / giá tăng / giá giảm)
- [x] Xem chi tiết sản phẩm
- [x] Xem ảnh sản phẩm (gallery)
- [x] Đánh giá sao + bình luận
- [x] Xem tổng quan đánh giá (rating bars)

### 🛒 Giỏ hàng & Đặt hàng (8)
- [x] Thêm vào giỏ (AJAX)
- [x] Cập nhật số lượng (AJAX)
- [x] Xóa 1 sản phẩm (AJAX)
- [x] Xóa toàn bộ giỏ
- [x] Nhập mã giảm giá
- [x] Xem tóm tắt đơn hàng
- [x] Đặt hàng (checkout)
- [x] Hủy đơn PENDING

### 👤 Tài khoản User (6)
- [x] Xem hồ sơ cá nhân
- [x] Cập nhật thông tin
- [x] Đổi mật khẩu
- [x] Lịch sử đơn hàng
- [x] Chi tiết đơn hàng
- [x] Danh sách yêu thích

### ❤️ Yêu thích (3)
- [x] Thêm vào yêu thích (AJAX toggle)
- [x] Xóa khỏi yêu thích
- [x] Xem danh sách yêu thích

### 📰 Tin tức (3)
- [x] Danh sách bài viết
- [x] Chi tiết bài viết
- [x] Bài viết liên quan

### ⚙️ Admin — Tổng quan (4)
- [x] Dashboard stat cards
- [x] Biểu đồ doanh thu (Chart.js)
- [x] Biểu đồ top sản phẩm (Doughnut)
- [x] Đơn hàng gần đây

### ⚙️ Admin — Quản lý (25+)
- [x] CRUD Sản phẩm (xem / thêm / sửa / xóa / tìm kiếm)
- [x] CRUD Danh mục
- [x] CRUD Nhà cung cấp
- [x] CRUD Đơn hàng (xem / cập nhật trạng thái)
- [x] CRUD Mã giảm giá
- [x] CRUD Người dùng (xem / khóa / mở khóa)
- [x] CRUD Bài viết
- [x] Quản lý Đánh giá (xem / xóa)

---

## 📞 Liên hệ

- **Frontend Lead**: Lục Văn Sơn — `2310900087`
- **Môn học**: K23CNT3 — Project 4
- **Repo**: *(link repo của nhóm)*
