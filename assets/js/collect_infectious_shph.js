    // --- ฟังก์ชันคำนวณและแสดงค่าครั้งที่ ---
    function updateTimes() {
        const date = recordDateElem.val();
        const shph = shphSelectElem.val();
        const group = wasteGroupElem.val();
        const type = wasteTypeElem.val();
        if (date && shph && group && type) {
            $.ajax({
                url: 'core/collect_infectious_shph_handler.php',
                type: 'GET',
                data: {
                    action: 'get_times',
                    date_collect: date,
                    shph_id: shph,
                    waste_group_id: group,
                    waste_type_id: type
                },
                dataType: 'json',
                success: function(res) {
                    if(res && res.data && typeof res.data.next_times !== 'undefined') {
                        timesElem.val(res.data.next_times);
                    } else {
                        timesElem.val('');
                    }
                },
                error: function() {
                    timesElem.val('');
                }
            });
        } else {
            timesElem.val('');
        }
    }

$(document).ready(function() {
    // --- Setup ---
    const form = $('#form-infectious-shph');
    const saveButton = $('#save-button-infectious-shph');
    const editButton = $('#edit-button-infectious-shph');
    const clearButton = $('#clear-button-infectious-shph');
    const addNewButton = $('#add-new-button-infectious-shph');
    const tableBody = $('#data-table-body-infectious-shph');
    const editIdField = $('#edit_id_infectious_shph');

    // Search elements
    const searchShphElem = $('#search-shph-id');
    const searchFromDateElem = $('#search-from-date');
    const searchToDateElem = $('#search-to-date');
    const searchDateButton = $('#search-date-button');

    // Form elements
    const recordDateElem = $('#date_collect');
    const timeRecordElem = $('#time_collect');
    const shphSelectElem = $('#shph_id');
    const timesElem = $('#times');
    const wasteGroupElem = $('#waste_group_id');
    const wasteTypeElem = $('#waste_type_id');
    const unitMatrixElem = $('#unit_matrix_id');
    const quantityElem = $('#quantity');
    const noteElem = $('#waste_note');

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

    function setFormState(state) {
        const is_disabled = state === 'disabled' || state === 'view';
        recordDateElem.prop('disabled', is_disabled);
        shphSelectElem.prop('disabled', is_disabled);
        wasteGroupElem.prop('disabled', is_disabled);
        wasteTypeElem.prop('disabled', is_disabled);
        unitMatrixElem.prop('disabled', is_disabled);
        quantityElem.prop('disabled', is_disabled);
        noteElem.prop('disabled', is_disabled);
        saveButton.prop('disabled', is_disabled);
        editButton.prop('disabled', state !== 'view');
    addNewButton.prop('disabled', false);
        // รี-init select2 เมื่อ enable/disable
        if (!is_disabled) {
            wasteGroupElem.select2({ theme: 'bootstrap-5' });
            wasteTypeElem.select2({ theme: 'bootstrap-5' });
            unitMatrixElem.select2({ theme: 'bootstrap-5' });
        }
    }

    addNewButton.on('click', function() {
        form[0].reset();
        editIdField.val('');
        setFormState('enabled');
        recordDate.setDate(new Date());
        recordDateElem.val(new Date().toLocaleDateString('th-TH'));
        timesElem.val('1');
        shphSelectElem.trigger('change');
        wasteGroupElem.trigger('change');
        wasteTypeElem.trigger('change');
    });

    clearButton.on('click', () => {
        form[0].reset();
        editIdField.val('');
        recordDate.setDate(new Date());
        recordDateElem.val(new Date().toLocaleDateString('th-TH'));
        timesElem.val('1');
        shphSelectElem.trigger('change');
        wasteGroupElem.trigger('change');
        wasteTypeElem.trigger('change');
        setFormState('disabled');
    });

    editButton.on('click', () => setFormState('enabled'));

    function fetchData(params = {}) {
        params.action = 'fetch';
        tableBody.html('<tr><td colspan="7" class="text-center">กำลังโหลดข้อมูล...</td></tr>');
        $.ajax({
            url: 'core/collect_infectious_shph_handler.php',
            type: 'GET',
            data: params,
            dataType: 'json',
            success: function(response) {
                tableBody.empty();
                if (response.data && response.data.length > 0) {
                    response.data.forEach(item => {
                        const row = `<tr>
                            <td class="text-center">${new Date(item.date_collect).toLocaleDateString('th-TH', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
                            <td class="text-center">${item.time_collect}</td>
                            <td>${item.shph_name}</td>
                            <td class="text-end">${parseFloat(item.quantity).toFixed(2)}</td>
                            <td>${item.note || ''}</td>
                            <td>${item.user_firstname || ''}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${item.id}" title="แก้ไข"><i class="bi bi-pencil-fill"></i></button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id}" title="ลบ"><i class="bi bi-trash-fill"></i></button>
                            </td>
                        </tr>`;
                        tableBody.append(row);
                    });
                } else {
                    tableBody.html('<tr><td colspan="7" class="text-center">ไม่พบข้อมูล</td></tr>');
                }
            }
        });
    }

    searchDateButton.on('click', () => fetchData({
        shph_id: searchShphElem.val(),
        from_date: searchFromDate.getDate('dd/mm/yyyy'),
        to_date: searchToDate.getDate('dd/mm/yyyy')
    }));
    searchShphElem.on('change', () => fetchData({ shph_id: searchShphElem.val() }));

    tableBody.on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'core/collect_infectious_shph_handler.php',
            type: 'GET',
            data: { action: 'get_one', id: id },
            dataType: 'json',
            success: function(data) {
                editIdField.val(data.id);
                recordDate.setDate(data.date_collect);
                timeRecordElem.val(data.time_collect);
                shphSelectElem.val(data.shph_id).trigger('change');
                quantityElem.val(data.quantity);
                noteElem.val(data.note);
                setFormState('view');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    });

    tableBody.on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?`)) {
            $.ajax({
                url: 'core/collect_infectious_shph_handler.php',
                type: 'POST',
                data: { action: 'delete', id: id },
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
                            fetchData();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message || 'ไม่สามารถลบข้อมูลได้',
                            });
                        }
                    } else {
                        alert(response.message);
                        if (response.status === 'success') fetchData();
                    }
                }
            });
        }
    });

    form.on('submit', function(e) {
        e.preventDefault();
        // อัปเดตค่า time_collect ให้แน่ใจก่อน submit
        const now = new Date();
        const timeString = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);
        timeRecordElem.val(timeString);
        timeRecordElem.prop('disabled', false);
        // Enable select ที่ disabled ชั่วคราวเพื่อ serialize
        wasteGroupElem.prop('disabled', false);
        wasteTypeElem.prop('disabled', false);
        timesElem.prop('disabled', false);
        const formData = $(this).serialize() + '&action=save';
        // กลับไป disabled หลัง serialize
        wasteGroupElem.prop('disabled', true);
        wasteTypeElem.prop('disabled', true);
        timesElem.prop('disabled', true);
        timeRecordElem.prop('disabled', true);
        $.ajax({
            url: 'core/collect_infectious_shph_handler.php',
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
                        clearButton.trigger('click');
                        fetchData();
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
                        clearButton.trigger('click');
                        fetchData();
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
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                }
            }
        });
    });

    // --- Initial Load ---
    form.find('select').select2({ theme: 'bootstrap-5' });
    setFormState('disabled');
    updateClock();
    recordDate.setDate(new Date());
    recordDateElem.val(new Date().toLocaleDateString('th-TH'));
    timesElem.val('1');
    fetchData();
});
