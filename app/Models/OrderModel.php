<?php
// app/Models/OrderModel.php

require_once __DIR__ . '/../Core/Database.php';

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // ذخیره سفارش جدید در دیتابیس
    public function createOrder($data) {
        $trackingCode = 'MG-' . strtoupper(uniqid()); // تولید کد پیگیری اختصاصی مثلا: MG-64A1B2...
        
        $sql = "INSERT INTO orders (tracking_code, length, width, height, quantity, material_id, finishes, total_price) 
                VALUES (:tracking_code, :length, :width, :height, :quantity, :material_id, :finishes, :total_price)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':tracking_code' => $trackingCode,
            ':length'        => $data['length'],
            ':width'         => $data['width'],
            ':height'        => $data['height'],
            ':quantity'      => $data['quantity'],
            ':material_id'   => $data['material_id'],
            ':finishes'      => json_encode($data['finishes']), // تبدیل آرایه خدمات به جیسون
            ':total_price'   => $data['total_price']
        ]);

        return $trackingCode;
    }

    // متد جدید برای دریافت لیست تمام سفارشات (برای پنل ادمین)
    public function getAllOrders() {
        // استفاده از JOIN برای اتصال جدول سفارشات به جدول متریال‌ها جهت دریافت نام مقوا
        $sql = "SELECT orders.*, materials.name as material_name 
                FROM orders 
                JOIN materials ON orders.material_id = materials.id 
                ORDER BY orders.created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}