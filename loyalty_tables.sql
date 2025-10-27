-- Tạo bảng users với cột points (nếu chưa có)
-- Giả sử bảng users đã tồn tại, chỉ thêm cột points
ALTER TABLE users
ADD COLUMN points INT DEFAULT 0 AFTER role;

-- Tạo bảng coupons với cột user_id (nếu chưa có)
-- Giả sử bảng coupons đã tồn tại, chỉ thêm cột user_id
ALTER TABLE coupons
ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id;

ALTER TABLE coupons ADD CONSTRAINT fk_coupons_user_id FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

-- Nếu cần tạo bảng users từ đầu (không khuyến nghị nếu đã có dữ liệu)
-- CREATE TABLE users (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     email VARCHAR(255) UNIQUE NOT NULL,
--     email_verified_at TIMESTAMP NULL,
--     password VARCHAR(255) NOT NULL,
--     role VARCHAR(255) DEFAULT 'user',
--     points INT DEFAULT 0,
--     remember_token VARCHAR(100) NULL,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL
-- );
-- Nếu cần tạo bảng coupons từ đầu (không khuyến nghị nếu đã có dữ liệu)
-- CREATE TABLE coupons (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     user_id BIGINT UNSIGNED NULL,
--     code VARCHAR(255) NOT NULL,
--     type VARCHAR(255) NOT NULL,
--     value DECIMAL(10,2) NOT NULL,
--     max_uses INT NULL,
--     used INT DEFAULT 0,
--     expires_at TIMESTAMP NULL,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL,
--     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
-- );