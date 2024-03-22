<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

class Notifications extends MY_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
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
        
        $this->db->select("notifications.*");
		$this->db->from("notifications");
        $query=$this->db->get();
        $rowcount =  $query->num_rows();
        if($rowcount > 0) {
            $response['status'] = "true";
            $response['message'] = 'Data Found';
            $response['data'] = $query->result_array();
            print_r(json_encode($response));
        }
        else {
            $response['status'] = "false";
            $response['message'] = 'No Data Found';
            print_r(json_encode($response));
        }
	}
}