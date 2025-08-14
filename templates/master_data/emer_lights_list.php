<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/envi_sys/templates/header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">ตั้งค่า : รายละเอียดของไฟฉุกเฉิน</h1>
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
                <div class="col-md-3"><label for="emer_lights_number" class="form-label">รหัสอุปกรณ์ 7 หลัก (เริ่มที่ ELN0001)</label><input type="text" class="form-control" id="emer_lights_number" name="emer_lights_number" required></div>
                <div class="col-md-3"><label for="brand" class="form-label">ยี่ห้อ</label><input type="text" class="form-control" id="brand" name="brand" required></div>
                <div class="col-md-3"><label for="model" class="form-label">รุ่น</label><input type="text" class="form-control" id="model" name="model" required></div>
                <div class="col-md-3"><label for="data_color_id" class="form-label">สี</label><select class="form-select" id="data_color_id" name="data_color_id" required></select></div>
            </div>
            <h6 class="text-muted mt-4"><b>คุณสมบัติ</b></h6>
            <hr class="mt-0">
            <div class="row g-3">
                <div class="col-md-4"><label for="emer_light_type_id" class="form-label">ประเภท</label><select class="form-select" id="emer_light_type_id" name="emer_light_type_id" required></select></div>
                <div class="col-md-4"><label for="data_emer_lights_installation_id" class="form-label">ลักษณะการติดตั้ง</label><select class="form-select" id="data_emer_lights_installation_id" name="data_emer_lights_installation_id" required></select></div>
                <div class="col-md-4"><label for="external_material" class="form-label">วัสดุภายนอก</label><input type="text" class="form-control" id="external_material" name="external_material"></div>
                <div class="col-md-3"><label for="size_width" class="form-label">กว้าง (ซม.)</label><input type="number" step="0.01" class="form-control" id="size_width" name="size_width"></div>
                <div class="col-md-3"><label for="size_height" class="form-label">สูง (ซม.)</label><input type="number" step="0.01" class="form-control" id="size_height" name="size_height"></div>
                <div class="col-md-3"><label for="size_thickness" class="form-label">หนา (ซม.)</label><input type="number" step="0.01" class="form-control" id="size_thickness" name="size_thickness"></div>
                <div class="col-md-3"><label for="weight" class="form-label">น้ำหนัก (กก.)</label><input type="number" step="0.01" class="form-control" id="weight" name="weight"></div>
            </div>
            <h6 class="text-muted mt-4"><b>ข้อมูลทางเทคนิค</b></h6>
            <hr class="mt-0">
            <div class="row g-3">
                <div class="col-md-3"><label for="input_voltage" class="form-label">แรงดันไฟเข้า (โวลต์)</label><input type="text" class="form-control" id="input_voltage" name="input_voltage"></div>
                <div class="col-md-3"><label for="output_voltage" class="form-label">แรงดันไฟออก (โวลต์)</label><input type="text" class="form-control" id="output_voltage" name="output_voltage"></div>
                <div class="col-md-3"><label for="power_watt" class="form-label">กำลังไฟ (วัตต์)</label><input type="number" step="0.01" class="form-control" id="power_watt" name="power_watt"></div>
                <div class="col-md-3"><label for="temperature" class="form-label">อุณหภูมิใช้งาน (°C)</label><input type="number" step="0.01" class="form-control" id="temperature" name="temperature"></div>
                <div class="col-md-3"><label for="ingress_protection" class="form-label">มาตรฐาน IP (กันฝุ่น กันน้ำ)</label><input type="text" class="form-control" id="ingress_protection" name="ingress_protection"></div>
                <div class="col-md-3"><label for="brightness_daylight" class="form-label">สว่างกลางวัน (lm)</label><input type="number" step="0.01" class="form-control" id="brightness_daylight" name="brightness_daylight"></div>
                <div class="col-md-3"><label for="brightness_nightlight" class="form-label">สว่างกลางคืน (lm)</label><input type="number" step="0.01" class="form-control" id="brightness_nightlight" name="brightness_nightlight"></div>
                <div class="col-md-3"><label for="light_distribution_angle" class="form-label">มุมกระจายแสง (°)</label><input type="text" class="form-control" id="light_distribution_angle" name="light_distribution_angle"></div>
                <div class="col-12 d-flex align-items-end">
                    <div class="form-check form-switch fs-5">
                        <input class="form-check-input" type="checkbox" role="switch" id="usage_status" name="usage_status" checked>
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

<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-table"></i> รายการข้อมูล</h5>
    </div>
    <div class="card-body bg-info-subtle">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="search-box" class="form-control" placeholder="ค้นหาหมายเลข, ยี่ห้อ, รุ่น...">
                    <button id="search-button" class="btn btn-primary" type="button"><i class="bi bi-search"></i> ค้นหา</button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <button id="show-all-button" class="btn btn-secondary" type="button"><i class="bi bi-list-ul"></i> แสดงข้อมูลทั้งหมด</button>
            </div>
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
<script src="../../assets/js/emer_lights_list.js"></script>
</body>

</html>