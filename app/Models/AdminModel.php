<?php
// app/Models/AdminModel.php

require_once __DIR__ . '/../Core/Database.php';

class AdminModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // جستجوی مدیر بر اساس نام کاربری
    public function getAdminByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }
}