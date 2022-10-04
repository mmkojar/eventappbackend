<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<div id="content">
	<div class="container-fluid">
	<a href="<?php echo site_url('admin/FAQ/create');?>" class="btn btn-info btn-fill btn-wd" style='margin:10px 0px'>Add</a>

		<div class="row">
			<div class="col-md-12">
				<div class="card">
				<?php $this->load->view('public/themes/default/error_msg'); ?>
					<div class="content">
						<div class="toolbar">
							<!--        Here you can write extra buttons/actions for the toolbar              -->
						</div>
						<div class="fresh-datatables">
							<table id="datatable" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
								<thead>
									<th>ID</th>
									<th>Title</th>
									<th>Description</th>
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