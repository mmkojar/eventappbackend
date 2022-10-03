<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Delegates extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('delegates_model');
		// $this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->data['current_tab'] = 'delegates';
	}

    public function index($user_id = null)
	{
	  $this->data['page_title'] = 'Delegates';
	  if(isset($user_id) && !empty($user_id)){
		  $this->data['user_id'] = $user_id;		  
	  }
		 
	  $this->render('admin/delegates/list_delegates_view');
	}
	
	public function delegates_list()
	{
		$list = $this->delegates_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$no++;
			$row = array();
			$row[] = $requested->delegate_id;
			$row[] = $requested->delegate_name;			
			$row[] = $requested->delegate_email;					
			$row[] = $requested->delegate_phone;	
			$row[] = date('jS-M-Y',strtotime($requested->created_on));							
			$row[] = anchor('admin/delegates/edit/'.$requested->delegate_id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.anchor('admin/delegates/delete/'.$requested->delegate_id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove"');
			$i++;
			$data[] = $row;
		}
		
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->delegates_model->count_all(),
				"recordsFiltered" => $this->delegates_model->count_filtered(),
				"data" => $data,
			);
		
		//output to json format
		echo json_encode($output);
	}

	public function create()
	{
		$this->data['page_title'] = 'Create Delegate';
	  
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('delegate_name','Delegate Name','trim|required');
		$this->form_validation->set_rules('delegate_email','Delegate Email','trim|required|valid_email|is_unique[delegates.delegate_email]');
		$this->form_validation->set_rules('delegate_phone','Delegate Phone','trim|required|is_unique[delegates.delegate_phone]|regex_match[/^[0-9]{10}$/]');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/delegates/create_delegates_view');
		}
		else
		{
			// $register_data["agenda_image_url"] ="";
			// $upload_dir = './assets/upload/images/agenda/';
			
			// if(!empty($_FILES['agenda_image'])){
			// 	if($_FILES['agenda_image']['name'] != "" || $_FILES['agenda_image']['name'] != null){
			// 		$ext = pathinfo($_FILES['agenda_image']['name'], PATHINFO_EXTENSION);
			// 		$file_name=date("dmY").time().$_FILES['agenda_image']['name'];
			// 		$this->image_upload("agenda_image",$file_name,$upload_dir);
			// 		$register_data["agenda_image_url"] = $upload_dir."".$file_name;				
			// 	}else{
			// 		$register_data["agenda_image_url"] = "";
			// 	}
			// }		

			$register_data["delegate_name"] = $this->input->post('delegate_name');
			$register_data["delegate_email"] = $this->input->post('delegate_email');
			$register_data["delegate_phone"] = $this->input->post('delegate_phone');

			$this->delegates_model->register_delegate($register_data);
			$this->session->set_flashdata('success','Delegate Added Successfully');
			redirect('admin/delegates','refresh');
		}
	}

	public function edit($id)
	{
		$this->data['page_title'] = 'Edit Delegate';		
		
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('delegate_name','Delegate Name','trim|required');
		$this->form_validation->set_rules('delegate_email','Delegate Email','trim|required|valid_email');
		$this->form_validation->set_rules('delegate_phone','Delegate Phone','trim|required|regex_match[/^[0-9]{10}$/]');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->data['delegate_data'] = $this->delegates_model->get_delegates($id);
			$this->render('admin/delegates/edit_delegates_view');
		}
		else
		{
			/*$register_data["agenda_image"] ="";
			$upload_dir = './assets/upload/images/agenda/';
			
			if(!empty($_FILES['agenda_image'])) {
				if($_FILES['agenda_image']['name'] != "" || $_FILES['agenda_image']['name'] != null){
					$ext = pathinfo($_FILES['agenda_image']['name'], PATHINFO_EXTENSION);
					$file_name=date("dmY").time().$_FILES['agenda_image']['name'];
					$this->image_upload("agenda_image",$file_name,$upload_dir);
					$register_data["agenda_image"] = $upload_dir."".$file_name;				
				}else{
					$register_data["agenda_image"] = $this->input->post('hidden_image');
				}
			}*/

			$register_data["delegate_name"] = $this->input->post('delegate_name');
			$register_data["delegate_email"] = $this->input->post('delegate_email');
			$register_data["delegate_phone"] = $this->input->post('delegate_phone');

			$this->delegates_model->update($id,$register_data);
			$this->session->set_flashdata('success','Delegate Updated Successfully');
			redirect('admin/delegates','refresh');
		}
	}

	public function delete($id = NULL)
	{
		if(is_null($id))
		{
			$this->session->set_flashdata('error','There\'s no agenda to delete');
		}
		else
		{
			$this->delegates_model->delete_($id);
		}
		redirect('admin/delegates','refresh');
	}

	
	/*function image_upload($input_file_name,$file_name,$path)
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
    }*/
	

}