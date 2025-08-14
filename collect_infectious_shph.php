<?php
include_once 'templates/header.php';
// ดึง master data ที่จำเป็น เช่น หน่วย, รายชื่อ รพ.สต. (ถ้ามี)
// ดึง master data
$units = $pdo->query("SELECT id, name_th FROM data_unit_matrix WHERE usage_id = '1' ORDER BY id")->fetchAll();
$shphs = $pdo->query("SELECT id, hosp_name FROM data_hospital ORDER BY hosp_name")->fetchAll();
$waste_groups = $pdo->query("SELECT id, name_group FROM data_waste_group WHERE usage_id = '1' ORDER BY id")->fetchAll();
$waste_types = $pdo->query("SELECT id, name_type FROM data_waste_type WHERE usage_id = '1' ORDER BY id")->fetchAll();
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker-bs5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

<div class="d-flex align-items-center gap-3 pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 mb-0 d-flex align-items-center text-danger" style="gap:12px;">
        <i class="bi bi-virus" style="font-size:2.2rem;color:#dc3545;"></i>
        บันทึกการเก็บขยะติดเชื้อ รพ.สต.
    </h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-danger text-white"><h5 class="mb-0">ฟอร์มบันทึกการเก็บขยะติดเชื้อ รพ.สต.</h5></div>
            <div class="card-body bg-light">
                <form id="form-infectious-shph" autocomplete="off">
                    <input type="hidden" name="edit_id" id="edit_id_infectious_shph">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">วันที่เก็บ</label>
                            <input type="text" class="form-control custom-datepicker" id="date_collect" name="date_collect" required disabled autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">เวลาเก็บ</label>
                            <input type="text" class="form-control" id="time_collect" name="time_collect" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">รพ.สต.</label>
                            <select class="form-select" name="shph_id" id="shph_id" required disabled>
                                <option value="">- เลือก รพ.สต. -</option>
                                <?php foreach($shphs as $shph): ?>
                                    <option value="<?php echo $shph['id']; ?>"><?php echo htmlspecialchars($shph['hosp_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">ครั้งที่</label>
                            <input type="number" class="form-control" name="times" id="times" min="1" required readonly disabled>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">กลุ่มขยะ</label>
                                    <select class="form-select" name="waste_group_id" id="waste_group_id" required disabled>
                                        <option value="">- เลือกกลุ่มขยะ -</option>
                                        <?php foreach($waste_groups as $group): ?>
                                            <option value="<?php echo $group['id']; ?>" <?php echo $group['id']==='WG03' ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($group['name_group']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ประเภทขยะ</label>
                                    <select class="form-select" name="waste_type_id" id="waste_type_id" required disabled>
                                        <option value="">- เลือกประเภทขยะ -</option>
                                        <?php foreach($waste_types as $type): ?>
                                            <option value="<?php echo $type['id']; ?>" <?php echo $type['id']==='WT00' ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($type['name_type']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ปริมาณ (กก.)</label>
                            <input type="number" step="0.01" class="form-control" name="quantity" id="quantity" required disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">หน่วย</label>
                            <select class="form-select" name="unit_matrix_id" id="unit_matrix_id" required disabled>
                                <?php foreach($units as $unit): ?>
                                    <option value="<?php echo $unit['id']; ?>" <?php echo $unit['id'] == 'UN03' ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($unit['name_th']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" rows="2" name="waste_note" id="waste_note" disabled></textarea>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="button" id="add-new-button-infectious-shph" class="btn btn-success"><i class="bi bi-plus-circle-fill"></i> เพิ่ม</button>
                        <button type="reset" id="clear-button-infectious-shph" class="btn btn-secondary"><i class="bi bi-x-circle"></i> เคลียร์</button>
                        <button type="button" id="edit-button-infectious-shph" class="btn btn-warning" disabled><i class="bi bi-pencil-square"></i> แก้ไข</button>
                        <button type="submit" id="save-button-infectious-shph" class="btn btn-primary" disabled><i class="bi bi-save-fill"></i> บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white"><h5 class="mb-0">รายการบันทึกการเก็บขยะติดเชื้อ รพ.สต.</h5></div>
            <div class="card-body bg-light">
                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label">ค้นหา รพ.สต.</label>
                        <select class="form-select" id="search-shph-id">
                            <option value="">- เลือก รพ.สต. -</option>
                            <?php foreach($shphs as $shph): ?>
                                <option value="<?php echo $shph['id']; ?>"><?php echo htmlspecialchars($shph['hosp_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ค้นหาตามวันที่</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-from-date" placeholder="จากวันที่">
                            <input type="text" class="form-control" id="search-to-date" placeholder="ถึงวันที่">
                            <button type="button" class="btn btn-primary" id="search-date-button"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th>วันที่เก็บ</th>
                                <th>เวลาเก็บ</th>
                                <th>รพ.สต.</th>
                                <th>ปริมาณ (กก.)</th>
                                <th>หมายเหตุ</th>
                                <th>ผู้บันทึก</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody id="data-table-body-infectious-shph">
                            <tr><td colspan="7" class="text-center">ยังไม่มีการค้นหาข้อมูล...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/locales/th.js"></script>
<script src="assets/js/collect_infectious_shph.js"></script>
