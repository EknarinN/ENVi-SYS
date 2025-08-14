<?php
session_start();
if (!file_exists('connect_db.php')) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection file not found.']);
    exit();
}
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
        try {
            $stmt = $pdo->prepare("SELECT id, matter_th AS name_state FROM data_state_of_matter WHERE usage_id = '1' ORDER BY id ASC");
            $stmt->execute();
            $states_of_matter = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['states_of_matter' => $states_of_matter]);
        } catch (PDOException $e) {
            error_log("Error in get_dropdown_data: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to retrieve dropdown data.']);
        }
        break;

    case 'fetch':
        $query = $_GET['query'] ?? '';
        // แก้ไข: เปลี่ยน pt.full_word_eng เป็น pt.full_word_en เพื่อให้ตรงกับชื่อคอลัมน์ในฐานข้อมูล
        $sql = "SELECT pt.id, pt.full_word_en, pt.abbreviation_word_eng, pt.full_word_th, pt.abbreviation_word_th, pt.usage_id, sm.matter_th AS name_state
                FROM data_propulsion_type pt
                LEFT JOIN data_state_of_matter sm ON pt.state_of_matter_id = sm.id";
        $params = [];
        if (!empty($query)) {
            // แก้ไข: เปลี่ยน pt.full_word_eng เป็น pt.full_word_en ในเงื่อนไข WHERE ด้วย
            $sql .= " WHERE pt.id LIKE ? OR pt.full_word_th LIKE ? OR pt.abbreviation_word_th LIKE ? OR sm.matter_th LIKE ? OR pt.full_word_en LIKE ? OR pt.abbreviation_word_eng LIKE ?";
            $searchTerm = "%$query%";
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }
        $sql .= " ORDER BY pt.id ASC";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['data' => $data]);
        } catch (PDOException $e) {
            error_log("Error in fetch: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถดึงข้อมูลได้: ' . $e->getMessage()]);
        }
        break;

    case 'get_one':
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ไม่พบ ID ที่ระบุ']);
            exit();
        }
        try {
            $stmt = $pdo->prepare("SELECT * FROM data_propulsion_type WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูลสำหรับ ID นี้']);
            }
        } catch (PDOException $e) {
            error_log("Error in get_one: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถดึงข้อมูลได้: ' . $e->getMessage()]);
        }
        break;

    case 'save':
        $is_edit = !empty($_POST['edit_id']);
        $id = $_POST['edit_id'] ?? null;

        // แก้ไข: ใช้ full_word_eng เป็นชื่อของฟอร์ม input
        $full_word_eng = $_POST['full_word_eng'] ?? ''; // ค่าที่มาจากฟอร์ม HTML
        $abbreviation_word_eng = $_POST['abbreviation_word_eng'] ?? '';
        $full_word_th = $_POST['full_word_th'] ?? '';
        $abbreviation_word_th = $_POST['abbreviation_word_th'] ?? '';
        $state_of_matter_id = $_POST['state_of_matter_id'] ?? '';
        $usage_id = isset($_POST['usage_status']) && $_POST['usage_status'] == 'on' ? '1' : '0';

        if (empty($full_word_eng) || empty($full_word_th) || empty($state_of_matter_id)) {
            echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน (ชื่อเต็มภาษาอังกฤษ, ชื่อเต็มภาษาไทย, สถานะของสสาร)']);
            exit();
        }

        try {
            if ($is_edit) {
                // แก้ไข: ใช้ full_word_en ใน SQL query ให้ตรงกับชื่อคอลัมน์ในฐานข้อมูล
                $sql = "UPDATE data_propulsion_type SET full_word_en=?, abbreviation_word_eng=?, full_word_th=?, abbreviation_word_th=?, state_of_matter_id=?, usage_id=? WHERE id=?";
                $params = [
                    $full_word_eng, // ใช้ค่าจากฟอร์ม input
                    $abbreviation_word_eng,
                    $full_word_th,
                    $abbreviation_word_th,
                    $state_of_matter_id,
                    $usage_id,
                    $id
                ];
            } else {
                $stmt = $pdo->query("SELECT MAX(CAST(SUBSTRING(id, 3) AS UNSIGNED)) as max_id FROM data_propulsion_type");
                $max_id = $stmt->fetchColumn();
                $new_id_num = ($max_id ?? 0) + 1;
                $id = 'PT' . str_pad($new_id_num, 2, '0', STR_PAD_LEFT);

                // แก้ไข: ใช้ full_word_en ใน SQL query ให้ตรงกับชื่อคอลัมน์ในฐานข้อมูล
                $sql = "INSERT INTO data_propulsion_type (id, full_word_en, abbreviation_word_eng, full_word_th, abbreviation_word_th, state_of_matter_id, usage_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $params = [
                    $id,
                    $full_word_eng, // ใช้ค่าจากฟอร์ม input
                    $abbreviation_word_eng,
                    $full_word_th,
                    $abbreviation_word_th,
                    $state_of_matter_id,
                    $usage_id
                ];
            }

            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                echo json_encode(['status' => 'success', 'message' => 'บันทึกข้อมูลสำเร็จ', 'id' => $id]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้: ' . implode(" ", $stmt->errorInfo())]);
            }
        } catch (PDOException $e) {
            error_log("Error in save: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
        }
        break;

    case 'delete':
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ไม่พบ ID ที่ระบุสำหรับการลบ']);
            exit();
        }
        try {
            $stmt = $pdo->prepare("DELETE FROM data_propulsion_type WHERE id = ?");
            if ($stmt->execute([$id])) {
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูลที่ต้องการลบ']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้: ' . implode(" ", $stmt->errorInfo())]);
            }
        } catch (PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Action ไม่ถูกต้อง']);
        break;
}
?>