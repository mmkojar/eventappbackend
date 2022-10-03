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
							<?php $this->load->view('public/themes/default/error_msg'); ?>
							<?php echo form_open('',array('id'=>'addspeaker', 'class'=>'form_validation','enctype'=>'multipart/form-data'));?>						
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
								echo form_label('name','name',$attributes);
								echo form_input('name',set_value('name'),'class="form-control" required = "true"');
								?>
							</div>	
							<div class="form-group">
									<?php
										echo form_label('Company Name','company_name',$attributes);
										echo form_input('company_name',set_value('company_name'),'class="form-control" required = "true"');
									?>
							</div>
						
							<div class="form-group">
									<?php
										echo form_label('Designation','designation',$attributes);
										echo form_input('designation',set_value('designation'),'class="form-control" required = "true"');
									?>
							</div>
							<div class="form-group">
								<?php
									echo form_label('Image (PNG, JPEG)','speaker_image',$attributes);
									echo form_upload('speaker_image',set_value('speaker_image'),'class="form-control" id="images_up"  required = "true"');
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