<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<style>
	.card .form-group > label {
		font-weight: 600;
	}
	.error{
		color:red;
	}
	#registration_fees_type_id-error{
		margin-top: 17px;
	}
</style>
<div id="content">
	<div class="container-fluid">
		<div class="row" >
			<div class="col-md-12">
				<div class="card">
				<?php echo form_open('',array('id'=>'addAttendee'));?>
					<?php $this->load->view('public/themes/default/error_msg');?>
					<div class="header">Add Attendee</div>
						<div class="col-md-6">
							<div class="content">
								<div class="toolbar">
									<!--Here you can write extra buttons/actions for the toolbar-->
								</div>
									<div class="form-group">
										<?php 
											$attributes = array(
											'class' => 'control-label'
											);
										echo form_label('Family name','family_name',$attributes);
										echo form_error('family_name');
										echo form_input('family_name',set_value('family_name'),'class="form-control" required = "true"');
										?>
									</div>
									
								  <div class="form-group">
									<?php
										echo form_label('First Name','first_name',$attributes);
										echo form_error('first_name');
										echo form_input('first_name',set_value('first_name'),'class="form-control" required = "true"');
									?>
								  </div>
								  <div class="form-group">
									<?php
										echo form_label('Company Name','company_name',$attributes);
										echo form_error('company_name');
										echo form_input('company_name',set_value('company_name'),'class="form-control" required = "true"');
									?>
								  </div>
								  <div class="form-group">
									<?php
										echo form_label('Address','address',$attributes);
										echo form_error('address');
										echo form_input('address',set_value('address'),'class="form-control"');
									?>
								  </div>
								  <div class="form-group">
									<?php
										echo form_label('Telephone Number','telephone',$attributes);
										echo form_error('telephone');
										echo form_input('telephone',set_value('telephone'),'class="form-control"');
									?>
								  </div>
									
								  <div class="form-group">
									<?php
										echo form_label('Email Address','email',$attributes);
										echo form_error('email');
										echo form_input('email',set_value('email'),'class="form-control" required = "true"');
									?>
								  </div>
								  <div class="form-group">
									<?php
										echo form_label('Mobile Number','mobile',$attributes);
										echo form_error('mobile');
										echo form_input('mobile',set_value('mobile'),'class="form-control" required = "true"');
									?>
								  </div>
								  <div class="form-group">
									<?php
										echo form_label('Attendee Website','website',$attributes);
										echo form_error('website');
										echo form_input('website',set_value('website'),'class="form-control"');
									?>
								  </div>
								  <div class="form-group">
									<?php
										echo form_label('Attendee Image (PNG, JPEG)','attendee_image',$attributes);
										echo form_error('attendee_image');
										echo form_upload('attendee_image',set_value('attendee_image'),'class="form-control" id="images_up" required = "true"');
									?>
								</div>
							</div>
						</div>
			
						<div class="col-md-6">
							<div class="content">
								
								<div class="form-group">
									<?php
										echo form_label('Attendee Spouse Family Name','attendee_spouse_family_name',$attributes);
										echo form_error('attendee_spouse_family_name');
										echo form_input('attendee_spouse_family_name',set_value('attendee_spouse_family_name'),'class="form-control" required = "true"');
									?>
								  </div>
								  <div class="form-group">
									<?php
										echo form_label('Attendee Spouse First Name','attendee_spouse_first_name',$attributes);echo form_error('attendee_spouse_first_name');
										echo form_input('attendee_spouse_first_name',set_value('attendee_spouse_first_name'),'class="form-control" required = "true"');
									?>
								  </div>
								  <div class="form-group">
										<?php
											echo form_label('Attendee Spouse Image (PNG, JPEG)','attendee_spouse_image',$attributes);
											echo form_error('attendee_spouse_image');
											echo form_upload('attendee_spouse_image',set_value('attendee_spouse_image'),'class="form-control" id="images_up" required = "true"');
										?>
									</div>
									
								  <div class="form-group">
										<?php
											echo form_label('Attendee Interested','attendee_interested[]',$attributes);
										?>
									<div class="col-md-12">
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('attendee_interested[]',"Ferrous Scrap", set_radio('attendee_interested[]'),'data-toggle="checkbox" required = "true"');
												echo 'Ferrous Scrap';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('attendee_interested[]',"Non- Ferrous Scrap", set_radio('attendee_interested[]'),'data-toggle="checkbox" required = "true"');
												echo 'Non- Ferrous Scrap';
												echo '</label>';
												echo '<label class="checkbox showotherinterest" id="showo">';
												echo form_checkbox('attendee_interested[]',"Others", set_radio('attendee_interested[]'),'data-toggle="checkbox" required = "true" class="showotherinterest"');
												echo 'Others';
												echo '</label>';
											?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('attendee_interested[]',"Recycling Machinery Manufacturer", set_radio('attendee_interested[]'),'data-toggle="checkbox" required = "true"');
												echo 'Recycling Machinery Manufacturer';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('attendee_interested[]',"Stainless Alloys / Scrap", set_radio('attendee_interested[]'),'data-toggle="checkbox" required = "true"');
												echo 'Stainless Alloys / Scrap';
												echo '</label>';
											?>
										</div>
									</div>
								  </div>
								  <div class="form-group" id="other_interest">
										<?php
											echo form_label('Attendee Other Interest','other_interest[]',$attributes);
										?>
									<div class="col-md-12">
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('other_interest[]',"Trader / Indenter &amp; Processor", set_radio('other_interest[]'),'data-toggle="checkbox"');
												echo 'Trader / Indenter &amp; Processor';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('other_interest[]',"Importer", set_radio('other_interest[]'),'data-toggle="checkbox"');
												echo 'Importer';
												echo '</label>';
											?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('other_interest[]',"Supplier", set_radio('other_interest[]'),'data-toggle="checkbox"');
												echo 'Supplier';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('other_interest[]',"Manufacturer", set_radio('other_interest[]'),'data-toggle="checkbox"');
												echo 'Manufacturer';
												echo '</label>';
											?>
										</div>
									</div>
								  </div>
								  <div class="form-group">
										<?php
											echo form_label('Registration Fees','registration_fees_type_id',$attributes);
											foreach($registration_fees as $registration_fee){
												echo '<label class="radio" style="margin-bottom: 15px;">';
												echo form_radio('registration_fees_type_id', $registration_fee["registration_fees_type_id"], set_radio('registration_fees_type_id'),'data-toggle="radio" required = "true"');
												echo ' '.$registration_fee["registration_fees_detail"];
												echo '</label>';
											}
										?>
									</div>
								  <div class="form-group">
									<?php
										echo form_label('Lunch Day 1','lunch_day_1',$attributes);?>
										<div class="col-md-12">
											<div class="col-md-6">
											<?php
												echo '<label class="radio">';
												echo form_radio('lunch_day_1',"Veg.", set_radio('lunch_day_1'),'data-toggle="radio"  required = "true" required = "true"');
												echo 'Veg.';
												echo '</label>';
												echo '<label class="radio">';
												echo form_radio('lunch_day_1',"Non-Veg.", set_radio('lunch_day_1'),'data-toggle="radio" required = "true"');
												echo 'Non-Veg.';
												echo '</label>';
											?>
											</div>
											<div class="col-md-6">
												<?php
													echo '<label class="radio">';
													echo form_radio('lunch_day_1',"Kosher.", set_radio('lunch_day_1[]'),'data-toggle="radio" required = "true"');
													echo 'Kosher.';
													echo '</label>';
													echo '<label class="radio">';
													echo form_radio('lunch_day_1',"Jain.", set_radio('lunch_day_1'),'data-toggle="radio" required = "true"');
													echo 'Jain.';
													echo '</label>';
												?>
											</div>
										</div>
									</div>
								  <div class="form-group">
									<?php
										echo form_label('Lunch Day 2','lunch_day_2',$attributes);?>
										<div class="col-md-12">
											<div class="col-md-6">
												<?php
													echo '<label class="radio">';
													echo form_radio('lunch_day_2',"Veg.", set_radio('lunch_day_2'),'data-toggle="radio" required = "true"');
													echo 'Veg.';
													echo '</label>';
													echo '<label class="radio">';
													echo form_radio('lunch_day_2',"Non-Veg.", set_radio('lunch_day_2'),'data-toggle="radio" required = "true"');
													echo 'Non-Veg.';
													echo '</label>';
												?>
											</div>
											<div class="col-md-6">
												<?php
													echo '<label class="radio">';
													echo form_radio('lunch_day_2',"Kosher.", set_radio('lunch_day_2'),'data-toggle="radio" required = "true"');
													echo 'Kosher.';
													echo '</label>';
													echo '<label class="radio">';
													echo form_radio('lunch_day_2',"Jain.", set_radio('lunch_day_2'),'data-toggle="radio" required = "true"');
													echo 'Jain.';
													echo '</label>';
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php echo form_submit('submit', 'Add Attendee', 'class="btn btn-info btn-fill btn-wd" style="margin: 20px 20px;"');?>
							<?php echo form_close();?>
					</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$("#other_interest").hide();
	$('#addAttendee').validate();
});

$(".showotherinterest").click(function() {
	var test = $(".showotherinterest input:checkbox:checked").length > 0;
	if(!test){
		$("#other_interest").show();
	}else{
		$("#other_interest").hide();		
	}
});
	
	
$( "#assignto" ).change(function() {
	var user = $( "#assignto" ).val();
	$.ajax({
		type:'POST',
		url:'<?php echo base_url()?>'+'/admin/attendees/counttouser',
		data:{'assignto': user},
		beforeSend:function() {
			$('#log1').show();
		},
		dataType: "json",
		success:function(data) {
			var errcode=data["errCode"];
			if(errcode =="-1"){
				var count = parseInt(data['errMsg']['number_to_attendee']) + 1;
				$("input[name='number_to_attendee']").val(count);
			} else {
				$("input[name='number_to_attendee']").val(1);
			}
		},
		error:function() {
		},
	});
});
</script>
