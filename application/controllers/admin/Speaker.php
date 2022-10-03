<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Speaker extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('speaker_model');
		// $this->load->helper('email');
		$this->load->helper(array('form', 'url'));
		$this->data['current_tab'] = 'speaker';
	}

    public function index($user_id = null)
	{
		$this->data['dttable_tab'] = 'dt_table';
		$this->data['tbl_name'] = 'speaker/speaker_list';
		$this->data['page_title'] = 'Speakers';
		if(isset($user_id) && !empty($user_id)){
			$this->data['user_id'] = $user_id;		  
		}
		 
	  $this->render('admin/speaker/list_speaker_view');
	}
	
	public function speaker_list()
	{
		$list = $this->speaker_model->get_datatables();
		
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
			$row[] = $requested->company_name;
			$row[] = $requested->designation;
			$row[] = $requested->status == '1' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
			$row[] = date('jS-M-Y',strtotime($requested->created_on));
			$row[] = anchor('admin/speaker/edit/'.$requested->id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.anchor('admin/speaker/delete/'.$requested->id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"');
			$i++;
			$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->speaker_model->count_all(),
			"recordsFiltered" => $this->speaker_model->count_filtered(),
			"data" => $data,
		);
		
		//output to json format
		echo json_encode($output);
	}

	public function create()
	{
		$this->data['page_title'] = 'Add speaker';
	  
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('name','speaker Name','trim|required');
		$this->form_validation->set_rules('company_name','Company Name','trim|required');
		$this->form_validation->set_rules('designation','Designation','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/speaker/create_speaker_view');
		}
		else
		{
			$register_data["speaker_image_url"] ="";
			$upload_dir = './assets/upload/images/speaker/';
			
			if(!empty($_FILES['speaker_image'])) {
				if($_FILES['speaker_image']['name'] != "" || $_FILES['speaker_image']['name'] != null){
					$ext = pathinfo($_FILES['speaker_image']['name'], PATHINFO_EXTENSION);
					$file_name=date("dmY").time().'_'.$_FILES['speaker_image']['name'];
					
					if(!$this->image_upload("speaker_image",$file_name,$upload_dir)) {
						$file_upload_error = $this->data['file_upload_error'];
						$this->session->set_flashdata('error', $file_upload_error);
						$this->render('admin/speaker/create_speaker_view');
					}
					else {
						$register_data["speaker_image_url"] = $upload_dir."".str_replace(' ','_',$file_name);	
						$register_data["name"] = $this->input->post('name');
						$register_data["company_name"] = $this->input->post('company_name');
						$register_data["designation"] = $this->input->post('designation');

						$this->speaker_model->register_speaker($register_data);
						$this->session->set_flashdata('success','speaker Added Successfully');
						redirect('admin/speaker','refresh');
					}								
				}else{
					$register_data["speaker_image_url"] = "";
				}
			}			
		}
	}

	public function edit($id)
	{
		$this->data['page_title'] = 'Edit speaker';		
		
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('name','speaker Name','trim|required');
		$this->form_validation->set_rules('company_name','Company Name','trim|required');
		$this->form_validation->set_rules('designation','Designation','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->data['speaker_data'] = $this->speaker_model->get_speaker($id);
			$this->render('admin/speaker/edit_speaker_view');
		}
		else
		{
			$register_data["image"] ="";
			$upload_dir = './assets/upload/images/speaker/';
			
			if(!empty($_FILES['speaker_image'])) {
				if($_FILES['speaker_image']['name'] != "" || $_FILES['speaker_image']['name'] != null){
					$ext = pathinfo($_FILES['speaker_image']['name'], PATHINFO_EXTENSION);
					$file_name=date("dmY").time().'_'.$_FILES['speaker_image']['name'];
					
					if(!$this->image_upload("speaker_image",$file_name,$upload_dir)) {
						$file_upload_error = $this->data['file_upload_error'];
						$this->session->set_flashdata('error',$file_upload_error);
						redirect('admin/speaker/edit/'.$id,'refresh');
					}
					if(file_exists($this->input->post('hidden_image'))) {
						unlink($this->input->post('hidden_image'));
					}		
					$register_data["image"] = $upload_dir."".str_replace(' ','_',$file_name);
				}else{
					$register_data["image"] = $this->input->post('hidden_image');
				}
			}
			
			$register_data["name"] = $this->input->post('name');
			$register_data["company_name"] = $this->input->post('company_name');
			$register_data["designation"] = $this->input->post('designation');		
			$register_data["status"] = $this->input->post('status');
			$register_data["updated_on"] = date("Y-m-d H:i:s");

			$this->speaker_model->update($id, $register_data);
			$this->session->set_flashdata('success','speaker Updated Successfully');
			redirect('admin/speaker','refresh');
		}
	}

	public function delete($id = NULL)
	{
		if(is_null($id))
		{
			$this->session->set_flashdata('error','There\'s no speaker to delete');
		}
		else
		{
			$user = $this->speaker_model->get_speaker($id);			
			if(file_exists($user['image'])) {
				unlink($user['image']);
			}
			$this->speaker_model->delete_($id);
		}
		redirect('admin/speaker','refresh');
	}

	
	public function image_upload($input_file_name,$file_name,$path)
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