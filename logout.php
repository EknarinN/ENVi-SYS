<?php
// เริ่มต้น session เพื่อเข้าถึงข้อมูล session ที่มีอยู่
session_start();

// 1. ลบตัวแปร session ทั้งหมด
$_SESSION = array();

// 2. ทำลาย session ที่เซิร์ฟเวอร์
session_destroy();

// 3. ส่งผู้ใช้กลับไปที่หน้า login
header("location: login.php");
exit;
