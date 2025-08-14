<?php
$pdo = require_once 'connect_db.php';
header('Content-Type: application/json');

// รับค่า type เพื่อบอกว่าต้องการจะเช็คอะไร (name, email, or username)
$type = $_GET['type'] ?? '';
$response = ['exists' => false];

switch ($type) {
    case 'name':
        $fname = $_GET['first_name'] ?? '';
        $lname = $_GET['last_name'] ?? '';
        if (!empty($fname) && !empty($lname)) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE first_name = ? AND last_name = ?");
            $stmt->execute([$fname, $lname]);
            if ($stmt->fetch()) {
                $response['exists'] = true;
            }
        }
        break;

    case 'email':
        $email = $_GET['email'] ?? '';
        if (!empty($email)) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $response['exists'] = true;
            }
        }
        break;

    case 'username':
        $username = $_GET['username'] ?? '';
        if (!empty($username)) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $response['exists'] = true;
            }
        }
        break;
}

echo json_encode($response);
