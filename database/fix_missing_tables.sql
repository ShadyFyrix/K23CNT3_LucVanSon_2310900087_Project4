-- ============================================
-- fix_missing_tables.sql
-- Chạy trong MySQL Workbench để tạo các bảng còn thiếu
-- Tác giả: Lục Văn Sơn (2310900087)
-- ============================================

USE umact_db;

-- 1. Bảng cart (bị thiếu trong project4.sql gốc)
--    UNIQUE KEY (user_id, product_id) là BẮT BUỘC để ON DUPLICATE KEY UPDATE hoạt động
CREATE TABLE IF NOT EXISTS cart (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    product_id  INT NOT NULL,
    quantity    INT NOT NULL DEFAULT 1,
    added_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY  unique_cart_item (user_id, product_id),
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 2. Kiểm tra bảng favorites đã tồn tại chưa (đề phòng)
CREATE TABLE IF NOT EXISTS favorites (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    product_id  INT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY  unique_favorite (user_id, product_id),
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 3. Kiểm tra bảng reviews (có thể thiếu UNIQUE để user chỉ review 1 lần)
CREATE TABLE IF NOT EXISTS reviews (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT,
    product_id  INT,
    rating      TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment     TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Xác nhận kết quả
SHOW TABLES;
SELECT 'cart' as table_name, COUNT(*) as rows FROM cart
UNION ALL
SELECT 'favorites', COUNT(*) FROM favorites
UNION ALL  
SELECT 'reviews', COUNT(*) FROM reviews;
