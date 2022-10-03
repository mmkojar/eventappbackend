<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

 
class Membership extends Admin_Controller
{
 
  function __construct()
  {
    parent::__construct();
	$this->load->model('membership_model');
	$this->load->helper('form');
	$this->load->helper('url');
	$this->data['current_tab'] = 'membership';
    if($this->ion_auth->in_group('members') || $this->ion_auth->in_group('security'))
    {
      $this->session->set_flashdata('error','You are not allowed to visit the Device page');
      redirect('admin','refresh');
    }
  }
 
    public function index($user_id = null)
	{
	  $this->data['page_title'] = 'Attendee';
	  if(isset($user_id) && !empty($user_id)){
		  $this->data['user_id'] = $user_id;		  
	  }
		 
	  $this->render('admin/membership/list_membership_view');
	}
	
	public function membership_list()
	{
		$list = $this->membership_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$no++;
			$row = array();
			$row[] = $requested->membership_id;
			$row[] = $requested->company_name;
			$row[] = $requested->membership_number;
			$row[] = date('jS-M-Y',strtotime($requested->created_on));
			$row[] = anchor('admin/membership/edit/'.$requested->membership_id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"')." ".anchor('admin/membership/delete/'.$requested->membership_id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove"');
			$i++;
			$data[] = $row;
		}
		
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->membership_model->count_all(),
				"recordsFiltered" => $this->membership_model->count_filtered(),
				"data" => $data,
			);
		
		//output to json format
		echo json_encode($output);
	}
	
	public function create()
	{
		$this->data['page_title'] = 'Create membership';
	  
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('company_name','Company Name','trim|required|is_unique[membership.company_name]');
		$this->form_validation->set_rules('membership_number','membership_number','trim|required|alpha_numeric|is_unique[membership.membership_number]');
	 
		if($this->form_validation->run()===FALSE)
		{
			$this->load->helper('form');
			$this->render('admin/membership/create_membership_view');
		}
		else
		{
		  
			$register_data["company_name"] = $this->input->post('company_name');
			$register_data["membership_number"] = $this->input->post('membership_number');
		
			$this->membership_model->register_membership($register_data);
			$this->session->set_flashdata('success','Membership Added Successfully');
			redirect('admin/membership','refresh');
		}
	}
	
	 
public function edit($membership_id = NULL)
{
	$membership_id = $this->input->post('membership_id') ? $this->input->post('membership_id') : $membership_id;
	
	$this->data['page_title'] = 'Edit membership';
	
	$this->load->library('form_validation');
	$membership_old = $this->membership_model->get_membership_id((int) $membership_id);
	
	if($membership_old["company_name"] == $this->input->post('company_name')){
		$this->form_validation->set_rules('company_name','Company name','trim|required');
	}else{
		$this->form_validation->set_rules('company_name','Company Name','trim|required|is_unique[membership.company_name]');
	}
	
	if($membership_old["membership_number"] == $this->input->post('membership_number')){
		$this->form_validation->set_rules('membership_number','membership number','trim|required');
	}else{
		$this->form_validation->set_rules('membership_number','membership number','trim|required|alpha_numeric|is_unique[membership.membership_number]');
	}
	  
   if($this->form_validation->run()===FALSE)
  {
    if($membership = $this->membership_model->get_membership_id((int) $membership_id))
    {
      $this->data['membership'] = $membership;
    }
    else
    {
      $this->session->set_flashdata('message', 'The membership doesn\'t exist.');
      redirect('admin/membership', 'refresh');
    }
	
    $this->load->helper('form');
    $this->render('admin/membership/edit_membership_view');
  }
  else
  {
	$membership_id = $this->input->post('membership_id');
    $new_data = array(
		'company_name' => $this->input->post('company_name'),
		'membership_number' => $this->input->post('membership_number')
    );

	
    $this->membership_model->update($membership_id, $new_data);

    $this->session->set_flashdata('message');
    redirect('admin/membership','refresh');
  }
}
 
public function delete($membership_id = NULL)
{
  if(is_null($membership_id))
  {
    $this->session->set_flashdata('error','There\'s no member to delete');
  }
  else
  {
	if($this->ion_auth->in_group('admin'))
	{
		 $this->membership_model->delete_membership($membership_id);
		 
		 $this->session->set_flashdata('success','member has been deleted successfully');
	}else{
		$this->session->set_flashdata('message1','A request Has been Sent to Administrator to Delete the following Device.');
	}
   
    //$this->session->set_flashdata('success',$this->ion_auth->messages());
  }
  redirect('admin/membership','refresh');
}

}