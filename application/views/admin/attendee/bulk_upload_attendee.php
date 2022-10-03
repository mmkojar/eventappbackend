<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<div id="content">
	<div class="container-fluid">
		<a href="<?php echo site_url('admin/attendee/create');?>" class="btn btn-info btn-fill btn-wd" style="margin:15px">Add Attendee</a>
		<a href="<?php echo site_url()."assets/upload/attendee.xlsx";?>" class="btn btn-info btn-fill btn-wd" style="margin:15px">Demo Excel Download</a>
		<div class="row">
			<div class="col-md-6">
				<div class="card">
				<?php $this->load->view('public/themes/default/error_msg'); ?>
					<div class="content">
						<div class="toolbar">
							<!--Here you can write extra buttons/actions for the toolbar-->
						</div>
						<div class="header">Upload Excel</div>
						<?php echo form_open_multipart('admin/Upload_attendee/do_upload',array('id'=>'Uploadform'));?>
						<br>
						<div class="control-group" id='images'>
						<?php
							echo form_label('Excel Upload','userfile');
							echo form_error('userfile');
							echo form_upload('userfile',set_value('userfile'),'class="form-control" required');
						?>
						</div>
						<br>
						<?php echo form_submit('submit', 'Upload Excel', 'class="btn btn-info btn-fill btn-wd"');?>
						<?php echo form_close();?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script>


 <script type="text/javascript">
	$().ready(function(){

		$('#Uploadform').validate();

	});
</script>