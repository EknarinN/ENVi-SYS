<footer class="py-4 mt-auto">
    <hr>
    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between small">
        <div class="text-muted">&copy; 2025 ENVi-SYS Version 1.0.0</div>
        <div class="text-center text-muted">
            <div>User Interface : เบญจมาศ วังนุราช.</div>
            <div>Logo & App Name & Master Data : สิริคุณ พวงทอง.</div>
            <div>Code & Databases Structure : เอกนรินทร์ ณัฐภณ.</div>
        </div>
    </div>
</footer>

</div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="assets/js/register_form.js"></script>
<?php if (basename($_SERVER['PHP_SELF']) === 'record_waste.php'): ?>
<script src="assets/js/record_waste.js?v=2"></script>
<script src="assets/js/record_waste_organic.js?v=1"></script>
<script src="assets/js/record_waste_recyclable.js?v=1"></script>
<script src="assets/js/record_waste_infectious.js?v=1"></script>
<script src="assets/js/record_waste_hazardous.js?v=1"></script>
<?php endif; ?>

</body>
</html>