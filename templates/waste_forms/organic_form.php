<?php
// ฟอร์มบันทึกขยะอินทรีย์ (Organic Waste)
// โครงสร้างเหมือน general_form.php แต่เปลี่ยนชื่อและ id/class ให้เหมาะสมกับ organic
?>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white"><h5 class="mb-0">ฟอร์มบันทึกขยะอินทรีย์</h5></div>
    <div class="card-body bg-light">
        <form id="form-organic">
            <input type="hidden" name="edit_id" id="edit_id_organic">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">กลุ่มขยะ</label>
                    <input type="text" class="form-control" name="waste_group_name" value="ขยะอินทรีย์" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">วันที่บันทึก</label>
                    <input type="text" class="form-control custom-datepicker" id="date_record_organic" name="date_record" required disabled autocomplete="off">
                </div>
                <div class="col-md-4">
                    <label class="form-label">เวลาที่บันทึก</label>
                    <input type="text" class="form-control" id="time_record_organic" name="time_record" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ครั้งที่</label>
                    <input type="number" class="form-control" id="times_organic" name="times" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ประเภทขยะ</label>
                    <select class="form-select" name="waste_type_id" id="waste_type_id_organic" required disabled>
                        <?php foreach($waste_types as $type): ?>
                            <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name_type']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ปริมาณ</label>
                    <input type="number" step="0.01" class="form-control" name="quantity" required disabled>
                </div>
                <div class="col-md-2">
                    <label class="form-label">หน่วย</label>
                    <select class="form-select" name="unit_matrix_id" id="unit_matrix_id_organic" required disabled>
                        <?php foreach($units as $unit): ?>
                            <option value="<?php echo $unit['id']; ?>" <?php echo $unit['id'] == 'UN03' ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($unit['name_th']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">หมายเหตุ</label>
                    <textarea class="form-control" rows="3" name="waste_note" id="waste_note_organic" disabled></textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="button" id="add-new-button-organic" class="btn btn-success"><i class="bi bi-plus-circle-fill"></i> เพิ่ม</button>
                <button type="reset" id="clear-button-organic" class="btn btn-secondary"><i class="bi bi-x-circle"></i> เคลียร์</button>
                <button type="button" id="edit-button-organic" class="btn btn-warning" disabled><i class="bi bi-pencil-square"></i> แก้ไข</button>
                <button type="submit" id="save-button-organic" class="btn btn-primary" disabled><i class="bi bi-save-fill"></i> บันทึก</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white"><h5 class="mb-0">รายการบันทึกขยะอินทรีย์</h5></div>
    <div class="card-body bg-light">
        <div class="row g-3 mb-3">
            <div class="col-md-12">
                <label class="form-label">คำค้น</label>
                <div class="input-group">
                    <input type="text" id="search-text-organic" class="form-control" placeholder="ค้นหาประเภท, หมายเหตุ...">
                    <button id="search-text-button-organic" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <div class="col-md-10">
                <label class="form-label">ค้นหาตามช่วงวันที่</label>
                <div class="input-group">
                    <input type="text" id="search-from-date-organic" class="form-control" placeholder="จากวันที่">
                    <input type="text" id="search-to-date-organic" class="form-control" placeholder="ถึงวันที่">
                    <button id="search-date-button-organic" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button id="show-all-button-organic" class="btn btn-secondary w-100"><i class="bi bi-list-ul"></i> ทั้งหมด</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light text-center">
                    <tr>
                        <th>วันที่</th>
                        <th>เวลา</th>
                        <th>ครั้งที่</th>
                        <th>ประเภท</th>
                        <th>ปริมาณ</th>
                        <th>หน่วย</th>
                        <th>ผู้บันทึก</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody id="data-table-body-organic">
                    <tr><td colspan="8" class="text-center">ยังไม่มีการค้นหาข้อมูล...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
