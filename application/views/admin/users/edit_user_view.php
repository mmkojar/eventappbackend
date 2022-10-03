<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>

<div id="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="header">Edit User</div>
					<div class="content">
						<?php $this->load->view('public/themes/default/error_msg'); ?>
						<div class="row">
							<?php echo form_open('',array('id'=>'userFormValidation','class'=>'form_validation'));?>
								<div class="col-md-6">
									<div class="form-group">
										<?php 
										$attributes = array(
											'class' => 'control-label'
										);
											echo form_label('First name','first_name',$attributes);
											echo form_error('first_name');
											echo form_input('first_name',set_value('first_name',$user->first_name),'class="form-control"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Last name','last_name',$attributes);
											echo form_error('last_name');
											echo form_input('last_name',set_value('last_name',$user->last_name),'class="form-control"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Company','company',$attributes);
											echo form_error('company');
											echo form_input('company',set_value('company',$user->company),'class="form-control"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Phone','phone',$attributes);
											echo form_error('phone');
											echo form_input('phone',set_value('phone',$user->phone),'class="form-control"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Username','username',$attributes);
											echo form_error('username');
											echo form_input('username',set_value('username',$user->username),'class="form-control"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Email','email',$attributes);
											echo form_error('email');
											echo form_input('email',set_value('email',$user->email),'class="form-control" required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Password','password',$attributes);
											echo form_error('password');
											echo form_password('password','','class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Confirm password','password_confirm',$attributes);
											echo form_error('password_confirm');
											echo form_password('password_confirm','','class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('City','city',$attributes);
											echo form_error('city');
											echo form_input('city',set_value('city',$user->city),'class="form-control"  required ="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Status','status',$attributes);
											echo form_error('status');
										?>
										<select name="status" class="form-control">
										    <option value="1" <?php echo $user->active == '1' ? 'selected' : '' ?>>Active</option>
										    <option value="0" <?php echo $user->active == '0' ? 'selected' : '' ?>>Inactive</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
										if(isset($groups))
										{
											echo form_label('Groups','groups[]',$attributes);
											foreach($groups as $group)
											{
												// if($group->id != "1"){
													echo '<label class="radio">';
													echo form_radio('groups[]', $group->id, set_radio('groups[]', $group->id, in_array($group->id,$usergroups)),'data-toggle="radio"');
													echo ' '.$group->description;
													echo '</label>';
												// }
											}
										}
										?>
									</div>
								</div>
							<br>
							<?php echo form_hidden('user_id',$user->id);?>
							<div class="col-md-12">
									<?php echo form_submit('submit', 'Update user', 'class="btn btn-info btn-fill btn-wd"');?>								
							</div>
							<?php echo form_close();?>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>