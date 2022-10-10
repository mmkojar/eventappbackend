<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Sponsor extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('sponsor_model');
		// $this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('imageupload');
		$this->data['current_tab'] = 'sponsor';
	}

    public function index($user_id = null)
	{
		$this->data['dttable_tab'] = 'dt_table';
		$this->data['tbl_name'] = 'sponsor/sponsor_list';
		$this->data['page_title'] = 'Sponsors';
		if(isset($user_id) && !empty($user_id)){
			$this->data['user_id'] = $user_id;		  
		}
		 
	  $this->render('admin/sponsor/list_sponsor_view');
	}
	
	public function sponsor_list()
	{
		$list = $this->sponsor_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$src = base_url()."".str_replace("./", "", $requested->image);
			$no++;
			$row = array();
			$row[] = $requested->id;
			$row[] = $requested->name;
			$row[] = '<img src="'.$src.'" height="100" width="100">';		
			$row[] = anchor($requested->link,$requested->link,'target="_blank"');		
			$row[] = $requested->status == '1' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
			$row[] = date('jS-M-Y',strtotime($requested->created_on));
			$row[] = anchor('admin/sponsor/edit/'.$requested->id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.anchor('admin/sponsor/delete/'.$requested->id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"');
			$i++;
			$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->sponsor_model->count_all(),
			"recordsFiltered" => $this->sponsor_model->count_filtered(),
			"data" => $data,
		);
		
		//output to json format
		echo json_encode($output);
	}

	public function create()
	{
		$this->data['page_title'] = 'Add Sponsor';
	  
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('name','Sponsor Name','trim|required');
		$this->form_validation->set_rules('link','Link','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/sponsor/create_sponsor_view');
		}
		else
		{				
			$upload_dir = './assets/upload/images/sponsor/';
			$fileUploadError = [];
			if($_FILES['sponsor_image']['name'] != "" || $_FILES['sponsor_image']['name'] != null){
				$ext = pathinfo($_FILES['sponsor_image']['name'], PATHINFO_EXTENSION);
				$file_name=date("dmY").time().'_'.$_FILES['sponsor_image']['name'];

				$fileUpload = ImageUpload("sponsor_image",$file_name,$upload_dir);          
				array_push($fileUploadError,$fileUpload);
			}
			else{
				$fileUploadError[0] = ['status'=>'1' ,'msg' => 'File Uploaded'];
				$file_name = "default.png";
			}

			if($fileUploadError[0]['status'] == '0') {
				$this->session->set_flashdata('error',$fileUploadError[0]['msg']);				
				$this->render('admin/sponsor/create_sponsor_view');
			}
			else {
				$register_data["name"] = ucwords($this->input->post('name'));
				$register_data["link"] = $this->input->post('link');
				$register_data["image"] = $upload_dir."".str_replace(' ','_',$file_name);

				$this->sponsor_model->register_sponsor($register_data);
				$this->session->set_flashdata('success','Sponsor Added Successfully');
				redirect('admin/sponsor','refresh');
			}
		}
	}

	public function edit($id)
	{
		$this->data['page_title'] = 'Edit Sponsor';		
		
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('name','Sponsor Name','trim|required');
		$this->form_validation->set_rules('link','Link','trim|required');
		
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->data['sponsor_data'] = $this->sponsor_model->get_sponsor($id);
			$this->render('admin/sponsor/edit_sponsor_view');
		}
		else
		{
			$hidden_image = $this->input->post('hidden_image');
			$upload_dir = './assets/upload/images/sponsor/';
			$fileUploadError = [];
			if($_FILES['sponsor_image']['name'] != "" || $_FILES['sponsor_image']['name'] != null){
				$ext = pathinfo($_FILES['sponsor_image']['name'], PATHINFO_EXTENSION);
				$file_name=date("dmY").time().'_'.$_FILES['sponsor_image']['name'];

				$fileUpload = ImageUpload("sponsor_image",$file_name,$upload_dir);          
				array_push($fileUploadError,$fileUpload);
				if($fileUploadError[0]['status'] !== '0') {
					if($hidden_image !== './assets/upload/images/sponsor/default.png') {
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
				redirect('admin/sponsor/edit/'.$user_id,'refresh');
			}
			else {
				$register_data["name"] = ucwords($this->input->post('name'));
				$register_data["link"] = $this->input->post('link');
				$register_data["image"] = str_replace(' ','_',$filenametoupload);
				$register_data["status"] = $this->input->post('status');
				$register_data["updated_on"] = date("Y-m-d H:i:s");

				$this->sponsor_model->update($id, $register_data);
				$this->session->set_flashdata('success','sponsor Updated Successfully');
				redirect('admin/sponsor','refresh');
			}
		}
	}

	public function delete($id = NULL)
	{
		if(is_null($id))
		{
			$this->session->set_flashdata('error','There\'s no sponsor to delete');
		}
		else
		{
			$sponsor = $this->sponsor_model->get_sponsor($id);
			if($sponsor['image'] !== './assets/upload/images/sponsor/default.png') {
				if(file_exists($sponsor['image'])) {
					unlink($sponsor['image']);
				}
			}
			$this->sponsor_model->delete_($id);
		}
		redirect('admin/sponsor','refresh');
	}		
}