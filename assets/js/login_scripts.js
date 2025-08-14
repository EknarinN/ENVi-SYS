$(document).ready(function () {
  const dbSettingsForm = $("#dbSettingsForm");
  const formInputs = dbSettingsForm.find("input");
  const editButton = $("#editDbSettings");
  const testButton = $("#testDbConnection");
  const saveButton = $('button[type="submit"][form="dbSettingsForm"]');
  const dbSettingsFeedback = $("#dbSettingsFeedback");
  const testConnectionFeedback = $("#testConnectionFeedback");
  const dbSettingsModal = document.getElementById("dbSettingsModal");
  const modal = new bootstrap.Modal(dbSettingsModal);

  function setFormState(disabled) {
    formInputs.prop("disabled", disabled);
    testButton.prop("disabled", disabled);
    saveButton.prop("disabled", disabled);
    editButton.prop("disabled", !disabled);
  }

  dbSettingsModal.addEventListener("show.bs.modal", function (event) {
    setFormState(true);
    fetch("core/get_config.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          $("#db_host").val(data.data.host);
          $("#db_port").val(data.data.port);
          $("#db_name").val(data.data.dbname);
          $("#db_user").val(data.data.user);
          $("#db_pass").val(data.data.pass);
        }
      })
      .catch((error) => console.error("Error loading config:", error));
  });

  editButton.on("click", function () {
    setFormState(false);
  });

  dbSettingsForm.on("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(this);
    dbSettingsFeedback.html(
      '<div class="text-primary mt-3">กำลังบันทึก...</div>'
    );
    fetch("core/save_config.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        const alertClass =
          data.status === "success" ? "alert-success" : "alert-danger";
        dbSettingsFeedback.html(
          `<div class="alert ${alertClass} mt-3">${data.message}</div>`
        );
        if (data.status === "success") {
          setTimeout(() => {
            modal.hide();
          }, 2000);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        dbSettingsFeedback.html(
          '<div class="alert alert-danger mt-3">เกิดข้อผิดพลาดในการเชื่อมต่อ</div>'
        );
      });
  });

  $("#testDbConnection").on("click", function () {
    const formData = new FormData(dbSettingsForm[0]);
    testConnectionFeedback
      .removeClass("text-success text-danger")
      .html("กำลังทดสอบ...");
    fetch("core/test_connection.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        const feedbackClass =
          data.status === "success" ? "text-success" : "text-danger";
        testConnectionFeedback
          .removeClass("text-success text-danger")
          .addClass(feedbackClass)
          .html(data.message);
      })
      .catch((error) => {
        console.error("Error:", error);
        testConnectionFeedback
          .removeClass("text-success")
          .addClass("text-danger")
          .html("เกิดข้อผิดพลาด");
      });
  });

  dbSettingsModal.addEventListener("hidden.bs.modal", function () {
    dbSettingsFeedback.empty();
    testConnectionFeedback.empty();
  });
});
