<?php
session_start();
$pdo = require_once 'connect_db.php';

// 1. ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// 2. ตรวจสอบว่าเป็นการส่งข้อมูลมาแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];
    $user_id = $_SESSION['user_id'];

    // 3. ตรวจสอบว่ารหัสผ่านใหม่ตรงกันหรือไม่
    if ($new_password !== $confirm_new_password) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'รหัสผ่านใหม่และยืนยันรหัสผ่านไม่ตรงกัน'];
        header('Location: ../change_password.php');
        exit();
    }

    // 4. ดึงรหัสผ่านปัจจุบันของผู้ใช้ออกจากฐานข้อมูล
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    // 5. ตรวจสอบว่ารหัสผ่านปัจจุบันถูกต้องหรือไม่
    if ($user && password_verify($current_password, $user['password_hash'])) {
        // ถ้ารหัสผ่านปัจจุบันถูกต้อง -> เข้ารหัสรหัสผ่านใหม่แล้วอัปเดต
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        $update_stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        if ($update_stmt->execute([$new_password_hash, $user_id])) {
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'เปลี่ยนรหัสผ่านสำเร็จแล้ว'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'เกิดข้อผิดพลาดในการอัปเดตฐานข้อมูล'];
        }
    } else {
        // ถ้ารหัสผ่านปัจจุบันไม่ถูกต้อง
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง'];
    }

    header('Location: ../change_password.php');
    exit();
}