<?php
header('Content-Type: application/json');

// ตรวจสอบว่าเป็นการส่งข้อมูลแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $configFile = __DIR__ . '/config.ini';

    // ตรวจสอบว่าไฟล์ config.ini เขียนได้หรือไม่
    if (!is_writable($configFile)) {
        echo json_encode(['status' => 'error', 'message' => 'ข้อผิดพลาด: ไฟล์ config.ini ไม่สามารถเขียนได้ กรุณาตรวจสอบ Permission ของไฟล์']);
        exit();
    }

    // สร้างเนื้อหาใหม่สำหรับไฟล์ .ini
    $newContent = "[database]\n";
    $newContent .= "host = \"" . ($_POST['db_host'] ?? '127.0.0.1') . "\"\n";
    $newContent .= "port = \"" . ($_POST['db_port'] ?? '3306') . "\"\n";
    $newContent .= "dbname = \"" . ($_POST['db_name'] ?? 'envi_sys') . "\"\n";
    $newContent .= "user = \"" . ($_POST['db_user'] ?? 'root') . "\"\n";
    $newContent .= "pass = \"" . ($_POST['db_pass'] ?? '') . "\"\n";
    $newContent .= "charset = \"utf8mb4\"\n";

    // เขียนไฟล์
    if (file_put_contents($configFile, $newContent)) {
        echo json_encode(['status' => 'success', 'message' => 'บันทึกการตั้งค่าเชื่อมต่อฐานข้อมูลแล้ว']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกไฟล์ config.ini ได้']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}