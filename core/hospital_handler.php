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
        $sql = "SELECT * FROM data_hospital";
        $params = [];
        if (!empty($query)) {
            $sql .= " WHERE id LIKE ? OR hosp_id LIKE ? OR hosp_name LIKE ? OR (CASE WHEN usage_id = '1' THEN 'ใช้งาน' ELSE 'ไม่ใช้งาน' END) LIKE ?";
            $searchTerm = "%$query%";
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        echo json_encode(['data' => $data]);
        break;

    case 'get_one':
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("SELECT * FROM data_hospital WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        echo json_encode($data);
        break;

    case 'save':
        $is_edit = !empty($_POST['edit_id']);
        $id = $is_edit ? $_POST['edit_id'] : null;
        
        if (!$is_edit) {
            // สร้าง ID ใหม่อัตโนมัติ
            $stmt = $pdo->query("SELECT MAX(CAST(SUBSTRING(id, 3) AS UNSIGNED)) as max_id FROM data_hospital");
            $max_id = $stmt->fetchColumn();
            $new_id_num = ($max_id ?? 0) + 1;
            $id = 'HP' . str_pad($new_id_num, 3, '0', STR_PAD_LEFT);
        }

        $sql = $is_edit
            ? "UPDATE data_hospital SET hosp_id=?, hosp_name=?, sub_district_id=?, usage_id=? WHERE id=?"
            : "INSERT INTO data_hospital (id, hosp_id, hosp_name, sub_district_id, usage_id) VALUES (?, ?, ?, ?, ?)";
        
        $params = [
            $_POST['hosp_id'],
            $_POST['hosp_name'],
            $_POST['sub_district_id'],
            isset($_POST['usage_status']) ? '1' : '0',
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
        $stmt = $pdo->prepare("DELETE FROM data_hospital WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
        }
        break;
}