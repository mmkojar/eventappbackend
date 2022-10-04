<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<div id="content">
	<div class="container-fluid">
	<a href="<?php echo site_url('admin/gallery/create');?>" class="btn btn-info btn-fill btn-wd" style='margin:10px 0px'>Add</a>
	<!--<a href="<?php //echo site_url('admin/sponsor/upload_attendee');?>" class="btn btn-info btn-fill btn-wd" style='margin:10px'>Bulk Upload Attendee</a>-->

	<div class="row">
		<div class="col-md-12">
			<div class="card">
			<?php $this->load->view('public/themes/default/error_msg'); ?>
				<div class="content">
					<div class="toolbar">
					</div>
						<div class="fresh-datatables">
							<table id="datatable" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
								<thead>
									<th>ID</th>
									<th>Title</th>
									<th>Image</th>
									<th>Status</th>
									<th>Requested On</th>
									<th>Operations</th>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>