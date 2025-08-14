<?php
// เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล
$pdo = require_once 'connect_db.php';

// ตั้งค่า Header ให้ browser รู้ว่าข้อมูลที่ส่งกลับไปเป็นชนิด JSON
header('Content-Type: application/json');

// รับค่า ID ของกลุ่มงานหลักที่ถูกส่งมา
$group_id = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;

if ($group_id > 0) {
    // เตรียมคำสั่ง SQL แบบ Prepared Statement เพื่อความปลอดภัย
    $stmt = $pdo->prepare("
        SELECT id, name_departments 
        FROM data_departments_sub 
        WHERE departments_group_id = ? AND usage_id = '1' 
        ORDER BY name_departments
    ");
    // รันคำสั่ง SQL โดยส่ง group_id เข้าไป
    $stmt->execute([$group_id]);
    // ดึงข้อมูลทั้งหมด
    $sub_departments = $stmt->fetchAll();

    // แปลงข้อมูลที่ได้เป็นรูปแบบ JSON แล้วส่งกลับไป
    echo json_encode($sub_departments);
} else {
    // ถ้าไม่มี group_id ส่งมา ให้ส่งค่าว่างกลับไป
    echo json_encode([]);
}
