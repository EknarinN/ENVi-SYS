<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/envi_sys/templates/header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">ตั้งค่า : รายละเอียดของถังดับเพลิง</h1>
</div>

<div id="alert-container"></div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-plus-circle-fill"></i> เพิ่ม / แก้ไขข้อมูล</h5>
    </div>
    <div class="card-body bg-success-subtle">
        <form id="data-form">
            <input type="hidden" id="edit_id" name="edit_id">
            <h6 class="text-muted"><b>ข้อมูลทั่วไป</b></h6>
            <hr class="mt-0">
            <div class="row g-3">
                <div class="col-md-3"><label for="fire_extinguisher_number" class="form-label">รหัสอุปกรณ์ 7 หลัก (เริ่มที่ FEN0001)</label><input type="text" class="form-control" id="fire_extinguisher_number" name="fire_extinguisher_number" required disabled></div>
                <div class="col-md-3"><label for="brand" class="form-label">ยี่ห้อ</label><input type="text" class="form-control" id="brand" name="brand" required disabled></div>
                <div class="col-md-3"><label for="model" class="form-label">รุ่น</label><input type="text" class="form-control" id="model" name="model" required disabled></div>
                <div class="col-md-3"><label for="data_fire_extinguisher_type_id" class="form-label">ประเภท</label><select class="form-select" id="data_fire_extinguisher_type_id" name="data_fire_extinguisher_type_id" required disabled></select></div>
            </div>
            <h6 class="text-muted mt-4"><b>คุณสมบัติ</b></h6>
            <hr class="mt-0">
            <div class="row g-3">
                <div class="col-md-3"><label for="data_color_id" class="form-label">สี</label><select class="form-select" id="data_color_id" name="data_color_id" required disabled></select></div>
                <div class="col-md-3"><label for="capacity" class="form-label">น้ำหนักเคมี (กก.)</label><input type="number" step="0.01" class="form-control" id="capacity" name="capacity" disabled></div>
                <div class="col-md-3"><label for="weight_of_container" class="form-label">น้ำหนักถัง (กก.)</label><input type="number" step="0.01" class="form-control" id="weight_of_container" name="weight_of_container" disabled></div>
                <div class="col-md-3"><label for="gross_weight_approx" class="form-label">น้ำหนักรวม (กก.)</label><input type="number" step="0.01" class="form-control" id="gross_weight_approx" name="gross_weight_approx" disabled></div>
                <div class="col-md-3"><label for="unit_height" class="form-label">ความสูง (ซม.)</label><input type="number" step="0.01" class="form-control" id="unit_height" name="unit_height" disabled></div>
                <div class="col-md-3"><label for="diameter" class="form-label">เส้นผ่านศูนย์กลาง (ซม.)</label><input type="number" step="0.01" class="form-control" id="diameter" name="diameter" disabled></div>
                <div class="col-md-3"><label for="propulsion_type_id" class="form-label">ประเภทแรงขับดัน</label><select class="form-select" id="propulsion_type_id" name="propulsion_type_id" required disabled></select></div>
                <div class="col-md-3"><label for="fire_rating" class="form-label">Fire Rating (เช่น 4A-5B , 6A-20B)</label><input type="text" class="form-control" id="fire_rating" name="fire_rating" disabled></div>
            </div>
            <h6 class="text-muted mt-4"><b>ความสามารถในการดับเพลิงประเภทต่างๆ</b></h6>
            <hr class="mt-0">
            <div class="row g-3">
                <div class="col-auto">
                    <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="fire_type_a" name="fire_type_a" disabled><label class="form-check-label" for="fire_type_a">ไฟ Class A</label></div>
                </div>
                <div class="col-auto">
                    <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="fire_type_b" name="fire_type_b" disabled><label class="form-check-label" for="fire_type_b">ไฟ Class B</label></div>
                </div>
                <div class="col-auto">
                    <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="fire_type_c" name="fire_type_c" disabled><label class="form-check-label" for="fire_type_c">ไฟ Class C</label></div>
                </div>
                <div class="col-auto">
                    <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="fire_type_d" name="fire_type_d" disabled><label class="form-check-label" for="fire_type_d">ไฟ Class D</label></div>
                </div>
                <div class="col-auto">
                    <div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="fire_type_k" name="fire_type_k" disabled><label class="form-check-label" for="fire_type_k">ไฟ Class K</label></div>
                </div>
            </div>
            <h6 class="text-muted mt-4"><b>ข้อมูลทางเทคนิค</b></h6>
            <hr class="mt-0">
            <div class="row g-3">
                <div class="col-md-3"><label for="working_pressure" class="form-label">แรงดันใช้งาน (psi)</label><input type="number" step="0.01" class="form-control" id="working_pressure" name="working_pressure" disabled></div>
                <div class="col-md-3"><label for="test_pressure" class="form-label">แรงดันทดสอบ (psi)</label><input type="number" step="0.01" class="form-control" id="test_pressure" name="test_pressure" disabled></div>
                <div class="col-md-3"><label for="discharging_time" class="form-label">ระยะเวลาฉีด (วินาที)</label><input type="number" class="form-control" id="discharging_time" name="discharging_time" disabled></div>
                <div class="col-md-3 d-flex">
                    <div class="flex-fill me-1"><label for="shooting_range_min" class="form-label">ระยะฉีดใกล้สุด (ม.)</label><input type="number" class="form-control" id="shooting_range_min" name="shooting_range_min" disabled></div>
                    <div class="flex-fill ms-1"><label for="shooting_range_max" class="form-label">ไกลสุด (ม.)</label><input type="number" class="form-control" id="shooting_range_max" name="shooting_range_max" disabled></div>
                </div>
                <div class="col-12 d-flex align-items-end">
                    <div class="form-check form-switch fs-5">
                        <input class="form-check-input" type="checkbox" role="switch" id="usage_status" name="usage_status" checked disabled>
                        <label class="form-check-label" for="usage_status">สถานะใช้งาน</label>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button type="button" id="add-new-button" class="btn btn-success"><i class="bi bi-plus-circle-fill"></i> เพิ่มข้อมูลใหม่</button>
                <button type="reset" id="clear-button" class="btn btn-secondary"><i class="bi bi-x-circle"></i> เคลียร์ข้อความ</button>
                <button type="button" id="edit-button" class="btn btn-warning" disabled><i class="bi bi-pencil-square"></i> แก้ไขข้อมูล</button>
                <button type="submit" id="save-button" class="btn btn-primary" disabled><i class="bi bi-save-fill"></i> บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-table"></i> รายการข้อมูล</h5>
    </div>
    <div class="card-body bg-info-subtle">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group"><input type="text" id="search-box" class="form-control" placeholder="ค้นหาหมายเลข, ยี่ห้อ, รุ่น..."><button id="search-button" class="btn btn-primary" type="button"><i class="bi bi-search"></i> ค้นหา</button></div>
            </div>
            <div class="col-md-6 text-end"><button id="show-all-button" class="btn btn-secondary" type="button"><i class="bi bi-list-ul"></i> แสดงข้อมูลทั้งหมด</button></div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-light text-center">
                    <tr>
                        <th scope="col">หมายเลข</th>
                        <th scope="col">ยี่ห้อ</th>
                        <th scope="col">รุ่น</th>
                        <th scope="col">ประเภท</th>
                        <th scope="col">สถานะ</th>
                        <th scope="col" style="width: 10%;">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="data-table-body">
                    <tr>
                        <td colspan="6" class="text-center">ยังไม่มีการค้นหาข้อมูล...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/envi_sys/templates/footer.php';
?>
<script src="../../assets/js/fire_extinguisher_list.js"></script>
</body>

</html>