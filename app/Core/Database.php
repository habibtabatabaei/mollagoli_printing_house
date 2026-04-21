<?php
// app/Core/Database.php

class Database {
    private static $instance = null;
    private $pdo;

    // متد سازنده به صورت private تعریف می‌شود تا از بیرون کلاس نشود از آن شیء ساخت (الگوی Singleton)
    private function __construct() {
        // فراخوانی فایل تنظیمات
        $config = require __DIR__ . '/../../config/database.php';
        
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
        // تنظیمات امنیتی و استانداردهای PDO
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // نمایش دقیق خطاهای SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // خروجی داده‌ها به صورت آرایه انجمنی
            PDO::ATTR_EMULATE_PREPARES   => false,                  // جلوگیری از حملات SQL Injection
        ];

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (\PDOException $e) {
            // در صورت قطعی دیتابیس، اجرای برنامه متوقف شده و خطا نمایش داده می‌شود
            die("خطای بحرانی در اتصال به پایگاه داده: " . $e->getMessage());
        }
    }

    // متد اصلی برای دریافت کانکشن دیتابیس
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}