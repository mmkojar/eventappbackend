<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<div id="content">
	<div class="container-fluid">
	<a href="<?php echo site_url('admin/attendee/create');?>" class="btn btn-info btn-fill btn-wd" style='margin:10px'>Add Attendee</a>
	<!--<a href="<?php echo site_url('admin/attendee/upload_attendee');?>" class="btn btn-info btn-fill btn-wd" style='margin:10px'>Bulk Upload Attendee</a>-->

	<div class="row">
		<div class="col-md-12">
			<div class="card">
			<?php $this->load->view('public/themes/default/error_msg'); ?>
				<div class="content">
					<div class="toolbar">
						<!--        Here you can write extra buttons/actions for the toolbar              -->
					</div>
						<div class="fresh-datatables">
							<table id="attendee_table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
								<thead>
									<th>ID</th>
									<th>Name</th>
									<th>Email</th>
									<th>Company Name</th>
									<th>Membership Number</th>
									<th>Status</th>
									<th>Publish Name</th>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery.datatables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/pdfmake.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/admin/js/jszip.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/admin/js/vfs_fonts.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$.noConflict();
		$('#attendee_table').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			// Load data for the table's content from an Ajax source
			 "ajax": {
				"url": "<?php echo base_url();?>admin/attendee/attendee_list",
				"type": "POST"
			},
		    "pagingType": "full_numbers",
		    lengthMenu: [[10,25,50,100, -1], [10,25,50,100, "All"]],
			columnDefs: [
				{ responsivePriority: 2, targets: 0 },
				{ responsivePriority: 2, targets: -1 }
			],
		    responsive: true,
		    language: {
				search: "_INPUT_",
				searchPlaceholder: "Search records",
		    },
			"dom": 'lBfrtip',
			 "buttons": [
				{
					extend: 'collection',
					text: '<span></span> Export',
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
	});
</script>