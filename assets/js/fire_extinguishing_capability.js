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
    const capabilityIdField = $('#capability_id');
    const nameCapabilityField = $('#name_capability'); // เพิ่มตัวแปรสำหรับ name_capability

    function showAlert(message, type) {
        const alertType = (type === 'success') ? 'success' : 'danger';
        const alertHtml = `<div class="alert alert-${alertType} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        $('#alert-container').html(alertHtml);
        setTimeout(() => { // เพิ่ม setTimeout เพื่อซ่อน alert อัตโนมัติ
            $('#alert-container').empty();
        }, 5000);
    }

    function setFormState(state) {
        const is_disabled = state === 'disabled' || state === 'view';
        // ปิดการใช้งาน input ยกเว้น capability_id และ edit_id (hidden)
        nameCapabilityField.prop('disabled', is_disabled); // ใช้ตัวแปรที่เพิ่มมา
        $('#usage_status').prop('disabled', is_disabled);

        saveButton.prop('disabled', is_disabled);
        editButton.prop('disabled', state !== 'view');
        addNewButton.prop('disabled', !is_disabled);
    }

    function fetchData(query = '') {
        // แก้ไข colspan เป็น 4
        tableBody.html('<tr><td colspan="4" class="text-center">กำลังโหลดข้อมูล...</td></tr>');
        $.ajax({
            url: '../../core/fire_extinguishing_capability_handler.php',
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
                                <td>${item.name_capability}</td>
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
                    // แก้ไข colspan เป็น 4
                    tableBody.html('<tr><td colspan="4" class="text-center">ไม่พบข้อมูล</td></tr>');
                }
            },
            error: function(xhr, status, error) { // เพิ่ม error handling
                console.error("Error fetching data:", status, error, xhr.responseText);
                // แก้ไข colspan เป็น 4
                tableBody.html('<tr><td colspan="4" class="text-center text-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>');
                showAlert("เกิดข้อผิดพลาดในการโหลดข้อมูล: " + xhr.statusText, "error");
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
        capabilityIdField.val(''); // เคลียร์ค่ารหัส
        $('#usage_status').prop('checked', true); // ตั้งค่าสถานะใช้งานเป็นปกติเมื่อเคลียร์
        setFormState('disabled');
        showAlert("", "success"); // ล้างข้อความแจ้งเตือน
    });

    addNewButton.on('click', function() {
        clearButton.trigger('click'); // เรียกเคลียร์ฟอร์มก่อน
        setFormState('enabled');
        nameCapabilityField.focus(); // ใช้ตัวแปรที่เพิ่มมา
    });

    editButton.on('click', function() {
        setFormState('enabled');
    });

    tableBody.on('click', '.edit-btn', function() {
        const id = $(this).closest('tr').data('id');
        showAlert("", "success"); // ล้างข้อความแจ้งเตือน
        $.ajax({
            url: '../../core/fire_extinguishing_capability_handler.php',
            type: 'GET',
            data: { action: 'get_one', id: id },
            dataType: 'json',
            success: function(data) {
                if (data) {
                    editIdField.val(data.id);
                    capabilityIdField.val(data.id);
                    nameCapabilityField.val(data.name_capability); // ใช้ตัวแปรที่เพิ่มมา
                    $('#usage_status').prop('checked', data.usage_id == '1');
                    setFormState('view');
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                } else {
                    showAlert("ไม่พบข้อมูลสำหรับรหัสที่เลือก", "error");
                }
            },
            error: function(xhr, status, error) { // เพิ่ม error handling
                console.error("Error getting single record:", status, error, xhr.responseText);
                showAlert("เกิดข้อผิดพลาดในการดึงข้อมูลเพื่อแก้ไข: " + xhr.statusText, "error");
            }
        });
    });

    tableBody.on('click', '.delete-btn', function() {
        const id = $(this).closest('tr').data('id');
        if (confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลรหัส ${id}?`)) {
            showAlert("", "success"); // ล้างข้อความแจ้งเตือน
            $.ajax({
                url: '../../core/fire_extinguishing_capability_handler.php',
                type: 'POST',
                data: { action: 'delete', id: id },
                dataType: 'json',
                success: function(response) {
                    showAlert(response.message, response.status);
                    if (response.status === 'success') {
                        clearButton.trigger('click'); // เคลียร์ฟอร์มหลังจากลบ
                        fetchData(searchBox.val());
                    }
                },
                error: function(xhr, status, error) { // เพิ่ม error handling
                    console.error("Error deleting record:", status, error, xhr.responseText);
                    showAlert("เกิดข้อผิดพลาดในการลบข้อมูล: " + xhr.statusText, "error");
                }
            });
        }
    });

    dataForm.on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize() + '&action=save';
        showAlert("", "success"); // ล้างข้อความแจ้งเตือนก่อนส่งข้อมูล
        $.ajax({
            url: '../../core/fire_extinguishing_capability_handler.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                showAlert(response.message, response.status);
                if (response.status === 'success') {
                    clearButton.trigger('click');
                    fetchData();
                }
            },
            error: function(xhr, status, error) { // เพิ่ม error handling
                console.error("Error saving data:", status, error, xhr.responseText);
                showAlert("เกิดข้อผิดพลาดในการบันทึกข้อมูล: " + xhr.statusText, "error");
            }
        });
    });

    setFormState('disabled');
    // fetchData(); // ลบบรรทัดนี้ออก เพื่อไม่ให้โหลดข้อมูลอัตโนมัติเมื่อเข้าหน้า UI

    // เปลี่ยนข้อความเริ่มต้นในตารางเมื่อหน้าโหลด
    tableBody.html('<tr><td colspan="4" class="text-center">ยังไม่มีการค้นหาข้อมูล...</td></tr>');
});