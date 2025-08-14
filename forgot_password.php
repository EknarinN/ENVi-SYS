<?php
session_start();
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

        <?php
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            echo '<div class="alert alert-' . $message['type'] . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($message['message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            unset($_SESSION['flash_message']);
        }
        ?>

        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h4 class="mb-0"><i class="bi bi-person-fill-exclamation"></i> ลืมรหัสผ่าน : ENVi-SYS</h4>
            </div>
            <div class="card-body p-4">
                <p class="card-text text-muted">กรุณากรอกอีเมลของคุณเพื่อขอลิงก์สำหรับตั้งรหัสผ่านใหม่ ระบบจะส่งลิงก์ไปยังอีเมลของคุณหากมีข้อมูลอยู่ในระบบ</p>
                <form action="core/forgot_password_process.php" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="กรอกอีเมลของคุณ" required>
                        <label for="email">อีเมล</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning"><i class="bi bi-send-fill"></i> ส่งลิงก์ตั้งรหัสผ่านใหม่</button>
                    </div>
                </form>
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