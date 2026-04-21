<?php
// app/Controllers/AdminController.php

require_once __DIR__ . '/../Models/OrderModel.php';
require_once __DIR__ . '/../Models/AdminModel.php';

class AdminController {
    
    // 1. نمایش صفحه لاگین
    public function login() {
        // اگر از قبل لاگین باشد، مستقیم برود به داشبورد
        if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header("Location: /admin/dashboard");
            exit;
        }
        require_once __DIR__ . '/../Views/admin/login.php';
    }

    // 2. پردازش فرم لاگین (بررسی نام کاربری و رمز)
    public function authenticate() {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $adminModel = new AdminModel();
        $admin = $adminModel->getAdminByUsername($username);

        // بررسی وجود کاربر و تطابق رمز عبور با نسخه هش شده
        if($admin && password_verify($password, $admin['password_hash'])) {
            // ایجاد نشست (Session) امن
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            
            header("Location: /admin/dashboard");
            exit;
        } else {
            // در صورت اشتباه بودن رمز، برگشت به صفحه لاگین با پیام خطا
            $_SESSION['login_error'] = "نام کاربری یا رمز عبور اشتباه است.";
            header("Location: /admin/login");
            exit;
        }
    }

    // 3. خروج از سیستم
    public function logout() {
        session_destroy();
        header("Location: /admin/login");
        exit;
    }

    // 4. نمایش پیشخوان (فقط در صورت لاگین بودن)
    public function index() {
        // چک کردن امنیت: آیا کاربر لاگین است؟
        if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: /admin/login");
            exit;
        }
        
        $orderModel = new OrderModel();
        $orders = $orderModel->getAllOrders();

        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }
}