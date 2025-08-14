<?php
include_once 'templates/header.php';
$waste_types = $pdo->query("SELECT id, name_type FROM data_waste_type WHERE usage_id = '1' ORDER BY id")->fetchAll();
$units = $pdo->query("SELECT id, name_th FROM data_unit_matrix WHERE usage_id = '1'")->fetchAll();
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker-bs5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

<style>
    .nav-pills .nav-link {
        display: flex;
        flex-direction: row;
        align-items: center;
        width: 100%;
        height: 70px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        background-color: #fff;
        color: #212529;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.2s ease-in-out;
    }

    .nav-pills .nav-link img {
        width: 45px;
        height: 45px;
        margin-right: 1rem;
    }

    .nav-pills .nav-link strong {
        font-size: 1.05rem;
    }

    .nav-pills .nav-link#v-pills-general-tab.active {
        background-color: #e2e3e5;
        border-left: 5px solid #6c757d;
        color: #000;
    }

    .nav-pills .nav-link#v-pills-organic-tab.active {
        background-color: #d1e7dd;
        border-left: 5px solid #198754;
        color: #000;
    }

    .nav-pills .nav-link#v-pills-recyclable-tab.active {
        background-color: #cff4fc;
        border-left: 5px solid #0dcaf0;
        color: #000;
    }

    .nav-pills .nav-link#v-pills-infectious-tab.active {
        background-color: #f8d7da;
        border-left: 5px solid #dc3545;
        color: #000;
    }

    .nav-pills .nav-link#v-pills-hazardous-tab.active {
        background-color: #fff3cd;
        border-left: 5px solid #ffc107;
        color: #000;
    }

    .nav-pills .nav-link:not(.active):hover {
        border-left: 5px solid #adb5bd;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 text-success"><i class="bi bi-trash-fill"></i> บันทึกปริมาณขยะประจำวัน</h1>
</div>

<div id="alert-container"></div>

<div class="row">
    <div class="col-lg-2">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button"><img src="assets/images/waste_logo/general_waste.png"><strong>ขยะทั่วไป</strong></button>
            <button class="nav-link" id="v-pills-organic-tab" data-bs-toggle="pill" data-bs-target="#v-pills-organic" type="button"><img src="assets/images/waste_logo/organic_waste.png"><strong>ขยะอินทรีย์</strong></button>
            <button class="nav-link" id="v-pills-recyclable-tab" data-bs-toggle="pill" data-bs-target="#v-pills-recyclable" type="button"><img src="assets/images/waste_logo/recyclable_waste.png"><strong>ขยะรีไซเคิล</strong></button>
            <button class="nav-link" id="v-pills-infectious-tab" data-bs-toggle="pill" data-bs-target="#v-pills-infectious" type="button"><img src="assets/images/waste_logo/infectious_waste.png"><strong>ขยะติดเชื้อ</strong></button>
            <button class="nav-link" id="v-pills-hazardous-tab" data-bs-toggle="pill" data-bs-target="#v-pills-hazardous" type="button"><img src="assets/images/waste_logo/hazardous_waste.png"><strong>ขยะอันตราย</strong></button>
        </div>
    </div>

    <div class="col-lg-10">
        <div class="tab-content" id="v-pills-tabContent">

            <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel">
                <?php include 'templates/waste_forms/general_form.php'; ?>
            </div>

            <div class="tab-pane fade" id="v-pills-organic" role="tabpanel">
                <?php include 'templates/waste_forms/organic_form.php'; ?>
            </div>

            <div class="tab-pane fade" id="v-pills-recyclable" role="tabpanel">
                <?php include 'templates/waste_forms/recyclable_form.php'; ?>
            </div>

            <div class="tab-pane fade" id="v-pills-infectious" role="tabpanel">
                <?php include 'templates/waste_forms/infectious_form.php'; ?>
            </div>

            <div class="tab-pane fade" id="v-pills-hazardous" role="tabpanel">
                <?php include 'templates/waste_forms/hazardous_form.php'; ?>
            </div>

        </div>
    </div>
</div>

<?php
include_once 'templates/footer.php';
?>
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/locales/th.js"></script>
<script src="assets/js/record_waste.js"></script>
<script src="assets/js/record_waste_organic.js"></script>
<script src="assets/js/record_waste_recyclable.js"></script>
<script src="assets/js/record_waste_infectious.js"></script>
<script src="assets/js/record_waste_hazardous.js"></script>
</body>

</html>