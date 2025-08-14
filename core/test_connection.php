<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = $_POST['db_host'] ?? '';
    $port = $_POST['db_port'] ?? '';
    $dbname = $_POST['db_name'] ?? '';
    $user = $_POST['db_user'] ?? '';
    $pass = $_POST['db_pass'] ?? '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        // ลองสร้างการเชื่อมต่อใหม่ด้วยข้อมูลที่ได้รับ
        new PDO($dsn, $user, $pass, $options);
        // ถ้าสำเร็จ
        echo json_encode(['status' => 'success', 'message' => 'เชื่อมต่อ Database ได้ !']);
    } catch (PDOException $e) {
        // ถ้าล้มเหลว
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถเชื่อมต่อ Database ได้ กรุณาตรวจสอบข้อมูล !']);
    }
}