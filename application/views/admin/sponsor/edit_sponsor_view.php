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
							<?php echo form_open('',array('id'=>'addSponsor', 'class'=>'form_validation', 'enctype'=>'multipart/form-data'));?>
							<?php if(validation_errors()): ?>
								<div class="alert alert-danger">
									<?php print_r(validation_errors());?> 
								</div>
							<?php endif ?>
						
							<div class="form-group">
								<?php 
									$attributes = array('class' => 'control-label');
									echo form_label('name','name',$attributes);
									echo form_input('name',set_value('name',$sponsor_data['name']),'class="form-control" required = "true"');
								?>
							</div>	
							<div class="form-group">
									<?php
										echo form_label('Link','link',$attributes);
										echo form_input('link',set_value('link',$sponsor_data['link']),'class="form-control" required = "true"');
									?>
							</div>
							<div class="form-group">
								<img class="avatar border-gray" src="<?php echo base_url()."".str_replace("./", "", $sponsor_data['image']); ?>" alt="..." />
								<?php 
									echo form_hidden('hidden_image',set_value('sponsor_image',$sponsor_data['image']),'class="form-control"');
								?>
								<?php
									echo form_label('Image (PNG, JPEG)','sponsor_image',$attributes);
									echo form_upload('sponsor_image',set_value('sponsor_image'),'class="form-control" id="images_up"');
								?>
							</div>
							<div class="form-group">
									<label>Status</label>
									<select name="status" class="form-control">
										<option value="1" <?php echo ($sponsor_data['status'] == '1') ? 'selected' : '' ?>>Active</option>
										<option value="0" <?php echo ($sponsor_data['status'] == '0') ? 'selected' : '' ?>>Inactive</option>
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