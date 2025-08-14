<?php
// ไฟล์: wamp64/www/ENVi-SyS/core/connect_db.php (เวอร์ชันปรับปรุง)
// กำหนด BASE_URL อัตโนมัติ
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
$base_url = "$protocol://{$_SERVER['HTTP_HOST']}/envi_sys";
define('BASE_URL', $base_url);

// --- อ่านค่าการตั้งค่าจากไฟล์ config.ini ---
// __DIR__ คือ magic constant ที่จะให้ path ของโฟลเดอร์ที่ไฟล์นี้อยู่
// ทำให้ไม่ว่าไฟล์นี้จะถูก include จากที่ไหน ก็จะหาไฟล์ config.ini เจอเสมอ
$config = parse_ini_file(__DIR__ . '/config.ini');

// --- นำค่าจาก config มาใส่ในตัวแปร ---
$host = $config['host'];
$port = $config['port'];
$dbname = $config['dbname'];
$user = $config['user'];
$pass = $config['pass'];
$charset = $config['charset'];

// --- ตัวเลือกการกำหนดค่าสำหรับ PDO (เหมือนเดิม) ---
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// --- สร้าง Data Source Name (DSN) ---
$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

// --- เริ่มการเชื่อมต่อด้วย try-catch block (เหมือนเดิม) ---
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
// เพิ่มบรรทัดนี้เข้าไป
return $pdo;