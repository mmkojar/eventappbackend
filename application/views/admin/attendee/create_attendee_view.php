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
		margin-top: -120px;
		left: -150px;
		width: 500px;
		position: absolute;
		max-width: 150px !important;
	}
	.new_radio{
		padding-left: 30px !important;
	 }
	.paddingleft_col6{
		padding: 0 2px 0 0 !important;
		margin-bottom: 10px;
	}
	.paddingright_col6{
		padding:0px !important;
		margin-bottom: 10px;
	}
	.label_span{
		color:#888888;
	}
	p.form-group{
		margin-bottom: 0px !important;
	}
	hr{
		margin-top:10px;
		margin-bottom: 10px;
		border-color: #333;
	}
	.color_red{
			color: red;
	}
</style>
<div id="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<?php echo form_open('',array('id'=>'addAttendee', 'enctype'=>'multipart/form-data'));?>
					<?php $this->load->view('public/themes/default/error_msg');?>
					<!-- <?php print_r(validation_errors());?> -->
					<div class="header">Delegate Registration</div>
						<div class="col-md-6">							
							<div class="content">
								<p>Company:</p>	
								<span>Company Information</span>
								<div class="form-group">
									<?php 
										$attributes = array(
										'class' => 'control-label'
										);	 ?>
									<p class="form-group">
									<?php
										echo form_label('Company','company',$attributes);
									?>
									</p>
									<!-- <div class="gender_error" style="color:red"></div> -->
									<div class="col-md-6 paddingleft_col6">
									<?php
										echo '<label class="radio">';
										echo form_radio('company',"Indian", set_radio('company'),'data-toggle="radio" id="Company_india" ');
										echo 'Indian';
										echo '</label>';
									?>									
									</div>
									<div class="col-md-6 paddingleft_col6">
									<?php 
										echo '<label class="radio" style="margin-top: 10px;">';
										echo form_radio('company',"Foreign ", set_radio('company'),'data-toggle="radio" id="Company_foreign"');
										echo 'Foreign ';
										echo '</label>';
									?>
									<?php
									// echo form_error('Company');
									?>										
									</div>										
								</div>
								<div class="form-group">
									<?php 
										$attributes = array(
										'class' => 'control-label'
										);	
										echo form_label('Name of Company/Association','noc',$attributes);
										echo form_error('noc');
										echo form_input('noc',set_value('noc'),'class="form-control" id="noc"');
									?>
								</div>
									
								<div class="form-group">
									<?php
										echo form_label('Name of parent company or Primary<br> Contact Company (When Applicable) :','nopc',$attributes);
										echo form_error('nopc');
										echo form_input('nopc',set_value('nopc'),'class="form-control"');
									?>
								</div>
								<div class="form-group">
									<p class="form-group">
									<?php 
										  echo form_label('Postal Address','address',$attributes);
										  // echo form_error('address'); 	
									?>
									</p>
									<div class="col-md-6 paddingleft_col6" style="padding-right: 10px !important">
									<?php
										echo form_input('address1',set_value('address1'),'class="form-control" 
											placeholder="Street Address 1"');
										echo form_input('address2',set_value('address2'),'class="form-control"
											placeholder="Street Address 2" style="margin:10px 0;" ');
										echo form_input('address3',set_value('address3'),'class="form-control"
											placeholder="city"');										
									?>
									</div>
									<div class="col-md-6 paddingright_col6" style="padding-left: 10px !important">
									<?php
										echo form_input('address4',set_value('address4'),'class="form-control"
											placeholder="State"');										
										echo form_input('address5',set_value('address5'),'class="form-control"
											placeholder="Postal/Zip code/P.O. Box" style="margin:10px 0;" ');
										echo form_input('address6',set_value('address6'),'class="form-control"
											placeholder="Country"');
									?>
									</div>
								</div>
								<div class="form-group">
									<?php 
										echo form_label('Date of Incorporation','dincorp',$attributes);
										echo form_input('dincorp',set_value('dincorp'),'class="form-control" id="dincorp"
											placeholder="Date"');	
											echo form_error('dincorp'); 									
									?>
								</div>
								<div class="form-group">
									<p class="form-group">
									<?php 
										  echo form_label('Telephone Number','telnum',$attributes);
										  // echo form_error('telnum'); 	
									?>
									</p>
									<div class="col-md-6 paddingleft_col6">
									<?php
										echo form_input('telnum1',set_value('telnum1'),'class="form-control" id="telnum1"
											placeholder="Country code"');	
										echo form_error('telnum1');									
									?>
									</div>
									<div class="col-md-6">
									<?php
										echo form_input('telnum2',set_value('telnum2'),'class="form-control" id="telnum2"
											placeholder="City Code"');	
										echo form_error('telnum2');																					
									?>
									</div>
									<?php
										echo form_input('telnum3',set_value('telnum3'),'class="form-control" id="telnum3"
											placeholder="Phone No" ');			
										echo form_error('telnum3');
									?>
									<div class="error_telnum" style="color:red"></div>
								</div>
								<div class="form-group">
									<p class="form-group">
									<?php 
										  echo form_label('Fax Number','faxnumber',$attributes);
										  // echo form_error('faxnumber'); 	
									?>
									</p>
									<div class="col-md-6 paddingleft_col6">
									<?php
										echo form_input('faxnumber1',set_value('faxnumber1'),'class="form-control" 
											placeholder="Country code"');	
										// echo form_error('faxnumber1'); 				
									?>
									</div>
									<div class="col-md-6">
									<?php
										echo form_input('faxnumber2',set_value('faxnumber2'),'class="form-control" 
											placeholder="City Code"');
										// echo form_error('faxnumber2'); 				
									?>
									</div>
									<?php
										echo form_input('faxnumber3',set_value('faxnumber3'),'class="form-control" 
											placeholder="Phone No"');
										// echo form_error('faxnumber3'); 			
									?>
								</div>
								<div class="form-group">
									<?php
										echo form_label('Email Address','email',$attributes);
										echo form_error('email');
										echo form_input('email',set_value('email'),'class="form-control" id="email"');
									?>
									<div class="error_email" style="color:red"></div>
								</div>
								<div class="form-group">
									<?php
										echo form_label('Nature of the Firm/Association','name_firm',$attributes);
										// echo form_error('name_firm');
										echo form_input('name_firm',set_value('name_firm'),'class="form-control"
											placeholder="Limited/Partnership/Proprietary" ');
									?>
								</div>
								<div class="form-group">
									<?php
										echo form_label('PAN Card Number','pan_card',$attributes);
										echo form_error('pan_card');
										echo form_input('pan_card',set_value('pan_card'),'class="form-control" id="pan_card" ');
									?>
									<div class="error_pan_card" style="color:red"></div>
								</div>	
								<div class="form-group">
									<?php
										echo form_label('GST Number','gst_num',$attributes);
										echo form_error('gst_num');
										echo form_input('gst_num',set_value('gst_num'),'class="form-control" id="gst_num" ');
									?>
									<div class="error_gst_num" style="color:red"></div>
								</div>							
							</div>
						</div>
						<div class="col-md-6">
							<div class="content">
								<p>Contacts:</p>	
								<span>Main Contact (for mail, invoices, etc.) :</span>
								<div class="form-group">
									<p class="form-group">
									<?php 
										  echo form_label('Gender','gender',$attributes);
										  echo form_error('gender'); 	
									?>
									</p>
									<div class="col-md-6 paddingleft_col6">
									<?php
										// echo form_input('cont_gender1',set_value('cont_gender1'),'class="form-control" id="cont_gender1" placeholder="Mr."');
											echo '<label class="radio">';
											echo form_radio('gender',"Mr.", set_radio('gender'),'data-toggle="radio" id="gender_mr" class="gender_mrs" ');
											echo 'Mr.';
											echo '</label>';
									?>
									</div>
									<div class="col-md-6 paddingright_col6">
									<?php
										// echo form_input('cont_gender2',set_value('cont_gender2'),'class="form-control" id="cont_gender2" placeholder="Ms."');
											echo '<label class="radio">';
											echo form_radio('gender',"Ms.", set_radio('gender'),'data-toggle="radio" id="gender_ms" class="gender_mrs" ');
											echo 'Ms.';
											echo '</label>';
									?>
									</div>
								</div>
									
								<div class="form-group">
									<p class="form-group">
									<?php 
										  echo form_label('Full Name','fmlname',$attributes);
									?>
									</p>
									<div class="col-md-6 paddingleft_col6">
									<?php
										echo form_input('last_name',set_value('last_name'),'class="form-control" id="last_name"
											placeholder="last_name" ');
										echo form_error('fmlname'); 											
									?>
									</div>
									<div class="col-md-6 paddingright_col6">
									<?php
										echo form_input('first_name',set_value('first_name'),'class="form-control" id="first_name"
											placeholder="first_name" ');
										echo form_error('fmlname'); 											
									?>
									</div>
									<?php
										echo form_input('middle_name',set_value('middle_name'),'class="form-control" id="middle_name"
											placeholder="middle_name" ');										
										echo form_error('fmlname'); 
									?>
									<div class="error_fmlname" style="color:red"></div>			
								</div>
								<div class="form-group">
									<?php
										echo form_label('Designation','desig',$attributes);
										echo form_error('desig');
										echo form_input('desig',set_value('desig'),'class="form-control" id="desig" ');
									?>
									<div class="error_desig" style="color:red"></div>
								</div>
								<div class="form-group">
									<p class="form-group">
									<?php 
										  echo form_label('Mobile Number','mob_num',$attributes);
									?>
									</p>
									<div class="col-md-6 paddingleft_col6">
									<?php
										echo form_input('mob_num1',set_value('mob_num1'),'class="form-control" id="mob_num1" 
											placeholder="Country Code" ');	
										echo form_error('mob_num1'); 											
									?>
									</div>
									<div class="col-md-6 paddingright_col6">
									<?php
										echo form_input('mob_num2',set_value('mob_num2'),'class="form-control" id="mob_num2" 
											placeholder="Mobile Number" ');										
										echo form_error('mob_num2'); 		
									?>
									</div>
									<div class="error_mobnum" style="color:red"></div>
								</div>
								<div class="form-group">
									<?php
										echo form_label('Additional Contact','add_contact',$attributes);
										// echo form_error('add_contact');
										echo form_input('add_contact',set_value('add_contact'),'class="form-control"');
									?>
								</div>
								<div class="form-group">
									<?php
										echo form_label('Email Address','email_contact',$attributes);
										echo form_error('email_contact');
										echo form_input('email_contact',set_value('email_contact'),'class="form-control" id="email_contact" ');
									?>
									<div class="error_emailcontact" style="color:red"></div>
								</div>							
							</div>
						</div>
						<div class="col-md-12">
							<div class="content">
								<p>Activities:</p>	
								<span>We are active in the following fields :<br>
								Please tick any appropriate box(es)</span><br>
								<div class="form-group">
									<div class="col-md-6" style="padding-left: 0px !important">
										<div>
										<?php
											echo form_label('Non-Ferrous Metals','ferrous_metals[]',$attributes);
										?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('ferrous_metals[]',"Copper", set_radio('ferrous_metals[]'),'data-toggle="checkbox"');
												echo 'Copper';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('ferrous_metals[]',"Brass/Bronze", set_radio('ferrous_metals[]'),'data-toggle="checkbox"');
												echo 'Brass/Bronze';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('ferrous_metals[]',"Aluminium", set_radio('ferrous_metals[]'),'data-toggle="checkbox"');
												echo 'Aluminium';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('ferrous_metals[]',"Lead", set_radio('ferrous_metals[]'),'data-toggle="checkbox"');
												echo 'Lead';
												echo '</label>';
											?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('ferrous_metals[]',"Zinc", set_radio('ferrous_metals[]'),'data-toggle="checkbox"');
												echo 'Zinc';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('ferrous_metals[]',"Tin", set_radio('ferrous_metals[]'),'data-toggle="checkbox"');
												echo 'Tin';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('ferrous_metals[]',"Magnesium", set_radio('ferrous_metals[]'),'data-toggle="checkbox"');
												echo 'Magnesium';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('ferrous_metals[]',"Special Alloys", set_radio('ferrous_metals[]'),'data-toggle="checkbox"');
												echo 'Special Alloys';
												echo '</label>';

											
											?>
										</div>
									</div>
									<div class="col-md-6">
										<div>
											<?php
												echo form_label('Textiles','textiles[]',$attributes);
											?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('textiles[]',"Original textiles from Collection (non graded)", set_radio('textiles[]'),'data-toggle="checkbox"');
												echo 'Original textiles from Collection (non graded)';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('textiles[]',"New Cuttings", set_radio('textiles[]'),'data-toggle="checkbox"');
												echo 'New Cuttings';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('textiles[]',"Cotton", set_radio('textiles[]'),'data-toggle="checkbox"');
												echo 'Cotton';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('textiles[]',"Wool", set_radio('textiles[]'),'data-toggle="checkbox"');
												echo 'Wool';
												echo '</label>';
											?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('textiles[]',"Synthetics", set_radio('textiles[]'),'data-toggle="checkbox"');
												echo 'Synthetics';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('textiles[]',"Used Clothing", set_radio('textiles[]'),'data-toggle="checkbox"');
												echo 'Used Clothing';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('textiles[]',"Wiping cloths", set_radio('textiles[]'),'data-toggle="checkbox"');
												echo 'Wiping cloths';
												echo '</label>';
											?>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-6" style="padding-left: 0px !important">
										<div>
										<?php
											echo form_label('Ferrous','Ferrous[]',$attributes);
										?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('Ferrous[]',"Iron and Steel Scrap", set_radio('Ferrous[]'),'data-toggle="checkbox"');
												echo 'Iron and Steel Scrap';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Ferrous[]',"Alloyed Scrap", set_radio('Ferrous[]'),'data-toggle="checkbox"');
												echo 'Alloyed Scrap';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Ferrous[]',"Shredded Scrap", set_radio('Ferrous[]'),'data-toggle="checkbox"');
												echo 'Shredded Scrap';
												echo '</label>';
											?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('Ferrous[]',"Shredder facility", set_radio('Ferrous[]'),'data-toggle="checkbox"');
												echo 'Shredder facility';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Ferrous[]',"Heavy Media plant", set_radio('Ferrous[]'),'data-toggle="checkbox"');
												echo 'Heavy Media plant';
											?>
										</div>
									</div>
									<div class="col-md-6">
										<div>
											<?php
												echo form_label('Paper','Paper[]',$attributes);
											?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('Paper[]',"Mixed paper", set_radio('Paper[]'),'data-toggle="checkbox"');
												echo 'Mixed paper';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Paper[]',"Over-issue news", set_radio('Paper[]'),'data-toggle="checkbox"');
												echo 'Over-issue news';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Paper[]',"Corrugated cardboard", set_radio('Paper[]'),'data-toggle="checkbox"');
												echo 'Corrugated cardboard';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Paper[]',"Pulp substitutes", set_radio('Paper[]'),'data-toggle="checkbox"');
												echo 'Pulp substitutes';
												echo '</label>';
											?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('Paper[]',"De-inking grades(woodfree)", set_radio('Paper[]'),'data-toggle="checkbox"');
												echo 'De-inking grades(woodfree)';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Paper[]',"De-inking grades(mechanical)", set_radio('Paper[]'),'data-toggle="checkbox"');
												echo 'De-inking grades(mechanical)';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Paper[]',"Special grades", set_radio('Paper[]'),'data-toggle="checkbox"');
												echo 'Special grades';
												echo '</label>';
											?>
										</div>
									</div>
								</div>								
								<div class="form-group">
									<div class="col-md-6" style="padding-left: 0px !important">
										<div>
										<?php
											echo form_label('Stainless Steel and Special Alloys','stainless_steel[]',$attributes);
										?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('stainless_steel[]',"Nickel and nickel alloys", set_radio('stainless_steel[]'),'data-toggle="checkbox"');
												echo 'Nickel and nickel alloys';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('stainless_steel[]',"Cobalt", set_radio('stainless_steel[]'),'data-toggle="checkbox"');
												echo 'Cobalt';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('stainless_steel[]',"High temperature", set_radio('stainless_steel[]'),'data-toggle="checkbox"');
												echo 'High temperature';
												echo '</label>';
											?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('stainless_steel[]',"Titanium", set_radio('stainless_steel[]'),'data-toggle="checkbox"');
												echo 'Titanium';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('stainless_steel[]',"High Speed Steel", set_radio('stainless_steel[]'),'data-toggle="checkbox"');
												echo 'High Speed Steel';
												echo '</label>';
											?>
										</div>
									</div>
									<div class="col-md-6">
										<div>
											<?php
												echo form_label('Plastics','Plastics[]',$attributes);
											?>
										</div>
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('Plastics[]',"Materials recycling", set_radio('Plastics[]'),'data-toggle="checkbox"');
												echo 'Materials recycling';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Plastics[]',"Over-issue news", set_radio('Plastics[]'),'data-toggle="checkbox"');
												echo 'Over-issue news';
												echo '</label>';												
												echo '<label class="checkbox">';
												echo form_checkbox('Plastics[]',"Corrugated cardboard", set_radio('Plastics[]'),'data-toggle="checkbox"');
												echo 'Corrugated cardboard';
												echo '</label>';
											?>
										</div>
										<div class="col-md-6">
											<?php 												
												echo '<label class="checkbox">';
												echo form_checkbox('Plastics[]',"Pulp substitutes", set_radio('Plastics[]'),'data-toggle="checkbox"');
												echo 'Pulp substitutes';
												echo '</label>';
											?>
										</div>
									</div>
								</div>	
								<div class="form-group">
									<div class="col-md-6" style="padding-left: 5px !important">
										<div>
										<?php
											echo '<label class="checkbox" style="color:#333;font-weight:700">';
											echo form_checkbox('recycled_glass',"Recycled Glass", set_radio('recycled_glass'),'data-toggle="checkbox"');
											echo 'Recycled Glass';
											echo '</label>';
										?>
										</div>
										<div>
											<?php
												echo '<label class="checkbox" style="color:#333;font-weight:700">';
												echo form_checkbox('electronic_waste',"Electronic Waste", set_radio('electronic_waste'),'data-toggle="checkbox"');
												echo 'Electronic Waste';
												echo '</label>';
											?>
										</div>
									</div>
									<div class="col-md-6">
										<div>
											<?php
												echo form_label('Tyres','Tyres[]',$attributes);
											?>
										</div>
										<div class="col-md-12">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('Tyres[]',"Re-use", set_radio('Tyres[]'),'data-toggle="checkbox"');
												echo 'Re-use';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Tyres[]',"Re-trading", set_radio('Tyres[]'),'data-toggle="checkbox"');
												echo 'Re-trading';
												echo '</label>';												
												echo '<label class="checkbox">';
												echo form_checkbox('Tyres[]',"Granulating", set_radio('Tyres[]'),'data-toggle="checkbox"');
												echo 'Granulating';
												echo '</label>';
											?>
										</div>
									</div>
								</div>							
							</div>
						</div>
						<div class="col-md-12">
							<div class="content">
								<p>Sectors:</p>	
								<h6><span>We are active in the sector as :</span></h6>
								<div class="form-group">
									<div class="col-md-6" style="padding-left: 0px !important">
										<div class="col-md-6">
											<?php
												echo '<label class="checkbox" style="padding-left: 0px;">';
												echo 'Trader/processor'.'<br>';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"(a) with recycling facilities", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo '(a) with recycling facilities';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"(b) without recycling facilities", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo '(b) without recycling facilities';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"Broker", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo 'Broker';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"Consumer (foundry, refinery, mill, user of recycled materials)", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo 'Consumer (foundry, refinery, mill, user of recycled materials)';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"Manufacturer/supplier of machinery and equipment", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo 'Manufacturer/supplier of machinery and equipment';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"Laboratory/assayer", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo 'Laboratory/assayer';
												echo '</label>';
											?>
										</div>
										<div class="col-md-6" style="margin-top: 26px;">
											<?php
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"Insurer", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo 'Insurer';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"Consultant/research bureau", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo 'Consultant/research bureau';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"Administration/State agency", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo 'Administration/State agency';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"Trade Press", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo 'Trade Press';
												echo '</label>';
												echo '<label class="checkbox">';
												echo form_checkbox('Sectors[]',"Others", set_radio('Sectors[]'),'data-toggle="checkbox"');
												echo 'Others';
												echo '</label>';
											?>
										</div>
									</div>								
								</div>								
							</div>
						</div>
						<div class="col-md-12">
							<div class="content">
								<h6><span>General Information :</span></h6><br>
								<div class="form-group">
									<?php 
										$attributes = array(
										'class' => 'control-label'
										);	
										echo form_label('DMember of any other Associations (Please Specify)','dmemassoc',$attributes);
										// echo form_error('dmemassoc');
										echo form_input('dmemassoc',set_value('dmemassoc'),'class="form-control" ');
									?>
								</div>
								<div class="form-group">
									<?php
										echo form_label('Application Recommended by existing<br>Member of MRAI (give name) :','app_name_reom',$attributes);
										// echo form_error('app_name_reom');
										echo form_input('app_name_reom',set_value('app_name_reom'),'class="form-control" ');
									?>
								</div>
								<div class="form-group">
									<?php
										echo form_label('Any other relevant information<br>(Attach Sheets, if necessary) :','otherinfo_sheet',$attributes);
										// echo form_error('otherinfo_sheet');
										echo form_input('otherinfo_sheet',set_value('otherinfo_sheet'),'class="form-control" ');
									?>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="content">
								<h6><span>List of Documents to be attached along with the form :</span></h6><br>
								<div class="form-group">
									<div class="col-md-6 paddingleft_col6">
									<?php 
										$attributes = array(
										'class' => 'control-label'
										);	
										echo form_label('Photocopy of Certificate of Incorporation<br>(Indian/Foreign):','photo_cert',$attributes);
										echo form_error('photo_cert');
										echo form_input('photo_cert',set_value('photo_cert'),'class="form-control cert_photo" id="photo_cert" placeholder="Yes" style="width:10%"').'<br>';
										echo form_input('photo_cert1',set_value('photo_cert1'),'class="form-control cert_photo" id="photo_cert1" placeholder="No" style="width:10%;"');
									?>
									<div class="error_photocert" style="color:red"></div>
									</div>
									<div class="col-md-6 paddingright_col6">
									<?php
										echo form_label('Photocopy of Pan Card<br>(for Indian companies/Association only) :','photo_pancard',$attributes);
										echo form_error('photo_pancard');
										echo form_input('photo_pancard',set_value('photo_pancard'),'class="form-control pan_photo" id="photo_pancard" placeholder="Yes" style="width:10%"').'<br>';
										echo form_input('photo_pancard1',set_value('photo_pancard1'),'class="form-control pan_photo" id="photo_pancard1" placeholder="No"  style="width:10%"');
									?>
									<div class="error_photopancard" style="color:red"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="content">
								<p>Fees:</p>	
								<h6><span>The details of the Fees is as below :</span></h6>
								<div class="content table-full-width">
									<table id="attendee_table" class="table  table-responsive table-striped table-bordered table-hover" cellspacing="0">
										<thead>
											<tr>
												<th></th>
												<th>Fees</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Based In India</td>
												<td><b>One time joining fee:</b> For Indian Company/Individual/ Proprietor<br>
													Concern/Partnership Firm is 10,000/- (+ 18% GST)<hr>
													<b>One time joining fee:</b> For Indian Associations is 30,000/- (+ 18% GST)<hr>
													<b>Annual Membership fee:</b> For Indian Company/Associations/Individual/<br>
													Proprietor Concern /Partnership Firm is 10,000 (+ 18% GST)</td>
											</tr>
											<tr>
												<td>Based outside India</td>
												<td><b>One time joining fee:</b> For Foreign Company/Individual/ Proprietor Concern/<br>
												Partnership Firm is USD 1,000/-<hr>
												<b>Annual Membership fee:</b> For Foreign Company/Individual/ Proprietor Concern/
												Partnership Firm is USD 500/-</td>
											</tr>
											<tr>
												<td>Note-</td>
												<td>For a Sister- concern (whose Parent or Holding company is already a member) the
												one time joining fee is payable. However , there will be a 50% reduction in Annual
												Membership Fees. (Proof of relation to parent /holding company needs to be
												submitted.) </td>
											</tr>
										</tbody>
									</table>
									<br>
									<!-- <div class="form-group" id="show-me">
										<?php
											echo form_label('MemberShip ID','membership_id',$attributes);
											echo form_input('membership_id',set_value('membership_id'),'class="form-control"');
										?>
									</div>
									<div class="form-group" id="show-me1">
										<?php
											echo form_label('Young Turk Member Age Proof (PNG, JPEG)','image_proof',$attributes);?>
											<span style="color:red">*</span>
											// <?php echo form_error('image_proof');
											echo form_upload('image_proof',set_value('image_proof'),'class="form-control" id = "image_proof_young" ');
										?>
									</div> -->
								</div>							
							</div>
						</div>
						<div class="col-md-12">
							<div class="content">
								<h6><span>Total amount to be settled – upon MRAI request :</span></h6>
								<div class="form-group">
									<div class="col-md-6 paddingleft_col6">
										<?php
											echo '<label class="radio">';
											echo form_radio('annul_mem',"Annual Membership (for 1 Year)", set_radio('annul_mem'),'data-toggle="radio"');
											echo 'Annual Membership (for 1 Year)';
											echo '</label>';
											echo '<label class="radio">';
											echo form_radio('annul_mem',"Sister – concern Company(for 1 Year)", set_radio('annul_mem'),'data-toggle="radio"');
											echo 'Sister – concern Company(for 1 Year)';
											echo '</label>';
										?>					
									</div>	
									<div class="col-md-6 paddingright_col6">
										<?php
											echo '<label class="radio">';
											echo form_radio('annul_mem',"Gold Membership (for 5 Years)", set_radio('annul_mem'),'data-toggle="radio"');
											echo 'Gold Membership (for 5 Years)';
											echo '</label>';
											echo '<label class="radio">';
											echo form_radio('annul_mem',"Sister – concern Company(for 5 Year)", set_radio('annul_mem'),'data-toggle="radio"');
											echo 'Sister – concern Company(for 5 Year)';
											echo '</label>';
										?>										
									</div>
									<?php 
											$attributes = array(
											'class' => 'control-label'
											);	
											echo form_label('Total Fees (Rs. ₹ / US $) :','tot_fees',$attributes);
											// echo form_error('tot_fees');
											echo form_input('tot_fees',set_value('tot_fees'),'class="form-control" 
												style="width:50%"');
									?>		
									<?php 
											echo '<label class="checkbox">';
											echo form_checkbox('agree_terms',"We have read and herewith agree to abide by the Articles of Association and Internal Regulations", set_radio('agree_terms'),'data-toggle="checkbox"');
											echo 'We have read and herewith agree to abide by the Articles of Association and Internal Regulations';
											echo '</label>';
									?>
								</div>								
							</div>
						</div>
						<div class="col-md-12">
							<div class="content">
								<h6><span>Payment Mode :</span></h6>
								<div class="form-group">
									<div class="col-md-6 paddingleft_col6">
										<?php
											echo '<label class="radio">';
											echo form_radio('payment_mode',"CHEQUE", set_radio('payment_mode'),'data-toggle="radio"');
											echo 'CHEQUE';
											echo '</label>';
											echo '<label class="radio">';
											echo form_radio('payment_mode',"DD", set_radio('payment_mode'),'data-toggle="radio"');
											echo 'DD';
											echo '</label>';
											echo '<label class="radio">';
											echo form_radio('payment_mode',"NEFT", set_radio('payment_mode'),'data-toggle="radio"');
											echo 'NEFT';
											echo '</label>';
										?>					
									</div>	
									<div class="col-md-6 paddingright_col6">
										<?php
											echo '<label class="radio">';
											echo form_radio('payment_mode',"RTGS", set_radio('payment_mode'),'data-toggle="radio"');
											echo 'RTGS';
											echo '</label>';
											echo '<label class="radio">';
											echo form_radio('payment_mode',"IMPS", set_radio('payment_mode'),'data-toggle="radio"');
											echo 'IMPS';
											echo '</label>';
											echo '<label class="radio">';
											echo form_radio('payment_mode',"SWIFT", set_radio('payment_mode'),'data-toggle="radio"');
											echo 'SWIFT';
											echo '</label>';
										?>										
									</div>
								</div>								
							</div>
						</div>
						<div class="col-md-12">
							<div class="content">
								<h6><span>Bank details for wire transfer :</span></h6>
								<div class="form-group">
								  	<table>
									  	<tbody>
									  		<tr>
									  			<td width="30%"><b>Bank Name</b></td>
									  			<td>: Union Bank of India</td>
									  		</tr>
									  		<tr>
									  			<td width="30%"><b>Address</b></td>
									  		    <td>: Kurla Industrial Estate, Sahakar Bhavan, Narayan Nagar Nari Seva Sadan Road
									  		    <br>&nbsp;&nbsp;Ghatkopar (West) Mumbai - 400 086 India.</td>
									  		</tr>
									  		<tr>
									  			<td width="30%"><b>Name of Beneficiary</b></td>
									  			<td>: Material Recycling Association of India</td>
									  		</tr>
									  		<tr>
									  			<td width="30%"><b>Current Account No</b></td>
									  			<td>: 317701010932403</td>
									  		</tr>
									  		<tr>
									  			<td width="30%"><b>IFSC Code</b></td>
									  			<td>: UBIN0531774</td>
									  		</tr>
									  		<tr>
									  			<td width="30%"><b>Swift Code</b></td>
									  			<td>: UBININBBGHT</td>
									  		</tr>
									  	</tbody>
								  	</table>  	
								 </div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="content">
								<p class="color_red"><b><u>Date:</u> </b>
								  	<b class="pull-right">Signature/Name/Stamp</b>
								  </p>
								  <p class="color_red"><b><u>Note:</u> </b></p>
								  <p class="color_red">1. The Financial Year of MRAI is from 1
									st April to 31st March. Membership of the Applicant shall be
									valid till March 31st in that financial year irrespective of the date on which the Membership
									commences (or as otherwise decided by the MRAI Committee from time to time).</p>
								<?php echo form_submit('submit', 'Add Attendee', 'class="btn btn-info btn-fill btn-wd"  id="submit" style="margin: 20px 20px;"');?>
								<?php echo form_close();?>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
$(document).ready(function(){
	console.log($(window).width());
	if($(window).width() > "700"){
		$("#addAttendee").css('display','inline-table');
	}
	$('#show-me').hide();
	$('#show-me1').hide();
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
	
$(".new_radio").click(function () {
	var $this = $(this);
	var main = $this["context"]["childNodes"][1]["value"];
	if(main == "5" || main == "7" || main == "9" || main == "11"){		
		$('#show-me').hide();		
	}else{
		$('#show-me').show();
	}
	if($($this).hasClass( "youngturk" )){
		$('#show-me1').show();		
		$('#image_proof_young').attr("required","required");		
	}else{
		$('#show-me1').hide();
		$('#image_proof_young').removeAttr("required");		
		$('#image_proof_young').removeAttr("aria-required");		
		$('#image_proof_young').removeAttr("aria-invalid");		
	}
});

/*$( "#addAttendee" ).validate({
  rules: {
  	company: {
    	required: true
    },
    noc: {
    	required: true
    },
    dincorp: {
    	required: true,    	
    },
    telnum1: {
    	required: true,
    	number: true
    },
    telnum2: {
    	required: true,
    	number: true
    },
    telnum3: {
    	required: true,
    	number: true
    },
    email: {
      required: true,
      email: true
    },
    pan_card: {
    	required: true,
    	rangelength: [5,20]
    },	
    gst_num: {
    	required: true,
    	rangelength: [5,20]
    },      
    gender_mr: {
    	require_from_group: [1, ".gender_mrs"]
    },
    gender_ms: {
    	require_from_group: [1, ".gender_mrs"]
    },
    last_name: {
    	required: true
    },
    first_name: {
    	required: true
    },

    middle_name: {
    	required: true
    },        
    desig: {
    	required: true
    },
    mob_num1: {
    	required: true,
    	number: true
    },
    mob_num2: {
    	required: true,
    	number: true
    },
    email_contact: {
      required: true,
      email: true
    },
    photo_cert: {
    	require_from_group: [1, ".cert_photo"]
    },
    photo_cert1: {
    	require_from_group: [1, ".cert_photo"]
    },
    photo_pancard: {
    	require_from_group: [1, ".pan_photo"]
    },
    photo_pancard1: {
    	require_from_group: [1, ".pan_photo"]
    }	
  }
});*/

</script>