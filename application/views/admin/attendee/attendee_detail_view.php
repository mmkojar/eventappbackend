<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<style>
.card .avatar1 {
    max-width: 250px;
    margin-right: 5px;
}
.card-user .content {
    min-height: 50px;
}
.card-user .author {
    text-align: center;
    text-transform: none;
    margin-top: 0px;
}
</style>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8">
				<div class="card">
					<div class="header">
						<h4 class="title">Attendee Profile</h4>
					</div>
					<div class="content">
						<?php $this->load->view('public/themes/default/error_msg'); ?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											echo form_label('Membership Id (disabled)');
											echo form_error('membership_id');
											echo form_input('membership_id',set_value('membership_id',$attendee["membership_id"]),'class="form-control" disabled');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											echo form_label('Email','email');
											echo form_error('email');
											echo form_input('email',set_value('email',$attendee["email"]),'class="form-control" disabled');
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											echo form_label('GST');
											echo form_error('gst');
											echo form_input('gst',set_value('gst',$attendee["gst"]),'class="form-control" disabled');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											echo form_label('D.O.B.','email');
											echo form_error('dob');
											echo form_input('dob',set_value('dob',$attendee["dob"]),'class="form-control" disabled');
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<?php 
											echo form_label('Amount Paid');
											echo form_error('chargetotal');
											echo form_input('chargetotal',set_value('chargetotal',$attendee["chargetotal"]),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<?php 
											echo form_label('Payment Status');
											echo form_error('status');
											echo form_input('status',set_value('status',$attendee["status"]),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<?php 
											echo form_label('Reason (Code)');
											echo form_error('approval_code');
											echo form_input('approval_code',set_value('approval_code',$attendee["approval_code"]),'class="form-control"');
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											echo form_label('Company Name');
											echo form_error('company_name');
											echo form_input('company_name',set_value('company_name',$attendee["company_name"]),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											echo form_label('Telephone');
											echo form_error('telephone');
											echo form_input('telephone',set_value('telephone',$attendee["telephone"]),'class="form-control"');
										?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<?php
											echo form_label('Address','address');
											echo form_error('address');
											echo form_input('address',set_value('address',$attendee["address"]),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<?php
											echo form_label('Publish Auth','publish_auth');
											echo form_error('publish_auth');
											echo form_input('publish_auth',set_value('publish_auth',$attendee["publish_auth"]),'class="form-control"');
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<?php
											echo form_label('City','city');
											echo form_error('city');
											echo form_input('city',set_value('city',$attendee["city"]),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<?php
											echo form_label('State','state');
											echo form_error('state');
											echo form_input('state',set_value('state',$attendee["state"]),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<?php
											echo form_label('Country','country');
											echo form_error('country');
											echo form_input('country',set_value('country',$attendee["country"]),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<?php
											echo form_label('Pincode / Postal Code','postal_code');
											echo form_error('postal_code');
											echo form_input('postal_code',set_value('postal_code',$attendee["postal_code"]),'class="form-control"');
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Mobile','mobile');
											echo form_error('mobile');
											echo form_input('mobile',set_value('mobile',$attendee["mobile"]),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('website','website');
											echo form_error('website');
											echo form_input('website',set_value('website',$attendee["website"]),'class="form-control"');
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Attendee Interested','attendee_interested');
											echo form_error('attendee_interested');
											echo form_input('attendee_interested',set_value('attendee_interested',$attendee["attendee_interested"]),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('attendee_business_type','attendee_business_type');
											echo form_error('attendee_business_type');
											echo form_input('attendee_business_type',set_value('attendee_business_type',$attendee["attendee_business_type"]),'class="form-control"');
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php
											if($attendee["registration_fees_type_id"] != "17"){
												$type = $attendee["company_type"]." ".$attendee["delegate_type"]." ".$attendee["member_type"];
											}else{
												$type = "OFFLINE";
											}
											echo form_label('Registration Fees Type','registration_fees_type_id');
											echo form_error('registration_fees_type_id');
											echo form_input('registration_fees_type_id',set_value('registration_fees_type_id',$type),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Lunch Day 1 And Day 2','lunch_day_1');
											echo form_error('lunch_day_1');
											echo form_input('lunch_day_1',set_value('lunch_day_1',$attendee["lunch_day_1"]),'class="form-control"');
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Requested On','requested_on');
											echo form_error('requested_on');
											echo form_input('requested_on',set_value('requested_on',date('jS-M-Y',strtotime($attendee["requested_on"]))),'class="form-control"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Request Accepted On','updated_on');
											echo form_error('updated_on');
											echo form_input('updated_on',set_value('updated_on',date('jS-M-Y',strtotime($attendee["updated_on"]))),'class="form-control"');
										?>
									</div>
								</div>								
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php
											echo form_label('Status Attendee','status_attendee');
											echo form_error('status_attendee');
											if($attendee["status_attendee"] == "Approve"){
												echo anchor('admin/requested/disapprove/'.$attendee["attendee_id"],'<span><span class="btn-label"><i class="fa fa-times"></i></span>  Disapprove</span>',array('class' => 'form-control btn btn-danger btn-fill btn-wd'));
											}else if($attendee["status_attendee"] == "Pending"){
												echo anchor('admin/requested/approve/'.$attendee["attendee_id"],'<span> <span class="btn-label"><i class="fa fa-check"></i></span>  Approve</span>',array('class' => 'form-control btn btn-success btn-fill btn-wd'))." </br> ".anchor('admin/requested/disapprove/'.$attendee["attendee_id"],'<span><span class="btn-label"><i class="fa fa-times"></i></span>  Disapprove</span>',array('class' => 'form-control btn btn-danger btn-fill btn-wd'));
											}else{
												echo form_input('status_attendee',set_value('status_attendee',$attendee["status_attendee"]),'class="form-control btn btn-danger btn-fill btn-wd"');
											}
												
										?>
									</div>
								</div>
							</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card card-user">
					<div class="content">
						<div class="author">
							<?php if(!empty($attendee['attendee_image'])){ ?>
								<!-- <img class="avatar border-gray" src="<?php echo base_url()."".str_replace(" ", "_", $attendee['attendee_image']); ?>" alt="..." /> -->
								<img class="avatar border-gray" src="<?php echo base_url()."".str_replace("./", "", $attendee['attendee_image']); ?>" alt="..." />
							<?php }else{ ?>
								<img class="avatar border-gray" src="<?php echo base_url(); ?>assets/admin/img/default-avatar.png" alt="..."/>
							<?php } ?>
							  <h4 class="title"><?php print_r($attendee["first_name"] .' '.$attendee["family_name"]);?><br/>
							  </h4>
							<?php if(!empty($attendee['attendee_spouse_image'])){ ?>
								<img class="avatar border-gray" src="<?php echo base_url()."".str_replace(" ", "_", $attendee['attendee_spouse_image']); ?>" alt="..."/>
								<?php } ?>
							<?php if(!empty($attendee['attendee_spouse_first_name'])){ ?>
								<h4 class="title"><?php print_r($attendee["attendee_spouse_first_name"] .' '.$attendee["attendee_spouse_family_name"]);?><br/>
								</h4>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php if(!empty($attendee['image_proof_url'])){ ?>
			<div class="col-md-4">
				<div class="card">
					<div class="content">
						<div class="author">
							<img class="avatar1 border-gray" src="<?php echo base_url()."".str_replace(" ", "_", $attendee['image_proof_url']); ?>"/>
						</div>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script>


 <script type="text/javascript">
	$().ready(function(){

		$('#userprofileValidation').validate();
		
	});
</script>