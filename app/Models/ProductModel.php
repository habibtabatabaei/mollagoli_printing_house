<?php
// app/Models/ProductModel.php

require_once __DIR__ . '/../Core/Database.php';

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // دریافت لیست متریال‌ها (کاغذ/مقوا)
    public function getPapers() {
        $stmt = $this->db->prepare("SELECT * FROM materials WHERE type = 'paper'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // دریافت لیست خدمات پس از چاپ (سلفون، طلاکوب و ...)
    public function getFinishes() {
        $stmt = $this->db->prepare("SELECT * FROM materials WHERE type = 'finish'");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}