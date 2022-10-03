<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

class Polling extends MY_Controller
{
 
	function __construct()
	{
	    $this->headers();
	    
		parent::__construct();
		$this->load->model('Polling_model');
		$this->load->library('ion_auth');
		$this->load->model('ion_auth_model');
	}

	public function headers() {
		$headers = getallheaders();
		
		if(array_key_exists('encryptedd',$headers)){
		
			if($headers['encryptedd'] !== 'api-token'){
				$response['status'] = "false";
				$response['message'] = 'Please Enter encryptedd value';
				print_r(json_encode($response));
				die();
			}
		}else{
			$response['status'] = "false";
			$response['message'] = 'Please Enter the detail';
			print_r(json_encode($response));
			die();
		}
	}

	public function polls($id=NULL)
	{			
		
        if($id) {
            $polls = $this->Polling_model->get_polls_api($id);
        } else {
            $polls = $this->Polling_model->get_polls_api();
        }
        if($polls) {
	        $response = ['status'=>'true','message'=>'Data found','data'=>$polls];
	    } else {
	        $response = ['status'=>'false','message'=>'No Data found','data'=>[]];
	    }
		print(json_encode($response));

	}
	
	public function checkUserVote() {

	    $pid = isset($_POST['pid']) ? $_POST['pid'] : '';
		$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
		if(!$user_id){
			print(json_encode(['status'=>'false','message'=>'Please enter User id']));
			die();
		}
		if(!$pid){
			print(json_encode(['status'=>'false','message'=>'Please enter poll id']));
			die();
		}
	    $res = $this->Polling_model->check_user_votes($user_id,$pid);
	    
	    if($res) {
			$response = ['status'=>'true','message'=>'Data found','data'=>$res];
	    } else {
			$response = ['status'=>'false','message'=>'No Data found','data'=>[]];
	    }
	    print_r(json_encode($response));
	}

	public function updateVotes() {

		$pid = isset($_POST['pid']) ? $_POST['pid'] : '';
		$paid = isset($_POST['paid']) ? $_POST['paid'] : '';
		$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
		
		if(!$user_id){
		    print(json_encode(['status'=>'false','message'=>'Please enter User id']));
			die();
		}
		if(!$paid){
			print(json_encode(['status'=>'false','message'=>'Please Select your answer']));
			die();
		}
		if(!$pid) {
			print(json_encode(['status'=>'false','message'=>'No Poll Id specified']));
			die();
		}
		$result = $this->Polling_model->vote_result($pid);
	    $query = $this->db->query('SELECT * FROM users where id='.$user_id);
	    $res = $query->row();
	    
	    $query1 = $this->db->query('SELECT * FROM poll_answers where id='.$paid.' && poll_id='.$pid);
	    $res1 = $query1->row();
	    
		if(!$result) {
			$response['status'] = "false";
			$response['message'] = 'No Poll Found';
		}
		else if(!$res){
		    $response['status'] = "false";
			$response['message'] = 'No User Found';
		}
		else if(!$res1){
		    $response['status'] = "false";
			$response['message'] = 'Invalid poll answer Id';
		}
		else {
		    	
			$res = $this->Polling_model->check_user_votes($user_id, $pid);
			
			if(!$res) {
				$user_votes = array(
					'user_id' => $user_id,
					'poll_id' => $pid,
					'poll_answer_id' => $paid,
					'created_at' => date('Y-m-d h:i:s')
				);
				$polls = $this->Polling_model->update_votes($paid,$user_votes);
				$response['status'] = "true";
				$response['message'] = 'Vote Added Successfully';
				// $response['data'] = $this->Polling_model->get_polls_api($pid);
			}
			else {
				$response['status'] = "false";
				$response['message'] = 'Your Already submit this poll';
				// $response['data'] = [];
			}
			
		}

		print(json_encode($response));

	}
}