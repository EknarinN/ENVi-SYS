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
    const roleIdField = $('#role_id');

    function showAlert(message, type) {
        const alertType = (type === 'success') ? 'success' : 'danger';
        const alertHtml = `<div class="alert alert-${alertType} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        $('#alert-container').html(alertHtml);
    }

    function setFormState(state) {
        const is_disabled = state === 'disabled' || state === 'view';
        $('input:not(#role_id)', dataForm).prop('disabled', is_disabled);
        $('#usage_status').prop('disabled', is_disabled);
        saveButton.prop('disabled', is_disabled);
        editButton.prop('disabled', state !== 'view');
        addNewButton.prop('disabled', !is_disabled);
    }
    
    function fetchData(query = '') {
        tableBody.html('<tr><td colspan="4" class="text-center">กำลังโหลดข้อมูล...</td></tr>');
        $.ajax({
            url: '../../core/role_handler.php',
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
                                <td>${item.name_role}</td>
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
                    tableBody.html('<tr><td colspan="4" class="text-center">ไม่พบข้อมูล</td></tr>');
                }
            }
        });
    }

    searchButton.on('click', () => fetchData(searchBox.val()));
    searchBox.on('keyup', (event) => { if (event.key === 'Enter') searchButton.trigger('click'); });
    showAllButton.on('click', () => {
        searchBox.val('');
        fetchData();
    });

    clearButton.on('click', function() {
        dataForm[0].reset();
        editIdField.val('');
        roleIdField.val('');
        setFormState('disabled');
    });

    addNewButton.on('click', function() {
        clearButton.trigger('click');
        setFormState('enabled');
        $('#name_role').focus();
    });

    editButton.on('click', function() {
        setFormState('enabled');
    });

    tableBody.on('click', '.edit-btn', function() {
        const id = $(this).closest('tr').data('id');
        $.ajax({
            url: '../../core/role_handler.php',
            type: 'GET',
            data: { action: 'get_one', id: id },
            dataType: 'json',
            success: function(data) {
                editIdField.val(data.id);
                roleIdField.val(data.id);
                $('#name_role').val(data.name_role);
                $('#usage_status').prop('checked', data.usage_id == '1');
                setFormState('view');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    });

    tableBody.on('click', '.delete-btn', function() {
        const id = $(this).closest('tr').data('id');
        if (confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลรหัส ${id}?`)) {
            $.ajax({
                url: '../../core/role_handler.php',
                type: 'POST',
                data: { action: 'delete', id: id },
                dataType: 'json',
                success: function(response) {
                    showAlert(response.message, response.status);
                    if (response.status === 'success') {
                        fetchData(searchBox.val());
                    }
                }
            });
        }
    });

    dataForm.on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize() + '&action=save';
        $.ajax({
            url: '../../core/role_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                showAlert(response.message, response.status);
                if (response.status === 'success') {
                    clearButton.trigger('click');
                    fetchData(searchBox.val());
                }
            }
        });
    });
    
    setFormState('disabled');
});