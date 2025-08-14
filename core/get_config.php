<?php
header('Content-Type: application/json');

$configFile = __DIR__ . '/config.ini';

if (file_exists($configFile)) {
    // ใช้ parse_ini_file เพื่ออ่านค่าจากไฟล์ .ini
    $config = parse_ini_file($configFile);
    echo json_encode(['status' => 'success', 'data' => $config]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'File not found.']);
}