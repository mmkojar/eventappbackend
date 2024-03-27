<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Support extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('Support_model');
		// $this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('imageupload');
		$this->data['current_tab'] = 'support';
	}

    public function index($user_id = null)
	{
		$this->data['dttable_tab'] = 'dt_table';
		$this->data['tbl_name'] = 'support/support_list';
		$this->data['page_title'] = 'supports';
		if(isset($user_id) && !empty($user_id)){
			$this->data['user_id'] = $user_id;		  
		}
		 
	  $this->render('admin/support/list_support_view');
	}
	
	public function support_list()
	{
		$list = $this->Support_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$no++;
			$row = array();
			$row[] = $requested->id;
			$row[] = $requested->title;
			$row[] = $requested->name;
			$row[] = $requested->phone;
			// $row[] = anchor($requested->link,$requested->link,'target="_blank"');		
			$row[] = $requested->status == '1' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
			$row[] = date('jS-M-Y',strtotime($requested->created_on));
			$row[] = anchor('admin/support/edit/'.$requested->id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.anchor('admin/support/delete/'.$requested->id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"');
			$i++;
			$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Support_model->count_all(),
			"recordsFiltered" => $this->Support_model->count_filtered(),
			"data" => $data,
		);
		
		//output to json format
		echo json_encode($output);
	}

	public function create()
	{
		$this->data['page_title'] = 'Add support';
	  
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('name','Title','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/support/create_support_view');
		}
		else
		{				
			$register_data["title"] = ucwords($this->input->post('title'));
			$register_data["name"] = ucwords($this->input->post('name'));
			$register_data["phone"] = ucwords($this->input->post('phone'));
			$register_data["status"] = '1';

			$this->Support_model->register_support($register_data);
			$this->session->set_flashdata('success','Data Added Successfully');
			redirect('admin/support','refresh');
		}
	}

	public function edit($id)
	{
		$this->data['page_title'] = 'Edit support';		
		
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('name','Title','trim|required');
		
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->data['result'] = $this->Support_model->get_support($id);
			$this->render('admin/support/edit_support_view');
		}
		else
		{
			$register_data["title"] = ucwords($this->input->post('title'));
			$register_data["name"] = ucwords($this->input->post('name'));
			$register_data["phone"] = ucwords($this->input->post('phone'));
			$register_data["status"] = $this->input->post('status');
			$register_data["updated_on"] = date("Y-m-d H:i:s");

			$this->Support_model->update($id, $register_data);
			$this->session->set_flashdata('success','Data Updated Successfully');
			redirect('admin/support','refresh');
		}
	}

	public function delete($id = NULL)
	{
		if(is_null($id))
		{
			$this->session->set_flashdata('error','There\'s no data to delete');
		}
		else
		{
			$this->Support_model->delete_($id);
		}
		redirect('admin/support','refresh');
	}		
}