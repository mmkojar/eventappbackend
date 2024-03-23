<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

class QR extends MY_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('QR_model');
		$this->load->library('ion_auth');
		$this->load->model('ion_auth_model');
	}

	public function scanned()
	{	
		$headers = getallheaders();
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE);
		
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

		if(isset($input['user_id']) && !empty($input['user_id'])){
			$user_id = cleanQueryParameter($input['user_id']);
			$user = $this->ion_auth->user($user_id)->row();
			if(!empty($user)){
				$user_id = cleanQueryParameter($input['user_id']);				
			}else{
			    $response['status'] = "false";
    			$response['message'] = 'Invalid user';
    			print_r(json_encode($response));
    			die();
			}
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the User Id';
			print_r(json_encode($response));
			die();
		}

		$checkQr = $this->QR_model->qr_code('uniq_id', $input['qr_code_id']);
		if(!$checkQr) {
			$response['status'] = "false";
			$response['message'] = 'No QR Found';
			print_r(json_encode($response));
			die();
		}
		if($checkQr['status'] == '0') {
			$response['status'] = "false";
			$response['message'] = ' QR is Inactive';
			print_r(json_encode($response));
			die();
		}
        
        $entry = '';
		$res = $this->QR_model->check_qr_entries($user_id);
		if(count($res) == 0) {
			$entry = 'In';
		}
		else {
			if($res) {
				if($res[0]['entries'] == 'In') {
					$entry = 'Out';
				}
				else {
					$entry = 'In';
				}
			}
			
		}
		$data = array(
			'qr_code_id' => $input['qr_code_id'],
			'user_id' => $input['user_id'],
			'entries' => $entry,
			'time' => date('h:i:s a'),
			'device' => $input['device'],
			'status' => 1,
			'created_on' => date("Y-m-d H:i:s")
		);

		$scan = $this->QR_model->insert_entries($data);

		if($scan) {
			$response['status'] = "true";
			$response['message'] = 'Data insert successfully';			
			print_r(json_encode($response));
		}
		else {
			$response['status'] = "false";
			$response['message'] = 'Data inserting failed';
			print_r(json_encode($response));
			die();

		}
	    
	}
}