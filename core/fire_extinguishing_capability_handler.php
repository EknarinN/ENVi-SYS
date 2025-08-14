<?php
session_start();
// ตรวจสอบว่าไฟล์ connect_db.php มีอยู่และสามารถ include ได้
if (!file_exists('connect_db.php')) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection file not found.']);
    exit();
}
$pdo = require_once 'connect_db.php';

// ตรวจสอบการเข้าสู่ระบบของผู้ใช้
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
        $sql = "SELECT id, name_capability, usage_id FROM data_fire_exting_capability"; // ดึงเฉพาะคอลัมน์ที่ต้องการ
        $params = [];
        if (!empty($query)) {
            // usage_id ในฐานข้อมูลเป็น varchar(1) ที่เก็บ '0' หรือ '1'
            // จึงควรใช้ LIKE กับค่าที่แปลงแล้ว ('ใช้งาน'/'ไม่ใช้งาน')
            $sql .= " WHERE id LIKE ? OR name_capability LIKE ? OR (CASE WHEN usage_id = '1' THEN 'ใช้งาน' WHEN usage_id = '0' THEN 'ไม่ใช้งาน' ELSE '' END) LIKE ?";
            $searchTerm = "%$query%";
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }
        $sql .= " ORDER BY id ASC"; // เพิ่มการเรียงลำดับข้อมูล

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลแบบ associative array
            echo json_encode(['data' => $data]);
        } catch (PDOException $e) {
            error_log("Error in fetch: " . $e->getMessage()); // บันทึก error ลงใน error log ของ PHP
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
            $stmt = $pdo->prepare("SELECT id, name_capability, usage_id FROM data_fire_exting_capability WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลแบบ associative array
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
        $id = $_POST['edit_id'] ?? null; // ใช้ค่าจาก edit_id ในกรณีที่เป็นการแก้ไข

        $name_capability = $_POST['name_capability'] ?? '';
        // ตรวจสอบค่าที่ส่งมาจาก checkbox: 'on' ถ้าถูกเลือก, ไม่มีค่าถ้าไม่ถูกเลือก
        $usage_id = isset($_POST['usage_status']) && $_POST['usage_status'] == 'on' ? '1' : '0';

        // การตรวจสอบข้อมูลที่จำเป็น
        if (empty($name_capability)) {
            echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกความสามารถในการดับเพลิง']);
            exit();
        }

        try {
            if ($is_edit) {
                // สำหรับ UPDATE, ID ถูกส่งมาแล้วจาก edit_id
                $sql = "UPDATE data_fire_exting_capability SET name_capability=?, usage_id=? WHERE id=?";
                $params = [
                    $name_capability,
                    $usage_id,
                    $id
                ];
            } else {
                // สำหรับ INSERT, ID ต้องเป็น '0' หรือ '1' เนื่องจาก varchar(1)
                // ตรวจสอบว่า ID '0' หรือ '1' มีอยู่แล้วหรือไม่
                // ถ้ามีข้อมูลแค่ '0' และ '1' ตาม SQL dump, อาจต้องให้ผู้ใช้เลือกหรือกำหนดเงื่อนไขการสร้าง ID ให้ชัดเจน
                // ในตัวอย่างนี้ ผมจะสมมติว่าถ้าไม่มี ID ในระบบเลย จะเริ่มต้นด้วย '0' หรือ '1' ได้
                // แต่ถ้าฐานข้อมูลมีอยู่แล้ว ควรจะจัดการให้ดีกว่านี้ (เช่น ถ้ามี '0' แล้วจะเพิ่ม '1' ได้หรือไม่?)

                // จาก SQL dump: INSERT INTO `data_fire_exting_capability` (`id`, `name_capability`, `usage_id`) VALUES ('0', 'ดับเพลิงประเภทนี้ไม่ได้', '1'), ('1', 'ดับเพลิงประเภทนี้ได้ดี', '1');
                // แสดงว่า ID ถูกกำหนดไว้อย่างชัดเจนแล้ว ไม่ได้สร้างแบบรันเลข
                // ดังนั้น การสร้าง ID แบบ MAX(SUBSTRING) จะใช้ไม่ได้กับ varchar(1) ที่เป็น '0' หรือ '1'
                // คุณควรจะให้ผู้ใช้กรอก '0' หรือ '1' ในช่องรหัสเมื่อเพิ่มข้อมูลใหม่
                // หรือเปลี่ยน type ของ id ใน DB เป็น int unsigned NOT NULL AUTO_INCREMENT

                // หากคุณต้องการให้ผู้ใช้เลือก '0' หรือ '1' เมื่อเพิ่มใหม่
                // คุณต้องอนุญาตให้ผู้ใช้แก้ไขช่อง ID ('capability_id') ได้เมื่อกด 'เพิ่มข้อมูลใหม่'
                // และตรวจสอบว่า ID ที่กรอกมานั้นถูกต้องและไม่ซ้ำ

                // ในกรณีนี้ ผมจะปรับให้ "การเพิ่มข้อมูลใหม่" ใช้ ID ที่ผู้ใช้กรอกมา
                // หรือถ้าช่อง ID ว่าง ให้แจ้งเตือนให้กรอก ID '0' หรือ '1'

                $new_id = $_POST['capability_id'] ?? ''; // ดึง ID จากฟอร์ม
                if (empty($new_id) || ($new_id != '0' && $new_id != '1')) {
                    echo json_encode(['status' => 'error', 'message' => 'สำหรับข้อมูลประเภทนี้ รหัสต้องเป็น "0" หรือ "1" เท่านั้น.']);
                    exit();
                }

                // ตรวจสอบว่า ID ที่จะเพิ่มซ้ำหรือไม่
                $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM data_fire_exting_capability WHERE id = ?");
                $stmt_check->execute([$new_id]);
                if ($stmt_check->fetchColumn() > 0) {
                    echo json_encode(['status' => 'error', 'message' => "รหัส {$new_id} มีอยู่แล้ว กรุณาใช้รหัสอื่นหรือแก้ไขข้อมูลที่มีอยู่"]);
                    exit();
                }

                $sql = "INSERT INTO data_fire_exting_capability (id, name_capability, usage_id) VALUES (?, ?, ?)";
                $params = [
                    $new_id,
                    $name_capability,
                    $usage_id
                ];
                $id = $new_id; // กำหนด ID ที่ใช้ในการตอบกลับ
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
            $stmt = $pdo->prepare("DELETE FROM data_fire_exting_capability WHERE id = ?");
            if ($stmt->execute([$id])) {
                if ($stmt->rowCount() > 0) { // ตรวจสอบว่ามีการลบข้อมูลจริงหรือไม่
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