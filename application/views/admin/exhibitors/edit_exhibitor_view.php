<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>

<div id="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="header"><?php echo $page_title ?></div>
					<div class="content">
						<div class="row">
							<?php $this->load->view('public/themes/default/error_msg');?>
							<?php echo form_open('',array('id'=>'addExhibitors','class'=>'form_validation','enctype'=>'multipart/form-data'));?>
							<?php if(validation_errors()): ?>
								<div class="alert alert-danger">
									<?php print_r(validation_errors());?> 
								</div>
							<?php endif ?>		
							<div class="form-group">
								<?php 
									$attributes = array(
									'class' => 'control-label'
									);
								echo form_label('Exhibitor Name','ex_name',$attributes);								
								echo form_input('ex_name',set_value('ex_name',$exhibitors_data['ex_name']),'class="form-control" required = "true"',);
								?>
							</div>
							<div class="form-group">
								<?php
									echo form_label('Website Url','web_url',$attributes);									
									echo form_input('web_url',set_value('web_url',$exhibitors_data['web_url']),'class="form-control" required = "true"');
								?>
							</div>
							<div class="form-group">
								<img class="avatar border-gray" src="<?php echo base_url()."".str_replace("./", "", $exhibitors_data['ex_image']); ?>" alt="..." />
								<?php 
								echo form_hidden('hidden_image',set_value('ex_image',$exhibitors_data['ex_image']),'class="form-control"');
								?>
								<?php
									echo form_label('Exhibitor Image (PNG, JPEG)','ex_image',$attributes);									
									echo form_upload('ex_image',set_value('ex_image'),'class="form-control" id="images_up"');
								?>
							</div>
							<div class="form-group">
								<label>Status</label>
								<select name="status" class="form-control">
									<option value="1" <?php echo ($exhibitors_data['status'] == '1') ? 'selected' : '' ?>>Active</option>
									<option value="0" <?php echo ($exhibitors_data['status'] == '0') ? 'selected' : '' ?>>Inactive</option>
								</select>
							</div>
							<?php echo form_submit('submit', 'Update', 'class="btn btn-info btn-fill btn-wd"');?>
							<?php echo form_close();?>								
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
