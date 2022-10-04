<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

class Note extends MY_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('note_model');
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

	public function index()
	{	
		$this->headers();
		
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE); //convert JSON into array

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
			$response['message'] = 'Please Enter the User id';
			print_r(json_encode($response));
			die();
		}
		
		$get_note_for_user = $this->note_model->get_note_for_user($user_id);		
		if(!empty($get_note_for_user)){
			$response['status'] = "true";
			$response['message'] = 'Data Found';
			$response['data'] = $get_note_for_user;
			print_r(json_encode($response));
		}
		else {
			$response['status'] = "false";
			$response['message'] = 'No Data Found';
			$response['data']= [];
			print_r(json_encode($response));
			die();
		}		
	}

	public function add()
	{	
		$this->headers();
		
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE); //convert JSON into array

		if(isset($input['user_id']) && !empty($input['user_id'])){
			$user_id = cleanQueryParameter($input['user_id']);
			$user = $this->ion_auth->user($user_id)->row();
			if(!empty($user)){
				$user_id = cleanQueryParameter($input['user_id']);				
			} 
			else{
				$response['status'] = "false";
				$response['message'] = 'Invalid user';
				print_r(json_encode($response));
				die();
			}
		} else{
			$response['status'] = "false";
			$response['message'] = 'Please Enter the User id';
			print_r(json_encode($response));
			die();
		}
		
		if(isset($input['note']) && !empty($input['note'])){
			$note = cleanQueryParameter($input['note']);
		} else{
			$response['status'] = "false";
			$response['message'] = 'Please Enter the note';
			print_r(json_encode($response));
			die();
		}
		
		$add_note = $this->note_model->add_note($user_id,$note);
		
		if($add_note){
			$response['status'] = "true";
			$response['message'] = 'Your Note added successfully';
			$response['data'] = $this->note_model->get_note_for_user($user_id, 'start');
			print_r(json_encode($response));
			
		}else{
			$response['status'] = "false";
			$response['message'] = 'fail to add the note';
			$response['data'] = [];
			print_r(json_encode($response));
			die();
		}
	}
	
	public function edit()
	{	
		$this->headers();
		
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE); //convert JSON into array
		
		if(isset($input['user_id']) && !empty($input['user_id'])){
			$notes["user_id"] = cleanQueryParameter($input['user_id']);
			$user = $this->ion_auth->user($notes["user_id"])->row();
			if(!empty($user)){
				$notes["user_id"] = cleanQueryParameter($input['user_id']);				
			} 
			else {
				$response['status'] = "false";
				$response['message'] = 'Invalid user';
				print_r(json_encode($response));
				die();
			}
		} else{
			$response['status'] = "false";
			$response['message'] = 'Please Enter the User id';
			print_r(json_encode($response));
			die();
		}
		
		if(isset($input['note']) && !empty($input['note'])){
			$notes["note"] = cleanQueryParameter($input['note']);
		} else{
			$response['status'] = "false";
			$response['message'] = 'Please Enter the note';
			print_r(json_encode($response));
			die();
		}		
						
		if(isset($input['notes_id']) && !empty($input['notes_id'])){
			$notes["notes_id"] = cleanQueryParameter($input['notes_id']);
			$note = $this->note_model->get_note_id_one($notes["notes_id"]);
			if(!empty($note)){
				$notes["notes_id"] = cleanQueryParameter($input['notes_id']);				
			}else {
				$response['status'] = "false";
				$response['message'] = 'Invalid note';
				print_r(json_encode($response));
				die();
			}
		}else{
			$response['status'] = "false";
			$response['message'] = 'Please Enter the note id';
			print_r(json_encode($response));
			die();
		}

		$notes["updated_on"] = date("Y-m-d H:i:s");
		$update_note = $this->note_model->update_note($notes["notes_id"],$notes);
		
		if($update_note){

			$response['status'] = "true";
			$response['message'] = 'note updated successfully';
			$response['data'] = $this->note_model->get_note_id_one($notes["notes_id"]);
			print_r(json_encode($response));

		} else{
			$response['status'] = "false";
			$response['message'] = 'fail to update note';
			$response['data'] = [];
			print_r(json_encode($response));
			die();			
		}	
	}

	public function delete() {

		$this->headers();
		
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE); //convert JSON into array
		
		if(isset($input['notes_id']) && !empty($input['notes_id'])){
			$notes["notes_id"] = cleanQueryParameter($input['notes_id']);
			$note = $this->note_model->get_note_id_one($notes["notes_id"]);
			if(!empty($note)){
				$notes["notes_id"] = cleanQueryParameter($input['notes_id']);				
			}else {
				$response['status'] = "false";
				$response['message'] = 'Invalid note';
				print_r(json_encode($response));
				die();
			}
		}else{
			$response['status'] = "false";
			$response['message'] = 'Please Enter the note id';
			print_r(json_encode($response));
			die();
		}

		$delete_note = $this->note_model->delete_($notes["notes_id"]);
		
		if($delete_note){
			$response['status'] = "true";
			$response['message'] = 'note deleted';
			print_r(json_encode($response));

		} else{
			$response['status'] = "false";
			$response['message'] = 'fail to delete note';
			die();			
		}	

	}
}