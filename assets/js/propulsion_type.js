$(document).ready(function() {
    const dataForm = $('#data-form');
    const saveButton = $('#save-button');
    const editButton = $('#edit-button');
    const clearButton = $('#clear-button');
    const addNewButton = $('#add-new-button');
    const searchButton = $('#search-button');
    const showAllButton = $('#show-all-button');
    const searchBox = $('#search-box');
    const tableBody = $('#data-table-body');
    const editIdField = $('#edit_id');
    const propulsionTypeIdField = $('#propulsion_type_id');
    const stateOfMatterSelect = $('#state_of_matter_id');

    function showAlert(message, type) {
        const alertType = (type === 'success') ? 'success' : 'danger';
        const alertHtml = `<div class="alert alert-${alertType} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        $('#alert-container').html(alertHtml);
        setTimeout(() => {
            $('#alert-container').empty();
        }, 5000);
    }

    function setFormState(state) {
        const is_disabled = state === 'disabled' || state === 'view';
        dataForm.find('input, select').not('#edit_id').prop('disabled', is_disabled);
        saveButton.prop('disabled', is_disabled);
        editButton.prop('disabled', state !== 'view');
        addNewButton.prop('disabled', !is_disabled);
    }

    function loadDropdownData() {
        $.ajax({
            url: '../../core/propulsion_type_handler.php',
            type: 'GET',
            data: { action: 'get_dropdown_data' },
            dataType: 'json',
            success: function(data) {
                stateOfMatterSelect.empty().append('<option value="">เลือกสถานะ...</option>');
                data.states_of_matter.forEach(item => stateOfMatterSelect.append(`<option value="${item.id}">${item.name_state}</option>`));
                stateOfMatterSelect.select2({ theme: 'bootstrap-5' });
            },
            error: function(xhr, status, error) {
                console.error("Error loading dropdown data:", status, error);
                showAlert("ไม่สามารถโหลดข้อมูลสถานะของสสารได้", "error");
            }
        });
    }

    function fetchData(query = '') {
        tableBody.html('<tr><td colspan="8" class="text-center">กำลังโหลดข้อมูล...</td></tr>');
        $.ajax({
            url: '../../core/propulsion_type_handler.php',
            type: 'GET',
            data: { action: 'fetch', query: query },
            dataType: 'json',
            success: function(response) {
                tableBody.empty();
                if (response.data && response.data.length > 0) {
                    response.data.forEach(item => {
                        const statusBadge = item.usage_id == '1'
                            ? '<span class="badge bg-success">ใช้งาน</span>'
                            : '<span class="badge bg-danger">ไม่ใช้งาน</span>';

                        const row = `
                            <tr data-id="${item.id}">
                                <td class="text-center">${item.id}</td>
                                <td>${item.full_word_en}</td>
                                <td>${item.abbreviation_word_eng}</td>
                                <td>${item.full_word_th}</td>
                                <td>${item.abbreviation_word_th}</td>
                                <td>${item.name_state}</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm edit-btn" title="แก้ไข"><i class="bi bi-pencil-fill"></i></button>
                                    <button class="btn btn-danger btn-sm delete-btn" title="ลบ"><i class="bi bi-trash-fill"></i></button>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });
                } else {
                    tableBody.html('<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", status, error, xhr.responseText);
                tableBody.html('<tr><td colspan="8" class="text-center text-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>');
                showAlert("เกิดข้อผิดพลาดในการโหลดข้อมูล", "error");
            }
        });
    }

    searchButton.on('click', () => fetchData(searchBox.val()));
    searchBox.on('keyup', (event) => { if (event.key === 'Enter') searchButton.trigger('click'); });
    showAllButton.on('click', () => { searchBox.val(''); fetchData(); });

    clearButton.on('click', function() {
        dataForm[0].reset();
        editIdField.val('');
        propulsionTypeIdField.val('');
        stateOfMatterSelect.val('').trigger('change');
        $('#usage_status').prop('checked', true);
        setFormState('disabled');
        showAlert("", "success");
    });

    addNewButton.on('click', function() {
        clearButton.trigger('click');
        setFormState('enabled');
        $('#full_word_eng').focus();
    });

    editButton.on('click', function() { setFormState('enabled'); });

    tableBody.on('click', '.edit-btn', function() {
        const id = $(this).closest('tr').data('id');
        showAlert("", "success");
        $.ajax({
            url: '../../core/propulsion_type_handler.php',
            type: 'GET',
            data: { action: 'get_one', id: id },
            dataType: 'json',
            success: function(data) {
                if (data) {
                    editIdField.val(data.id);
                    propulsionTypeIdField.val(data.id);
                    $('#full_word_eng').val(data.full_word_en);
                    $('#abbreviation_word_eng').val(data.abbreviation_word_eng);
                    $('#full_word_th').val(data.full_word_th);
                    $('#abbreviation_word_th').val(data.abbreviation_word_th);
                    stateOfMatterSelect.val(data.state_of_matter_id).trigger('change');
                    $('#usage_status').prop('checked', data.usage_id == '1');
                    setFormState('view');
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                } else {
                    showAlert("ไม่พบข้อมูลสำหรับรหัสที่เลือก", "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error getting single record:", status, error, xhr.responseText);
                showAlert("เกิดข้อผิดพลาดในการดึงข้อมูลเพื่อแก้ไข", "error");
            }
        });
    });

    tableBody.on('click', '.delete-btn', function() {
        const id = $(this).closest('tr').data('id');
        if (confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลรหัส ${id}?`)) {
            showAlert("", "success");
            $.ajax({
                url: '../../core/propulsion_type_handler.php',
                type: 'POST',
                data: { action: 'delete', id: id },
                dataType: 'json',
                success: function(response) {
                    showAlert(response.message, response.status);
                    if (response.status === 'success') {
                        clearButton.trigger('click');
                        fetchData(searchBox.val());
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error deleting record:", status, error, xhr.responseText);
                    showAlert("เกิดข้อผิดพลาดในการลบข้อมูล", "error");
                }
            });
        }
    });

    dataForm.on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const actionType = editIdField.val() ? 'save' : 'save';
        $.ajax({
            url: '../../core/propulsion_type_handler.php',
            type: 'POST',
            data: formData + '&action=' + actionType,
            dataType: 'json',
            success: function(response) {
                showAlert(response.message, response.status);
                if (response.status === 'success') {
                    clearButton.trigger('click');
                    fetchData();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error saving data:", status, error, xhr.responseText);
                showAlert("เกิดข้อผิดพลาดในการบันทึกข้อมูล", "error");
            }
        });
    });

    setFormState('disabled');
    loadDropdownData();
    // fetchData(); // คอมเมนต์บรรทัดนี้ออกไป เพื่อไม่ให้โหลดข้อมูลอัตโนมัติเมื่อเข้าหน้า UI
});