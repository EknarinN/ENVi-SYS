<?php
session_start();
$pdo = require_once 'connect_db.php';

// เรียกใช้ไลบรารี PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // --- ส่วนรับข้อมูลและ Validation (เหมือนเดิม) ---
    $prefix_id = $_POST['prefix'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $dob_day = $_POST['dob_day'];
    $dob_month = $_POST['dob_month'];
    $dob_year_be = $_POST['dob_year'];
    $department_group_id = $_POST['department_group'];
    $department_sub_id = $_POST['department_sub'];
    $role_id = $_POST['role'];
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // --- (โค้ด Validation ทั้งหมดของคุณเหมือนเดิม) ---

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $dob_year_ad = $dob_year_be - 543;
    $dob_formatted = sprintf("%04d-%02d-%02d", $dob_year_ad, $dob_month, $dob_day);
    $birthDate = new DateTime($dob_formatted);
    $today = new DateTime('today');
    $age = $birthDate->diff($today)->y;

    // --- ส่วนที่แก้ไข: สร้าง Token และตั้งค่าสถานะผู้ใช้ ---
    $token = bin2hex(random_bytes(50));
    $token_hash = hash('sha256', $token);
    $usage_id = '0'; // '0' หมายถึงยังไม่ Active

    try {
        // เพิ่ม token และ usage_id เข้าไปในคำสั่ง INSERT
        $sql = "INSERT INTO users 
                (prefix_name_id, first_name, last_name, dob, age, email, username, password_hash, departments_group_id, departments_sub_id, role_id, email_verification_token, usage_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $prefix_id, $first_name, $last_name, $dob_formatted, $age, $email, $username,
            $password_hash, $department_group_id, $department_sub_id, $role_id, $token_hash, $usage_id
        ]);
        
        // ---- ส่วนการส่งอีเมลจริง ----
        $config = parse_ini_file('config.ini', true);
        $verification_link = "http://{$_SERVER['HTTP_HOST']}/envi_sys/verify_email.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $config['mail']['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['mail']['smtp_user'];
            $mail->Password   = $config['mail']['smtp_pass'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $config['mail']['smtp_port'];
            $mail->CharSet    = "UTF-8";

            //Recipients
            $mail->setFrom('dn.premium.001@gmail.com', 'ENVi-SyS System'); // ใช้อีเมลที่คุณยืนยันกับ Brevo
            $mail->addAddress($email, $first_name);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'ยืนยันการลงทะเบียนเข้าใช้งานระบบ ENVi-SyS';
            $mail->Body    = "สวัสดีครับ,<br><br>ขอบคุณที่ลงทะเบียนเข้าใช้งานระบบ ENVi-SyS กรุณาคลิกที่ลิงก์ด้านล่างเพื่อยืนยันอีเมลและเปิดใช้งานบัญชีของคุณ:<br><br><a href='{$verification_link}'>{$verification_link}</a><br><br>ขอบคุณครับ,<br>ทีมงาน ENVi-SyS";
            
            $mail->send();
            
            // เปลี่ยนหน้าไปแสดงข้อความแจ้งเตือน
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => "ลงทะเบียนสำเร็จ! กรุณาตรวจสอบอีเมล ".htmlspecialchars($email)." เพื่อยืนยันการใช้งาน"];
            header('Location: ../login.php');
            exit();

        } catch (Exception $e) {
            // กรณีส่งอีเมลไม่สำเร็จ
            $_SESSION['flash_message'] = ['type' => 'danger', 'message' => "ลงทะเบียนสำเร็จ แต่ไม่สามารถส่งอีเมลยืนยันได้ กรุณาติดต่อผู้ดูแลระบบ"];
            header('Location: ../login.php');
            exit();
        }

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}