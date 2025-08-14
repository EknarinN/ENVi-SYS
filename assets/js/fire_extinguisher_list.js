$(document).ready(function () {
  // --- Variable Declarations ---
  const dataForm = $("#data-form");
  const tableBody = $("#data-table-body");
  const searchBox = $("#search-box");
  const editIdField = $("#edit_id");
  // Buttons
  const saveButton = $("#save-button");
  const editButton = $("#edit-button");
  const clearButton = $("#clear-button");
  const addNewButton = $("#add-new-button");
  const searchButton = $("#search-button");
  const showAllButton = $("#show-all-button");
  // Dropdowns
  const typeSelect = $("#data_fire_extinguisher_type_id");
  const colorSelect = $("#data_color_id");
  const propulsionSelect = $("#propulsion_type_id");

  // --- Helper Functions ---
  function showAlert(message, type) {
    const alertType = type === "success" ? "success" : "danger";
    const alertHtml = `<div class="alert alert-${alertType} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    $("#alert-container").html(alertHtml);
  }

  function setFormState(state) {
    const is_disabled = state === "disabled" || state === "view";
    dataForm
      .find("input, select")
      .not("#edit_id")
      .prop("disabled", is_disabled);
    // เพิ่ม: จัดการ Checkbox แยกต่างหาก
    dataForm.find('input[type="checkbox"]').prop("disabled", is_disabled);
    saveButton.prop("disabled", is_disabled);
    editButton.prop("disabled", state !== "view");
    addNewButton.prop("disabled", !is_disabled);
  }
  // --- Data Fetching ---
  function loadDropdownData() {
    $.ajax({
      url: "../../core/fire_extinguisher_list_handler.php",
      type: "GET",
      data: { action: "get_dropdown_data" },
      dataType: "json",
      success: function (data) {
        typeSelect.empty().append('<option value="">เลือกประเภท...</option>');
        data.types.forEach((item) =>
          typeSelect.append(
            `<option value="${item.id}">${item.name_type}</option>`
          )
        );

        colorSelect.empty().append('<option value="">เลือกสี...</option>');
        data.colors.forEach((item) =>
          colorSelect.append(
            `<option value="${item.id}">${item.name_color}</option>`
          )
        );

        propulsionSelect
          .empty()
          .append('<option value="">เลือกแรงขับดัน...</option>');
        data.propulsions.forEach((item) =>
          propulsionSelect.append(
            `<option value="${item.id}">${item.full_word_th}</option>`
          )
        );

        typeSelect.select2({ theme: "bootstrap-5" });
        colorSelect.select2({ theme: "bootstrap-5" });
        propulsionSelect.select2({ theme: "bootstrap-5" });
      },
    });
  }

  function fetchData(query = "") {
    tableBody.html(
      '<tr><td colspan="6" class="text-center">กำลังโหลดข้อมูล...</td></tr>'
    );
    $.ajax({
      url: "../../core/fire_extinguisher_list_handler.php",
      type: "GET",
      data: { action: "fetch", query: query },
      dataType: "json",
      success: function (response) {
        tableBody.empty();
        if (response.data && response.data.length > 0) {
          response.data.forEach((item) => {
            const statusBadge =
              item.usage_id == "1"
                ? '<span class="badge bg-success">ใช้งาน</span>'
                : '<span class="badge bg-danger">ไม่ใช้งาน</span>';
            const row = `<tr data-id="${item.id}">
                            <td class="text-center">${item.fire_extinguisher_number}</td>
                            <td>${item.brand}</td>
                            <td>${item.model}</td>
                            <td>${item.name_type}</td>
                            <td class="text-center">${statusBadge}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm edit-btn" title="แก้ไข"><i class="bi bi-pencil-fill"></i></button>
                                <button class="btn btn-danger btn-sm delete-btn" title="ลบ"><i class="bi bi-trash-fill"></i></button>
                            </td>
                        </tr>`;
            tableBody.append(row);
          });
        } else {
          tableBody.html(
            '<tr><td colspan="6" class="text-center">ไม่พบข้อมูล</td></tr>'
          );
        }
      },
    });
  }

  // --- Event Handlers ---
  searchButton.on("click", () => fetchData(searchBox.val()));
  searchBox.on("keyup", (event) => {
    if (event.key === "Enter") searchButton.trigger("click");
  });
  showAllButton.on("click", () => {
    searchBox.val("");
    fetchData();
  });

  clearButton.on("click", function () {
    dataForm[0].reset();
    editIdField.val("");
    typeSelect.val("").trigger("change");
    colorSelect.val("").trigger("change");
    propulsionSelect.val("").trigger("change");
    // เพิ่ม: Uncheck all fire type checkboxes
    dataForm.find('input[name^="fire_type_"]').prop("checked", false);
    setFormState("disabled");
  });

  addNewButton.on("click", function () {
    clearButton.trigger("click");
    setFormState("enabled");
    $("#fire_extinguisher_number").focus();
  });

  editButton.on("click", function () {
    setFormState("enabled");
  });

  tableBody.on("click", ".edit-btn", function () {
    const id = $(this).closest("tr").data("id");
    $.ajax({
      url: "../../core/fire_extinguisher_list_handler.php",
      type: "GET",
      data: { action: "get_one", id: id },
      dataType: "json",
      success: function (data) {
        editIdField.val(data.id);
        for (const key in data) {
          const el = $(`#${key}`);
          if (el.is("select")) {
            el.val(data[key]).trigger("change");
          } else if (el.is('input[type="checkbox"]')) {
            // แก้ไข: จัดการ Checkbox
            el.prop("checked", data[key] == "1");
          } else {
            el.val(data[key]);
          }
        }
        $("#usage_status").prop("checked", data.usage_id == "1");
        setFormState("view");
        $("html, body").animate({ scrollTop: 0 }, "slow");
      },
    });
  });

  tableBody.on("click", ".delete-btn", function () {
    /* ... Delete logic ... */
  });
  dataForm.on("submit", function (e) {
    /* ... Save logic ... */
  });

  // --- Initial Load ---
  setFormState("disabled");
  loadDropdownData();
});
