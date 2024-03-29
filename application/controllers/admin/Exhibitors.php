<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Exhibitors extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('exhibitors_model');
		// $this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('imageupload');
		$this->data['current_tab'] = 'exhibitors';
	}

    public function index($user_id = null)
	{
		$this->data['dttable_tab'] = 'dt_table';
		$this->data['tbl_name'] = 'exhibitors/exhibitors_list';
		$this->data['page_title'] = 'Exhibitors';
		if(isset($user_id) && !empty($user_id)){
			$this->data['user_id'] = $user_id;		  
		}
		 
	  $this->render('admin/exhibitors/list_exhibitor_view');
	}
	
	public function exhibitors_list()
	{
		$list = $this->exhibitors_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$src = base_url()."".str_replace("./", "", $requested->ex_image);
			$no++;
			$row = array();
			$row[] = $requested->ex_id;
			$row[] = $requested->ex_name;
			$row[] = '<img src="'.$src.'" height="100" width="100">';
			$row[] = anchor($requested->web_url,$requested->web_url,'target="_blank"');		
			$row[] = ($requested->status == '1' ? '<span class="badge badge-success text-white">Active</span>' : '<span class="badge badge-danger text-white">Inactive</span>');
			$row[] = date('jS-M-Y',strtotime($requested->created_on));
			$row[] = anchor('admin/exhibitors/edit/'.$requested->ex_id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.anchor('admin/exhibitors/delete/'.$requested->ex_id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"');
			$i++;
			$data[] = $row;
		}
		
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->exhibitors_model->count_all(),
				"recordsFiltered" => $this->exhibitors_model->count_filtered(),
				"data" => $data,
			);
		
		//output to json format
		echo json_encode($output);
	}

	public function create()
	{
		$this->data['page_title'] = 'Add Exhibitors';
	  
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('ex_name','Exhibitors Name','trim|required');
		$this->form_validation->set_rules('web_url','Website Url','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/exhibitors/create_exhibitor_view');
		}
		else
		{

			$upload_dir = './assets/upload/images/exhibitors/';
			$fileUploadError = [];
			if($_FILES['ex_image']['name'] != "" || $_FILES['ex_image']['name'] != null){
				$ext = pathinfo($_FILES['ex_image']['name'], PATHINFO_EXTENSION);
				$file_name=date("dmY").time().'_'.$_FILES['ex_image']['name'];

				$fileUpload = ImageUpload("ex_image",$file_name,$upload_dir);          
				array_push($fileUploadError,$fileUpload);
			}
			else{
				$fileUploadError[0] = ['status'=>'1' ,'msg' => 'File Uploaded'];
				$file_name = "default.png";
			}

			if($fileUploadError[0]['status'] == '0') {
				$this->session->set_flashdata('error',$fileUploadError[0]['msg']);				
				$this->render('admin/exhibitors/create_exhibitor_view');
			}
			else {

				// foreach ($_POST as $key => $value) {
				// 	if($value == null ||$value == ""){
				// 		$register_data[$key] = NULL;
				// 	}else{
				// 		$register_data[$key] = $value;
				// 	}
				// }
				$register_data = array(
					'ex_name'   => ucwords($this->input->post("ex_name")),
					'ex_image'   => $upload_dir."".str_replace(' ','_',$file_name),
					'web_url'   => $this->input->post("web_url"),
					'city'   => $this->input->post("city"),
					'state'   => $this->input->post("state"),
					'country'   => $this->input->post("country"),
					'pincode'   => $this->input->post("pincode"),
					'comp_ownership'   => $this->input->post("comp_ownership"),
					'comp_key'   => $this->input->post("comp_key"),
					'comp_is_an'   => $this->input->post("comp_is_an"),
					'status'   => 1,
				);
				$ex_id = $this->exhibitors_model->register_exhibitor($register_data,'exhibitors');
				
				if(!empty($_POST["emp_name"])) {
					for($i = 0; $i < count($_POST["emp_name"]); $i++)
					{    		    
						if(!empty($_POST["emp_name"][$i])) {
		
							$additional_data = [
								'ex_id' => $ex_id,
								'name' => $_POST["emp_name"][$i],
								'designation' => $_POST["emp_designation"][$i],
								'city' => $_POST["emp_city"][$i],
								'state' => $_POST["emp_state"][$i],
								'country' => $_POST["emp_country"][$i],
							];
							$this->exhibitors_model->register_exhibitor($additional_data,'exhibitors_team');
						}
					}
				}

				if(!empty($_POST["title"])) {
					for($i = 0; $i < count($_POST["title"]); $i++)
					{    		    
						if(!empty($_POST["title"][$i])) {
		
							$additional_data = [
								'ex_id' => $ex_id,
								'title' => $_POST["title"][$i],
								'description' => $_POST["description"][$i],
							];
												
							$this->exhibitors_model->register_exhibitor($additional_data,'exhibitors_offering');
						}
					}
				}

				$this->session->set_flashdata('success','Exhibitors Added Successfully');
				redirect('admin/exhibitors','refresh');
			}
		}
	}

	public function edit($id)
	{
		$this->data['page_title'] = 'Edit Exhibitors';		
		
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('ex_name','Exhibitors Name','trim|required');
		$this->form_validation->set_rules('web_url','Website Url','trim|required');
		
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->data['exhibitors_data'] = $this->exhibitors_model->get_exhibitors($id);
			$this->render('admin/exhibitors/edit_exhibitor_view');
		}
		else
		{
			$hidden_image = $this->input->post('hidden_image');
			$upload_dir = './assets/upload/images/exhibitors/';
			$fileUploadError = [];
			if($_FILES['ex_image']['name'] != "" || $_FILES['ex_image']['name'] != null){
				$ext = pathinfo($_FILES['ex_image']['name'], PATHINFO_EXTENSION);
				$file_name=date("dmY").time().'_'.$_FILES['ex_image']['name'];

				$fileUpload = ImageUpload("ex_image",$file_name,$upload_dir);          
				array_push($fileUploadError,$fileUpload);
				if($fileUploadError[0]['status'] !== '0') {
					if($hidden_image !== './assets/upload/images/exhibitors/default.png') {
						if(file_exists($hidden_image)) {
							unlink($hidden_image);
						}
					}
				}
				$filenametoupload = $upload_dir."".$file_name;
			}else{
				$fileUploadError[0] = ['status'=>'1' ,'msg' => 'File Uploaded'];
				$filenametoupload = $hidden_image ? $hidden_image : $upload_dir."".'default.png';
			}

			if($fileUploadError[0]['status'] == '0') {
				$this->session->set_flashdata('error',$fileUploadError[0]['msg']);
				redirect('admin/exhibitors/edit/'.$user_id,'refresh');
			}
			else {
				foreach ($_POST as $key => $value) {
					if($value == null ||$value == ""){
						$register_data[$key] = NULL;
					}else{
						$register_data[$key] = $value;
					}
				}
				// $register_data["ex_name"] = ucwords($this->input->post("ex_name"));
				// $register_data["web_url"] = $this->input->post('web_url');
				$register_data["ex_image"] = str_replace(' ','_',$filenametoupload);
				unset($register_data["hidden_image"],$register_data["submit"]);
				// $register_data["status"] = $this->input->post('status');
				$register_data["updated_on"] = date("Y-m-d H:i:s");

				$this->exhibitors_model->update($id,$register_data);
				$this->session->set_flashdata('success','Exhibitors Updated Successfully');
				redirect('admin/exhibitors','refresh');
			}
		}
	}

	public function delete($id = NULL)
	{
		if(is_null($id))
		{
			$this->session->set_flashdata('error','There\'s no exhibitor to delete');
		}
		else
		{
			$exhi = $this->exhibitors_model->get_exhibitors($id);
			if($exhi['ex_image'] !== './assets/upload/images/exhibitors/default.png') {
				if(file_exists($exhi['ex_image'])) {
					unlink($exhi['ex_image']);
				}
			}
			$this->exhibitors_model->delete_($id);
		}
		redirect('admin/exhibitors','refresh');
	}

	
	function image_upload($input_file_name,$file_name,$path)
    {
        $this->load->library('upload');
        $config['file_name'] =$file_name; 
        $config['upload_path'] =$path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['overwrite'] = false;
        $config['remove_spaces'] = true;
        $config['file_ext_tolower'] = true;
        $this->upload->initialize($config); 
        if ($this->upload->do_upload($input_file_name))
        {
			$this->data['file_upload_error'] = '';
			return true;
        }
		else {
			$this->data['file_upload_error'] = $this->upload->display_errors();
			return false;
		}
    }
	

}