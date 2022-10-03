<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

class Agenda extends MY_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('Agenda_model');
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
        
        if($id) {
		    $get_agenda = $this->Agenda_model->get_agenda_api($id);
			print(json_encode($get_agenda));
		}
		else {		    
		    $get_agenda = $this->Agenda_model->get_agenda_api();
    		print(json_encode($get_agenda));
		}
	    
	}
}