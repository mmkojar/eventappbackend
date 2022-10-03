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
							<?php echo form_open('',array('id'=>'create_qr','class'=>'form_validation'));?>
									
							<div class="form-group">
								<?php							 
								$attributes = array(
									'class' => 'control-label'
								);
								
								echo form_label('Status','status',$attributes);
								?>
								<div class="controls">
									<?php
									$options = array(
											''=> 'Select the Status',
											'1'=> 'Active',
											'0'=> 'Inactive',
									);
									echo form_error('status');
									
									echo form_dropdown('status',$options,$qr_code["status"],'class="form-control"');
									?>
								</div>
							</div>
							
							<br>
							<?php echo form_hidden('id',$qr_code["id"]);?>						
							<?php echo form_submit('submit', 'Update', 'class="btn btn-info btn-fill btn-wd"');?>
							<?php echo form_close();?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>