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
								echo form_input('ex_name',set_value('ex_name'),'class="form-control" required = "true"',);
								?>
							</div>
							<div class="form-group">
								<?php
									echo form_label('Website Url','web_url',$attributes);									
									echo form_input('web_url',set_value('web_url'),'class="form-control" required = "true"');
								?>
							</div>
							<div class="form-group">
								<?php
									echo form_label('Exhibitor Image (PNG, JPG, JPEG) (min128 x max512)','ex_image',$attributes);
									echo form_upload('ex_image',set_value('ex_image'),'class="form-control" id="images_up"');
								?>
							</div>
							<?php echo form_submit('submit', 'Save', 'class="btn btn-info btn-fill btn-wd"');?>
							<?php echo form_close();?>								
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
