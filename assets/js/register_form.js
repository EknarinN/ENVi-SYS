$(document).ready(function () {
  // 1. เรียกใช้งาน Select2
  $("select").select2({
    theme: "bootstrap-5",
  });

  // 2. สคริปต์คำนวณอายุ
  const ageDisplay = document.getElementById("ageDisplay");
  function calculateAndDisplayAge() {
    const day = $("#dob_day").val();
    const month = $("#dob_month").val();
    const beYear = $("#dob_year").val();

    if (day && month && beYear) {
      const adYear = beYear - 543;
      const birthDate = new Date(adYear, month - 1, day);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const m = today.getMonth() - birthDate.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      ageDisplay.value = age >= 0 ? `${age} ปี` : "-";
    } else {
      ageDisplay.value = "-";
    }
  }
  $("#dob_day, #dob_month, #dob_year").on("change", calculateAndDisplayAge);

  // 3. สคริปต์สำหรับ Cascading Dropdown
  $("#department_group").on("change", function () {
    const groupId = $(this).val();
    const subDeptSelect = $("#department_sub");

    subDeptSelect
      .prop("disabled", true)
      .html('<option value="">กำลังโหลด...</option>')
      .trigger("change");

    if (groupId) {
      fetch(`core/get_sub_departments.php?group_id=${groupId}`)
        .then((response) => response.json())
        .then((data) => {
          subDeptSelect
            .empty()
            .append(
              '<option value="" selected disabled>เลือกหน่วยงานย่อย...</option>'
            );
          if (data.length > 0) {
            data.forEach((sub) => {
              const option = new Option(
                sub.name_departments,
                sub.id,
                false,
                false
              );
              subDeptSelect.append(option);
            });
            subDeptSelect.prop("disabled", false);
          } else {
            subDeptSelect
              .empty()
              .append(
                '<option value="" selected disabled>ไม่มีหน่วยงานย่อย</option>'
              );
          }
          subDeptSelect.trigger("change");
        })
        .catch((error) => {
          console.error("Error:", error);
          subDeptSelect
            .empty()
            .append(
              '<option value="" selected disabled>เกิดข้อผิดพลาด</option>'
            )
            .trigger("change");
        });
    } else {
      subDeptSelect
        .empty()
        .append(
          '<option value="" selected disabled>กรุณาเลือกกลุ่มงานหลักก่อน</option>'
        )
        .prop("disabled", true);
      subDeptSelect.trigger("change");
    }
  });

  // 4. ส่วนตรวจสอบข้อมูลซ้ำแบบ Real-time (โค้ดใหม่ที่นำมารวม)
  const firstNameInput = $("#first_name");
  const lastNameInput = $("#last_name");
  const emailInput = $("#email");
  const usernameInput = $("#username");
  const submitButton = $('button[type="submit"]');

  function showFeedback(element, message, is_valid) {
    element.empty();
    if (is_valid) {
      element
        .removeClass("text-danger")
        .addClass("text-success")
        .html(`<i class="bi bi-check-circle-fill"></i> ${message}`);
    } else {
      element
        .removeClass("text-success")
        .addClass("text-danger")
        .html(`<i class="bi bi-exclamation-triangle-fill"></i> ${message}`);
    }
  }

  function checkAllValidations() {
    if ($(".form-text.text-danger").length > 0) {
      submitButton.prop("disabled", true);
    } else {
      submitButton.prop("disabled", false);
    }
  }

  function checkName() {
    const fname = firstNameInput.val().trim();
    const lname = lastNameInput.val().trim();
    const feedback = $("#name_feedback");

    if (fname && lname) {
      fetch(
        `core/check_duplicates.php?type=name&first_name=${fname}&last_name=${lname}`
      )
        .then((response) => response.json())
        .then((data) => {
          if (data.exists) {
            showFeedback(feedback, "ชื่อ-นามสกุลนี้มีผู้ใช้งานแล้ว", false);
          } else {
            showFeedback(feedback, "ชื่อ-นามสกุลนี้สามารถใช้ได้", true);
          }
          checkAllValidations();
        });
    } else {
      feedback.empty();
      checkAllValidations();
    }
  }
  firstNameInput.on("blur", checkName);
  lastNameInput.on("blur", checkName);

  emailInput.on("blur", function () {
    const email = $(this).val().trim();
    const feedback = $("#email_feedback");
    if (email) {
      fetch(`core/check_duplicates.php?type=email&email=${email}`)
        .then((response) => response.json())
        .then((data) => {
          if (data.exists) {
            showFeedback(feedback, "อีเมลนี้มีผู้ใช้งานแล้ว", false);
          } else {
            showFeedback(feedback, "อีเมลนี้สามารถใช้ได้", true);
          }
          checkAllValidations();
        });
    } else {
      feedback.empty();
      checkAllValidations();
    }
  });

  usernameInput.on("blur", function () {
    const username = $(this).val().trim();
    const feedback = $("#username_feedback");
    if (username) {
      fetch(`core/check_duplicates.php?type=username&username=${username}`)
        .then((response) => response.json())
        .then((data) => {
          if (data.exists) {
            showFeedback(feedback, "Username นี้มีผู้ใช้งานแล้ว", false);
          } else {
            showFeedback(feedback, "Username นี้สามารถใช้ได้", true);
          }
          checkAllValidations();
        });
    } else {
      feedback.empty();
      checkAllValidations();
    }
  });
});
