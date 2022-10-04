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
							<?php echo form_open('',array('class'=>'form_validation','enctype'=>'multipart/form-data'));?>					
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
								echo form_label('Title','title',$attributes);
								echo form_input('title',set_value('title',$faq['title']),'class="form-control" required = "true"');
								?>
							</div>	
							<div class="form-group">
									<?php
										echo form_label('Description','description',$attributes);
										echo form_textarea('description',set_value('description',$faq['description']),'class="form-control" id="tiny" required = "true"');
									?>
							</div>
							<div class="form-group">
								<label>Status</label>
								<select name="status" class="form-control">
									<option value="1" <?php echo ($faq['status'] == '1') ? 'selected' : '' ?>>Active</option>
									<option value="0" <?php echo ($faq['status'] == '0') ? 'selected' : '' ?>>Inactive</option>
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

<script src="https://cdn.tiny.cloud/1/1a5w4ka884d2keahn36xltwaif9zp4pckvbu3ht2utanno1i/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({selector: 'textarea#tiny'});

</script>
