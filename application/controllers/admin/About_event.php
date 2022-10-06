<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
 
class About_event extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('about_event_model');
		// $this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('random_string');
		$this->data['current_tab'] = 'about_event';
	}

    public function index($user_id = null)
	{
	  $this->data['dttable_tab'] = 'dt_table';
	  $this->data['tbl_name'] = 'about_event/about_event_list';
	  $this->data['page_title'] = 'About Event';
	  if(isset($user_id) && !empty($user_id)){
		  $this->data['user_id'] = $user_id;		  
	  }
		 	  	  
	  $data = $this->about_event_model->get_aboutEvent();	  
	  if($data > 0){
	  	$this->render('admin/about_event/list_about_view');	
	  }
	  else{
	  	$this->render('admin/about_event/create_about_view');
	  }
	}
		
	public function about_event_list()
	{
		$list = $this->about_event_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$no++;
			$row = array();
			$row[] = $requested->about_id;
			$row[] = $requested->about_heading;			
			$row[] = str_replace(['<p>', '</p>'],'',htmlspecialchars_decode($requested->about_msg));
			$row[] = date('jS-M-Y',strtotime($requested->created_on));							
			$row[] = anchor('admin/about_event/edit/'.$requested->about_id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.anchor('admin/about_event/delete/'.$requested->about_id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"');
			$i++;
			$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->about_event_model->count_all(),
			"recordsFiltered" => $this->about_event_model->count_filtered(),
			"data" => $data,
		);
		
		//output to json format
		echo json_encode($output);
	}

	public function create()
	{
		$this->data['page_title'] = 'Create Session';
	  
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('about_heading','Heading','trim|required');
		$this->form_validation->set_rules('about_msg','Message','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/about_event/create_about_view');
		}
		else
		{
			$register_data["about_heading"] = $this->input->post('about_heading');
			$register_data["about_msg"] = str_replace(['<p>', '</p>'],'',htmlspecialchars_decode($this->input->post('about_msg')));

			$this->about_event_model->register_about_event($register_data);
			$this->session->set_flashdata('success','Message Added Successfully');
			redirect('admin/about_event','refresh');
		}
	}

	public function edit($id)
	{
		$this->data['page_title'] = 'Edit About Messages';		
		
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('about_heading','Heading','trim|required');
		$this->form_validation->set_rules('about_msg','Message','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->data['about_event_data'] = $this->about_event_model->get_aboutEvent($id);
			$this->render('admin/about_event/edit_about_view');
		}
		else
		{
			
			$register_data["about_heading"] = $this->input->post('about_heading');
			$register_data["about_msg"] = str_replace(['<p>', '</p>'],'',htmlspecialchars_decode($this->input->post('about_msg')));


			$this->about_event_model->update($id,$register_data);
			$this->session->set_flashdata('success','Updated Successfully');
			redirect('admin/about_event','refresh');
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
			$this->about_event_model->delete_($id);
		}
		redirect('admin/about_event','refresh');
	}

	public function set_callback($GET,$return_output){
	    if((isset($GET['callback']))&&(trim($GET['callback'])!=""))
	    {
	        echo $GET['callback']."(".json_encode($return_output).")";
	    }
	    else
	    {
	    	echo json_encode(array(
	    		'status' => 'error',
	    		'message' => 'Callback Parameter Not Found'
	    	));
	    }
	    exit;
	}

	public function about_api()
	{			
		$GET = $this->input->get();

		$get = $this->about_event_model->get_aboutEvent();
		if(!empty($get))
		{
			$return_output["Code"]  = '-1';	
			$return_output["Msg"]  =  'Data Get Successfully';	
			$return_output["data"]  =  $get;	
		}
		else
		{
			$return_output["Code"]  = '1';
			$return_output["Msg"]  = 'No Data Found';
		}
		$this->set_callback($GET,$return_output);
	}	

}