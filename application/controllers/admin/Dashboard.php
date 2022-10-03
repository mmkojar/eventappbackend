<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
 
  function __construct()
  {
    parent::__construct();
	$this->data['current_tab'] = 'dashboard';
	$this->load->model('dashboard_model');
	$this->load->helper('form');
  }
 
	public function index()
	{
		if($this->ion_auth->in_group('members')){
			
			$this->render('admin/dashboard_user_view');
		}else{
			$group_id = '3';
			$this->data['users'] = $this->ion_auth->users($group_id)->result();
			$this->data['total_users'] = $this->dashboard_model->get_total_users();
			$this->data['active_users'] = $this->dashboard_model->get_count('devices');
			$this->data['total_qr'] = $this->dashboard_model->get_count('qr_code');
			$this->data['total_polls'] = $this->dashboard_model->get_count('polls');
			$this->render('admin/dashboard_view');
		}
	}
  
	public function users(){
		$mall_id = $this->input->post('mall_id');
		$group_id = '3';
		$users = $this->ion_auth->users($group_id)->result();
		if(!empty($users)){
			$user_all = array(); 
			foreach($users as $user){
				if($user->mall_id == $mall_id){
					$user_all[$user->id] = $user;
				}
				if($mall_id == 'all'){
					$user_all[$user->id] = $user;
				}
			}	
			if(count($user_all) == 0){
				$returnArr["errCode"] = '3';
				$returnArr["errMsg"] = "No user for this mall.";
			}else{
				$returnArr["errCode"] = "-1";
				$returnArr["errMsg"] = $user_all;				
			}
		}else{
			$returnArr["errCode"] = '2';
			$returnArr["errMsg"] = "No user.";
		}
		print(json_encode($returnArr,true));
	}
	
	
	public function get_for_it()
	{
		$search = $this->input->post('search');
		$users_id = $this->input->post('retailer');
		if(isset($users_id) && !empty($users_id)){
			$users_id = $users_id;
		}else{
			$startdate = "";
		}
		$mall_id = $this->input->post('mall_name');
		if(isset($mall_id) && !empty($mall_id)){
			$mall_id = $mall_id;			
		}else{
			$mall_id = "";
		}
		$startdate = $this->input->post('startdate');
		if(isset($startdate) && !empty($startdate)){
			$startdate = date('Y-m-d H:i:s', strtotime($startdate . ' -1 day'));			
		}else{
			$startdate = "";
		}
		$enddate = $this->input->post('enddate');
		if(isset($enddate) && !empty($enddate)){
			$enddate = date('Y-m-d H:i:s', strtotime($enddate . ' +1 day'));			
		}else{
			$enddate = "";
		}
		if($search == 'yes'){
			$result = $this->dashboard_model->get_query_search($users_id,$mall_id,$startdate,$enddate);
			$resultAll = $this->dashboard_model->get_order_count($search,$users_id,$mall_id,$startdate,$enddate); 
			$returnArr["errMsg"]["all"] = $resultAll;
			$returnArr["errMsg"]["username"] = $resultAll[0]['username'];
		}else{
			$result = $this->dashboard_model->get_query_search($users_id,$mall_id,$startdate,$enddate);
			$resultAll = $this->dashboard_model->get_order_count($search,$users_id,$mall_id,$startdate,$enddate); 
			$returnArr["errMsg"]["all"] = $resultAll;
		}
		$orderDelivered = array();
		
		foreach($result as $key => $resul) {	
			if($resul["order_status"] == "Delivered"){
					$point2 = array("label" => "Week " .$resul["weeks"], "y" => $resul["Count"]);            
					array_push($orderDelivered, $point2);  
				}
		}

		$returnArr["errCode"] = '-1';
		$returnArr["errMsg"]["orderDelivered"] = $orderDelivered;

		echo json_encode($returnArr, JSON_NUMERIC_CHECK);
	}
	
	
	public function get_for_it_one()
	{
		$search = $this->input->post('search');
		
		$user = $this->ion_auth->user()->row();
		$users_id = $user->id;
		$startdate = $this->input->post('startdate');
		if(isset($startdate) && !empty($startdate)){
			$startdate = date('Y-m-d H:i:s', strtotime($startdate . ' -1 day'));			
		}else{
			$startdate = "";
		}
		$enddate = $this->input->post('enddate');
		if(isset($enddate) && !empty($enddate)){
			$enddate = date('Y-m-d H:i:s', strtotime($enddate . ' +1 day'));			
		}else{
			$enddate = "";
		}
		if($search == 'yes'){			
			$result = $this->dashboard_model->get_query_search_one($users_id,$startdate,$enddate);
			$resultAll = $this->dashboard_model->get_order_count_search($users_id,$startdate,$enddate);
			$returnArr["errMsg"]["all"] = $resultAll;
		}else{
			$result = $this->dashboard_model->get_query_search_one($users_id,$startdate,$enddate);	
		}
		$orderDelivered = array();
		
		foreach($result as $key => $resul) {	
			if($resul["order_status"] == "Delivered"){
					$point2 = array("label" => "Week " .$resul["weeks"], "y" => $resul["Count"]);            
					array_push($orderDelivered, $point2);  
				}
		}

		$returnArr["errCode"] = '-1';
		$returnArr["errMsg"]["orderDelivered"] = $orderDelivered;
		
		echo json_encode($returnArr, JSON_NUMERIC_CHECK);
		//print(json_encode($returnArr,true));
	}
}