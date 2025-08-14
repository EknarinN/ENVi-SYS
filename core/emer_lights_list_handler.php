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
    // Case to fetch data for dropdowns
    case 'get_dropdown_data':
        $colors = $pdo->query("SELECT id, name_color FROM data_color WHERE usage_id = '1'")->fetchAll();
        $types = $pdo->query("SELECT id, name_type FROM data_emer_lights_type WHERE usage_id = '1'")->fetchAll();
        $installations = $pdo->query("SELECT id, name_type FROM data_emer_lights_installation WHERE usage_id = '1'")->fetchAll();
        echo json_encode([
            'colors' => $colors,
            'types' => $types,
            'installations' => $installations
        ]);
        break;

    case 'fetch':
        $query = $_GET['query'] ?? '';
        $sql = "SELECT d.*, c.name_color, t.name_type as light_type_name 
                FROM data_emer_lights_list d
                LEFT JOIN data_color c ON d.data_color_id = c.id
                LEFT JOIN data_emer_lights_type t ON d.emer_light_type_id = t.id";
        $params = [];
        if (!empty($query)) {
            $sql .= " WHERE d.emer_lights_number LIKE ? OR d.brand LIKE ? OR d.model LIKE ?";
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
        $stmt = $pdo->prepare("SELECT * FROM data_emer_lights_list WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        echo json_encode($data);
        break;

    case 'save':
        $is_edit = !empty($_POST['edit_id']);
        $id = $is_edit ? $_POST['edit_id'] : null;

        $sql = $is_edit
            ? "UPDATE data_emer_lights_list SET emer_lights_number=?, brand=?, model=?, data_color_id=?, emer_light_type_id=?, size_width=?, size_height=?, size_thickness=?, weight=?, input_voltage=?, output_voltage=?, power_watt=?, temperature=?, ingress_protection=?, brightness_daylight=?, brightness_nightlight=?, light_distribution_angle=?, external_material=?, data_emer_lights_installation_id=?, usage_id=? WHERE id=?"
            : "INSERT INTO data_emer_lights_list (emer_lights_number, brand, model, data_color_id, emer_light_type_id, size_width, size_height, size_thickness, weight, input_voltage, output_voltage, power_watt, temperature, ingress_protection, brightness_daylight, brightness_nightlight, light_distribution_angle, external_material, data_emer_lights_installation_id, usage_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $_POST['emer_lights_number'], $_POST['brand'], $_POST['model'], $_POST['data_color_id'], $_POST['emer_light_type_id'],
            $_POST['size_width'], $_POST['size_height'], $_POST['size_thickness'], $_POST['weight'], $_POST['input_voltage'],
            $_POST['output_voltage'], $_POST['power_watt'], $_POST['temperature'], $_POST['ingress_protection'], $_POST['brightness_daylight'],
            $_POST['brightness_nightlight'], $_POST['light_distribution_angle'], $_POST['external_material'], $_POST['data_emer_lights_installation_id'],
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
        $stmt = $pdo->prepare("DELETE FROM data_emer_lights_list WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
        }
        break;
}