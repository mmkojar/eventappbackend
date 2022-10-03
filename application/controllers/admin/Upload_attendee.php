<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Upload_attendee extends Admin_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('excel');
		$this->load->library('upload');
		$this->load->model('attendee_model');
		if($this->ion_auth->in_group('members') || $this->ion_auth->in_group('security'))
		{
		  $this->session->set_flashdata('error','You are not allowed to visit the Upload Beacon page');
		  redirect('admin','refresh');
		}
	}

	function do_upload(){
		$user = $this->ion_auth->user()->row();
		$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] ."/MRAI-IMRC/assets/upload/excel";
		$config['allowed_types'] = 'xls|xlsx|csv|XLS|XLSX|CSV';
		$config['max_size']	= '10000';
		$config['overwrite'] = false;
        $config['remove_spaces'] = true;
		$new_name = $user->username ."_".time();
		$config['file_name'] = $new_name;
		if(!is_dir($config['upload_path'])) 
			mkdir($config['upload_path'], 0777, TRUE);
		if(is_file($config['upload_path'])){
			chmod($config['upload_path'], 777);
		}
		//die(var_dump(is_dir($config['upload_path'])));
		$this->upload->initialize($config);
		if( !$this->upload->do_upload('userfile')){
			$error = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('error', $error["error"] );
			redirect('admin/attendee/upload_attendee', 'refresh');
		} else {
			$upload_data=array('upload_data' => $this->upload->data());	
			foreach($upload_data as $key=>$value){
				$file_name=$value['file_name'];
				$file_path=$value['file_path'];
			}
			
			$dom = 0;
			$sheet_fields = 0;
			//phpexcelreader
			$objPHPExcel = PHPExcel_IOFactory::load($file_path."/$file_name");
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			//extract to a PHP readable array format
			foreach ($cell_collection as $cell){
				$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
				$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
				$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
				//header will/should be in row 1 only. of course this can be modified to suit your need.
				if ($row == 1) {
					$header[$row][$column] = $data_value;
				} else {
					$arr_data[$row][$column] = $data_value;
				}
			}
			
			//send the data in an array format
			$excel_data['header'] = $header;
			$excel_data['values'] = $arr_data;
			$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			//loop from first data until last data
				for($i=2; $i<=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow(); $i++){
					
					$family_name = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
					$first_name = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
					$company_name = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
					$address = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
					$telephone = $objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
					$email = $objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
					$mobile = $objWorksheet->getCellByColumnAndRow(6,$i)->getValue();
					$website = $objWorksheet->getCellByColumnAndRow(7,$i)->getValue();
					$publish_auth = $objWorksheet->getCellByColumnAndRow(8,$i)->getValue();
					$attendee_spouse_family_name = $objWorksheet->getCellByColumnAndRow(9,$i)->getValue();
					$attendee_spouse_first_name = $objWorksheet->getCellByColumnAndRow(10,$i)->getValue();
					$attendee_interested = $objWorksheet->getCellByColumnAndRow(11,$i)->getValue();
					$attendee_business_type = $objWorksheet->getCellByColumnAndRow(12,$i)->getValue();
					$registration_fees_type_id = $objWorksheet->getCellByColumnAndRow(13,$i)->getValue();
					$membership_id = $objWorksheet->getCellByColumnAndRow(14,$i)->getValue();
					if(empty($membership_id)){
						$membership_id ="";
					}
					$attendee_participation = $objWorksheet->getCellByColumnAndRow(15,$i)->getValue();
					$lunch_day_1 = $objWorksheet->getCellByColumnAndRow(16,$i)->getValue();
					$attendee_agree = $objWorksheet->getCellByColumnAndRow(17,$i)->getValue();
					/* $status_attendee = $objWorksheet->getCellByColumnAndRow(18,$i)->getValue(); */
					
					$ech = $this->create_by_excel_upload($family_name,$first_name,$company_name,$address, $telephone, $email, $mobile, $website, $publish_auth,$attendee_spouse_family_name,$attendee_spouse_first_name,$attendee_interested,$attendee_business_type,$registration_fees_type_id,$membership_id,$attendee_participation,$lunch_day_1,$attendee_agree);
				}
				
				$this->session->set_flashdata('success', "Excel Added");
				redirect('admin/attendee/upload_attendee','refresh');
		}
	}
	
	public function create_by_excel_upload($family_name,$first_name,$company_name,$address, $telephone, $email, $mobile, $website, $publish_auth,$attendee_spouse_family_name,$attendee_spouse_first_name,$attendee_interested,$attendee_business_type,$registration_fees_type_id,$membership_id,$attendee_participation,$lunch_day_1,$attendee_agree){

	$attendee_image_url = "";
	$attendee_spouse_image_url = "";
	
	$this->load->library('form_validation');
		$this->form_validation->set_data(array(
			'family_name'   => $family_name,
			'first_name'   => $first_name,
			'company_name'   => $company_name,
			'address'   => $address,
			'telephone'   => $telephone,
			'email'   => $email,
			'mobile'   => $mobile,
			'website'   => $website,
			'attendee_spouse_family_name'   => $attendee_spouse_family_name,
			'attendee_spouse_first_name'   => $attendee_spouse_first_name,
			'attendee_interested'   => 	$attendee_interested,
			'attendee_business_type'   => $attendee_business_type,
			'registration_fees_type_id'   => $registration_fees_type_id,
			'membership_id'   => $membership_id,
			'lunch_day_1'   =>  $lunch_day_1
		));

		$this->form_validation->set_rules('family_name','Family Name','trim|required');
		$this->form_validation->set_rules('first_name','First Name','trim|required');
		$this->form_validation->set_rules('company_name','Company Name','trim|required');
		$this->form_validation->set_rules('address','address','trim');
		$this->form_validation->set_rules('telephone','telephone','trim|is_unique[attendee.telephone]');
		$this->form_validation->set_rules('email','email','trim|required|is_unique[attendee.email]');
		$this->form_validation->set_rules('mobile','mobile','trim|is_unique[attendee.mobile]');
		$this->form_validation->set_rules('website','website','trim');
		
		$this->form_validation->set_rules('attendee_spouse_family_name','Attendee Spouse Family Name','trim');
		$this->form_validation->set_rules('attendee_spouse_first_name','Attendee Spouse First Name','trim');
		$this->form_validation->set_rules('attendee_business_type','Attendee Business Type','trim');
		
		$this->form_validation->set_rules('registration_fees_type_id','Registration Fees','trim|required');
		
		$id = $this->input->post('registration_fees_type_id');
		if($id == "1" || $id == "2" || $id == "3" || $id == "4"){			
			$this->form_validation->set_rules('membership_id','MemberShip ID','trim|required|callback_membership_check');
		}
		$this->form_validation->set_rules('lunch_day_1','Lunch Day 1','trim|required');

		if($this->form_validation->run()===FALSE)
		{
			$this->form_validation->reset_validation();
		}
		else
		{
			$register_data = array(
				'family_name'   => $family_name,
				'first_name'   => $first_name,
				'company_name'   => $company_name,
				'address'   => $address,
				'telephone'   => $telephone,
				'email'   => $email,
				'mobile'   => $mobile,
				'website'   => $website,
				'attendee_image_url'   => $attendee_image_url,
				'attendee_spouse_family_name'   => $attendee_spouse_family_name,
				'attendee_spouse_first_name'   => $attendee_spouse_first_name,
				'attendee_spouse_image_url'   => $attendee_spouse_image_url,
				'attendee_interested'   => 	$attendee_interested,
				'attendee_business_type'   => $attendee_business_type,
				'registration_fees_type_id'   => $registration_fees_type_id,
				'membership_id'   => $membership_id,
				'lunch_day_1'   =>  $lunch_day_1,
				'publish_auth'   => $publish_auth,
				'attendee_participation'   => $attendee_participation,
				'attendee_agree'   => $attendee_agree,
				'status_attendee'   => "Pending",
				'requested_on' => date("Y-m-d H:i:s"),
				'created_on' => date("Y-m-d H:i:s"),
				'updated_on' => date("Y-m-d H:i:s")
			);
		
			$register = $this->attendee_model->register_attendee($register_data);
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
	
}

?>