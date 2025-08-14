<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/envi_sys/templates/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">ตั้งค่า : ประเภทของไฟฉุกเฉิน</h1>
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
                    <label for="emer_light_type_id" class="form-label">รหัส</label>
                    <input type="text" class="form-control" id="emer_light_type_id" name="emer_light_type_id" placeholder="ระบบจะสร้างให้อัตโนมัติ" readonly>
                </div>
                <div class="col-md-6">
                    <label for="name_type" class="form-label">ประเภทของไฟฉุกเฉิน</label>
                    <input type="text" class="form-control" id="name_type" name="name_type" required disabled>
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
                    <input type="text" id="search-box" class="form-control" placeholder="พิมพ์รหัส หรือ ประเภทไฟฉุกเฉิน เพื่อค้นหา...">
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
                        <th scope="col">ประเภทของไฟฉุกเฉิน</th>
                        <th scope="col">สถานะ</th>
                        <th scope="col" style="width: 10%;">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="data-table-body">
                    <tr>
                        <td colspan="4" class="text-center">ยังไม่มีการค้นหาข้อมูล...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/envi_sys/templates/footer.php';
?>
<script src="../../assets/js/emer_lights_type.js"></script>
</body>

</html>