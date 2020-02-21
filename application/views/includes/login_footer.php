        <!-- Libraries -->
        <script src="<?= assets_url("datatables.net/js/jquery.dataTables.min.js") ?>"></script>
        <script src="<?= assets_url("datatables.net-bs4/js/dataTables.responsive.min.js") ?>"></script>
        <script src="<?= assets_url("sweetalert2/dist/sweetalert2.all.min.js") ?>"></script>
        <script src="<?= assets_url("bootstrap/js/popper.min.js") ?>"></script>
        <script src="<?= assets_url("bootstrap/js/bootstrap.min.js") ?>"></script>
        <script src="<?= assets_url("ps/perfect-scrollbar.jquery.min.js") ?>"></script>
        <script src="<?= assets_url("raphael/raphael.min.js") ?>"></script>
        <script src="<?= assets_url("morrisjs/morris.min.js") ?>"></script>
        <script src="<?= assets_url("d3/d3.min.js") ?>"></script>
        <script src="<?= assets_url("c3-master/c3.min.js") ?>"></script>
        <script src="<?= assets_url("toast-master/js/jquery.toast.js") ?>"></script>
        <script src="<?= assets_url("js/waves.js") ?>"></script>
        <script src="<?= assets_url("js/sidebarmenu.js") ?>"></script>
        <script src="<?= assets_url("js/custom.min.js") ?>"></script>
        <script src="<?= assets_url("js/validation.js") ?>"></script>
        <!-- End of Libraries -->

        <!-- Custom Scripts -->
        <script src="<?= assets_url("js/common_scripts.js") ?>" charset="utf-8"></script>
        <?php get_module_script(); ?>

        <script type="text/javascript">
            $(function() {
                $(".preloader").fadeOut();
            });
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            });
            // ==============================================================
            // Login and Recover Password
            // ==============================================================
            $('#to-recover').on("click", function() {
                $("#loginform").slideUp();
                $("#recoverform").fadeIn();
            });
        </script>

        <!-- End Custom Scripts -->
        </body>

</html>
