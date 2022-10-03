<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8">
				<div class="card">
					<div class="header">
						<h4 class="title">Edit Profile</h4>
					</div>
					<div class="content">
						<?php $this->load->view('public/themes/default/error_msg'); ?>
						 <?php echo form_open('',array('id'=>'userprofileValidation','enctype'=>'multipart/form-data' ));?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											echo form_label('UserName (disabled)');
											echo form_error('username');
											echo form_input('username',set_value('username',$user->username),'class="form-control" disabled');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											echo form_label('Email','email');
											echo form_error('email');
											echo form_input('email',set_value('email',$user->email),'class="form-control" disabled');
										?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											echo form_label('First Name');
											echo form_error('first_name');
											echo form_input('first_name',set_value('first_name',$user->first_name),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											echo form_label('Last Name');
											echo form_error('last_name');
											echo form_input('last_name',set_value('last_name',$user->last_name),'class="form-control"');
										?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Password','password');
											echo form_error('password');
											echo form_password('password',set_value('password'),'class="form-control" id="registerPassword"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Confirm password','password_confirm');
											echo form_error('password_confirm');
											echo form_password('password_confirm',set_value('password_confirm'),'class="form-control" id="registerPasswordConfirmation" equalTo="#registerPassword"');
										?>
									</div>
								</div>
							</div>
							
							<input type="file" name="imageprofile" id="imgupload" style="display:none"/>
							<div class="row">
							    <div class="col-md-12">
							        <?php echo form_submit('submit', 'Update Profile', 'class="btn btn-fill btn-info btn-wd"');?>
							    </div>
							  </div>
							<div class="clearfix"></div>
						<?php echo form_close();?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card card-user">
					<div class="image">
						<img src="<?php echo base_url(); ?>assets/admin/img/full-screen-image-3.jpg" alt="..." />
					</div>
					<div class="content">
						<div class="author">
							<a href="javascript:;">
							<img class="avatar border-gray" src="<?php echo base_url(); ?>assets/admin/img/default-avatar.png" alt="..." id="OpenImgUpload"/>
							  <h4 class="title"><?php print_r($this->ion_auth->user()->row()->first_name ." ".$this->ion_auth->user()->row()->last_name);?><br/>
								 <small><?php print_r($this->ion_auth->user()->row()->username);?></small><br/>
								 <small><?php print_r($this->ion_auth->user()->row()->phone);?></small><br/>
							  </h4>
							</a>
						</div>
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

		$('#userprofileValidation').validate();
		
	});
	
	$('#OpenImgUpload').click(function(){
		$('#imgupload').trigger('click'); 
	});
		
	$("#imgupload").change(function(){
		readURL(this);
	});
	
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function (e) {
				$('#OpenImgUpload').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]);
		}
	}
</script>