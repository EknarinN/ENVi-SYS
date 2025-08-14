<?php
session_start();
$pdo = require_once 'connect_db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT id, first_name FROM users WHERE email = ? AND usage_id = '1'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // ย้ายการสร้าง Token มาไว้ตรงนี้
        $token = bin2hex(random_bytes(50));
        $token_hash = hash('sha256', $token);
        $expires_at = date("Y-m-d H:i:s", time() + 3600); 

        $insert_stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $insert_stmt->execute([$user['id'], $token_hash, $expires_at]);

        $config = parse_ini_file('config.ini', true);
        $reset_link = "http://{$_SERVER['HTTP_HOST']}/envi_sys/reset_password.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            // ... การตั้งค่า PHPMailer เหมือนเดิม ...
            $mail->isSMTP();
            $mail->Host       = $config['mail']['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['mail']['smtp_user'];
            $mail->Password   = $config['mail']['smtp_pass'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $config['mail']['smtp_port'];
            $mail->CharSet    = "UTF-8";

            //$mail->setFrom('noreply@envi-sys.com', 'ENVi-SYS System');
            //$mail->setFrom('eknarinnatthaphon@gmail.com', 'ENVi-SYS System');
            $mail->setFrom('dn.premium.001@gmail.com', 'ENVi-SYS System'); //เป็น E-mail ที่ Add ไว้ในส่วนของ Sender ของ Brevo
            $mail->addAddress($email, $user['first_name']);

            $mail->isHTML(true);
            $mail->Subject = 'คำขอตั้งรหัสผ่านใหม่สำหรับระบบ ENVi-SYS';
            $mail->Body    = "สวัสดีครับ,<br><br>เราได้รับคำขอให้ตั้งรหัสผ่านใหม่สำหรับบัญชีของคุณ กรุณาคลิกที่ลิงก์ด้านล่างเพื่อดำเนินการต่อ:<br><br><a href='{$reset_link}'>{$reset_link}</a><br><br>ลิงก์นี้จะหมดอายุใน 1 ชั่วโมง หากคุณไม่ได้เป็นผู้ร้องขอ กรุณาเพิกเฉยต่ออีเมลฉบับนี้<br><br>ขอบคุณครับ,<br>ทีมงาน ENVi-SyS";
            
            $mail->send();
        } catch (Exception $e) {
            // error_log("Mailer Error: " . $mail->ErrorInfo);
        }
    }
    
    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'หากอีเมลของคุณมีอยู่ในระบบ เราได้ส่งลิงก์สำหรับตั้งรหัสผ่านใหม่ให้แล้ว'];
    header('Location: ../forgot_password.php');
    exit();
}