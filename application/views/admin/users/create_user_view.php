<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>

<div id="content">
	<div class="container-fluid">	
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="header">Add User</div>
					<div class="content">					
						<div class="row">
							<?php $this->load->view('public/themes/default/error_msg'); ?>
							<?php echo form_open('',array('id'=>'userFormValidation','class'=>'form_validation','enctype'=>'multipart/form-data'));?>
							<?php if(validation_errors()): ?>
								<div class="alert alert-danger">
									<?php print_r(validation_errors());?> 
								</div>
							<?php endif ?>
								<div class="col-md-6">
									<div class="form-group">
										<?php 
										$attributes = array(
											'class' => 'control-label'
										);
											echo form_label('Employee ID','emp_code',$attributes);											
											echo form_input('emp_code',set_value('emp_code'),'class="form-control" maxlength="6"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php 
										$attributes = array(
											'class' => 'control-label'
										);
											echo form_label('First name','first_name',$attributes);											
											echo form_input('first_name',set_value('first_name'),'class="form-control"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Last name','last_name',$attributes);											
											echo form_input('last_name',set_value('last_name'),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Company','company',$attributes);											
											echo form_input('company',set_value('company'),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Phone','phone',$attributes);											
											echo form_input('phone',set_value('phone'),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Username','username',$attributes);											
											echo form_input('username',set_value('username'),'class="form-control"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Email','email',$attributes);											
											echo form_input('email',set_value('email'),'class="form-control"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Password','password',$attributes);											
											echo form_password('password','','class="form-control"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Confirm password','password_confirm',$attributes);											
											echo form_password('password_confirm','','class="form-control" required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('City','city',$attributes);											
											echo form_input('city',set_value('city'),'class="form-control" required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Image (PNG, JPG, JPEG) (min128 x max512)','user_image',$attributes);
											echo form_upload('user_image',set_value('user_image'),'class="form-control" ');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											if(isset($groups))
											{
												echo form_label('Groups','groups[]',$attributes);
												foreach($groups as $group){
													// if($this->ion_auth->in_group('admin')){
														if($group->name !== "sp-admin"){
															echo '<label class="radio">';
															echo form_radio('groups[]', $group->id, set_radio('groups[]', $group->id),'data-toggle="radio" required ="true"');
															echo ' '.$group->description;
															echo '</label>';
														}
													// }
												}
											}
										?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<?php echo form_submit('submit', 'Create user', 'class="btn btn-info btn-fill btn-wd"');?>								
									</div>
								</div>
							<?php echo form_close();?>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>