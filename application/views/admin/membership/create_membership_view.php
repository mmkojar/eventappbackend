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
					<?php echo form_open('',array('id'=>'addMembership'));?>
						<div class="header">Add Membership</div>
						<br>
						<div class="form-group">
							<?php
								$attributes = array(
								'class' => 'control-label'
							);
								echo form_label('Membership Number','membership_number',$attributes);
								echo form_error('membership_number');
								echo form_input('membership_number',set_value('membership_number'),'class="form-control"  required ="true"');
							?>
						</div>
						<div class="form-group">
							<?php
								echo form_label('Company Name','company_name',$attributes);
								echo form_error('company_name');
								echo form_input('company_name',set_value('company_name'),'class="form-control"  required ="true"');
							?>
						</div>
						<br>
						<?php echo form_submit('submit', 'Create Membership', 'class="btn btn-info btn-fill btn-wd"');?>
						<?php echo form_close();?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$('#addMembership').validate();
});
</script>
