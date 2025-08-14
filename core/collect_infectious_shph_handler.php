// ...existing code...

if ($action === 'get_times') {
    $date_collect = $_GET['date_collect'] ?? '';
    $shph_id = $_GET['shph_id'] ?? '';
    $waste_group_id = $_GET['waste_group_id'] ?? '';
    $waste_type_id = $_GET['waste_type_id'] ?? '';
    if (!$date_collect || !$shph_id || !$waste_group_id || !$waste_type_id) {
        response('success', '', ['next_times' => 1]);
    }
    $stmt = $pdo->prepare("SELECT MAX(times) as max_times FROM waste_infectious_shph WHERE date_collect = STR_TO_DATE(?, '%d/%m/%Y') AND shph_id = ? AND waste_group_id = ? AND waste_type_id = ?");
    $stmt->execute([$date_collect, $shph_id, $waste_group_id, $waste_type_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_times = ($row && $row['max_times']) ? ((int)$row['max_times'] + 1) : 1;
    response('success', '', ['next_times' => $next_times]);
}
// Handler สำหรับบันทึก/ดึงข้อมูล waste_infectious_shph
include_once 'connect_db.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_REQUEST['action'] ?? '';

function response($status, $message = '', $data = null) {
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit;
}

if ($action === 'fetch') {
    $where = [];
    $params = [];
    if (!empty($_GET['shph_id'])) {
        $where[] = 'shph_id = ?';
        $params[] = $_GET['shph_id'];
    }
    if (!empty($_GET['from_date'])) {
        $where[] = 'date_collect >= STR_TO_DATE(?, "%d/%m/%Y")';
        $params[] = $_GET['from_date'];
    }
    if (!empty($_GET['to_date'])) {
        $where[] = 'date_collect <= STR_TO_DATE(?, "%d/%m/%Y")';
        $params[] = $_GET['to_date'];
    }
    $sql = "SELECT w.*, h.name AS shph_name, u.firstname AS user_firstname
            FROM waste_infectious_shph w
            LEFT JOIN data_hospital h ON w.shph_id = h.id
            LEFT JOIN users u ON w.user_id = u.id
            " . (count($where) ? 'WHERE ' . implode(' AND ', $where) : '') . "
            ORDER BY w.date_collect DESC, w.time_collect DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    response('success', '', ['data' => $data]);
}

if ($action === 'get_one' && !empty($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM waste_infectious_shph WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    response('success', '', $data);
}

if ($action === 'save') {
    $id = $_POST['edit_id'] ?? '';
    $date_collect = $_POST['date_collect'] ?? '';
    $time_collect = $_POST['time_collect'] ?? '';
    $shph_id = $_POST['shph_id'] ?? '';
    $waste_group_id = $_POST['waste_group_id'] ?? '';
    $waste_type_id = $_POST['waste_type_id'] ?? '';
    $times = $_POST['times'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $note = $_POST['waste_note'] ?? '';
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$date_collect || !$time_collect || !$shph_id || !$waste_group_id || !$waste_type_id || !$times || !$quantity) {
        response('error', 'กรุณากรอกข้อมูลให้ครบถ้วน');
    }
    if ($id) {
        // update
        $stmt = $pdo->prepare("UPDATE waste_infectious_shph SET date_collect=STR_TO_DATE(?, '%d/%m/%Y'), time_collect=?, shph_id=?, waste_group_id=?, waste_type_id=?, times=?, quantity=?, note=?, user_id=? WHERE id=?");
        $ok = $stmt->execute([$date_collect, $time_collect, $shph_id, $waste_group_id, $waste_type_id, $times, $quantity, $note, $user_id, $id]);
        response($ok ? 'success' : 'error', $ok ? 'แก้ไขข้อมูลสำเร็จ' : 'เกิดข้อผิดพลาด');
    } else {
        // insert
        $stmt = $pdo->prepare("INSERT INTO waste_infectious_shph (date_collect, time_collect, shph_id, waste_group_id, waste_type_id, times, quantity, note, user_id) VALUES (STR_TO_DATE(?, '%d/%m/%Y'), ?, ?, ?, ?, ?, ?, ?, ?)");
        $ok = $stmt->execute([$date_collect, $time_collect, $shph_id, $waste_group_id, $waste_type_id, $times, $quantity, $note, $user_id]);
        response($ok ? 'success' : 'error', $ok ? 'บันทึกข้อมูลสำเร็จ' : 'เกิดข้อผิดพลาด');
    }
}

if ($action === 'delete' && !empty($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM waste_infectious_shph WHERE id = ?");
    $ok = $stmt->execute([$_POST['id']]);
    response($ok ? 'success' : 'error', $ok ? 'ลบข้อมูลสำเร็จ' : 'เกิดข้อผิดพลาด');
}

response('error', 'ไม่พบการกระทำที่ร้องขอ');
