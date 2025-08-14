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

// Map request type to table name
$table_map = [
    'general' => 'waste_general',
    'organic' => 'waste_organic',
    'recyclable' => 'waste_recyclable',
    'infectious' => 'waste_infectious',
    'hazardous' => 'waste_hazardous'
];
$table = $table_map[$_REQUEST['type']] ?? null;

if (!$table) {
    echo json_encode(['status' => 'error', 'message' => 'ประเภทขยะไม่ถูกต้อง']);
    exit();
}

// Function to convert dd/mm/yyyy to yyyy-mm-dd
function convert_date_to_sql($date) {
    if (empty($date)) return null;
    $parts = explode('/', $date);
    return count($parts) === 3 ? "{$parts[2]}-{$parts[1]}-{$parts[0]}" : $date;
}

switch ($action) {
    case 'get_next_times':
        $date = $_GET['date'] ?? date('d/m/Y');
        $sqlDate = convert_date_to_sql($date);
        $waste_type_id = $_GET['waste_type_id'] ?? null;
        if ($waste_type_id) {
            $stmt = $pdo->prepare("SELECT COUNT(id) FROM `$table` WHERE date_record = ? AND waste_type_id = ?");
            $stmt->execute([$sqlDate, $waste_type_id]);
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(id) FROM `$table` WHERE date_record = ?");
            $stmt->execute([$sqlDate]);
        }
        echo json_encode(['times' => $stmt->fetchColumn() + 1]);
        break;

    case 'fetch':
        $sql = "SELECT wg.*, wt.name_type, um.name_th as unit_name, u.first_name as user_firstname
                FROM `$table` wg
                LEFT JOIN data_waste_type wt ON wg.waste_type_id = wt.id
                LEFT JOIN data_unit_matrix um ON wg.unit_matrix_id = um.id
                LEFT JOIN users u ON wg.user_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($_GET['from_date'])) {
            $sql .= " AND wg.date_record >= ?";
            $params[] = convert_date_to_sql($_GET['from_date']);
        }
        if (!empty($_GET['to_date'])) {
            $sql .= " AND wg.date_record <= ?";
            $params[] = convert_date_to_sql($_GET['to_date']);
        }
        if (!empty($_GET['query'])) {
            $sql .= " AND (wt.name_type LIKE ? OR wg.waste_note LIKE ?)";
            $params[] = "%{$_GET['query']}%";
            $params[] = "%{$_GET['query']}%";
        }
        $sql .= " ORDER BY wg.date_record DESC, wg.time_record DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['data' => $stmt->fetchAll()]);
        break;

    case 'get_one':
        $id = $_GET['id'] ?? 0;
        $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if ($data) {
            $data['date_record'] = date('d/m/Y', strtotime($data['date_record']));
        }
        echo json_encode($data);
        break;

    case 'save':
        $date_record = convert_date_to_sql($_POST['date_record']);
        $is_edit = !empty($_POST['edit_id']);

        if ($is_edit) {
            $sql = "UPDATE `$table` SET date_record=?, time_record=?, times=?, waste_type_id=?, quantity=?, unit_matrix_id=?, waste_note=? WHERE id=?";
            $params = [$date_record, $_POST['time_record'], $_POST['times'], $_POST['waste_type_id'], $_POST['quantity'], $_POST['unit_matrix_id'], $_POST['waste_note'], $_POST['edit_id']];
        } else {
            $sql = "INSERT INTO `$table` (date_record, time_record, times, waste_type_id, quantity, unit_matrix_id, waste_note, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [$date_record, $_POST['time_record'], $_POST['times'], $_POST['waste_type_id'], $_POST['quantity'], $_POST['unit_matrix_id'], $_POST['waste_note'], $_SESSION['user_id']];
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
        $stmt = $pdo->prepare("DELETE FROM `$table` WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
        }
        break;
}