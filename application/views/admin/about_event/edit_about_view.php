<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>

<div id="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="header"><?php echo $page_title ?></div>
					<div class="content">
						<?php $this->load->view('public/themes/default/error_msg');?>
						<div class="row">
							<?php echo form_open('',array('id'=>'addAttendee','class'=>'form_validation','enctype'=>'multipart/form-data'));?>
					
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
								echo form_label('Heading','about_heading',$attributes);
								echo form_error('about_heading');
								echo form_input('about_heading',set_value('about_heading',$about_event_data['about_heading']),'class="form-control" required = "true"');
								?>
							</div>	
							<div class="form-group">
									<?php
										echo form_label('About Message','about_msg',$attributes);
										echo form_error('about_msg');
										echo form_textarea('about_msg',set_value('about_msg',$about_event_data['about_msg']),'class="form-control" id="tiny" required = "true"');
									?>
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

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({selector: 'textarea#tiny'});

</script>
