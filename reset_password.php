<?php
$pdo = require_once 'core/connect_db.php';
session_start();

$token_is_valid = false;
$user_id = null;
$token = $_GET['token'] ?? '';

if (!empty($token)) {
    $token_hash = hash('sha256', $token);

    // ตรวจสอบ Token ในฐานข้อมูล (โดยยังไม่เช็คเวลาหมดอายุ)
    $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = ?");
    $stmt->execute([$token_hash]);
    $reset_request = $stmt->fetch();

    if ($reset_request) {
        $token_is_valid = true;
        $user_id = $reset_request['user_id'];
    }
}

include_once 'templates/header_form.php';
?>

<div class="text-center my-4">
    <img src="assets/images/logo/logo.png" alt="ENVi-SYS Logo" width="200" class="mb-3">
    <h3 class="text-success">ระบบจัดการสิ่งแวดล้อม</h3>
    <h4 class="text-muted fw-normal">โรงพยาบาลอุทุมพรพิสัย</h4>
    <hr class="w-50 mx-auto">
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-shield-lock-fill"></i> ตั้งรหัสผ่านใหม่</h4>
            </div>
            <div class="card-body p-4">
                <?php if ($token_is_valid): ?>
                    <form action="core/reset_password_process.php" method="POST">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="รหัสผ่านใหม่" required>
                            <label for="new_password">รหัสผ่านใหม่</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="ยืนยันรหัสผ่านใหม่" required>
                            <label for="confirm_new_password">ยืนยันรหัสผ่านใหม่</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">บันทึกรหัสผ่านใหม่</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger">
                        ลิงก์สำหรับตั้งรหัสผ่านใหม่ไม่ถูกต้องหรือหมดอายุแล้ว กรุณาทำรายการ <a href="forgot_password.php">ลืมรหัสผ่าน</a> ใหม่อีกครั้ง
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$show_footer = false;
include_once 'templates/footer.php';
?>
</body>

</html>