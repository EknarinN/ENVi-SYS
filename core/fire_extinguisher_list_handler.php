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
        $types = $pdo->query("SELECT id, name_type FROM data_fire_extinguisher_type WHERE usage_id = '1'")->fetchAll();
        $colors = $pdo->query("SELECT id, name_color FROM data_color WHERE usage_id = '1'")->fetchAll();
        $propulsions = $pdo->query("SELECT id, full_word_th FROM data_propulsion_type WHERE usage_id = '1'")->fetchAll();

        echo json_encode([
            'types' => $types,
            'colors' => $colors,
            'propulsions' => $propulsions,
        ]);
        break;

    case 'save':
        $is_edit = !empty($_POST['edit_id']);
        $id = $is_edit ? $_POST['edit_id'] : null;

        $sql = $is_edit
            ? "UPDATE data_fire_extinguisher_list SET fire_extinguisher_number=?, brand=?, model=?, data_fire_extinguisher_type_id=?, data_color_id=?, capacity=?, weight_of_container=?, gross_weight_approx=?, unit_height=?, diameter=?, propulsion_type_id=?, working_pressure=?, test_pressure=?, discharging_time=?, shooting_range_min=?, shooting_range_max=?, fire_rating=?, fire_type_a=?, fire_type_b=?, fire_type_c=?, fire_type_d=?, fire_type_k=?, usage_id=? WHERE id=?"
            : "INSERT INTO data_fire_extinguisher_list (fire_extinguisher_number, brand, model, data_fire_extinguisher_type_id, data_color_id, capacity, weight_of_container, gross_weight_approx, unit_height, diameter, propulsion_type_id, working_pressure, test_pressure, discharging_time, shooting_range_min, shooting_range_max, fire_rating, fire_type_a, fire_type_b, fire_type_c, fire_type_d, fire_type_k, usage_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $_POST['fire_extinguisher_number'], $_POST['brand'], $_POST['model'], $_POST['data_fire_extinguisher_type_id'], $_POST['data_color_id'],
            $_POST['capacity'], $_POST['weight_of_container'], $_POST['gross_weight_approx'], $_POST['unit_height'], $_POST['diameter'],
            $_POST['propulsion_type_id'], $_POST['working_pressure'], $_POST['test_pressure'], $_POST['discharging_time'],
            $_POST['shooting_range_min'], $_POST['shooting_range_max'], 
            $_POST['fire_rating'],
            isset($_POST['fire_type_a']) ? '1' : '0', // ตรวจสอบค่าจาก Checkbox
            isset($_POST['fire_type_b']) ? '1' : '0',
            isset($_POST['fire_type_c']) ? '1' : '0',
            isset($_POST['fire_type_d']) ? '1' : '0',
            isset($_POST['fire_type_k']) ? '1' : '0',
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

    case 'fetch':
        $query = $_GET['query'] ?? '';
        $sql = "SELECT d.*, t.name_type 
                FROM data_fire_extinguisher_list d
                LEFT JOIN data_fire_extinguisher_type t ON d.data_fire_extinguisher_type_id = t.id";
        if (!empty($query)) {
            $sql .= " WHERE d.fire_extinguisher_number LIKE ? OR d.brand LIKE ? OR d.model LIKE ?";
            $params = ["%$query%", "%$query%", "%$query%"];
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params ?? []);
        $data = $stmt->fetchAll();
        echo json_encode(['data' => $data]);
        break;

    case 'get_one':
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("SELECT * FROM data_fire_extinguisher_list WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        echo json_encode($data);
        break;

    case 'save':
        $is_edit = !empty($_POST['edit_id']);
        $id = $is_edit ? $_POST['edit_id'] : null;

        $sql = $is_edit
            ? "UPDATE data_fire_extinguisher_list SET fire_extinguisher_number=?, brand=?, model=?, data_fire_extinguisher_type_id=?, data_color_id=?, capacity=?, weight_of_container=?, gross_weight_approx=?, unit_height=?, diameter=?, propulsion_type_id=?, working_pressure=?, test_pressure=?, discharging_time=?, shooting_range_min=?, shooting_range_max=?, fire_rating=?, usage_id=? WHERE id=?"
            : "INSERT INTO data_fire_extinguisher_list (fire_extinguisher_number, brand, model, data_fire_extinguisher_type_id, data_color_id, capacity, weight_of_container, gross_weight_approx, unit_height, diameter, propulsion_type_id, working_pressure, test_pressure, discharging_time, shooting_range_min, shooting_range_max, fire_rating, usage_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $_POST['fire_extinguisher_number'], $_POST['brand'], $_POST['model'], $_POST['data_fire_extinguisher_type_id'], $_POST['data_color_id'],
            $_POST['capacity'], $_POST['weight_of_container'], $_POST['gross_weight_approx'], $_POST['unit_height'], $_POST['diameter'],
            $_POST['propulsion_type_id'], $_POST['working_pressure'], $_POST['test_pressure'], $_POST['discharging_time'],
            $_POST['shooting_range_min'], $_POST['shooting_range_max'], $_POST['fire_rating'],
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
        $stmt = $pdo->prepare("DELETE FROM data_fire_extinguisher_list WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
        }
        break;
}