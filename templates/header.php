<?php
// เริ่มต้น session เพื่อใช้งานตัวแปร session
session_start();
// เรียกใช้ connect_db.php เพื่อให้รู้จัก BASE_URL
require_once __DIR__ . '/../core/connect_db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ' . BASE_URL . '/login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>ENVi-SYS</title>

  <link rel="icon" href="<?php echo BASE_URL; ?>/assets/images/favicon/favicon.ico">

  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/custom.css">

  <style>
    .main-content {
      padding-top: 80px;
      padding-bottom: 50px;
    }

    .menu-card {
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
      border: 1px solid #dee2e6;
    }

    .menu-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .dropdown-menu {
      max-height: 400px;
      overflow-y: auto;
    }
  </style>

  <script>
    // ปักธงในเบราว์เซอร์ว่ากำลังล็อกอินอยู่
    localStorage.setItem('loginStatus', 'loggedIn');

    // เพิ่ม Event Listener เพื่อคอยดักฟังการเปลี่ยนแปลงจาก Tab อื่น
    window.addEventListener('storage', function(event) {
      // ถ้า loginStatus ถูกลบหรือเปลี่ยนไปจาก Tab อื่น
      if (event.key === 'loginStatus' && event.newValue === null) {
        // ให้รีโหลดหน้านี้ใหม่ทันที
        window.location.reload();
      }
    });
  </script>
</head>

<body class="page-background">

  <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="<?php echo BASE_URL; ?>/index.php">
        <!--<img src="<?php echo BASE_URL; ?>/assets/images/logo/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">-->
        หน้าแรก : ENVi-SYS
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-gear-fill"></i> ตั้งค่า Master Data
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <h6 class="dropdown-header text-success"><i class="bi bi-trash3"></i> ขยะ</h6>
              </li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/waste_group.php">กลุ่มขยะ</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/waste_type.php">ประเภทของขยะ</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <h6 class="dropdown-header text-warning"><i class="bi bi-highlights"></i> ไฟฉุกเฉิน</h6>
              </li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/emer_lights_list.php">รายละเอียดของไฟฉุกเฉิน</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/emer_lights_type.php">ประเภทของไฟฉุกเฉิน</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/emer_lights_installation.php">ลักษณะการติดตั้ง</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <h6 class="dropdown-header text-danger"><i class="bi bi-fire"></i> ถังดับเพลิง</h6>
              </li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/fire_extinguisher_list.php">รายละเอียดถังดับเพลิง</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/fire_extinguisher_type.php">ประเภทถังดับเพลิง</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/fire_extinguisher_parts.php">ชิ้นส่วนถังดับเพลิง</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/propulsion_type.php">ประเภทแรงขับดันถังดับเพลิง</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <h6 class="dropdown-header text-primary"><i class="bi bi-hospital"></i> หน่วยบริการ / หน่วยงาน</h6>
              </li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/hospital.php">หน่วยบริการ / รพ.สต.</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/departments_group.php">กลุ่มงานหลัก</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/departments_sub.php">หน่วยงานย่อย</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/role.php">ตำแหน่งงาน</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/prefix_name.php">คำนำหน้าชื่อ</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <h6 class="dropdown-header text-info"><i class="bi bi-clipboard-data"></i> ข้อมูลทั่วไป</h6>
              </li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/color.php">สี</a></li>                            
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/checkpoint.php">จุดตรวจ / สถานที่ติดตั้ง</a></li>
              <li><a class="dropdown-item" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/envi_sys/templates/master_data/appropriate_value.php">ค่ามาตรฐาน / ค่าที่เหมาะสม</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/coliform_bacteria.php">การตรวจพบโคลิฟอร์ม</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/fire_type.php">ประเภทของเพลิงไหม้</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/fire_extinguishing_capability.php">ความสามารถในการดับเพลิง</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/defectiveness_of_parts.php">ความชำรุดของชิ้นส่วน</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/unit_matrix.php">หน่วยนับน้ำหนัก</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/state_of_matter.php">สถานะของสสาร</a></li>
              <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/templates/master_data/usage.php">สถานะการใช้งาน</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle"></i>
              <?php echo htmlspecialchars($_SESSION['user_firstname'] ?? 'ผู้ใช้งาน'); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item text-warning" href="<?php echo BASE_URL; ?>/change_password.php"><i class="bi bi-key"></i> เปลี่ยนรหัสผ่าน</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>/logout.php"><i class="bi bi-box-arrow-right"></i> ออกจากระบบ</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container main-content">