<?php
// app/Controllers/PageController.php

require_once __DIR__ . '/../Models/ProductModel.php';

class PageController {
    
    // نمایش صفحه اصلی سایت
    public function home() {
        // لود کردن فایل رابط کاربری (در مراحل بعد می‌سازیم)
        require_once __DIR__ . '/../Views/front/home.php';
    }

    // نمایش صفحه ماشین‌حساب چاپ
    public function calculator() {
        $productModel = new ProductModel();
        
        // دریافت دیتا از دیتابیس برای تزریق به فرم فرانت‌اند
        $papers = $productModel->getPapers();
        $finishes = $productModel->getFinishes();

        require_once __DIR__ . '/../Views/front/calculator.php';
    }
}