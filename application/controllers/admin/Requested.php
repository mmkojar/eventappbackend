<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 
class Requested extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('requested_model');
		$this->data['current_tab'] = 'requested';
		if($this->ion_auth->in_group('members') || $this->ion_auth->in_group('security'))
		{
		  $this->session->set_flashdata('error','You are not allowed to visit the Requested page');
		  redirect('admin','refresh');
		}
	}
 
	public function index()
	{
	  $this->load->helper('form');
	  $this->data['page_title'] = 'Requested';
	  if($this->ion_auth->in_group('user')){
		  $this->session->set_flashdata('error','You are not allowed to visit the Requested page');
		  redirect('admin/user/profile','refresh');
	  }else{
		 $this->render('admin/requested/list_requested_view');  
	  }
	}
	
	
	public function requested_list()
	{
		$list = $this->requested_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$no++;
			$row = array();
			$row[] = $requested->attendee_id;
			$row[] = anchor('admin/attendee/attendee_details/'.$requested->attendee_id,$requested->first_name);
			 // $row[] = $requested->email; 
			$row[] = $requested->company_name;
			$row[] = $requested->membership_id;
			$row[] = $requested->chargetotal;
			if($requested->status == "" || $requested->status == null){
				$row[] = "No Status";
			}else{
				$row[] = $requested->status;
			}
			$row[] = anchor('admin/requested/approve/'.$requested->attendee_id,'<span> <span class="btn-label"><i class="fa fa-check"></i></span>  Approve</span>',array('class' => 'btn btn-success btn-fill')).'   '.anchor('admin/requested/disapprove/'.$requested->attendee_id,'<span><span class="btn-label"><i class="fa fa-times"></i></span>  Disapprove</span>',array('class' => 'btn btn-warning btn-fill')).'   '.anchor('admin/requested/delete/'.$requested->attendee_id,'<span><span class="btn-label"><i class="fa fa-times"></i></span>  Delete</span>',array('class' => 'btn btn-danger btn-fill'));
			$row[] = date('jS-M-Y',strtotime($requested->requested_on));
				
			$i++;
			$data[] = $row;
		}
		
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->requested_model->count_all(),
				"recordsFiltered" => $this->requested_model->count_filtered(),
				"data" => $data,
			);
		
		//output to json format
		echo json_encode($output);
	}
	
	public function Approve($attendee_id = null)
	{
		$this->data['page_title'] = 'Requested';
		$this->load->model('requested_model'); 
		$this->load->library('form_validation');
		$this->load->helper('random_string');
		$attendee_id = $attendee_id ? $attendee_id : "0";
		if($attendee_id == "0"){
			
			$this->session->set_flashdata('error','Unable to Update the request');
			redirect('admin/requested');
			
		}else{
			$attendee_Data =  $this->requested_model->get_attendees($attendee_id);
			//print_r($attendee_Data);
			
			$data = array(
				"status_attendee" => "Approve",
				"updated_on" => date("Y-m-d H:i:s")
			);
			
			$username = explode("@",$attendee_Data["email"]);
			
			$username1 = explode(".",$username["1"]);
				
			$this->form_validation->set_data(array(
				'username'    =>  $username[0]."".$username1[0]
			));
			
			$this->form_validation->set_rules('username','username','is_unique[users.username]');
			
			if($this->form_validation->run()===FALSE)
			{
				$this->session->set_flashdata('error','Username already available.');
				redirect('admin/requested');
			}
			else
			{
				
				$update = $this->requested_model->update_requested($attendee_id,$data);
				if($update){
					$attendee_Data["password"] = $uid=gen_string(8,FALSE,'lud');
					$attendee_Data["username"] = $username[0]."".$username1[0];
					$group_ids = "2";
					$additional_data = array(
						'first_name' => $attendee_Data["first_name"],
						'last_name' => $attendee_Data["family_name"],
						'company' => $attendee_Data["company_name"],
						'phone' => $attendee_Data["mobile"]
					);

					$this->ion_auth->register($attendee_Data["username"], $attendee_Data["password"],$attendee_Data["email"], $additional_data, $group_ids);
					
					$var["username"] = $attendee_Data["username"];
					$var["password"] = $attendee_Data["password"];
					$var["email"] = $attendee_Data["email"];
					$var["name"] = $attendee_Data["first_name"]." ".$attendee_Data["family_name"];
					$email = $attendee_Data["email"];
					$this->load->helper('email');
					$html=$this->load->view("email/email_template_view",$var,true);
					$subject = "Event Partication Approval";
					$send = send_mail($email,$html);
					$send = send_mail_to_admin($email,$html,$subject);
					$this->session->set_flashdata('success','you have successfully approved the request');
					redirect('admin/requested');
				}
				
			}
		}
	}
	
	public function disapprove($attendee_id = null)
	{
	  $this->data['page_title'] = 'Requested';
	  $this->load->model('requested_model'); 
	  $attendee_id = $attendee_id ? $attendee_id : "0";
	  if($attendee_id == "0"){
		  $this->session->set_flashdata('error','Unable to Update the request');
		  redirect('admin/requested');
	  }else{
		  $data = array(
				"status_attendee" => "Disapprove",
				"updated_on" => date("Y-m-d H:i:s")
		  );
		  
		  $update = $this->requested_model->update_requested($attendee_id,$data);
		  
		  if($update){
			  
			   $attendee = $this->requested_model->get_attendees($attendee_id);
				$var["email"] = $attendee["email"];
				$var["name"] = $attendee["first_name"]." ".$attendee["family_name"];
				$this->load->helper('email');
				$email = $attendee["email"];
			   $test = $this->db->query("INSERT attendee_disapprove SELECT * FROM attendee where attendee_id = $attendee_id");
			   $this->delete($attendee_id);
			   $this->session->set_flashdata('error','you have successfully disapproved the request');
			 
				$html=$this->load->view("email/rejected_template_view",$var,true);
				$send = send_mail($email,$html);
				redirect('admin/requested');
		  }
	  }
	  
	}
	
	public function delete($attendee_id)
	{
		$de = $this->requested_model->delete_attendee($attendee_id);
		$this->session->set_flashdata('error','Attendee Deleted Successfully');
		redirect('admin/requested');
	}

}