<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<?php $this->load->view('public/themes/default/error_msg'); ?>
					<?php echo form_open('',array('id'=>'groupFormValidation'));?>
					<div class="header">Add Group</div>
					<div class="content">
						<div class="form-group">
							<?php 
								$attributes = array(
									'class' => 'control-label'
								);
								echo form_label('Group name','group_name',$attributes);
								echo form_error('group_name');
								echo form_input('group_name',set_value('group_name'),'class="form-control" required ="true"');
							?>
						</div>
						<div class="form-group">
							<?php 
								$attributes = array(
									'class' => 'control-label'
								);
								echo form_label('Group description','group_description',$attributes);
								echo form_error('group_description');
								echo form_input('group_description',set_value('group_description'),'class="form-control" required ="true"');
							?>
						</div>
						<br>
						<?php
							echo form_submit('submit', 'Create group', 'class="btn btn-info btn-fill btn-wd"');
							echo form_close();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/bootstrap.min.js" type="text/javascript"></script>


 <script type="text/javascript">
	$().ready(function(){

		$('#groupFormValidation').validate();

	});
</script>