-- Sửa lỗi cột 'type' trong bảng coupons
-- Chạy lệnh này trong phpMyAdmin hoặc MySQL Workbench
-- 1. Kiểm tra cấu trúc bảng hiện tại
DESCRIBE coupons;

-- 2. Sửa cột type để hỗ trợ giá trị 'voucher'
ALTER TABLE coupons MODIFY COLUMN type VARCHAR(50) NOT NULL;

-- 3. Kiểm tra lại cấu trúc sau khi sửa
DESCRIBE coupons;

-- 4. Nếu muốn revert về enum (không khuyến nghị):
-- ALTER TABLE coupons MODIFY COLUMN type ENUM('percent', 'fixed') NOT NULL;