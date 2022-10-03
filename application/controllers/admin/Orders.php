<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Orders extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('orders_model');
		$this->load->helper('notification');
		$this->data['current_tab'] = 'orders';
		if($this->ion_auth->in_group('members') || $this->ion_auth->in_group('security'))
		{
		  $this->session->set_flashdata('error','You are not allowed to visit the Orders page');
		  redirect('admin','refresh');
		}
	}
 
	public function index()
	{
	  $this->load->helper('form');
	  $this->data['page_title'] = 'Orders';
	  $this->render('user/orders/list_admin_orders_view');  
	}
	
	public function orders_list()
	{
		$list = $this->orders_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $order) {
			$no++;
			$row = array();
			$row[] = $i;
			$row[] = $order->transaction_number;
			$row[] = $order->username;
			$row[] = $order->order_number;
			$row[] = date('jS-M-Y',strtotime($order->order_placed));
			if($order->order_ready == "0000-00-00 00:00:00"){
				$row[] = 'In Process';
			}else{
				$row[] =  date("h:i A jS M Y", strtotime($order->order_ready));
			}
			if($order->order_delivered == "0000-00-00 00:00:00"){
				$row[] =  'In Process';
			}else{
				$row[] =  date("h:i A jS M Y", strtotime($order->order_delivered));
			}
			
			if($order->order_status == 'In Process'){
				$row[] =  '<span class="btn btn-warning btn-mini">'.$order->order_status.'</span>';
			}else if($order->order_status == 'Ready'){
				$row[] =  '<span class="btn btn-info btn-mini">'.$order->order_status.'</span>';
			}else{
				$row[] =  '<span class="btn btn-success btn-mini">'.$order->order_status.'</span>';		
			}
		
			$i++;
			$data[] = $row;
		}
		
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->orders_model->count_all(),
				"recordsFiltered" => $this->orders_model->count_filtered(),
				"data" => $data,
			);
		
		//output to json format
		echo json_encode($output);
	}
	

}