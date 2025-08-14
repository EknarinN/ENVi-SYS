$(document).ready(function() {
    // --- Setup for General Waste Tab ---
    const formGeneral = $('#form-general');
    const saveButtonGeneral = $('#save-button-general');
    const editButtonGeneral = $('#edit-button-general');
    const clearButtonGeneral = $('#clear-button-general');
    const addNewButtonGeneral = $('#add-new-button-general');
    const tableBodyGeneral = $('#data-table-body-general');
    const editIdFieldGeneral = $('#edit_id_general');

    // Search elements
    const searchFromDateElem = $('#search-from-date-general');
    const searchToDateElem = $('#search-to-date-general');
    const searchTextElem = $('#search-text-general');
    const searchDateButton = $('#search-date-button-general');
    const searchTextButton = $('#search-text-button-general');
    const showAllButton = $('#show-all-button-general');

    // Form elements
    const recordDateElem = $('#date_record_general');
    const timeRecordElem = $('#time_record_general');
    const timesElem = $('#times_general');

    function showAlert(message, type = 'success') {
        const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        $('#alert-container').html(alertHtml).find('.alert').delay(3000).fadeOut();
    }

    // ใช้ datepicker สวยงาม (เช่น vanillajs-datepicker)
    const datepickerConfig = { format: 'dd/mm/yyyy', autohide: true, language: 'th-TH', todayHighlight: true };
    const recordDate = new Datepicker(recordDateElem[0], datepickerConfig);
    const searchFromDate = new Datepicker(searchFromDateElem[0], datepickerConfig);
    const searchToDate = new Datepicker(searchToDateElem[0], datepickerConfig);
    // ตั้งค่าวันที่ปัจจุบันทันทีเมื่อโหลดหน้า
    recordDate.setDate(new Date());
    recordDateElem.val(recordDateElem.val() || new Date().toLocaleDateString('th-TH'));

    function updateClock() {
        const now = new Date();
        const timeString = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);
        timeRecordElem.val(timeString);
    }
    // เรียก updateClock ทุก 1 วินาที
    setInterval(updateClock, 1000);
    updateClock();

    function getNextTimes(jsDate, wasteType, targetField) {
        if (!jsDate || !wasteType || !targetField) return;
        const day = ('0' + jsDate.getDate()).slice(-2);
        const month = ('0' + (jsDate.getMonth() + 1)).slice(-2);
        const year = jsDate.getFullYear();
        const formattedDate = `${day}/${month}/${year}`;

        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: { action: 'get_next_times', date: formattedDate, type: wasteType },
            dataType: 'json',
            success: response => $(targetField).val(response.times)
        });
    }


    // ฟังก์ชันตรวจสอบจำนวนครั้งที่บันทึกแล้วในวันนั้นและประเภทขยะนั้น
    function updateTimesGeneral() {
        // ใช้วันที่และประเภทขยะที่เลือก
        const dateVal = recordDateElem.val();
        const wasteTypeId = formGeneral.find('select[name="waste_type_id"]').val();
        if (!dateVal || !wasteTypeId) {
            timesElem.val('');
            return;
        }
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: {
                action: 'get_next_times',
                type: 'general',
                date: dateVal,
                waste_type_id: wasteTypeId
            },
            dataType: 'json',
            success: function(response) {
                // response.times = จำนวนครั้งที่มีอยู่แล้ว + 1
                if (response && response.times) {
                    timesElem.val(response.times);
                } else {
                    timesElem.val('1');
                }
            }
        });
    }

    // เมื่อเปลี่ยนวันที่หรือประเภทขยะ (เฉพาะตอนเพิ่มข้อมูล)
    recordDateElem.on('changeDate', function(e) {
        if (!saveButtonGeneral.prop('disabled')) updateTimesGeneral();
    });
    formGeneral.find('select[name="waste_type_id"]').on('change', function() {
        if (!saveButtonGeneral.prop('disabled')) updateTimesGeneral();
    });

    function setFormStateGeneral(state) {
        const is_disabled = state === 'disabled' || state === 'view';
        // กลุ่มขยะ, เวลาที่บันทึก, ครั้งที่: disable ตลอด
        formGeneral.find('input[name="waste_group_name"]').prop('disabled', true);
        timeRecordElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        // วันที่บันทึก: enable เฉพาะตอนเพิ่ม/แก้ไข
        recordDateElem.prop('disabled', is_disabled ? true : false);
        // ประเภทขยะ, ปริมาณ, หน่วย, หมายเหตุ: enable เฉพาะตอนเพิ่ม/แก้ไข
        formGeneral.find('select[name="waste_type_id"]').prop('disabled', is_disabled ? true : false);
        formGeneral.find('input[name="quantity"]').prop('disabled', is_disabled ? true : false);
        formGeneral.find('select[name="unit_matrix_id"]').prop('disabled', is_disabled ? true : false);
        formGeneral.find('textarea[name="waste_note"]').prop('disabled', is_disabled ? true : false);
        // ปุ่ม
        saveButtonGeneral.prop('disabled', is_disabled);
        editButtonGeneral.prop('disabled', state !== 'view');
        addNewButtonGeneral.prop('disabled', !is_disabled);
    }

    addNewButtonGeneral.on('click', function() {
        clearButtonGeneral.trigger('click');
        setFormStateGeneral('enabled');
        // ตั้งค่าวันที่ปัจจุบันเมื่อกดเพิ่ม
        recordDate.setDate(new Date());
        // อัปเดตจำนวนครั้งที่ (ครั้งที่) ทันที
        setTimeout(updateTimesGeneral, 200); // รอให้ select2/render เสร็จก่อน
    });

    clearButtonGeneral.on('click', () => {
        formGeneral[0].reset();
        editIdFieldGeneral.val('');
        recordDate.setDate(new Date());
        recordDateElem.val(new Date().toLocaleDateString('th-TH'));
        getNextTimes(new Date(), 'general', '#times_general');
        formGeneral.find('select').trigger('change');
        setFormStateGeneral('disabled');
    });

    editButtonGeneral.on('click', () => {
        setFormStateGeneral('enabled');
    });

    function fetchDataGeneral(searchParams = {}) {
        searchParams.action = 'fetch';
        searchParams.type = 'general';

        tableBodyGeneral.html('<tr><td colspan="8" class="text-center">กำลังโหลดข้อมูล...</td></tr>');
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: searchParams,
            dataType: 'json',
            success: function(response) {
                tableBodyGeneral.empty();
                if (response.data && response.data.length > 0) {
                    response.data.forEach(item => {
                        const row = `<tr>
                            <td class="text-center">${new Date(item.date_record).toLocaleDateString('th-TH', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
                            <td class="text-center">${item.time_record}</td>
                            <td class="text-center">${item.times}</td>
                            <td>${item.name_type}</td>
                            <td class="text-end">${parseFloat(item.quantity).toFixed(2)}</td>
                            <td class="text-center">${item.unit_name}</td>
                            <td>${item.user_firstname}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${item.id}" title="แก้ไข"><i class="bi bi-pencil-fill"></i></button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id}" title="ลบ"><i class="bi bi-trash-fill"></i></button>
                            </td>
                        </tr>`;
                        tableBodyGeneral.append(row);
                    });
                } else {
                    tableBodyGeneral.html('<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>');
                }
            }
        });
    }

    searchTextButton.on('click', () => fetchDataGeneral({ query: searchTextElem.val() }));
    searchDateButton.on('click', () => fetchDataGeneral({ from_date: searchFromDate.getDate('dd/mm/yyyy'), to_date: searchToDate.getDate('dd/mm/yyyy') }));
    showAllButton.on('click', () => {
        searchFromDate.setDate(null);
        searchToDate.setDate(null);
        searchTextElem.val('');
        fetchDataGeneral();
    });

    tableBodyGeneral.on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: { action: 'get_one', type: 'general', id: id },
            dataType: 'json',
            success: function(data) {
                editIdFieldGeneral.val(data.id);
                recordDate.setDate(data.date_record);
                timeRecordElem.val(data.time_record);
                timesElem.val(data.times); // set ครั้งที่ ตามข้อมูลเดิม ไม่ใช่เลขถัดไป
                formGeneral.find('[name="waste_type_id"]').val(data.waste_type_id).trigger('change');
                formGeneral.find('[name="quantity"]').val(data.quantity);
                formGeneral.find('[name="unit_matrix_id"]').val(data.unit_matrix_id).trigger('change');
                formGeneral.find('[name="waste_note"]').val(data.waste_note);
                setFormStateGeneral('view');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    });

    tableBodyGeneral.on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?`)) {
            $.ajax({
                url: 'core/record_waste_handler.php',
                type: 'POST',
                data: { action: 'delete', type: 'general', id: id },
                dataType: 'json',
                success: function(response) {
                    showAlert(response.message, response.status);
                    if (response.status === 'success') {
                        fetchDataGeneral();
                    }
                }
            });
        }
    });

    formGeneral.off('submit').on('submit', function(e) {
        e.preventDefault();
        // อัปเดตค่า time_record และ times ให้แน่ใจก่อน submit
        // เวลาปัจจุบัน
        const now = new Date();
        const timeString = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);
        timeRecordElem.val(timeString);
        if (!timesElem.val()) {
            timesElem.val('1');
        }
        // ปลด disabled ชั่วคราวเพื่อให้ serialize ส่งค่าได้
        timeRecordElem.prop('disabled', false);
        timesElem.prop('disabled', false);
        const formData = $(this).serialize() + '&action=save&type=general';
        // set disabled กลับหลัง serialize
        timeRecordElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (typeof Swal !== 'undefined') {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                            timer: 1800,
                            showConfirmButton: false
                        });
                        clearButtonGeneral.trigger('click');
                        fetchDataGeneral();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: response.message || 'ไม่สามารถบันทึกข้อมูลได้',
                        });
                    }
                } else {
                    showAlert(response.message, response.status);
                    if (response.status === 'success') {
                        clearButtonGeneral.trigger('click');
                        fetchDataGeneral();
                    }
                }
            },
            error: function(xhr) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: xhr.responseText || 'ไม่สามารถบันทึกข้อมูลได้',
                    });
                } else {
                    showAlert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'danger');
                }
            }
        });
    });

    // --- Initial Load ---
    formGeneral.find('select').select2({ theme: 'bootstrap-5' });
    setFormStateGeneral('disabled');
    updateClock();
    recordDate.setDate(new Date());
    getNextTimes(new Date(), 'general', '#times_general');
});