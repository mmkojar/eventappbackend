<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>

<div id="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
						<div class="header"><?php echo $page_title ?></div>
						<div class="content">
							<?php $this->load->view('public/themes/default/error_msg'); ?>
							<div class="row">
								<?php echo form_open('',array('id'=>'addpolling','class'=>'form_validation', 'enctype'=>'multipart/form-data'));?>							
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
									echo form_label('title','Title',$attributes);
									echo form_input('title',set_value('title'),'class="form-control" required = "true"');
									?>
								</div>	
								<div class="form-group">
										<?php
											echo form_label('Choices','choice',$attributes);
											echo form_input('choice',set_value('choice'),'class="form-control" required = "true"');
										?>
									<label class="text-danger">Add Choices by comma seperated (for eg. cricket, football)</label>
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
</div>