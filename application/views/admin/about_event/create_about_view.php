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
							<?php echo form_open('admin/about_event/create',array('id'=>'addAgenda','class'=>'form_validation', 'enctype'=>'multipart/form-data'));?>
					
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
								echo form_input('about_heading',set_value('about_heading'),'class="form-control" required = "true"');
								?>
							</div>	
							<div class="form-group">
									<?php
										echo form_label('About Message','about_msg',$attributes);
										echo form_textarea('about_msg',set_value('about_msg'),'class="form-control" required = "true"');
									?>
							</div>
							<!-- <div class="form-group">
								<s?php
									// echo form_label('Agenda Image (PNG, JPEG)','agenda_image',$attributes);
									// echo form_error('agenda_image');
									// echo form_upload('agenda_image',set_value('agenda_image'),'class="form-control" id="images_up"  required = "true"');
								?>
							</div> -->
							<?php echo form_submit('submit', 'Save', 'class="btn btn-info btn-fill btn-wd"');?>
							<?php echo form_close();?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.tiny.cloud/1/1a5w4ka884d2keahn36xltwaif9zp4pckvbu3ht2utanno1i/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({selector: 'textarea#tiny'});

</script>
