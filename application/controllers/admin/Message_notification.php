<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 0); 
 
class Message_notification extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('message_notification_model');
		$this->data['current_tab'] = 'message';
		if($this->ion_auth->in_group('members') || $this->ion_auth->in_group('security'))
		{
		  $this->session->set_flashdata('error','You are not allowed to visit the Message Notification page');
		  redirect('admin','refresh');
		}
	}
 
	public function index()
	{
		$this->data['dttable_tab'] = 'dt_table';
		$this->data['tbl_name'] = 'message_notification/orders_list';
		$this->load->helper('form');
		$this->data['page_title'] = 'Message Notification';
		$this->render('admin/message_notification/list_message_notification_view');  
	}
	
	
	public function orders_list()
	{
		$list = $this->message_notification_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $message) {
			$sendbtn = $message->status == '1' ? anchor('admin/message_notification/send_all/'.$message->msg_id,'<i class="fa fa-paper-plane"></i>','class="btn btn-simple btn-success btn-icon remove"') : '';
			$no++;
			$row = array();
			$row[] = $message->msg_id;
			$row[] = $message->title;
			$row[] = $message->message;
			$row[] = $message->type;
			$row[] = ($message->status == '1' ? '<span class="badge badge-success text-white">Active</span>' : '<span class="badge badge-danger text-white">Inactive</span>');
			$row[] = date('jS-M-Y',strtotime($message->created_on));
			$row[] = anchor('admin/message_notification/edit/'.$message->msg_id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"')." ".
					anchor('admin/message_notification/delete/'.$message->msg_id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"')." ".$sendbtn;
			
			$i++;
			$data[] = $row;
		}
		
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->message_notification_model->count_all(),
				"recordsFiltered" => $this->message_notification_model->count_filtered(),
				"data" => $data,
			);
		
		//output to json format
		echo json_encode($output);
	}
	
	public function create()
	{
		$this->data['page_title'] = 'Create Message Notification';
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title','title','trim|required');
		$this->form_validation->set_rules('message','message','trim|required');
		$this->form_validation->set_rules('type','type','trim|required|is_unique[message_notification.type]');
		$this->form_validation->set_rules('status','Status','trim|required');

		if($this->form_validation->run()===FALSE)
		{
			$this->load->helper('form');
			$this->render('admin/message_notification/create_message_notification_view');
		}
		else
		{
			$title = $this->input->post('title');
			$message = $this->input->post('message');
			$type = $this->input->post('type');
			$status = $this->input->post('status');

			$this->message_notification_model->register_message_notification($title, $message, $type,$status);
			
			$this->session->set_flashdata('success','Message Notification Added Successfully');
			redirect('admin/message_notification','refresh');
		}
	}

	public function edit($msg_id = NULL)
	{
		$msg_id = $this->input->post('msg_id') ? $this->input->post('msg_id') : $msg_id;

		$this->data['page_title'] = 'Edit Message Notification';
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title','title','trim|required');
		$this->form_validation->set_rules('message','message','trim|required');
		$this->form_validation->set_rules('type','type','trim|required');
		$this->form_validation->set_rules('status','Status','trim|required');
	
		
		if($this->form_validation->run()===FALSE){
			
			if($message_notification = $this->message_notification_model->get_message_notification((int) $msg_id))
			{
				$this->data['message_notification'] = $message_notification;
			}
			else
			{
				$this->session->set_flashdata('error', 'The Message Notification doesn\'t exist.');
				redirect('admin/devices', 'refresh');
			}
			$this->load->helper('form');
			$this->render('admin/message_notification/edit_message_notification_view');
		
		}else{
			
			$msg_id = $this->input->post('msg_id');
			
			$new_data = array(
				'title' => $this->input->post('title'),
				'message' => $this->input->post('message'),
				'type' => $this->input->post('type'),
				'status' => $this->input->post('status'),
				'updated_on' => date("Y-m-d H:i:s")
			);
			
			$this->message_notification_model->update($msg_id, $new_data);

			redirect('admin/message_notification','refresh');
		}
	}

	public function send_all($msg_id = NULL){
		if(is_null($msg_id))
		{
			$this->session->set_flashdata('error','There\'s no message notification of this type');
		}
		else
		{
			$user = $this->ion_auth->user()->row();
			if($this->ion_auth->in_group('sp-admin') || $this->ion_auth->in_group('admin')){
				$message_notify = $this->message_notification_model->get_message_notification($msg_id);
				$notification_id = $this->message_notification_model->get_all_user_to_notify();
				//print_r($notification_id);
				$_id = array_column($notification_id, 'device_notification_id');
				/*print_r("<pre>");
				print_r($notification_id);
				print_r("</pre>");
				exit;*/
				if(!empty($notification_id) && ($notification_id)){
					$iosIds = array();$androidIds = array();
					foreach($notification_id as $value){
						if(($value["devicetype"]) == 'android'){
							array_push($androidIds,$value['device_notification_id']);
							//sendFCMAndroid( $message_notify["message"], $message_notify["title"],$androidIds,$message_notify["type"]);
						}else{
							array_push($iosIds,$value['device_notification_id']);
                            //sendFCM( $message_notify["message"], $message_notify["title"],$iosIds,$message_notify["type"]);
						}
					}
					sendFCM( $message_notify["message"], $message_notify["title"],$iosIds,$message_notify["type"]);
					sendFCMAndroid( $message_notify["message"], $message_notify["title"],$androidIds,$message_notify["type"]);
					
					$notify['title'] = $message_notify['title'];
					$notify['msg'] = $message_notify['message'];
					$notify['type'] = $message_notify['type'];
					$notify['status'] = '1';

					$this->db->insert('notifications', $notify);

					$this->session->set_flashdata('success', 'send notification to all the users.');
				}else{
					$this->session->set_flashdata('error', 'no user available to send notification.');
				}
			}else{
				$this->session->set_flashdata('error', 'Admin is allowed to send the notification.');
			}
		}
		redirect('admin/message_notification','refresh');
	}
	
	public function delete($msg_id = NULL)
	{
		if(is_null($msg_id))
		{
			$this->session->set_flashdata('error','There\'s no message notification to delete');
		}
		else
		{
			$this->message_notification_model->delete_($msg_id);
		}
		redirect('admin/message_notification','refresh');
	}

}