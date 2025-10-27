-- ===========================================
-- CODE SQL HOÀN CHỈNH CHO CHỨC NĂNG TÍCH ĐIỂM
-- Chạy các lệnh này trong phpMyAdmin hoặc MySQL Workbench
-- ===========================================
-- 1. THÊM CỘT POINTS VÀO BẢNG USERS
-- (Nếu chưa có cột points)
ALTER TABLE users
ADD COLUMN points INT DEFAULT 0 AFTER role;

-- 2. THÊM CỘT USER_ID VÀO BẢNG COUPONS
-- (Nếu chưa có cột user_id)
ALTER TABLE coupons
ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id;

-- 3. THÊM FOREIGN KEY CONSTRAINT CHO USER_ID
ALTER TABLE coupons ADD CONSTRAINT fk_coupons_user_id FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

-- 4. SỬA CỘT TYPE ĐỂ HỖ TRỢ GIÁ TRỊ 'VOUCHER'
-- (Đây là lệnh quan trọng để khắc phục lỗi "Data truncated")
ALTER TABLE coupons MODIFY COLUMN type VARCHAR(50) NOT NULL;

-- ===========================================
-- KIỂM TRA KẾT QUẢ
-- ===========================================
-- Kiểm tra cấu trúc bảng users
DESCRIBE users;

-- Kiểm tra cấu trúc bảng coupons
DESCRIBE coupons;

-- ===========================================
-- LỆNH REVERT (NẾU CẦN HOÀN TÁC)
-- ===========================================
-- Xóa cột points khỏi users (cẩn thận!)
-- ALTER TABLE users DROP COLUMN points;
-- Xóa foreign key và cột user_id khỏi coupons (cẩn thận!)
-- ALTER TABLE coupons DROP FOREIGN KEY fk_coupons_user_id;
-- ALTER TABLE coupons DROP COLUMN user_id;
-- Revert cột type về enum (nếu cần)
-- ALTER TABLE coupons MODIFY COLUMN type ENUM('percent', 'fixed') NOT NULL;