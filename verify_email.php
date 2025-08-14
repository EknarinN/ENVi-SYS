<?php
session_start();
$pdo = require_once 'core/connect_db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $token_hash = hash('sha256', $token);

    // ค้นหาผู้ใช้ที่มี token ตรงกัน
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email_verification_token = ?");
    $stmt->execute([$token_hash]);
    $user = $stmt->fetch();

    if ($user) {
        // ถ้าเจอ: อัปเดตสถานะผู้ใช้ให้ active และลบ token ทิ้ง
        $update_stmt = $pdo->prepare("UPDATE users SET email_verification_token = NULL, email_verified_date_at = NOW(), usage_id = '1' WHERE id = ?");
        $update_stmt->execute([$user['id']]);

        // ส่งข้อความสำเร็จกลับไปที่หน้า Login
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'ยืนยันอีเมลสำเร็จแล้ว! กรุณาเข้าสู่ระบบ'];
        header('Location: login.php');
        exit();
    }
}

// ถ้า token ไม่ถูกต้อง หรือไม่มี token ส่งมา
$_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'ลิงก์ยืนยันไม่ถูกต้องหรือหมดอายุแล้ว'];
header('Location: login.php');
exit();