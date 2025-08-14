<?php
session_start();
$pdo = require_once 'connect_db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

header('Content-Type: application/json');
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'fetch':
        $query = $_GET['query'] ?? '';
        $sql = "SELECT * FROM data_usage";
        $params = [];
        if (!empty($query)) {
            $sql .= " WHERE id LIKE ? OR name_usage LIKE ?";
            $searchTerm = "%$query%";
            $params = [$searchTerm, $searchTerm];
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        echo json_encode(['data' => $data]);
        break;

    case 'get_one':
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("SELECT * FROM data_usage WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        echo json_encode($data);
        break;

    case 'save':
        $is_edit = !empty($_POST['edit_id']);
        $id = $is_edit ? $_POST['edit_id'] : $_POST['usage_id'];

        $sql = $is_edit
            ? "UPDATE data_usage SET name_usage=? WHERE id=?"
            : "INSERT INTO data_usage (id, name_usage) VALUES (?, ?)";
        
        $params = [
            $_POST['name_usage'],
            $id
        ];
        
        if(!$is_edit) {
             array_unshift($params, $id);
        }

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            echo json_encode(['status' => 'success', 'message' => 'บันทึกข้อมูลสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
        }
        break;

    case 'delete':
        $id = $_POST['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM data_usage WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
        }
        break;
}