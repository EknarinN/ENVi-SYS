<?php
// เรียกใช้งาน header สำหรับหน้าภายใน
include_once 'templates/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-6">

        <?php
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            echo '<div class="alert alert-' . $message['type'] . ' alert-dismissible fade show" role="alert">';
            echo $message['message'];
            echo '<button type-button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['flash_message']);
        }
        ?>
        <div class="text-center my-4">
            <img src="assets/images/logo/logo.png" alt="ENVi-SYS Logo" width="200" class="mb-3">
            <h3 class="text-success">ระบบจัดการสิ่งแวดล้อม</h3>
            <h4 class="text-muted fw-normal">โรงพยาบาลอุทุมพรพิสัย</h4>
            <hr class="w-50 mx-auto">
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-muted">
                <h4 class="mb-0"><i class="bi bi-key-fill"></i> เปลี่ยนรหัสผ่าน : ENVi-SYS</h4>
            </div>
            <div class="card-body p-4">
                <form action="core/change_password_process.php" method="POST">
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="current_password" name="current_password" placeholder="รหัสผ่านปัจจุบัน" required>
                        <label for="current_password">รหัสผ่านปัจจุบัน</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="รหัสผ่านใหม่" required>
                        <label for="new_password">รหัสผ่านใหม่</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="ยืนยันรหัสผ่านใหม่" required>
                        <label for="confirm_new_password">ยืนยันรหัสผ่านใหม่</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save-fill"></i> บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// เรียกใช้งาน footer
include_once 'templates/footer.php';
?>