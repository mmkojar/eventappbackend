<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<style>
	.card .form-group > label {
		font-weight: 600;
	}
	.error{
		color:red;
	}
	#registration_fees_type_id-error{
		margin-top: -120px;
		left: -150px;
		width: 500px;
		position: absolute;
		max-width: 150px !important;
	}
	.new_radio{
		padding-left: 30px !important;
	 }
</style>
<div id="content">
	<div class="container-fluid">
		<div class="row" >
			<div class="col-md-12">
				<div class="card">
				<?php echo form_open('',array('id'=>'editDelegate', 'enctype'=>'multipart/form-data'));?>
					<?php $this->load->view('public/themes/default/error_msg');?>
					<!-- <?php //print_r(validation_errors());?> -->
					<!-- <div class="header">Delegate Registration</div> -->
						<div class="col-md-6">
							<div class="content">
								<div class="form-group">
									<?php 
										$attributes = array(
										'class' => 'control-label'
										);
									echo form_label('Delegate Name','delegate_name',$attributes);
									echo form_error('delegate_name');
									echo form_input('delegate_name',set_value('delegate_name',$delegate_data['delegate_name']),'class="form-control" required = "true"');
									?>
								</div>	
								<div class="form-group">
					                    <?php
											echo form_label('Delegate Email','delegate_email',$attributes);
											echo form_error('delegate_email');
											echo form_input('delegate_email',set_value('delegate_email',$delegate_data['delegate_email']),'class="form-control" required = "true"');
										?>
								</div>
								<div class="form-group">
									<?php
										echo form_label('Delegate Phone','delegate_phone',$attributes);
										echo form_error('delegate_phone');
										echo form_input('delegate_phone',set_value('delegate_phone',$delegate_data['delegate_phone']),'class="form-control" required = "true"');
									?>
								</div>		
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="content">
								<div class="form-group">
									<?php echo form_submit('submit', 'Update Delegate', 'class="btn btn-info btn-fill btn-wd" style="margin: 0px 10px;"');?>
										<?php echo form_close();?>
								</div>
							</div>								
						</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	console.log($(window).width());
	// if($(window).width() > "700"){
	// 	$("#editDelegate").css('display','inline-table');
	// }
	$('#editDelegate').validate();
	$('#datepicker').datetimepicker({format: 'DD-MM-YYYY'});
	$('#timepicker').datetimepicker({format: 'LT'});
});


</script>
