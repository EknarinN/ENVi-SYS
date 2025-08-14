<?php
session_start();
$pdo = require_once 'connect_db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if ($new_password !== $confirm_new_password) {
        // ควรทำเป็น Flash Message ในระบบจริง
        die('รหัสผ่านใหม่ไม่ตรงกัน');
    }

    $token_hash = hash('sha256', $token);
    $stmt = $pdo->prepare("SELECT user_id, expires_at FROM password_resets WHERE token = ?");
    $stmt->execute([$token_hash]);
    $reset_request = $stmt->fetch();

    if ($reset_request && strtotime($reset_request['expires_at']) > time()) {
        $user_id = $reset_request['user_id'];
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        $update_stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $update_stmt->execute([$new_password_hash, $user_id]);

        $delete_stmt = $pdo->prepare("DELETE FROM password_resets WHERE user_id = ?");
        $delete_stmt->execute([$user_id]);

        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'ตั้งรหัสผ่านใหม่สำเร็จแล้ว กรุณาเข้าสู่ระบบ'];
        header('Location: ../login.php');
        exit();
    } else {
        die('ลิงก์สำหรับตั้งรหัสผ่านใหม่ไม่ถูกต้องหรือหมดอายุแล้ว');
    }
}
