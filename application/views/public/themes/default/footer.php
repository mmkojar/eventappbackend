		<footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-right">
                    &copy; <?php echo date("Y"); ?> <a href=https://info2ideas.com/" style="color:#23CCEF;    font-size: 18px;">info2ideas</a>, made with love for a better web
                </p>
            </div>
        </footer>
        
    </div>
</div>
    <!--   Core JS Files and PerfectScrollbar library inside jquery.ui   -->
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
     <script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script> 
	<script src="<?php echo base_url(); ?>assets/admin/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Forms Validations Plugin -->
	<script src="<?php echo base_url(); ?>assets/admin/js/jquery.validate.min.js"></script>

	<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
	<script src="<?php echo base_url(); ?>assets/admin/js/moment.min.js"></script>

    <!--  Date Time Picker Plugin is included in this js file -->
    <script src="<?php echo base_url(); ?>assets/admin/js/bootstrap-datetimepicker.js"></script>

    <!--  Select Picker Plugin -->
    <script src="<?php echo base_url(); ?>assets/admin/js/bootstrap-selectpicker.js"></script>

    <!-- Sweet Alert 2 plugin -->
	<script src="<?php echo base_url(); ?>assets/admin/js/sweetalert2.js"></script>
    
    <?php if(isset($dttable_tab) == 'dt_table'): ?>
	<!--  Plugin for DataTables.net  -->
        <script src="<?php echo base_url(); ?>assets/admin/js/jquery.datatables.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/js/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/js/buttons.html5.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin/js/pdfmake.min.js"></script> 
        <script src="<?php echo base_url(); ?>assets/admin/js/jszip.min.js"></script> 
        <script src="<?php echo base_url(); ?>assets/admin/js/vfs_fonts.js"></script>
    <?php endif; ?>    
    <!-- Light Bootstrap Dashboard Core javascript and methods -->
	<script src="<?php echo base_url(); ?>assets/admin/js/light-bootstrap-dashboard.js"></script>
    <!-- DEMO JS -->
    <script src="<?php echo base_url(); ?>assets/admin/js/demo.js"></script>    
    <!--  Checkbox, Radio, Switch and Tags Input Plugins -->
	<script src="<?php echo base_url(); ?>assets/admin/js/bootstrap-checkbox-radio-switch-tags.js"></script>

	<!--  Charts Plugin -->
	<!-- <script src="<?php //echo base_url(); ?>assets/admin/js/chartist.min.js"></script> -->

    <!--  Notifications Plugin    -->
    <!-- <script src="<?php //echo base_url(); ?>assets/admin/js/bootstrap-notify.js"></script> -->
    <!-- Vector Map plugin -->
	<!-- <script src="<?php //echo base_url(); ?>assets/admin/js/jquery-jvectormap.js"></script> -->

    <!--  Google Maps Plugin    
    <script src="https://maps.googleapis.com/maps/api/js"></script>-->

	<!-- Wizard Plugin    -->
    <!-- <script src="<?php //echo base_url(); ?>assets/admin/js/jquery.bootstrap.wizard.min.js"></script> -->

    <!--  Bootstrap Table Plugin    -->
    <!-- <script src="<?php //echo base_url(); ?>assets/admin/js/bootstrap-table.js"></script> -->


    <!--  Full Calendar Plugin    -->
    <!-- <script src="<?php //echo base_url(); ?>assets/admin/js/fullcalendar.min.js"></script> -->


	<!--   Sharrre Library    -->
    <!-- <script src="<?php //echo base_url(); ?>assets/admin/js/jquery.sharrre.js"></script> -->

	<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
	
    <script type="text/javascript">
       
        $(document).ready(function(){
            setTimeout(() => {
                $(".alert_msg").hide();
                <?php unset($_SESSION['success']); unset($_SESSION['message']); unset($_SESSION['message1']); unset($_SESSION['error']); ?>
            }, 2500);
            
            // lbd.checkFullPageBackgroundImage();
            
            setTimeout(function(){
              // after 1000 ms we add the class animated to the login/register card-
               $('.card').removeClass('card-hidden')
            }, 200)

            $('#datepicker').datetimepicker({format: 'DD-MM-YYYY',minDate: new Date()});
	        $('#timepicker').datetimepicker({format: 'LT'});
            
            <?php if(isset($dttable_tab) && $dttable_tab == 'jqdatatable'): ?>
                $("#jqdatatable").DataTable({
                    responsive:true
                });
            <?php endif; ?>   
            <?php if(isset($dttable_tab) && $dttable_tab == 'dt_table'): ?>
                /*  $.noConflict(); */
                var Table = $('#datatable').dataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo base_url();?>admin/<?php echo $tbl_name?>",
                    "type": "POST"
                },
                responsive: true,
                lengthMenu: [[10,25,50,100, -1], [10,25,50,100, "All"]],
                columnDefs: [
                    { responsivePriority: 2, targets: 0 },
                    { responsivePriority: 2, targets: -1 }
                ],
                "dom": 'lBfrtip',
                "buttons": [
                    {
                        extend: 'collection',
                        text: 'Export',
                        buttons: [
                            'excel',
                            'csv',
                            'pdf',
                        ]
                    }
                ]
                });
                $("#datatables_filter label input").addClass("form-control input-sm");
                $("#datatables_length label select").addClass("form-control input-sm");
                $(".dt-buttons a").removeClass("dt-button buttons-collection");
                $(".dt-buttons a").addClass("btn btn-info btn-fill btn-wd");
                $(".dt-buttons").css("left","10px");
                <?php endif; ?>    
                $('.form_validation').validate();                
        });

    </script>
    </body>
</html>