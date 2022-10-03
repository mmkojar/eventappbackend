<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

	<?php $this->load->view('public/themes/default/slider');?>
	<div id="content">
		<div class="container-fluid">
			<div class="row">
			  <div class="col-md-6">
				<div class="card">
					<div class="content">
						<div class="toolbar">
							<!--        Here you can write extra buttons/actions for the toolbar              -->
						</div>
				<?php $this->load->view('public/themes/default/error_msg'); ?>
				<?php echo form_open('',array('id'=>'ChangeFormValidation'));?>
					<?php 
						$attributes = array(
							'class' => 'control-label'
						);?>
				<div class="header">Change Password</div>
				<br>
					<div class="form-group">
					<?php
						echo form_label('Old password','old',$attributes);
						echo form_error('old');
						echo form_password('old','','class="form-control" required ="true"');
						?>
				  </div>
				   <div class="form-group">
					<?php
						echo form_label('New Password','new',$attributes);
						echo form_error('new');
						echo form_password('new','','class="form-control" required ="true"');
					?>
				  </div>
				  <div class="form-group">
					<?php
						echo form_label('Confirm New Password','new_confirm',$attributes);
						echo form_error('new_confirm');
						echo form_password('new_confirm','','class="form-control" required ="true"');
					?>
				  </div>
					<br>
					<?php echo form_submit('submit', 'Change Password', 'class="btn btn-info btn-fill btn-wd"');?>
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

		$('#ChangeFormValidation').validate();

	});
</script>