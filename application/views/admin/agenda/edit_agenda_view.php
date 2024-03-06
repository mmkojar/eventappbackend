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
							<?php echo form_open('',array('id'=>'addAgenda','class'=>'form_validation', 'enctype'=>'multipart/form-data'));?>
							<?php if(validation_errors()): ?>
								<div class="alert alert-danger">
									<?php print_r(validation_errors());?> 
								</div>
							<?php endif ?>
							<div class="col-md-6">
								<label>Select Title</label>
								<select name="title_id" class="form-control">
									<?php foreach($titles as $row): ?>
										<option value="<?php echo $row->id ?>" <?php echo ($row->id==$agenda_data['title_id']) ? 'selected' : '' ?>><?php echo $row->name ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="col-md-6">								
								<div class="form-group">
									<?php 
										$attributes = array(
										'class' => 'control-label'
										);
									echo form_label('Agenda name','agenda_name',$attributes);									
									echo form_input('agenda_name',set_value('agenda_name',$agenda_data['agenda_name']),'class="form-control" required = "true"');
									?>
								</div>	
							</div>	
							<div class="col-md-6">			
								<div class="form-group">
										<?php
											echo form_label('Agenda Time','agenda_time',$attributes);											
											echo form_input('agenda_time',set_value('agenda_time',$agenda_data['agenda_time']),'class="form-control"  id="timepickers" required = "true"');
										?>
								</div>
							</div>	
							<div class="col-md-6">			
								<div class="form-group">
									<?php
										echo form_label('Venue','agenda_venue',$attributes);										
										echo form_input('agenda_venue',set_value('agenda_venue',$agenda_data['agenda_venue']),'class="form-control" required = "true"');
									?>
								</div>
							</div>			
							<!-- <div class="col-md-6">
									<div class="form-group">
										<s?php 
											$attributes = array(
											'class' => 'control-label'
											);
										echo form_label('Agenda Date','agenda_date',$attributes);										
										echo form_input('agenda_date',set_value('agenda_date',$agenda_data['agenda_date']),'class="form-control" required = "true" id="datepicker" placeholder = "dd-mm-YYYY"',);
										?>
									</div>
							</div>	
							<div class="col-md-6">			
									<div class="form-group">
										<s?php
											echo form_label('Speaker Name','speaker_name',$attributes);											
											echo form_input('speaker_name',set_value('speaker_name',$agenda_data['speaker_name']),'class="form-control" required = "true"');
										?>
									</div>
							</div> -->	
							<div class="col-md-6">
								<div class="form-group">
									<label>Status</label>
									<select name="status" class="form-control">
										<option value="1" <?php echo ($agenda_data['status'] == '1') ? 'selected' : '' ?>>Active</option>
										<option value="0" <?php echo ($agenda_data['status'] == '0') ? 'selected' : '' ?>>Inactive</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">			
									<!-- <div class="form-group">
										<img class="avatar border-gray" src="<?php //echo base_url()."".str_replace("./", "", $agenda_data['agenda_image']); ?>" alt="..." />
										<?php 
										//echo form_hidden('hidden_image',set_value('agenda_image',$agenda_data['agenda_image']),'class="form-control"');
										?>
										<?php
											// echo form_label('Agenda Image (PNG, JPEG)','agenda_image',$attributes);
										// 
											// echo form_upload('agenda_image',set_value('agenda_image'),'class="form-control" id="images_up"');
										?>
									</div> -->
							</div>
							<div class="col-md-12">
								<?php echo form_submit('submit', 'Update', 'class="btn btn-info btn-fill btn-wd"');?>
								<?php echo form_close();?>			
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>