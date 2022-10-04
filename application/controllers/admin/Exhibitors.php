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
		$this->data['page_title'] = 'Create Exhibitors';
	  
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
			$register_data["ex_image_url"] ="";
			$upload_dir = './assets/upload/images/exhibitors/';
			
			if(!empty($_FILES['ex_image'])){
				if($_FILES['ex_image']['name'] != "" || $_FILES['ex_image']['name'] != null){
					$ext = pathinfo($_FILES['ex_image']['name'], PATHINFO_EXTENSION);
					$file_name=date("dmY").time().'_'.$_FILES['ex_image']['name'];
										
					$fileUpload = ImageUpload("ex_image",$file_name,$upload_dir);
					if($fileUpload['status'] == '0') {
						$this->session->set_flashdata('error',$fileUpload['msg']);
						$this->render('admin/exhibitors/create_exhibitor_view');
					}
					else {
						$register_data = array(
							'ex_name'   => $this->input->post("ex_name"),
							'ex_image'   => $upload_dir."".str_replace(' ','_',$file_name),
							'web_url'   => $this->input->post("web_url"),
							'status'   => 1,
						);	
			
						$this->exhibitors_model->register_exhibitor($register_data);
						$this->session->set_flashdata('success','Exhibitors Added Successfully');
						redirect('admin/exhibitors','refresh');
					}							
				}else{
					$register_data["ex_image_url"] = "";
				}
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
			$register_data["ex_image"] ="";
			$upload_dir = './assets/upload/images/exhibitors/';
			
			if(!empty($_FILES['ex_image'])) {
				if($_FILES['ex_image']['name'] != "" || $_FILES['ex_image']['name'] != null){
					$ext = pathinfo($_FILES['ex_image']['name'], PATHINFO_EXTENSION);
					$file_name=date("dmY").time().'_'.$_FILES['ex_image']['name'];
										
					$fileUpload = ImageUpload("ex_image",$file_name,$upload_dir);
					if($fileUpload['status'] == '0') {
						$this->session->set_flashdata('error',$fileUpload['msg']);
						redirect('admin/exhibitors/edit/'.$id,'refresh');
					}
					if(file_exists($this->input->post('hidden_image'))) {
						unlink($this->input->post('hidden_image'));
					}
					$register_data["ex_image"] = $upload_dir."".str_replace(' ','_',$file_name);				
				}else{
					$register_data["ex_image"] = $this->input->post('hidden_image');
				}
			}		

			$register_data["ex_name"] = $this->input->post('ex_name');
			$register_data["web_url"] = $this->input->post('web_url');
			$register_data["status"] = $this->input->post('status');
			$register_data["updated_on"] = date("Y-m-d H:i:s");

			$this->exhibitors_model->update($id,$register_data);
			$this->session->set_flashdata('success','Exhibitors Updated Successfully');
			redirect('admin/exhibitors','refresh');
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
			$user = $this->exhibitors_model->get_exhibitors($id);			
			if(file_exists($user['ex_image'])) {
				unlink($user['ex_image']);
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