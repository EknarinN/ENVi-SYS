$(document).ready(function() {
    // --- Setup for Hazardous Waste Tab ---
    const formHazardous = $('#form-hazardous');
    const saveButtonHazardous = $('#save-button-hazardous');
    const editButtonHazardous = $('#edit-button-hazardous');
    const clearButtonHazardous = $('#clear-button-hazardous');
    const addNewButtonHazardous = $('#add-new-button-hazardous');
    const tableBodyHazardous = $('#data-table-body-hazardous');
    const editIdFieldHazardous = $('#edit_id_hazardous');

    // Search elements
    const searchFromDateElem = $('#search-from-date-hazardous');
    const searchToDateElem = $('#search-to-date-hazardous');
    const searchTextElem = $('#search-text-hazardous');
    const searchDateButton = $('#search-date-button-hazardous');
    const searchTextButton = $('#search-text-button-hazardous');
    const showAllButton = $('#show-all-button-hazardous');

    // Form elements
    const recordDateElem = $('#date_record_hazardous');
    const timeRecordElem = $('#time_record_hazardous');
    const timesElem = $('#times_hazardous');

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
    function updateTimesHazardous() {
        const dateVal = recordDateElem.val();
        const wasteTypeId = formHazardous.find('select[name="waste_type_id"]').val();
        if (!dateVal || !wasteTypeId) {
            timesElem.val('');
            return;
        }
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: {
                action: 'get_next_times',
                type: 'hazardous',
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
        if (!saveButtonHazardous.prop('disabled')) updateTimesHazardous();
    });
    formHazardous.find('select[name="waste_type_id"]').on('change', function() {
        if (!saveButtonHazardous.prop('disabled')) updateTimesHazardous();
    });

    function setFormStateHazardous(state) {
        const is_disabled = state === 'disabled' || state === 'view';
        formHazardous.find('input[name="waste_group_name"]').prop('disabled', true);
        timeRecordElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        recordDateElem.prop('disabled', is_disabled ? true : false);
        formHazardous.find('select[name="waste_type_id"]').prop('disabled', is_disabled ? true : false);
        formHazardous.find('input[name="quantity"]').prop('disabled', is_disabled ? true : false);
        formHazardous.find('select[name="unit_matrix_id"]').prop('disabled', is_disabled ? true : false);
        formHazardous.find('textarea[name="waste_note"]').prop('disabled', is_disabled ? true : false);
        saveButtonHazardous.prop('disabled', is_disabled);
        editButtonHazardous.prop('disabled', state !== 'view');
        addNewButtonHazardous.prop('disabled', !is_disabled);
    }

    addNewButtonHazardous.on('click', function() {
        clearButtonHazardous.trigger('click');
        setFormStateHazardous('enabled');
        recordDate.setDate(new Date());
        setTimeout(updateTimesHazardous, 200);
    });

    clearButtonHazardous.on('click', () => {
        formHazardous[0].reset();
        editIdFieldHazardous.val('');
        recordDate.setDate(new Date());
        recordDateElem.val(new Date().toLocaleDateString('th-TH'));
        updateTimesHazardous();
        formHazardous.find('select').trigger('change');
        setFormStateHazardous('disabled');
    });

    editButtonHazardous.on('click', () => setFormStateHazardous('enabled'));

    function fetchDataHazardous(searchParams = {}) {
        searchParams.action = 'fetch';
        searchParams.type = 'hazardous';
        tableBodyHazardous.html('<tr><td colspan="8" class="text-center">กำลังโหลดข้อมูล...</td></tr>');
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: searchParams,
            dataType: 'json',
            success: function(response) {
                tableBodyHazardous.empty();
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
                        tableBodyHazardous.append(row);
                    });
                } else {
                    tableBodyHazardous.html('<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>');
                }
            }
        });
    }

    searchTextButton.on('click', () => fetchDataHazardous({ query: searchTextElem.val() }));
    searchDateButton.on('click', () => fetchDataHazardous({ from_date: searchFromDate.getDate('dd/mm/yyyy'), to_date: searchToDate.getDate('dd/mm/yyyy') }));
    showAllButton.on('click', () => {
        searchFromDate.setDate(null);
        searchToDate.setDate(null);
        searchTextElem.val('');
        fetchDataHazardous();
    });

    tableBodyHazardous.on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: { action: 'get_one', type: 'hazardous', id: id },
            dataType: 'json',
            success: function(data) {
                editIdFieldHazardous.val(data.id);
                recordDate.setDate(data.date_record);
                timeRecordElem.val(data.time_record);
                timesElem.val(data.times);
                formHazardous.find('[name="waste_type_id"]').val(data.waste_type_id).trigger('change');
                formHazardous.find('[name="quantity"]').val(data.quantity);
                formHazardous.find('[name="unit_matrix_id"]').val(data.unit_matrix_id).trigger('change');
                formHazardous.find('[name="waste_note"]').val(data.waste_note);
                setFormStateHazardous('view');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    });

    tableBodyHazardous.on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?`)) {
            $.ajax({
                url: 'core/record_waste_handler.php',
                type: 'POST',
                data: { action: 'delete', type: 'hazardous', id: id },
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
                            fetchDataHazardous();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message || 'ไม่สามารถลบข้อมูลได้',
                            });
                        }
                    } else {
                        alert(response.message);
                        if (response.status === 'success') fetchDataHazardous();
                    }
                }
            });
        }
    });

    formHazardous.off('submit').on('submit', function(e) {
        e.preventDefault();
        // อัปเดตค่า time_record และ times ให้แน่ใจก่อน submit
        e.preventDefault();
        saveButtonHazardous.prop('disabled', true);
        // อัปเดตค่า time_record และ times ให้แน่ใจก่อน submit
        const now = new Date();
        const timeString = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);
        timeRecordElem.val(timeString);
        if (!timesElem.val()) {
            timesElem.val('1');
        }
        timeRecordElem.prop('disabled', false);
        timesElem.prop('disabled', false);
        const formData = $(this).serialize() + '&action=save&type=hazardous';
        timeRecordElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                saveButtonHazardous.prop('disabled', false);
                if (typeof Swal !== 'undefined') {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                            timer: 1800,
                            showConfirmButton: false
                        });
                        clearButtonHazardous.trigger('click');
                        fetchDataHazardous();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: response.message || 'ไม่สามารถบันทึกข้อมูลได้',
                        });
                    }
                } else {
                    alert(response.message);
                    if (response.status === 'success') fetchDataHazardous();
                }
            },
            error: function(xhr) {
                saveButtonHazardous.prop('disabled', false);
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
    formHazardous.find('select').select2({ theme: 'bootstrap-5' });
    setFormStateHazardous('disabled');
    updateClock();
    recordDate.setDate(new Date());
    updateTimesHazardous();
});
