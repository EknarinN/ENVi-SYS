<?php
session_start();
$pdo = require_once 'core/connect_db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'กรุณากรอกชื่อผู้ใช้งานและรหัสผ่าน'];
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND usage_id = '1'");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_firstname'] = $user['first_name'];
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง'];
        }
    }
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - ENVi-SyS</title>
    <link rel="icon" href="assets/images/favicon/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>

<body class="login-page-body">
    <div class="card login-card p-4">
        <div class="card-body">
            <?php
            if (isset($_SESSION['flash_message'])) {
                $message = $_SESSION['flash_message'];
                echo '<div class="alert alert-' . $message['type'] . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($message['message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                unset($_SESSION['flash_message']);
            }
            ?>
            <div class="text-center mb-4">
                <img src="assets/images/logo/logo.png" class="" alt="ENVi-SYS Logo" width="200">
                <h3 class="text-success">ระบบจัดการสิ่งแวดล้อม</h3>
                <h4 class="text-muted fw-normal">โรงพยาบาลอุทุมพรพิสัย</h4>
            </div>
            <form action="login.php" method="POST" id="loginForm">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="ชื่อผู้ใช้งาน" required>
                    <label for="username">ชื่อผู้ใช้งาน</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน" required>
                    <label for="password">รหัสผ่าน</label>
                </div>
                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-primary-custom btn-lg text-light"><i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ</button>
                </div>
            </form>
            <div class="d-grid mt-2">
                <a href="register.php" class="btn btn-primary btn-lg"><i class="bi bi-person-plus-fill"></i> ลงทะเบียนเข้าใช้งาน</a>
            </div>
            <hr>
            <div class="d-grid gap-2">
                <a href="forgot_password.php" class="btn btn-warning btn-lg"><i class="bi bi-person-fill-exclamation"></i> ลืมรหัสผ่าน</a>
                <a href="#" class="btn btn-outline-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#dbSettingsModal"><i class="bi bi-database-fill-gear"></i> ตั้งค่าฐานข้อมูล</a>
            </div>
            <p class="text-center text-muted small mt-4 mb-0">&copy; 2025 ENVi-SYS Version 1.0.0</p>
        </div>
    </div>
    <div class="modal fade" id="dbSettingsModal" tabindex="-1" aria-labelledby="dbSettingsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dbSettingsModalLabel"><i class="bi bi-database-fill-gear"></i> ตั้งค่าการเชื่อมต่อฐานข้อมูล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="dbSettingsForm">
                        <div class="form-floating mb-3"><input type="text" class="form-control" id="db_host" name="db_host" placeholder="Server IP Address" required disabled>
                            <label for="db_host">Server IP Address (Host)</label>
                        </div>
                        <div class="form-floating mb-3"><input type="text" class="form-control" id="db_port" name="db_port" placeholder="Port" required disabled>
                            <label for="db_port">Database Port</label>
                        </div>
                        <div class="form-floating mb-3"><input type="text" class="form-control" id="db_name" name="db_name" placeholder="Database Name" required disabled>
                            <label for="db_name">Database Name</label>
                        </div>
                        <div class="form-floating mb-3"><input type="text" class="form-control" id="db_user" name="db_user" placeholder="Username" required disabled>
                            <label for="db_user">Username</label>
                        </div>
                        <div class="form-floating mb-3"><input type="password" class="form-control" id="db_pass" name="db_pass" placeholder="Password" disabled>
                            <label for="db_pass">Password</label>
                        </div>
                    </form>
                    <div id="testConnectionFeedback" class="mt-2 text-center fw-bold"></div>
                    <div id="dbSettingsFeedback"></div>
                </div>
                <div class="modal-footer d-flex flex-column flex-sm-row justify-content-between w-100">
                    <div class="mb-2 mb-sm-0"> <button type="button" id="editDbSettings" class="btn btn-warning">แก้ไข</button>
                    </div>
                    <div class="d-flex gap-2"> <button type="button" id="testDbConnection" class="btn btn-primary" disabled>ทดสอบการเชื่อมต่อ</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" form="dbSettingsForm" class="btn btn-success" disabled>บันทึกการตั้งค่า</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/login_scripts.js"></script>
</body>

</html>