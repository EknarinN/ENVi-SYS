$(document).ready(function() {
    // --- Setup for Organic Waste Tab ---
    const formOrganic = $('#form-organic');
    const saveButtonOrganic = $('#save-button-organic');
    const editButtonOrganic = $('#edit-button-organic');
    const clearButtonOrganic = $('#clear-button-organic');
    const addNewButtonOrganic = $('#add-new-button-organic');
    const tableBodyOrganic = $('#data-table-body-organic');
    const editIdFieldOrganic = $('#edit_id_organic');

    // Search elements
    const searchFromDateElem = $('#search-from-date-organic');
    const searchToDateElem = $('#search-to-date-organic');
    const searchTextElem = $('#search-text-organic');
    const searchDateButton = $('#search-date-button-organic');
    const searchTextButton = $('#search-text-button-organic');
    const showAllButton = $('#show-all-button-organic');

    // Form elements
    const recordDateElem = $('#date_record_organic');
    const timeRecordElem = $('#time_record_organic');
    const timesElem = $('#times_organic');

    // Datepicker
    const datepickerConfig = { format: 'dd/mm/yyyy', autohide: true, language: 'th-TH', todayHighlight: true };
    const recordDate = new Datepicker(recordDateElem[0], datepickerConfig);
    const searchFromDate = new Datepicker(searchFromDateElem[0], datepickerConfig);
    const searchToDate = new Datepicker(searchToDateElem[0], datepickerConfig);
    recordDate.setDate(new Date());
    recordDateElem.val(recordDateElem.val() || new Date().toLocaleDateString('th-TH'));

    function updateClock() {
        const now = new Date();
        const timeString = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);
        timeRecordElem.val(timeString);
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ฟังก์ชันตรวจสอบจำนวนครั้งที่บันทึกแล้วในวันนั้นและประเภทขยะนั้น
    function updateTimesOrganic() {
        const dateVal = recordDateElem.val();
        const wasteTypeId = formOrganic.find('select[name="waste_type_id"]').val();
        if (!dateVal || !wasteTypeId) {
            timesElem.val('');
            return;
        }
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: {
                action: 'get_next_times',
                type: 'organic',
                date: dateVal,
                waste_type_id: wasteTypeId
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.times) {
                    timesElem.val(response.times);
                } else {
                    timesElem.val('1');
                }
            }
        });
    }

    recordDateElem.on('changeDate', function(e) {
        if (!saveButtonOrganic.prop('disabled')) updateTimesOrganic();
    });
    formOrganic.find('select[name="waste_type_id"]').on('change', function() {
        if (!saveButtonOrganic.prop('disabled')) updateTimesOrganic();
    });

    function setFormStateOrganic(state) {
        const is_disabled = state === 'disabled' || state === 'view';
        formOrganic.find('input[name="waste_group_name"]').prop('disabled', true);
        timeRecordElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        recordDateElem.prop('disabled', is_disabled ? true : false);
        formOrganic.find('select[name="waste_type_id"]').prop('disabled', is_disabled ? true : false);
        formOrganic.find('input[name="quantity"]').prop('disabled', is_disabled ? true : false);
        formOrganic.find('select[name="unit_matrix_id"]').prop('disabled', is_disabled ? true : false);
        formOrganic.find('textarea[name="waste_note"]').prop('disabled', is_disabled ? true : false);
        saveButtonOrganic.prop('disabled', is_disabled);
        editButtonOrganic.prop('disabled', state !== 'view');
        addNewButtonOrganic.prop('disabled', !is_disabled);
    }

    addNewButtonOrganic.on('click', function() {
        clearButtonOrganic.trigger('click');
        setFormStateOrganic('enabled');
        recordDate.setDate(new Date());
        setTimeout(updateTimesOrganic, 200);
    });

    clearButtonOrganic.on('click', () => {
        formOrganic[0].reset();
        editIdFieldOrganic.val('');
        recordDate.setDate(new Date());
        recordDateElem.val(new Date().toLocaleDateString('th-TH'));
        updateTimesOrganic();
        formOrganic.find('select').trigger('change');
        setFormStateOrganic('disabled');
    });

    editButtonOrganic.on('click', () => setFormStateOrganic('enabled'));

    function fetchDataOrganic(searchParams = {}) {
        searchParams.action = 'fetch';
        searchParams.type = 'organic';
        tableBodyOrganic.html('<tr><td colspan="8" class="text-center">กำลังโหลดข้อมูล...</td></tr>');
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: searchParams,
            dataType: 'json',
            success: function(response) {
                tableBodyOrganic.empty();
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
                        tableBodyOrganic.append(row);
                    });
                } else {
                    tableBodyOrganic.html('<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>');
                }
            }
        });
    }

    searchTextButton.on('click', () => fetchDataOrganic({ query: searchTextElem.val() }));
    searchDateButton.on('click', () => fetchDataOrganic({ from_date: searchFromDate.getDate('dd/mm/yyyy'), to_date: searchToDate.getDate('dd/mm/yyyy') }));
    showAllButton.on('click', () => {
        searchFromDate.setDate(null);
        searchToDate.setDate(null);
        searchTextElem.val('');
        fetchDataOrganic();
    });

    tableBodyOrganic.on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: { action: 'get_one', type: 'organic', id: id },
            dataType: 'json',
            success: function(data) {
                editIdFieldOrganic.val(data.id);
                recordDate.setDate(data.date_record);
                timeRecordElem.val(data.time_record);
                timesElem.val(data.times);
                formOrganic.find('[name="waste_type_id"]').val(data.waste_type_id).trigger('change');
                formOrganic.find('[name="quantity"]').val(data.quantity);
                formOrganic.find('[name="unit_matrix_id"]').val(data.unit_matrix_id).trigger('change');
                formOrganic.find('[name="waste_note"]').val(data.waste_note);
                setFormStateOrganic('view');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    });

    tableBodyOrganic.on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?`)) {
            $.ajax({
                url: 'core/record_waste_handler.php',
                type: 'POST',
                data: { action: 'delete', type: 'organic', id: id },
                dataType: 'json',
                success: function(response) {
                    if (typeof Swal !== 'undefined') {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ',
                                text: 'ลบข้อมูลเรียบร้อยแล้ว',
                                timer: 1800,
                                showConfirmButton: false
                            });
                            fetchDataOrganic();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message || 'ไม่สามารถลบข้อมูลได้',
                            });
                        }
                    } else {
                        alert(response.message);
                        if (response.status === 'success') fetchDataOrganic();
                    }
                }
            });
        }
    });

    formOrganic.off('submit').on('submit', function(e) {
        e.preventDefault();
        // อัปเดตค่า time_record และ times ให้แน่ใจก่อน submit
        e.preventDefault();
        saveButtonOrganic.prop('disabled', true);
        // อัปเดตค่า time_record และ times ให้แน่ใจก่อน submit
        const now = new Date();
        const timeString = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);
        timeRecordElem.val(timeString);
        if (!timesElem.val()) {
            timesElem.val('1');
        }
        timeRecordElem.prop('disabled', false);
        timesElem.prop('disabled', false);
        const formData = $(this).serialize() + '&action=save&type=organic';
        timeRecordElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                saveButtonOrganic.prop('disabled', false);
                if (typeof Swal !== 'undefined') {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                            timer: 1800,
                            showConfirmButton: false
                        });
                        clearButtonOrganic.trigger('click');
                        fetchDataOrganic();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: response.message || 'ไม่สามารถบันทึกข้อมูลได้',
                        });
                    }
                } else {
                    alert(response.message);
                    if (response.status === 'success') fetchDataOrganic();
                }
            },
            error: function(xhr) {
                saveButtonOrganic.prop('disabled', false);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: xhr.responseText || 'ไม่สามารถบันทึกข้อมูลได้',
                    });
                } else {
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                }
            }
        });
    });
    });

    // --- Initial Load ---
    formOrganic.find('select').select2({ theme: 'bootstrap-5' });
    setFormStateOrganic('disabled');
    updateClock();
    recordDate.setDate(new Date());
    updateTimesOrganic();
});
