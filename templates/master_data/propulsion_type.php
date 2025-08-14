<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/envi_sys/templates/header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">ตั้งค่า : ประเภทแรงขับดันถังดับเพลิง</h1>
</div>

<div id="alert-container"></div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-plus-circle-fill"></i> เพิ่ม / แก้ไขข้อมูล</h5>
    </div>
    <div class="card-body bg-success-subtle">
        <form id="data-form">
            <input type="hidden" id="edit_id" name="edit_id">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="propulsion_type_id" class="form-label">รหัส</label>
                    <input type="text" class="form-control" id="propulsion_type_id" name="propulsion_type_id" placeholder="ระบบจะสร้างให้อัตโนมัติ" readonly>
                </div>
                <div class="col-md-4">
                    <label for="full_word_eng" class="form-label">ชื่อเต็ม (Eng)</label>
                    <input type="text" class="form-control" id="full_word_eng" name="full_word_eng" required disabled>
                </div>
                <div class="col-md-5">
                    <label for="abbreviation_word_eng" class="form-label">ชื่อย่อ (Eng)</label>
                    <input type="text" class="form-control" id="abbreviation_word_eng" name="abbreviation_word_eng" disabled>
                </div>
                <div class="col-md-3">
                    <label for="full_word_th" class="form-label">ชื่อเต็ม (ไทย)</label>
                    <input type="text" class="form-control" id="full_word_th" name="full_word_th" required disabled>
                </div>
                <div class="col-md-3">
                    <label for="abbreviation_word_th" class="form-label">ชื่อย่อ (ไทย)</label>
                    <input type="text" class="form-control" id="abbreviation_word_th" name="abbreviation_word_th" disabled>
                </div>
                <div class="col-md-3">
                    <label for="state_of_matter_id" class="form-label">สถานะของสสาร</label>
                    <select class="form-select" id="state_of_matter_id" name="state_of_matter_id" required disabled></select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
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

<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-table"></i> รายการข้อมูล</h5>
    </div>
    <div class="card-body bg-info-subtle">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="search-box" class="form-control" placeholder="พิมพ์คำค้น...">
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
                        <th scope="col">รหัส</th>
                        <th scope="col">ชื่อเต็ม (Eng)</th>
                        <th scope="col">ชื่อย่อ (Eng)</th>
                        <th scope="col">ชื่อเต็ม (ไทย)</th>
                        <th scope="col">ชื่อย่อ (ไทย)</th>
                        <th scope="col">สถานะของสสาร</th>
                        <th scope="col">สถานะ</th>
                        <th scope="col" style="width: 10%;">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="data-table-body">
                    <tr>
                        <td colspan="8" class="text-center">กำลังโหลดข้อมูล...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/envi_sys/templates/footer.php';
?>
<script src="../../assets/js/propulsion_type.js"></script>
</body>
</html>