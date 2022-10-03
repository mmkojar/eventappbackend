<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Attendee extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('attendee_model');
		$this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->data['current_tab'] = 'attendee';
	}

    public function index($user_id = null)
	{
	  $this->data['page_title'] = 'Attendee';
	  if(isset($user_id) && !empty($user_id)){
		  $this->data['user_id'] = $user_id;		  
	  }
		 
	  $this->render('admin/attendee/list_attendee_view');
	}
	
	public function upload_attendee($user_id = NULL)
	{
		$this->load->helper('form');
		$this->data['page_title'] = 'Upload Attendee';
		if(isset($user_id) && !empty($user_id)){
			$this->data['user_id'] = $user_id;
		}
		
		$this->render('admin/attendee/bulk_upload_attendee');
	}
	
	
	public function attendee_list()
	{
		$list = $this->attendee_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$no++;
			$row = array();
			$row[] = $requested->attendee_id;
			$row[] = anchor('admin/attendee/attendee_details/'.$requested->attendee_id,$requested->first_name);
			$row[] = $requested->email;
			$row[] = $requested->company_name;
			$row[] = $requested->membership_id;
			$row[] = "<div class = 'btn btn-success'><span class='btn-label'><i class='fa fa-check'></i></span> Approved</div>";
			//$row[] = date('jS-M-Y',strtotime($requested->requested_on));
			if($requested->publish_auth == "Yes" && $requested->publish_auth_final == "Yes"){
				$row[] = "<span style='color:green;font-weight: bolder;'>Authorised</span>";
			}else if($requested->publish_auth == "Yes"){
				$row[] = anchor('admin/attendee/update_auth_to_yes/'.$requested->attendee_id,'<i class="fa fa-check"></i>','class="btn btn-simple btn-success btn-icon edit"');
			}else{
				$row[] = "<span style='color:red;font-weight: bolder;'>Not Authorised</span>";
			}
				
			$i++;
			$data[] = $row;
		}
		
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->attendee_model->count_all(),
				"recordsFiltered" => $this->attendee_model->count_filtered(),
				"data" => $data,
			);
		
		//output to json format
		echo json_encode($output);
	}
	
	public function dob_check($str){
	    if (!DateTime::createFromFormat('Y-m-d', $str)) { //yes it's YYYY-MM-DD
	        $this->form_validation->set_message('dob_check', 'The {field} has not a valid date format');
	        return FALSE;
	    } else {
	        return TRUE;
	    }
	}

	public function create()
	{
		$this->data['page_title'] = 'Create attendee';
	  
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('company','Company','trim|required');
		$this->form_validation->set_rules('noc','Name of Company/Association','trim|required');
		$this->form_validation->set_rules('dincorp','Date of Incorporation','trim|required|callback_dob_check');
		$this->form_validation->set_rules('telnum1','Telephone Number','trim|required');
		$this->form_validation->set_rules('telnum2','Telephone Number','trim|required');
		$this->form_validation->set_rules('telnum3','Telephone Number','trim|required');
		$this->form_validation->set_rules('email','Email Address','trim|required');
		$this->form_validation->set_rules('pan_card','PAN Card Number','trim|required');		
		$this->form_validation->set_rules('gender','Gender','trim|required');
		$this->form_validation->set_rules('last_name','Full Name','trim|required');
		$this->form_validation->set_rules('first_name','Full Name','trim|required');
		$this->form_validation->set_rules('middle_name','Full Name','trim|required');
		$this->form_validation->set_rules('desig','Designation','trim|required');
		$this->form_validation->set_rules('mob_num1','Mobile Number','trim|required');
		$this->form_validation->set_rules('mob_num2','Mobile Number','trim|required');
		$this->form_validation->set_rules('email_contact','Email Address','trim|required');
		$this->form_validation->set_rules('photo_cert','Photocopy of Certificate of Incorporation<br>(Indian/Foreign)','trim|required');
		$this->form_validation->set_rules('photo_pancard','Photocopy of Pan Card<br>(for Indian companies/Association only)','trim|required');


		// $this->form_validation->set_rules('email_contact','Email Address','trim|required|is_unique[attendee.email]');
		// $this->form_validation->set_rules('mobile','mobile','trim|is_unique[attendee.mobile]');
		// $this->form_validation->set_rules('website','website','trim');
		
		// $this->form_validation->set_rules('attendee_interested[]','attendee interested','trim|required');
		// $attendee_interested = $this->input->post('attendee_interested[]');	
		// if(!empty($attendee_interested)){
		// 	if (in_array("other", $attendee_interested)){
		// 		$this->form_validation->set_rules('other_interest','attendee interested','trim|required');
		// 	}			
		// }
		// $this->form_validation->set_rules('attendee_business_type[]','Attendee Other Interest','trim');
		// $this->form_validation->set_rules('registration_fees_type_id','Registration Fees','trim|required');
		// $membership_id = $this->input->post('membership_id');	
		// if(!empty($membership_id)){
		// $this->form_validation->set_rules('membership_id','Membership Id','trim|required|callback_membership_check');	
		// }
		// $this->form_validation->set_rules('lunch_day_1','Lunch Day 1 AND Day 2','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/attendee/create_attendee_view');
		}
		else
		{
			// $register_data["attendee_image_url"] ="";
			// $register_data["attendee_spouse_image_url"] ="";
			// $register_data["image_proof_url"] ="";
			
			// $upload_dir = './assets/upload/images/attendee/';
			// $upload_dir1 = './assets/upload/images/attendee_spouse/';
			// $upload_dir2 = './assets/upload/images/youngturk/';
			
			// if (!is_dir($upload_dir)) {
			// 	mkdir($upload_dir);
			// }	
			// if (!is_dir($upload_dir1)) {
			// 	mkdir($upload_dir1);
			// }
			// if (!is_dir($upload_dir2)) {
			// 	mkdir($upload_dir2);
			// }
			// if(!empty($_FILES['attendee_image'])){
			// 	if($_FILES['attendee_image']['name'] != "" || $_FILES['attendee_image']['name'] != null){
			// 		$ext = pathinfo($_FILES['attendee_image']['name'], PATHINFO_EXTENSION);
			// 		$file_name=date("dmY").time().$_FILES['attendee_image']['name'];
			// 		$this->image_upload("attendee_image",$file_name,$upload_dir);
			// 		$register_data["attendee_image_url"] = $upload_dir."".$file_name;				
			// 	}else{
			// 		$register_data["attendee_image_url"] = "";
			// 	}
			// }
			// if(!empty($_FILES['attendee_spouse_image'])){
			// 	if($_FILES['attendee_spouse_image']['name'] != "" || $_FILES['attendee_spouse_image']['name'] != null){
			// 		$ext1 = pathinfo($_FILES['attendee_spouse_image']['name'], PATHINFO_EXTENSION);
			// 		$file_name=date("dmY").time().$_FILES['attendee_spouse_image']['name'];
			// 		$this->image_upload("attendee_spouse_image",$file_name,$upload_dir1);
			// 		$register_data["attendee_spouse_image_url"] = $upload_dir1."".$file_name;
			// 	}else{
			// 		$register_data["attendee_spouse_image_url"] = "";
			// 	}
			// }
			
			// if(!empty($_FILES['image_proof'])){
			// 	if($_FILES['image_proof']['name'] != "" || $_FILES['image_proof']['name'] != null){
			// 		$ext = pathinfo($_FILES['image_proof']['name'], PATHINFO_EXTENSION);
			// 		$file_name= date("dmY").time().$_FILES['image_proof']['name'];
			// 		$url = $this->image_upload("image_proof",$file_name,$upload_dir2);
			// 		$register_data["image_proof_url"] = $upload_dir2."".$file_name;					
			// 	}else{
			// 		$register_data["image_proof_url"] = "";
			// 	}
			// }

			// $register_data["family_name"] = $this->input->post('family_name');
			// $register_data["first_name"] = $this->input->post('first_name');
			// $register_data["company_name"] = $this->input->post('company_name');
			// $register_data["address"] = $this->input->post('address');
			// $register_data["country"] = $this->input->post('country');
			// $register_data["state"] = $this->input->post('state');
			// $register_data["city"] = $this->input->post('city');
			// $register_data["postal_code"] = $this->input->post('postal_code');
			// $register_data["dob"] = $this->input->post('dob');
			// $register_data["gst"] = $this->input->post('gst');
			// $register_data["telephone"] = $this->input->post('telephone');
			// $register_data["email"] = $this->input->post('email');
			// $register_data["mobile"] = $this->input->post('mobile');
			// $register_data["website"] = $this->input->post('website');
			// $register_data["attendee_spouse_family_name"] = $this->input->post('attendee_spouse_family_name');
			// $register_data["attendee_spouse_first_name"] = $this->input->post('attendee_spouse_first_name');
			// $interested = $this->input->post('attendee_interested');
			// if(!empty($interested)){
			// 	$register_data["attendee_interested"] = implode(', ',$interested);				
			// }else{
			// 	$register_data["attendee_interested"] = "";
			// }
			// if (in_array("other", $interested)){
			// 	if(empty($register_data["attendee_interested"])){
			// 		$register_data["attendee_interested"] = $this->input->post('other_interest');
			// 	}else{
			// 		$register_data["attendee_interested"] = $register_data["attendee_interested"] .", ". $this->input->post('other_interest');
			// 	}
			// }
			// $attendee_business_type = $this->input->post('attendee_business_type');
			// if(!empty($attendee_business_type)){
			// 	$register_data["attendee_business_type"] = implode(', ',$attendee_business_type);
			// }else{
			// 	$register_data["attendee_business_type"] = "";
			// }
			// $register_data["registration_fees_type_id"] = $this->input->post('registration_fees_type_id');	
			// $idss = array(1,3,13,15);
			
			// if (in_array($register_data["registration_fees_type_id"], $idss)){
			// 	$register_data["membership_id"] = $this->input->post('membership_id');	
			// }else{
			// 	$register_data["membership_id"] = "";
			// }
			// $register_data["lunch_day_1"] = $this->input->post('lunch_day_1');
			// $register_data["publish_auth"] = 'Yes';
			// $register_data["attendee_participation"] = 'yes';
			// $register_data["attendee_agree"] = 'Yes';
			// $register_data["status_attendee"] = 'pending';
			// $register_data["attendee_request_id"] = "";

			$register_data["company"] = $this->input->post('company');
			$register_data["noc"] = $this->input->post('noc');
			$register_data["nopc"] = $this->input->post('nopc');
			$register_data["address1"] = $this->input->post('address1');
			$register_data["address2"] = $this->input->post('address2');
			$register_data["address3"] = $this->input->post('address3');
			$register_data["address4"] = $this->input->post('address4');
			$register_data["address5"] = $this->input->post('address5');
			$register_data["address6"] = $this->input->post('address6');
			$register_data["dincorp"] = $this->input->post('dincorp');
			$register_data["telnum1"] = $this->input->post('telnum1');
			$register_data["telnum2"] = $this->input->post('telnum2');
			$register_data["telnum3"] = $this->input->post('telnum3');
			$register_data["faxnumber1"] = $this->input->post('faxnumber1');
			$register_data["faxnumber2"] = $this->input->post('faxnumber2');
			$register_data["faxnumber3"] = $this->input->post('faxnumber3');
			$register_data["email"] = $this->input->post('email');
			$register_data["name_firm"] = $this->input->post('name_firm');
			$register_data["pan_card"] = $this->input->post('pan_card');
			$register_data["gst_num"] = $this->input->post('gst_num');
			$register_data["gender"] = $this->input->post('gender');
			$register_data["last_name"] = $this->input->post('last_name');
			$register_data["first_name"] = $this->input->post('first_name');
			$register_data["middle_name"] = $this->input->post('middle_name');
			$register_data["desig"] = $this->input->post('desig');
			$register_data["mob_num1"] = $this->input->post('mob_num1');
			$register_data["mob_num2"] = $this->input->post('mob_num2');
			$register_data["add_contact"] = $this->input->post('add_contact');
			$register_data["email_contact"] = $this->input->post('email_contact');
			$ferrous_metals = $this->input->post('ferrous_metals');
			if(!empty($ferrous_metals)){
				$register_data["ferrous_metals"] = implode(', ',$ferrous_metals);				
			}else{
				$register_data["ferrous_metals"] = "";
			}
			$textiles = $this->input->post('textiles');
			if(!empty($textiles)){
				$register_data["textiles"] = implode(', ',$textiles);				
			}else{
				$register_data["textiles"] = "";
			}
			$Ferrous = $this->input->post('Ferrous');
			if(!empty($Ferrous)){
				$register_data["Ferrous"] = implode(', ',$Ferrous);				
			}else{
				$register_data["Ferrous"] = "";
			}
			$Paper= $this->input->post('Paper');
			if(!empty($Paper)){
				$register_data["Paper"] = implode(', ',$Paper);				
			}else{
				$register_data["Paper"] = "";
			}
			$stainless_steel = $this->input->post('stainless_steel');
			if(!empty($stainless_steel)){
				$register_data["stainless_steel"] = implode(', ',$stainless_steel);				
			}else{
				$register_data["stainless_steel"] = "";
			}
			$Plastics = $this->input->post('Plastics');
			if(!empty($Plastics)){
				$register_data["Plastics"] = implode(', ',$Plastics);				
			}else{
				$register_data["Plastics"] = "";
			}
			$register_data["recycled_glass"] = $this->input->post('recycled_glass');
			$register_data["electronic_waste"] = $this->input->post('electronic_waste');
			$Tyres = $this->input->post('Tyres');
			if(!empty($Tyres)){
				$register_data["Tyres"] = implode(', ',$Tyres);				
			}else{
				$register_data["Tyres"] = "";
			}
			$Sectors = $this->input->post('Sectors');
			if(!empty($Sectors)){
				$register_data["Sectors"] = implode(', ',$Sectors);				
			}else{
				$register_data["Sectors"] = "";
			}
			$register_data["dmemassoc"] = $this->input->post('dmemassoc');
			$register_data["app_name_reom"] = $this->input->post('app_name_reom');
			$register_data["otherinfo_sheet"] = $this->input->post('otherinfo_sheet');
			$register_data["photo_cert"] = $this->input->post('photo_cert');
			// $register_data["photo_cert1"] = $this->input->post('photo_cert1');
			$register_data["photo_pancard"] = $this->input->post('photo_pancard');
			// $register_data["photo_pancard1"] = $this->input->post('photo_pancard1');
			$register_data["annul_mem"] = $this->input->post('annul_mem');
			$register_data["tot_fees"] = $this->input->post('tot_fees');
			$register_data["payment_mode"] = $this->input->post('payment_mode');
				
			$this->attendee_model->register_attendee($register_data);
			// print_r("<pre>");
			// print_r($register_data);
			// exit;
			$this->session->set_flashdata('success','Attendee Added Successfully');
			redirect('admin/requested','refresh');
		}
	}
	
	
	 public function membership_check($membership)
	 {
		$check = $this->attendee_model->checkmembership($membership);
		if(empty($check))
		{
			$this->form_validation->set_message('membership_check', 'The {field} is invalid.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	 
	public function edit($attendee_id = NULL)
	{
		$attendee_id = $this->input->post('attendee_id') ? $this->input->post('attendee_id') : $attendee_id;
		
		$this->data['page_title'] = 'Edit attendee';
		
		$this->load->library('form_validation');
		$attendee_old = $this->attendee_model->get_attendee_id((int) $attendee_id);
		  $this->form_validation->set_rules('attendee_name','attendee name','trim|required');
		  $this->form_validation->set_rules('assignto','assignto','trim|required');
		 
		 if($attendee_old["attendee_code"] != $this->input->post('attendee_code')){
			  $this->form_validation->set_rules('attendee_code','attendee_code','trim|required|is_unique[attendee.attendee_code]');		  
		  }else{
			  $this->form_validation->set_rules('attendee_code','attendee_code','trim|required');		  
		  }
		  
		  if($attendee_old["attendee_notification_id"] != $this->input->post('attendee_notification_id')){
			  $this->form_validation->set_rules('attendee_notification_id','attendee notification id','trim|is_unique[attendee.attendee_notification_id]');
		  }else{
			  $this->form_validation->set_rules('attendee_notification_id','attendee notification id','trim');
		  }
		 
		  
		  if($attendee_old["attendee_imei"] != $this->input->post('attendee_imei')){
			  $this->form_validation->set_rules('attendee_imei','attendee imei','trim|required|min_length[15]|max_length[15]|is_unique[attendee.attendee_imei]');
		  }else{
			  $this->form_validation->set_rules('attendee_imei','attendee imei','trim|required|min_length[15]|max_length[15]');
		  }
		  
		   if($attendee_old["attendee_mac_id"] != $this->input->post('attendee_mac_id')){
			  $this->form_validation->set_rules('attendee_mac_id','attendee mac id','trim|valid_mac|is_unique[attendee.valid_mac]');
		  }else{
			   $this->form_validation->set_rules('attendee_mac_id','attendee mac id','trim|valid_mac');
		  }
		 
		  $this->form_validation->set_rules('number_to_attendee','number to attendee','trim|required');
		  $this->form_validation->set_rules('status','Status','trim|required');
		  
	   if($this->form_validation->run()===FALSE)
	  {
		if($attendee = $this->attendee_model->get_attendee_id((int) $attendee_id))
		{
		  $this->data['attendee'] = $attendee;
		}
		else
		{
		  $this->session->set_flashdata('message', 'The attendee doesn\'t exist.');
		  redirect('admin/attendee', 'refresh');
		}
		$this->load->helper('form');
		$group_id = "3";
		$users = $this->ion_auth->users($group_id)->result();
		$group_id1 = "4";
		$users1 = $this->ion_auth->users($group_id1)->result();
		$finaluser = array_merge($users,$users1);
		$this->data['users'] = $finaluser;
		$this->render('admin/attendee/edit_attendee_view');
	  }
	  else
	  {
		$attendee_id = $this->input->post('attendee_id');
		$new_data = array(
			'attendee_name' => $this->input->post('attendee_name'),
			'user_id' => $this->input->post('assignto'),
			'attendee_code' => $this->input->post('attendee_code'),
			'attendee_notification_id' => $this->input->post('attendee_notification_id'),
			'attendee_mac_id' => $this->input->post('attendee_mac_id'),
			'attendee_imei' => $this->input->post('attendee_imei'),
			'number_to_attendee' => $this->input->post('number_to_attendee'),
			'status' => $this->input->post('status'),
			'updated_on' => date("Y-m-d H:i:s")
		);

		
		$this->attendee_model->update($attendee_id, $new_data);

		//$this->session->set_flashdata('message');
		redirect('admin/attendee','refresh');
	  }
	}

 
	public function update_auth_to_yes($attendee_id = NULL)
	{
		if(is_null($attendee_id))
		{
			$this->session->set_flashdata('message','There\'s no attendee to delete');
		}
		else
		{
			$new_data = array(
				'publish_auth_final' => "Yes",
				'updated_on' => date("Y-m-d H:i:s")
			);

		
			$this->attendee_model->update($attendee_id, $new_data);
			//$this->session->set_flashdata('message',$this->ion_auth->messages());
		}
		redirect('admin/attendee','refresh');
	}


	public function delete($attendee_id = NULL)
	{
	  if(is_null($attendee_id))
	  {
		$this->session->set_flashdata('message','There\'s no user to delete');
	  }
	  else
	  {
		if($this->ion_auth->in_group('admin'))
		{
			 $this->attendee_model->delete_attendee($attendee_id);
		}else{
			$this->session->set_flashdata('message1','A request Has been Sent to Administrator to Delete the following Device.');
		}
	   
		//$this->session->set_flashdata('message',$this->ion_auth->messages());
	  }
	  redirect('admin/attendee','refresh');
	}


	public function attendee_details($attendee_id = null)
	{
	  $this->data['page_title'] = 'Attendee';
	  if(isset($attendee_id) && !empty($attendee_id)){
		  $this->data['attendee'] = $this->attendee_model->get_attendees_id($attendee_id);
		}
		 
	  $this->render('admin/attendee/attendee_detail_view');
	}


	function image_upload($input_file_name,$file_name,$path)
    {
        $this->load->library('upload');
        $config['file_name'] =$file_name; 
        $config['upload_path'] =$path;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['overwrite'] = false;
        $config['remove_spaces'] = true;
        $config['file_ext_tolower'] = true;
        $this->upload->initialize($config); 
        if (!$this->upload->do_upload($input_file_name))
        {
			return false;
        }
    }
	
/* public function image_upload($image){

		$upload_dir = './assets/upload/images';

		if (!is_dir($upload_dir)) {
			mkdir($upload_dir);
		}	

		$config['upload_path']   = $upload_dir;
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['file_name']     = 'image_'.rand();
		$config['overwrite']     = false;
		$config['max_size']	 = '200000';

		$url = $upload_dir.'/'.$config['file_name'];

		$this->load->library('upload', $config);
		
		if($image == 'attendee_image'){
			if (!$this->upload->do_upload('attendee_image')){
				return "Unable to Upload Image";
			}	
			else{
				return $url;
			}
		}else if($image == 'attendee_spouse_image'){
			if (!$this->upload->do_upload('attendee_spouse_image')){
				return "Unable to Upload Image";
			}	
			else{
				return $url;
			}
		}

	} */

	public function create_attendee_api(){

			$register_data["company"] = $this->input->post('company');
			$register_data["noc"] = $this->input->post('noc');
			$register_data["nopc"] = $this->input->post('nopc');
			$register_data["address1"] = $this->input->post('address1');
			$register_data["address2"] = $this->input->post('address2');
			$register_data["address3"] = $this->input->post('address3');
			$register_data["address4"] = $this->input->post('address4');
			$register_data["address5"] = $this->input->post('address5');
			$register_data["address6"] = $this->input->post('address6');
			$register_data["dincorp"] = $this->input->post('dincorp');
			$register_data["telnum1"] = $this->input->post('telnum1');
			$register_data["telnum2"] = $this->input->post('telnum2');
			$register_data["telnum3"] = $this->input->post('telnum3');
			$register_data["faxnumber1"] = $this->input->post('faxnumber1');
			$register_data["faxnumber2"] = $this->input->post('faxnumber2');
			$register_data["faxnumber3"] = $this->input->post('faxnumber3');
			$register_data["email"] = $this->input->post('email');
			$register_data["name_firm"] = $this->input->post('name_firm');
			$register_data["pan_card"] = $this->input->post('pan_card');
			$register_data["gst_num"] = $this->input->post('gst_num');
			$register_data["gender"] = $this->input->post('gender');
			$register_data["last_name"] = $this->input->post('last_name');
			$register_data["first_name"] = $this->input->post('first_name');
			$register_data["middle_name"] = $this->input->post('middle_name');
			$register_data["desig"] = $this->input->post('desig');
			$register_data["mob_num1"] = $this->input->post('mob_num1');
			$register_data["mob_num2"] = $this->input->post('mob_num2');
			$register_data["add_contact"] = $this->input->post('add_contact');
			$register_data["email_contact"] = $this->input->post('email_contact');
			$ferrous_metals = $this->input->post('ferrous_metals');
			if(!empty($ferrous_metals)){
				$register_data["ferrous_metals"] = implode(', ',$ferrous_metals);				
			}else{
				$register_data["ferrous_metals"] = "";
			}
			$textiles = $this->input->post('textiles');
			if(!empty($textiles)){
				$register_data["textiles"] = implode(', ',$textiles);				
			}else{
				$register_data["textiles"] = "";
			}
			$Ferrous = $this->input->post('Ferrous');
			if(!empty($Ferrous)){
				$register_data["Ferrous"] = implode(', ',$Ferrous);				
			}else{
				$register_data["Ferrous"] = "";
			}
			$Paper= $this->input->post('Paper');
			if(!empty($Paper)){
				$register_data["Paper"] = implode(', ',$Paper);				
			}else{
				$register_data["Paper"] = "";
			}
			$stainless_steel = $this->input->post('stainless_steel');
			if(!empty($stainless_steel)){
				$register_data["stainless_steel"] = implode(', ',$stainless_steel);				
			}else{
				$register_data["stainless_steel"] = "";
			}
			$Plastics = $this->input->post('Plastics');
			if(!empty($Plastics)){
				$register_data["Plastics"] = implode(', ',$Plastics);				
			}else{
				$register_data["Plastics"] = "";
			}
			$register_data["recycled_glass"] = $this->input->post('recycled_glass');
			$register_data["electronic_waste"] = $this->input->post('electronic_waste');
			$Tyres = $this->input->post('Tyres');
			if(!empty($Tyres)){
				$register_data["Tyres"] = implode(', ',$Tyres);				
			}else{
				$register_data["Tyres"] = "";
			}
			$Sectors = $this->input->post('Sectors');
			if(!empty($Sectors)){
				$register_data["Sectors"] = implode(', ',$Sectors);				
			}else{
				$register_data["Sectors"] = "";
			}
			$register_data["dmemassoc"] = $this->input->post('dmemassoc');
			$register_data["app_name_reom"] = $this->input->post('app_name_reom');
			$register_data["otherinfo_sheet"] = $this->input->post('otherinfo_sheet');
			$register_data["photo_cert"] = $this->input->post('photo_cert');
			// $register_data["photo_cert1"] = $this->input->post('photo_cert1');
			$register_data["photo_pancard"] = $this->input->post('photo_pancard');
			// $register_data["photo_pancard1"] = $this->input->post('photo_pancard1');
			$register_data["annul_mem"] = $this->input->post('annul_mem');
			$register_data["tot_fees"] = $this->input->post('tot_fees');
			$register_data["payment_mode"] = $this->input->post('payment_mode');
			// $data = $this->security->xss_clean($register_data);
			if(!empty($register_data)){
				$create_attendee = $this->attendee_model->register_attendee($register_data);
				if(!empty($create_attendee)){
					$return["errCode"]  = '-1';
					$return["errMsg"]  = $create_attendee;
					$return["errMsg"]['msg']  =  'Attendee Added Successfully';
				}else{
					$return["errCode"]  = '1';
					$return["errMsg"]  = 'Failed';
				}
			}
			else{
					$return["errCode"]  = '1';
					$return["errMsg"]  = 'Invalid';
			}
			print_r(json_encode($return));

	}

}