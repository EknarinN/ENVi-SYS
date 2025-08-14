$(document).ready(function() {
    // --- Setup for Infectious Waste Tab ---
    const formInfectious = $('#form-infectious');
    const saveButtonInfectious = $('#save-button-infectious');
    const editButtonInfectious = $('#edit-button-infectious');
    const clearButtonInfectious = $('#clear-button-infectious');
    const addNewButtonInfectious = $('#add-new-button-infectious');
    const tableBodyInfectious = $('#data-table-body-infectious');
    const editIdFieldInfectious = $('#edit_id_infectious');

    // Search elements
    const searchFromDateElem = $('#search-from-date-infectious');
    const searchToDateElem = $('#search-to-date-infectious');
    const searchTextElem = $('#search-text-infectious');
    const searchDateButton = $('#search-date-button-infectious');
    const searchTextButton = $('#search-text-button-infectious');
    const showAllButton = $('#show-all-button-infectious');

    // Form elements
    const recordDateElem = $('#date_record_infectious');
    const timeRecordElem = $('#time_record_infectious');
    const timesElem = $('#times_infectious');

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
    function updateTimesInfectious() {
        const dateVal = recordDateElem.val();
        const wasteTypeId = formInfectious.find('select[name="waste_type_id"]').val();
        if (!dateVal || !wasteTypeId) {
            timesElem.val('');
            return;
        }
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: {
                action: 'get_next_times',
                type: 'infectious',
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
        if (!saveButtonInfectious.prop('disabled')) updateTimesInfectious();
    });
    formInfectious.find('select[name="waste_type_id"]').on('change', function() {
        if (!saveButtonInfectious.prop('disabled')) updateTimesInfectious();
    });

    function setFormStateInfectious(state) {
        const is_disabled = state === 'disabled' || state === 'view';
        formInfectious.find('input[name="waste_group_name"]').prop('disabled', true);
        timeRecordElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        recordDateElem.prop('disabled', is_disabled ? true : false);
        formInfectious.find('select[name="waste_type_id"]').prop('disabled', is_disabled ? true : false);
        formInfectious.find('input[name="quantity"]').prop('disabled', is_disabled ? true : false);
        formInfectious.find('select[name="unit_matrix_id"]').prop('disabled', is_disabled ? true : false);
        formInfectious.find('textarea[name="waste_note"]').prop('disabled', is_disabled ? true : false);
        saveButtonInfectious.prop('disabled', is_disabled);
        editButtonInfectious.prop('disabled', state !== 'view');
        addNewButtonInfectious.prop('disabled', !is_disabled);
    }

    addNewButtonInfectious.on('click', function() {
        clearButtonInfectious.trigger('click');
        setFormStateInfectious('enabled');
        recordDate.setDate(new Date());
        setTimeout(updateTimesInfectious, 200);
    });

    clearButtonInfectious.on('click', () => {
        formInfectious[0].reset();
        editIdFieldInfectious.val('');
        recordDate.setDate(new Date());
        recordDateElem.val(new Date().toLocaleDateString('th-TH'));
        updateTimesInfectious();
        formInfectious.find('select').trigger('change');
        setFormStateInfectious('disabled');
    });

    editButtonInfectious.on('click', () => setFormStateInfectious('enabled'));

    function fetchDataInfectious(searchParams = {}) {
        searchParams.action = 'fetch';
        searchParams.type = 'infectious';
        tableBodyInfectious.html('<tr><td colspan="8" class="text-center">กำลังโหลดข้อมูล...</td></tr>');
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: searchParams,
            dataType: 'json',
            success: function(response) {
                tableBodyInfectious.empty();
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
                        tableBodyInfectious.append(row);
                    });
                } else {
                    tableBodyInfectious.html('<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>');
                }
            }
        });
    }

    searchTextButton.on('click', () => fetchDataInfectious({ query: searchTextElem.val() }));
    searchDateButton.on('click', () => fetchDataInfectious({ from_date: searchFromDate.getDate('dd/mm/yyyy'), to_date: searchToDate.getDate('dd/mm/yyyy') }));
    showAllButton.on('click', () => {
        searchFromDate.setDate(null);
        searchToDate.setDate(null);
        searchTextElem.val('');
        fetchDataInfectious();
    });

    tableBodyInfectious.on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: { action: 'get_one', type: 'infectious', id: id },
            dataType: 'json',
            success: function(data) {
                editIdFieldInfectious.val(data.id);
                recordDate.setDate(data.date_record);
                timeRecordElem.val(data.time_record);
                timesElem.val(data.times);
                formInfectious.find('[name="waste_type_id"]').val(data.waste_type_id).trigger('change');
                formInfectious.find('[name="quantity"]').val(data.quantity);
                formInfectious.find('[name="unit_matrix_id"]').val(data.unit_matrix_id).trigger('change');
                formInfectious.find('[name="waste_note"]').val(data.waste_note);
                setFormStateInfectious('view');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    });

    tableBodyInfectious.on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?`)) {
            $.ajax({
                url: 'core/record_waste_handler.php',
                type: 'POST',
                data: { action: 'delete', type: 'infectious', id: id },
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
                            fetchDataInfectious();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message || 'ไม่สามารถลบข้อมูลได้',
                            });
                        }
                    } else {
                        alert(response.message);
                        if (response.status === 'success') fetchDataInfectious();
                    }
                }
            });
        }
    });

    formInfectious.off('submit').on('submit', function(e) {
        e.preventDefault();
        // อัปเดตค่า time_record และ times ให้แน่ใจก่อน submit
        e.preventDefault();
        saveButtonInfectious.prop('disabled', true);
        // อัปเดตค่า time_record และ times ให้แน่ใจก่อน submit
        const now = new Date();
        const timeString = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);
        timeRecordElem.val(timeString);
        if (!timesElem.val()) {
            timesElem.val('1');
        }
        timeRecordElem.prop('disabled', false);
        timesElem.prop('disabled', false);
        const formData = $(this).serialize() + '&action=save&type=infectious';
        timeRecordElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                saveButtonInfectious.prop('disabled', false);
                if (typeof Swal !== 'undefined') {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                            timer: 1800,
                            showConfirmButton: false
                        });
                        clearButtonInfectious.trigger('click');
                        fetchDataInfectious();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: response.message || 'ไม่สามารถบันทึกข้อมูลได้',
                        });
                    }
                } else {
                    alert(response.message);
                    if (response.status === 'success') fetchDataInfectious();
                }
            },
            error: function(xhr) {
                saveButtonInfectious.prop('disabled', false);
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
    formInfectious.find('select').select2({ theme: 'bootstrap-5' });
    setFormStateInfectious('disabled');
    updateClock();
    recordDate.setDate(new Date());
    updateTimesInfectious();
});
