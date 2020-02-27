
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->

        <!-- View Modal -->
        <?php include('modals.php') ?>

        <footer class="footer">
            © 2019 VGE Trading - <a target="_blank" href="https://www.web2.ph">Web2.ph</a>
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

    <!-- Libraries -->
    <script src="<?= assets_url("echarts/echarts-all.js") ?>"></script>
    <script src="<?= assets_url("select2/select2.min.js") ?>"></script>
    <script src="<?= assets_url("datatables.net/js/jquery.dataTables.min.js") ?>"></script>
	<script src="<?= assets_url("datatables.net-bs4/js/dataTables.responsive.min.js") ?>"></script>
    <script src="<?= assets_url("sweetalert2/dist/sweetalert2.all.min.js") ?>"></script>
    <script src="<?= assets_url("bootstrap/js/popper.min.js") ?>"></script>
    <script src="<?= assets_url("bootstrap/js/bootstrap.min.js") ?>"></script>
    <script src="<?= assets_url("ps/perfect-scrollbar.jquery.min.js") ?>"></script>
    <script src="<?= assets_url("raphael/raphael.min.js") ?>"></script>
    <script src="<?= assets_url("morrisjs/morris.min.js") ?>"></script>
    <script src="<?= assets_url("magic_suggest/magicsuggest-min.js") ?>"></script>
    <script src="<?= assets_url("d3/d3.min.js") ?>"></script>
    <script src="<?= assets_url("c3-master/c3.min.js") ?>"></script>
    <script src="<?= assets_url("toast-master/js/jquery.toast.js") ?>"></script>
    <script src="<?= assets_url("js/waves.js") ?>"></script>
    <script src="<?= assets_url("Chart.js/Chart.min.js") ?>"></script>
    <script src="<?= assets_url("doersjquery/jQuery.print.min.js") ?>"></script>

    <script src="<?= assets_url("bootstrap-select/bootstrap-select.min.js") ?>"></script>
    <script src="<?= assets_url("bootstrap_pagination/jquery.twbsPagination.min.js") ?>"></script>
    <script src="<?= assets_url("js/sidebarmenu.js") ?>"></script>
    <script src="<?= assets_url("js/custom.min.js") ?>"></script>
    <script src="<?= assets_url("js/validation.js") ?>"></script>
    <script src="<?= assets_url("js/datepicker.min.js") ?>"></script>
    <script src="<?= assets_url("js/datepicker.en.js") ?>"></script>
    <script src="<?= assets_url("domtoimage/domtoimage.min.js") ?>"></script>
    <script src="<?= assets_url("domtoimage/filesaver.min.js") ?>"></script>

    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/7.9.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.9.1/firebase-firestore.js"></script>

    <!-- TODO: Add SDKs for Firebase products that you want to use
         https://firebase.google.com/docs/web/setup#available-libraries -->

    <script>

    var firebaseConfig = {
        apiKey: "AIzaSyBpP3FdCVe-arb68knsC3oGAD5cBf90Rik",
        authDomain: "vgeoryspa-75b37.firebaseapp.com",
        databaseURL: "https://vgeoryspa-75b37.firebaseio.com",
        projectId: "vgeoryspa-75b37",
        storageBucket: "vgeoryspa-75b37.appspot.com",
        messagingSenderId: "1089179682312",
        appId: "1:1089179682312:web:ee64583ec7863023fc33bd"
    };
    firebase.initializeApp(firebaseConfig);

    var db = firebase.firestore();

    <?php if ($this->session->type != 2) { ?>
        db.collection("notifications").onSnapshot(function(snapshot) {
            snapshot.docChanges().forEach(function(change) {
                if (change.type === "added") {
                    const msg = {
                        type : 'success',
                        message : change.doc.data().content
                    };
                    showNotification(msg, 5000);
                    db.collection("notifications").doc(change.doc.id).delete()
                }
        });
        }, function(error) {

        });
    <?php } ?>
    </script>
    <!-- END OF FIREBASE -->

    <!-- End of Libraries -->

    <!-- Custom Scripts -->
    <script src="<?= assets_url("js/common_scripts.js") ?>"></script>
    <?php get_module_script(); ?>
    <!-- End Custom Scripts -->

    <script type="text/javascript">
    <?php if ($this->session->flashdata('error')) { ?>
        const notifConfig = {
            type : 'error',
            message : '<?= $this->session->flashdata('error') ?>',
        };
        showNotification(notifConfig)
    <?php } ?>

    <?php if ($this->session->flashdata('success')) { ?>
        const notifConfig = {
            type : 'success',
            message : '<?= $this->session->flashdata('success') ?>',
        };
        showNotification(notifConfig)
    <?php } ?>
    </script>

</body>

</html>
