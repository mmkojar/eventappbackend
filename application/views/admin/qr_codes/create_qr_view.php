<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

											$options = [];
											foreach ($users as $data) {
												$options[$data['id']] = $data['first_name'].' '.$data['last_name'];
											}
											echo form_label('Select User','uid',$attributes);
											echo form_error('user_id');
											echo form_dropdown('user_id[]',$options,'','class="form-control select21" id="uid" multiple required ="true"');
										?>
								</div>
								<!-- <div class="form-group">
									<s?php
										$options = array(
												''=> 'Select the Status',
												'1'=> 'Active',
												'0'=> 'Inactive',
										);
										echo form_label('Status','status',$attributes);
										echo form_error('status');
										
										echo form_dropdown('status',$options,'','class="form-control" required ="true"');
									?>
								</div> -->
								<!-- <div class="form-group">
										<s?php
											// echo form_label('Link','link',$attributes);
										?>
										<div class="controls">
											<s?php
											// echo form_error('link');
											// echo form_input('link',set_value('link'),'class="form-control"');
											s?>
										</div>
								</div> -->
								<!-- <div class="form-group">
										<s?php// echo form_label('Size','size',$attributes); s?>
										<div class="controls">
											<s?php
												// $options = array(
												// 		''=> 'Select the Size',
												// 		'100x100'=>'100x100',
												// 		'200x200'=>'200x200',
												// 		'300x300'=>'300x300',
												// 		'400x400'=>'400x400',
												// );
												// echo form_error('size');
												// echo form_dropdown('size',$options,'','class="form-control"');
											?>
										</div>
								</div>
								<div class="form-group">
										<s?php //echo form_label('Color','color',$attributes); ?>
										<div class="controls">
											<s?php
												// $options = array(
												// 		''=> 'Select the Color',
												// 		'FFFFFF'=>'White',
												// 		'000000'=>'Black',
												// 		'FF0000'=>'Red',
												// 		'0000FF'=>'Blue',
												// );
												// echo form_error('color');
												// echo form_dropdown('color',$options,'','class="form-control"');
											?>
										</div>
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
<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
	$('.select2').select2();
</script>