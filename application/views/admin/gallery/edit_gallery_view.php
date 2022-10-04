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
							<?php echo form_open('',array('class'=>'form_validation', 'enctype'=>'multipart/form-data'));?>
							<?php if(validation_errors()): ?>
								<div class="alert alert-danger">
									<?php print_r(validation_errors());?> 
								</div>
							<?php endif ?>						
							<div class="form-group">
								<?php 
									$attributes = array('class' => 'control-label');
									echo form_label('title','title',$attributes);
									echo form_input('title',set_value('title',$result['title']),'class="form-control" required = "true"');
								?>
							</div>
							<div class="form-group">
								<img class="avatar border-gray" src="<?php echo base_url()."".str_replace("./", "", $result['image']); ?>" alt="..." />
								<?php 
									echo form_hidden('hidden_image',set_value('image',$result['image']),'class="form-control"');
								?>
								<?php
									echo form_label('Image (PNG, JPEG)','image',$attributes);
									echo form_upload('image',set_value('image'),'class="form-control"');
								?>
							</div>
							<div class="form-group">
									<label>Status</label>
									<select name="status" class="form-control">
										<option value="1" <?php echo ($result['status'] == '1') ? 'selected' : '' ?>>Active</option>
										<option value="0" <?php echo ($result['status'] == '0') ? 'selected' : '' ?>>Inactive</option>
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