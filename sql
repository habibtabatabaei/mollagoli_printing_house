-- ساخت دیتابیس با پشتیبانی کامل از زبان فارسی
CREATE DATABASE IF NOT EXISTS mollagoli_print CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mollagoli_print;

-- جدول متریال‌ها و خدمات پس از چاپ
CREATE TABLE materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL, -- نام مقوا یا خدمات (مثلاً: ایندربرد ۳۰۰ گرم)
    type ENUM('paper', 'finish') NOT NULL, -- نوع: کاغذ اصلی یا خدمات تکمیلی
    base_price DECIMAL(15,2) NOT NULL, -- قیمت پایه
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- وارد کردن اطلاعات اولیه ماشین‌حساب در دیتابیس
INSERT INTO materials (name, type, base_price) VALUES 
('مقوای ایندربرد ۳۰۰ گرم', 'paper', 50000.00),
('گلاسه ۲۵۰ گرم', 'paper', 40000.00),
('کرافت ۲۸۰ گرم', 'paper', 35000.00),
('سلفون مات/براق', 'finish', 5000.00),
('طلاکوب', 'finish', 12000.00);

-- جدول ذخیره سفارشات
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_code VARCHAR(20) UNIQUE NOT NULL, -- کد رهگیری یکتا برای مشتری
    length DECIMAL(10,2) NOT NULL, -- طول
    width DECIMAL(10,2) NOT NULL, -- عرض
    height DECIMAL(10,2) NOT NULL, -- ارتفاع
    quantity INT NOT NULL, -- تیراژ
    material_id INT NOT NULL, -- کلید خارجی به جدول متریال
    finishes JSON, -- ذخیره خدمات تکمیلی به صورت آرایه جیسون
    total_price DECIMAL(15,2) NOT NULL, -- قیمت نهایی محاسبه شده
    status ENUM('pending', 'printing', 'ready', 'delivered') DEFAULT 'pending', -- وضعیت سفارش
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id)
);