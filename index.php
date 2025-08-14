<?php
// เรียกใช้งาน header
include_once 'templates/header.php';
?>

<div class="text-center my-4">
    <img src="assets/images/logo/logo.png" alt="ENVi-SYS Logo" width="200" class="mb-3 main-logo-flip">
    <h3 class="text-success">ระบบจัดการสิ่งแวดล้อม</h3>
    <h4 class="text-muted fw-normal">โรงพยาบาลอุทุมพรพิสัย</h4>
    <hr class="w-50 mx-auto">
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100 menu-card bg-success-subtle">
            <div class="card-body d-flex flex-column">
                <h4 class="card-title text-success">
                    <i class="bi bi-trash-fill"></i> บันทึกปริมาณขยะประจำวัน
                </h4>
                <p class="card-text">บันทึกข้อมูลขยะแต่ละประเภท แบ่งแต่ละกลุ่มงาน</p>
                <a href="record_waste.php" class="btn btn-success mt-auto">ไปหน้าระบบงาน</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100 menu-card bg-primary-subtle">
            <div class="card-body d-flex flex-column">
                <h4 class="card-title text-primary">
                    <i class="bi bi-droplet-half"></i> บันทึกการตรวจน้ำ
                </h4>
                <p class="card-text">บันทึกข้อมูลผลการตรวจน้ำเสีย น้ำทิ้ง และน้ำใช้ แต่ละพารามิเตอร์ประจำวันและสัปดาห์</p>
                <a href="#" class="btn btn-primary mt-auto">ไปหน้าระบบงาน</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100 menu-card bg-danger-subtle">
            <div class="card-body d-flex flex-column">
                <h4 class="card-title text-danger">
                    <i class="bi bi-virus"></i> บันทึกการเก็บขยะติดเชื้อ รพ.สต.
                </h4>
                <p class="card-text">บันทึกข้อมูลผลการเก็บขยะติดเชื้อประจำสัปดาห์ แยกราย รพ.สต.</p>
                <a href="collect_infectious_shph.php" class="btn btn-danger mt-auto">ไปหน้าระบบงาน</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100 menu-card bg-warning-subtle">
            <div class="card-body d-flex flex-column">
                <h4 class="card-title text-warning">
                    <i class="bi bi-shield-fill-check"></i> บันทึกการตรวจสอบอุปกรณ์ความปลอดภัย
                </h4>
                <p class="card-text">บันทึกผลการตรวจสอบอุปกรณ์ความปลอดภัยรายเดือน เช่น ถังดับเพลิง ไฟฉุกเฉิน แยกรายกลุ่มงาน</p>
                <a href="#" class="btn btn-warning mt-auto">ไปหน้าระบบงาน</a>
            </div>
        </div>
    </div>
</div>

<?php
// เรียกใช้งาน footer
include_once 'templates/footer.php';
?>