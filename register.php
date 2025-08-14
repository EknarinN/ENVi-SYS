<?php
// 1. เชื่อมต่อฐานข้อมูล
$pdo = require_once 'core/connect_db.php';

// 2. ดึงข้อมูลสำหรับ Dropdowns
$stmt_prefix = $pdo->query("SELECT id, full_word FROM data_prefix_name WHERE usage_id = '1' ORDER BY id");
$prefixes = $stmt_prefix->fetchAll();
$stmt_dep_group = $pdo->query("SELECT id, name_group FROM data_departments_group WHERE usage_id = '1' ORDER BY id");
$department_groups = $stmt_dep_group->fetchAll();
$stmt_role = $pdo->query("SELECT id, name_role FROM data_role WHERE usage_id = '1' ORDER BY id");
$roles = $stmt_role->fetchAll();

// ... ส่วน include ...

// เรียกใช้งาน header สำหรับฟอร์ม
include_once 'templates/header_form.php';

// --- ส่วนสำหรับแสดง Flash Message (เพิ่มใหม่) ---
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    echo '<div class="row justify-content-center"><div class="col-lg-8"><div class="alert alert-' . $message['type'] . ' alert-dismissible fade show" role="alert">';
    echo $message['message'];
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div></div></div>';
    // ลบ message ออกไปหลังจากแสดงผลแล้ว
    unset($_SESSION['flash_message']);
}
// --- จบส่วน Flash Message ---
?>

<div class="text-center my-4">
    <img src="assets/images/logo/logo.png" alt="ENVi-SYS Logo" width="200" class="mb-3">
    <h3 class="text-success">ระบบจัดการสิ่งแวดล้อม</h3>
    <h4 class="text-muted fw-normal">โรงพยาบาลอุทุมพรพิสัย</h4>
    <hr class="w-50 mx-auto">
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> ลงทะเบียนเข้าใช้งาน : ENVi-SYS</h4>
            </div>
            <div class="card-body p-4">
                <form action="core/register_process.php" method="POST">

                    <div class="border p-3 mb-4 rounded bg-success-subtle">
                        <h5 class="mb-3 text-success"><i class="bi bi-person-bounding-box"></i> ข้อมูลส่วนตัว</h5>
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label for="prefix" class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                                <select class="form-select" id="prefix" name="prefix" required>
                                    <option value="" selected disabled>เลือก...</option>
                                    <?php foreach ($prefixes as $prefix): ?>
                                        <option value="<?php echo htmlspecialchars($prefix['id']); ?>">
                                            <?php echo htmlspecialchars($prefix['full_word']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="first_name" class="form-label">ชื่อจริง <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                                <div id="name_feedback" class="form-text"></div>
                            </div>
                            <div class="col-md-5">
                                <label for="last_name" class="form-label">นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-2 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label">วัน/เดือน/ปีเกิด (พ.ศ.) <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <div class="col-sm-4">
                                        <select class="form-select" id="dob_day" name="dob_day" required>
                                            <option value="">-- วัน --</option>
                                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="dob_month" name="dob_month" required>
                                            <option value="">-- เดือน --</option>
                                            <?php
                                            $months = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
                                            foreach ($months as $index => $month):
                                            ?>
                                                <option value="<?php echo $index + 1; ?>"><?php echo $month; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="dob_year" name="dob_year" required>
                                            <option value="">-- ปี พ.ศ. --</option>
                                            <?php
                                            $current_be_year = date("Y") + 543;
                                            for ($i = $current_be_year; $i >= $current_be_year - 100; $i--):
                                            ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">อายุ</label>
                                <input type="text" class="form-control" id="ageDisplay" placeholder="-" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="border p-3 mb-4 rounded bg-primary-subtle">
                        <h5 class="mb-3 text-primary"><i class="bi bi-diagram-3"></i> ข้อมูลหน่วยงานและตำแหน่ง</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="department_group" class="form-label">กลุ่มงานหลัก <span class="text-danger">*</span></label>
                                <select class="form-select" id="department_group" name="department_group" required>
                                    <option value="" selected disabled>เลือกกลุ่มงานหลัก...</option>
                                    <?php foreach ($department_groups as $group): ?>
                                        <option value="<?php echo htmlspecialchars($group['id']); ?>">
                                            <?php echo htmlspecialchars($group['name_group']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="department_sub" class="form-label">หน่วยงานย่อย <span class="text-danger">*</span></label>
                                <select class="form-select" id="department_sub" name="department_sub" required disabled>
                                    <option value="" selected disabled>กรุณาเลือกกลุ่มงานหลักก่อน</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="role" class="form-label">ตำแหน่งงาน <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" selected disabled>เลือกตำแหน่งงาน...</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo htmlspecialchars($role['id']); ?>">
                                            <?php echo htmlspecialchars($role['name_role']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="border p-3 mb-4 rounded bg-warning-subtle">
                        <h5 class="mb-3 text-warning"><i class="bi bi-key-fill"></i> ข้อมูลสำหรับเข้าระบบ</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">อีเมล <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div id="email_feedback" class="form-text"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">ชื่อผู้ใช้งาน (Username) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <div id="username_feedback" class="form-text"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        <br>
                        <span class="text-danger">* กรุณากรอก E-mail ที่ใช้งานจริง เนื่องจากระบบจะส่ง Link เพื่อยืนยันการลงทะเบียนไปที่ E-mail ที่ลงทะเบียนไว้ </span>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle-fill"></i> ยืนยันการลงทะเบียน</button>
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