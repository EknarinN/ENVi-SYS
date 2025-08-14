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
    case 'get_dropdown_data':
        $groups = $pdo->query("SELECT id, name_group FROM data_departments_group WHERE usage_id = '1' ORDER BY name_group")->fetchAll();
        echo json_encode(['groups' => $groups]);
        break;

    case 'fetch':
        $query = $_GET['query'] ?? '';
        $sql = "SELECT ds.*, dg.name_group 
                FROM data_departments_sub ds
                LEFT JOIN data_departments_group dg ON ds.departments_group_id = dg.id";
        $params = [];
        if (!empty($query)) {
            $sql .= " WHERE ds.name_departments LIKE ? OR dg.name_group LIKE ? OR (CASE WHEN ds.usage_id = '1' THEN 'ใช้งาน' ELSE 'ไม่ใช้งาน' END) LIKE ?";
            $searchTerm = "%$query%";
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        echo json_encode(['data' => $data]);
        break;

    case 'get_one':
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("SELECT * FROM data_departments_sub WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        echo json_encode($data);
        break;

    case 'save':
        $is_edit = !empty($_POST['edit_id']);
        $id = $is_edit ? $_POST['edit_id'] : null;

        $sql = $is_edit
            ? "UPDATE data_departments_sub SET name_departments=?, departments_group_id=?, usage_id=? WHERE id=?"
            : "INSERT INTO data_departments_sub (name_departments, departments_group_id, usage_id) VALUES (?, ?, ?)";
        
        $params = [
            $_POST['name_departments'],
            $_POST['departments_group_id'],
            isset($_POST['usage_status']) ? '1' : '0'
        ];
        
        if ($is_edit) {
            $params[] = $id;
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
        $stmt = $pdo->prepare("DELETE FROM data_departments_sub WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
        }
        break;
}