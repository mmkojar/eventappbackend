<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class FAQ extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('faq_model');
		// $this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->data['current_tab'] = 'faqs';
	}

    public function index($user_id = null)
	{
		$this->data['dttable_tab'] = 'dt_table';
        $this->data['tbl_name'] = 'faq/faqs_list';
        $this->data['page_title'] = 'FAQ';
        if(isset($user_id) && !empty($user_id)){
            $this->data['user_id'] = $user_id;
        }
		 
	  $this->render('admin/faq/list_faq_view');
	}
	
	public function faqs_list()
	{
		$list = $this->faq_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$no++;
			$row = array();
			$row[] = $requested->id;
			$row[] = $requested->title;			
			$row[] = $requested->description;
			$row[] = ($requested->status == '1' ? '<span class="badge badge-success text-white">Active</span>' : '<span class="badge badge-danger text-white">Inactive</span>');
			$row[] = date('jS-M-Y',strtotime($requested->created_on));							
			$row[] = anchor('admin/faq/edit/'.$requested->id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.anchor('admin/faq/delete/'.$requested->id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"');
			$i++;
			$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->faq_model->count_all(),
			"recordsFiltered" => $this->faq_model->count_filtered(),
			"data" => $data,
		);
		
		//output to json format
		echo json_encode($output);
	}

	public function create()
	{
		$this->data['page_title'] = 'Create FAQ';
	  
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('title','Title','trim|required');
		$this->form_validation->set_rules('description','Description','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/faq/create_faq_view');
		}
		else
		{					
			$register_data = [
				'title'   => $this->input->post("title"),
				'description'   => $this->input->post("description"),
			];
			
			$this->faq_model->register_faqs($register_data);			
			$this->session->set_flashdata('success','faq Added Successfully');
			redirect('admin/faq','refresh');
		}
	}

	public function edit($id)
	{
		$this->data['page_title'] = 'Edit FAQ';
		
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('title','Title','trim|required');
		$this->form_validation->set_rules('description','Description','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{			
			$this->load->helper('form');
			$this->data['faq'] = $this->faq_model->get_faqs($id);
			$this->render('admin/faq/edit_faq_view');
		}
		else
		{					
			$register_data = [
				'title'   => $this->input->post("title"),
				'description'   => $this->input->post("description"),		
				'status'   => $this->input->post("status"),
				'updated_on' => date("Y-m-d H:i:s")
			];

			$this->faq_model->update($id,$register_data);
			$this->session->set_flashdata('success','faq Updated Successfully');
			redirect('admin/faq','refresh');
		}
	}

	public function delete($id = NULL)
	{
		if(is_null($id))
		{
			$this->session->set_flashdata('error','There\'s no faqs to delete');
		}
		else
		{
			$this->faq_model->delete_($id);
		}
		redirect('admin/faq','refresh');
	}
}