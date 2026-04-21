<?php
// app/Controllers/OrderController.php

require_once __DIR__ . '/../Models/OrderModel.php';
require_once __DIR__ . '/../Models/ProductModel.php';

class OrderController {
    
    // دریافت اطلاعات از فرانت‌اند و محاسبه نهایی
    public function submitOrder() {
        // تنظیم هدر برای پاسخ‌دهی به صورت JSON به جاوا اسکریپت
        header('Content-Type: application/json');

        // دریافت اطلاعات ارسال شده (به صورت JSON)
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'اطلاعاتی دریافت نشد.']);
            return;
        }

        // 1. اعتبارسنجی اولیه دیتا (Validation)
        $length = floatval($input['length']);
        $width = floatval($input['width']);
        $height = floatval($input['height']);
        $qty = intval($input['quantity']);
        $material_id = intval($input['material_id']);
        
        if ($length <= 0 || $width <= 0 || $height <= 0 || $qty < 500) {
            echo json_encode(['success' => false, 'message' => 'مقادیر وارد شده نامعتبر است. 최소 تیراژ ۵۰۰ عدد است.']);
            return;
        }

        // 2. محاسبه مجدد قیمت در سرور (امنیت)
        // در یک پروژه واقعی، فرمول دقیق چاپخانه شما اینجا قرار می‌گیرد
        $area = (2 * (($length * $width) + ($length * $height) + ($width * $height))) / 10000;
        
        // فرض می‌کنیم قیمت پایه متریال را از دیتابیس گرفتیم (برای سادگی اینجا 50000 در نظر گرفته شده)
        // باید با ProductModel آیدی متریال را چک کنید
        $basePrice = $area * 50000; 
        
        $totalPrice = $basePrice * $qty;
        
        // هزینه ثابت راه‌اندازی (زینک و قالب)
        $setupFee = 500000;
        $finalPrice = $totalPrice + $setupFee;

        // 3. ثبت در دیتابیس
        $orderModel = new OrderModel();
        $orderData = [
            'length'      => $length,
            'width'       => $width,
            'height'      => $height,
            'quantity'    => $qty,
            'material_id' => $material_id,
            'finishes'    => $input['finishes'] ?? [],
            'total_price' => $finalPrice
        ];

        try {
            $trackingCode = $orderModel->createOrder($orderData);
            
            // ارسال پاسخ موفقیت‌آمیز به فرانت‌اند
            echo json_encode([
                'success' => true, 
                'message' => 'سفارش با موفقیت ثبت شد.',
                'tracking_code' => $trackingCode
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'خطا در ثبت سفارش: ' . $e->getMessage()]);
        }
    }
}