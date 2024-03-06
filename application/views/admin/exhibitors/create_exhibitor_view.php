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
							<?php echo form_open('',array('id'=>'addExhibitors','class'=>'form_validation','enctype'=>'multipart/form-data'));?>
								<?php if(validation_errors()): ?>
									<div class="alert alert-danger">
										<?php print_r(validation_errors());?> 
									</div>
								<?php endif ?>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<?php 
												$attributes = array(
												'class' => 'control-label'
												);
											echo form_label('Exhibitor Name','ex_name',$attributes);								
											echo form_input('ex_name',set_value('ex_name'),'class="form-control" required = "true"',);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php 
												$attributes = array(
												'class' => 'control-label'
												);
											echo form_label('city','city',$attributes);								
											echo form_input('city',set_value('city'),'class="form-control" required = "true"',);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php 
												$attributes = array(
												'class' => 'control-label'
												);
											echo form_label('State','state',$attributes);								
											echo form_input('state',set_value('state'),'class="form-control" required = "true"',);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php 
												$attributes = array(
												'class' => 'control-label'
												);
											echo form_label('Country','country',$attributes);								
											echo form_input('country',set_value('country'),'class="form-control" required = "true"',);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php 
												$attributes = array(
												'class' => 'control-label'
												);
											echo form_label('PinCode','pincode',$attributes);								
											echo form_input('pincode',set_value('pincode'),'class="form-control" required = "true"',);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php 
												$attributes = array(
												'class' => 'control-label'
												);
											echo form_label('Company Ownership','comp_ownership',$attributes);								
											echo form_input('comp_ownership',set_value('comp_ownership'),'class="form-control" required = "true"',);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php 
												$attributes = array(
												'class' => 'control-label'
												);
											echo form_label('Company Key','comp_key',$attributes);								
											echo form_input('comp_key',set_value('comp_key'),'class="form-control" required = "true"',);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php 
												$attributes = array(
												'class' => 'control-label'
												);
											echo form_label('Company is/an','comp_is_an',$attributes);								
											echo form_input('comp_is_an',set_value('comp_is_an'),'class="form-control" required = "true"',);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php
												echo form_label('Website Url','web_url',$attributes);									
												echo form_input('web_url',set_value('web_url'),'class="form-control" required = "true"');
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php
												echo form_label('Exhibitor Image (PNG, JPG, JPEG) (min128 x max512)','ex_image',$attributes);
												echo form_upload('ex_image',set_value('ex_image'),'class="form-control" id="images_up"');
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<h5 class="mb-0">Exhibitors Team & Offering</h5>
										<div class="table-responsive">
											<table id="multi_form" class="table table-striped table-bordered table-responsive-md" cellspacing="0" width="100%">
												<thead>
													<tr>    
														<th>Offering Title</th>
														<th>Offering Description (comman separated like a,b,c)</th>
														<th>Team Name</th>
														<th>Team Designation</th>
														<th>Team City</th>
														<th>Team State</th>
														<th>Team Country</th>
														<th width="50px" id="remove_rows">
															<div class="add_row"><i class="text-primary fa fa-plus"></i></div>
														</th>
													</tr>
												</thead>
												<tbody id="append_rows">
												</tbody>
											</table>     
										</div>
									</div>
								</div>
								<!-- <div class="row">
									<div class="col-md-12">
										<h5 class="mb-0">Exhibitors Offering</h5>
										<div class="table-responsive">
											<table id="multi_form" class="table table-striped table-bordered table-responsive-md" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th>Title</th>
														<th>Description (comman separated like a,b,c)</th>
														<th width="50px" id="remove_rows">
															<div class="add_row"><i class="text-primary fa fa-plus"></i></div>
														</th>
													</tr>
												</thead>
												<tbody id="append_rows">
												</tbody>
											</table>     
										</div>
									</div>
								</div> -->
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<?php echo form_submit('submit', 'Save', 'class="btn btn-info btn-fill btn-wd"');?>
										</div>
									</div>
								</div>
							<?php echo form_close();?>								
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		var count = 0;
		function addRows() {
            count += 1;
            var html = '';
            html += '<tr>';
            html += '<td width="20%"><input type="text" name="title[]" id="title_'+ count + '" data-sub_item='+count+' class="form-control" required="true" aria-required="true"></td>';
            html += '<td width="20%"><input type="text" name="description[]" id="description_'+ count + '" data-sub_item='+count+' class="form-control" required="true" aria-required="true"></td>';
            html += '<td width="20%"><input type="text" name="emp_name[]" id="emp_name_'+ count + '" data-sub_item='+count+' class="form-control" required="true" aria-required="true"></td>';
            html += '<td width="20%"><input type="text" name="emp_designation[]" id="designation_'+ count + '" data-sub_item='+count+' class="form-control" required="true" aria-required="true"></td>';
            html += '<td width="20%"><input type="text" name="emp_city[]" id="city_'+ count + '" data-sub_item='+count+' class="form-control" required="true" aria-required="true"></td>';
            html += '<td width="20%"><input type="text" name="emp_state[]" id="state_'+ count + '" data-sub_item='+count+' class="form-control" required="true" aria-required="true"></td>';
            html += '<td width="20%"><input type="text" name="emp_country[]" id="country_'+ count + '" data-sub_item='+count+' class="form-control" required="true" aria-required="true"></td>';
            html += '<td><div class="delete_row text-center"><i class="text-danger fa fa-minus"></i></div></td></tr>';
            $('#append_rows').append(html);
        }
        addRows();
        
        $(document).on('click', '.add_row', function(e){ 
		
            addRows();
            if($("#append_rows").find('tr').length == 0){
                $("#saveBtn").hide();
                
            }
            else{
                $("#saveBtn").show();
            }
            $(".select2").select2();
        });

        $(document).on('click', '.delete_row', function(){
            $(this).closest('tr').remove();
            if($("#append_rows").find('tr').length == 0){
                $("#saveBtn").hide();
                count = 0;
            }
            else{
                $("#saveBtn").show();
            } 
        });
	})
</script>