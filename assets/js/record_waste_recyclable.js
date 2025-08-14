$(document).ready(function() {
    // --- Setup for Recyclable Waste Tab ---
    const formRecyclable = $('#form-recyclable');
    const saveButtonRecyclable = $('#save-button-recyclable');
    const editButtonRecyclable = $('#edit-button-recyclable');
    const clearButtonRecyclable = $('#clear-button-recyclable');
    const addNewButtonRecyclable = $('#add-new-button-recyclable');
    const tableBodyRecyclable = $('#data-table-body-recyclable');
    const editIdFieldRecyclable = $('#edit_id_recyclable');

    // Search elements
    const searchFromDateElem = $('#search-from-date-recyclable');
    const searchToDateElem = $('#search-to-date-recyclable');
    const searchTextElem = $('#search-text-recyclable');
    const searchDateButton = $('#search-date-button-recyclable');
    const searchTextButton = $('#search-text-button-recyclable');
    const showAllButton = $('#show-all-button-recyclable');

    // Form elements
    const recordDateElem = $('#date_record_recyclable');
    const timeRecordElem = $('#time_record_recyclable');
    const timesElem = $('#times_recyclable');

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
    function updateTimesRecyclable() {
        const dateVal = recordDateElem.val();
        const wasteTypeId = formRecyclable.find('select[name="waste_type_id"]').val();
        if (!dateVal || !wasteTypeId) {
            timesElem.val('');
            return;
        }
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: {
                action: 'get_next_times',
                type: 'recyclable',
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
        if (!saveButtonRecyclable.prop('disabled')) updateTimesRecyclable();
    });
    formRecyclable.find('select[name="waste_type_id"]').on('change', function() {
        if (!saveButtonRecyclable.prop('disabled')) updateTimesRecyclable();
    });

    function setFormStateRecyclable(state) {
        const is_disabled = state === 'disabled' || state === 'view';
        formRecyclable.find('input[name="waste_group_name"]').prop('disabled', true);
        timeRecordElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        recordDateElem.prop('disabled', is_disabled ? true : false);
        formRecyclable.find('select[name="waste_type_id"]').prop('disabled', is_disabled ? true : false);
        formRecyclable.find('input[name="quantity"]').prop('disabled', is_disabled ? true : false);
        formRecyclable.find('select[name="unit_matrix_id"]').prop('disabled', is_disabled ? true : false);
        formRecyclable.find('textarea[name="waste_note"]').prop('disabled', is_disabled ? true : false);
        saveButtonRecyclable.prop('disabled', is_disabled);
        editButtonRecyclable.prop('disabled', state !== 'view');
        addNewButtonRecyclable.prop('disabled', !is_disabled);
    }

    addNewButtonRecyclable.on('click', function() {
        clearButtonRecyclable.trigger('click');
        setFormStateRecyclable('enabled');
        recordDate.setDate(new Date());
        setTimeout(updateTimesRecyclable, 200);
    });

    clearButtonRecyclable.on('click', () => {
        formRecyclable[0].reset();
        editIdFieldRecyclable.val('');
        recordDate.setDate(new Date());
        recordDateElem.val(new Date().toLocaleDateString('th-TH'));
        updateTimesRecyclable();
        formRecyclable.find('select').trigger('change');
        setFormStateRecyclable('disabled');
    });

    editButtonRecyclable.on('click', () => setFormStateRecyclable('enabled'));

    function fetchDataRecyclable(searchParams = {}) {
        searchParams.action = 'fetch';
        searchParams.type = 'recyclable';
        tableBodyRecyclable.html('<tr><td colspan="8" class="text-center">กำลังโหลดข้อมูล...</td></tr>');
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: searchParams,
            dataType: 'json',
            success: function(response) {
                tableBodyRecyclable.empty();
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
                        tableBodyRecyclable.append(row);
                    });
                } else {
                    tableBodyRecyclable.html('<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>');
                }
            }
        });
    }

    searchTextButton.on('click', () => fetchDataRecyclable({ query: searchTextElem.val() }));
    searchDateButton.on('click', () => fetchDataRecyclable({ from_date: searchFromDate.getDate('dd/mm/yyyy'), to_date: searchToDate.getDate('dd/mm/yyyy') }));
    showAllButton.on('click', () => {
        searchFromDate.setDate(null);
        searchToDate.setDate(null);
        searchTextElem.val('');
        fetchDataRecyclable();
    });

    tableBodyRecyclable.on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'GET',
            data: { action: 'get_one', type: 'recyclable', id: id },
            dataType: 'json',
            success: function(data) {
                editIdFieldRecyclable.val(data.id);
                recordDate.setDate(data.date_record);
                timeRecordElem.val(data.time_record);
                timesElem.val(data.times);
                formRecyclable.find('[name="waste_type_id"]').val(data.waste_type_id).trigger('change');
                formRecyclable.find('[name="quantity"]').val(data.quantity);
                formRecyclable.find('[name="unit_matrix_id"]').val(data.unit_matrix_id).trigger('change');
                formRecyclable.find('[name="waste_note"]').val(data.waste_note);
                setFormStateRecyclable('view');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    });

    tableBodyRecyclable.on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?`)) {
            $.ajax({
                url: 'core/record_waste_handler.php',
                type: 'POST',
                data: { action: 'delete', type: 'recyclable', id: id },
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
                            fetchDataRecyclable();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message || 'ไม่สามารถลบข้อมูลได้',
                            });
                        }
                    } else {
                        alert(response.message);
                        if (response.status === 'success') fetchDataRecyclable();
                    }
                }
            });
        }
    });

    formRecyclable.off('submit').on('submit', function(e) {
        e.preventDefault();
        saveButtonRecyclable.prop('disabled', true); // ป้องกันการกดซ้ำ
        // อัปเดตค่า time_record และ times ให้แน่ใจก่อน submit
        const now = new Date();
        const timeString = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);
        timeRecordElem.val(timeString);
        if (!timesElem.val()) {
            timesElem.val('1');
        }
        timeRecordElem.prop('disabled', false);
        timesElem.prop('disabled', false);
        const formData = $(this).serialize() + '&action=save&type=recyclable';
        timeRecordElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        $.ajax({
            url: 'core/record_waste_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                saveButtonRecyclable.prop('disabled', false); // enable เมื่อเสร็จ
                if (typeof Swal !== 'undefined') {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                            timer: 1800,
                            showConfirmButton: false
                        });
                        clearButtonRecyclable.trigger('click');
                        fetchDataRecyclable();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: response.message || 'ไม่สามารถบันทึกข้อมูลได้',
                        });
                    }
                } else {
                    alert(response.message);
                    if (response.status === 'success') {
                        clearButtonRecyclable.trigger('click');
                        fetchDataRecyclable();
                    }
                }
            },
            error: function(xhr) {
                saveButtonRecyclable.prop('disabled', false); // enable เมื่อ error
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

    // --- Initial Load ---
    formRecyclable.find('select').select2({ theme: 'bootstrap-5' });
    setFormStateRecyclable('disabled');
    updateClock();
    recordDate.setDate(new Date());
    updateTimesRecyclable();
});
