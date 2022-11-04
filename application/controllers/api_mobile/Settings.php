<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

class Settings extends MY_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->model('ion_auth_model');
	}

	public function index($id =FALSE)
	{	
		$headers = getallheaders();
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE); //convert JSON into array
		
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
        
        $data = array();
        $stmt = "SELECT a.key,a.value"
                . " FROM system_settings AS a";
        $query = $this->db->query($stmt);
        if ($query->num_rows()) {
            $data = $query->result_array();
        }
		$pusha['key'] = [];
		$pushb['value'] = [];
		foreach ($data as $key => $val) {
			array_push($pusha['key'], $val['key']);
			array_push($pushb['value'], $val['value']);
		}
		$settings = array_combine($pusha['key'], $pushb['value']);
		
        $json = ['status'=>'true', 'message'=>'Data found','data'=> $settings];
		print_r(json_encode($json));
	    
	}
}