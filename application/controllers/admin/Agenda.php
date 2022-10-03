<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Agenda extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('agenda_model');
		// $this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->data['current_tab'] = 'agenda';
	}

    public function index($user_id = null)
	{
		$this->data['dttable_tab'] = 'dt_table';
	  $this->data['tbl_name'] = 'agenda/agenda_list';
	  $this->data['page_title'] = 'Event Agenda';
	  if(isset($user_id) && !empty($user_id)){
		  $this->data['user_id'] = $user_id;		  
	  }
		 
	  $this->render('admin/agenda/list_agenda_view');
	}
	
	public function agenda_list()
	{
		$list = $this->agenda_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$no++;
			$row = array();
			$row[] = $requested->agenda_id;
			$row[] = $requested->agenda_name;			
			$row[] = $requested->agenda_date;					
			$row[] = $requested->agenda_time;
			$row[] = $requested->speaker_name;
			$row[] = $requested->agenda_venue;
			$row[] = ($requested->status == '1' ? '<span class="badge badge-success text-white">Active</span>' : '<span class="badge badge-danger text-white">Inactive</span>');
			$row[] = date('jS-M-Y',strtotime($requested->created_on));							
			$row[] = anchor('admin/agenda/edit/'.$requested->agenda_id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.anchor('admin/agenda/delete/'.$requested->agenda_id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"');
			$i++;
			$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->agenda_model->count_all(),
			"recordsFiltered" => $this->agenda_model->count_filtered(),
			"data" => $data,
		);
		
		//output to json format
		echo json_encode($output);
	}

	public function create()
	{
		$this->data['page_title'] = 'Create Session';
	  
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('agenda_name','Agenda Name','trim|required');
		$this->form_validation->set_rules('agenda_date','Agenda Date','trim|required');
		$this->form_validation->set_rules('agenda_time','Agenda Time','trim|required');
		$this->form_validation->set_rules('speaker_name','Sepaker Name','trim|required');
		$this->form_validation->set_rules('agenda_venue','Venue','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/agenda/create_agenda_view');
		}
		else
		{
			
			/* $register_data["agenda_image_url"] ="";
			$upload_dir = './assets/upload/images/agenda/';
			
			if(!empty($_FILES['agenda_image'])){
				if($_FILES['agenda_image']['name'] != "" || $_FILES['agenda_image']['name'] != null){
					$ext = pathinfo($_FILES['agenda_image']['name'], PATHINFO_EXTENSION);
					$file_name=date("dmY").time().$_FILES['agenda_image']['name'];
					$this->image_upload("agenda_image",$file_name,$upload_dir);
					$register_data["agenda_image_url"] = $upload_dir."".$file_name;				
				}else{
					$register_data["agenda_image_url"] = "";
				}
			} */		
			$register_data = [
				'agenda_name'   => $this->input->post("agenda_name"),
				'agenda_date'   => $this->input->post("agenda_date"),
				'agenda_time'   => $this->input->post("agenda_time"),
				'speaker_name'   => $this->input->post("speaker_name"),
				'agenda_venue'   => $this->input->post("agenda_venue"),
				// 'agenda_image'   => $register_data["agenda_image_url"],			
				'status'   => 1,
			];
			
			$this->agenda_model->register_agenda($register_data);			
			$this->session->set_flashdata('success','Agenda Added Successfully');
			redirect('admin/agenda','refresh');
		}
	}

	public function edit($id)
	{
		$this->data['page_title'] = 'Edit Session';		
		
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('agenda_name','Agenda Name','trim|required');
		$this->form_validation->set_rules('agenda_date','Agenda Date','trim|required');
		$this->form_validation->set_rules('agenda_time','Agenda Time','trim|required');
		$this->form_validation->set_rules('speaker_name','Sepaker Name','trim|required');
		$this->form_validation->set_rules('agenda_venue','Venue','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{			
			$this->load->helper('form');
			$this->data['agenda_data'] = $this->agenda_model->get_agenda($id);
			$this->render('admin/agenda/edit_agenda_view');
		}
		else
		{
			/* $register_data["agenda_image"] ="";
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
			} */		
			$register_data = [
				'agenda_name'   => $this->input->post("agenda_name"),
				'agenda_date'   => $this->input->post("agenda_date"),
				'agenda_time'   => $this->input->post("agenda_time"),
				'speaker_name'   => $this->input->post("speaker_name"),
				'agenda_venue'   => $this->input->post("agenda_venue"),
				// 'agenda_image'   => $register_data["agenda_image_url"],			
				'status'   => $this->input->post("status"),
				'updated_on' => date("Y-m-d H:i:s")
			];

			$this->agenda_model->update($id,$register_data);
			$this->session->set_flashdata('success','Agenda Updated Successfully');
			redirect('admin/agenda','refresh');
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
			$this->agenda_model->delete_($id);
		}
		redirect('admin/agenda','refresh');
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