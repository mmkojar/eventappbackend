<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Note extends MY_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('note_model');
		$this->load->library('ion_auth');
		$this->load->model('ion_auth_model');
		
	}
	
	public function index()
	{	
		$todayd = date("M-Y");
		$today = date("Y-m-d H:i:s");
		$start_page = '<--------------------Start_Page  '.$today.'-------------------->';
		$end_page = '<--------------------End_Page  '.$today.'-------------------->';
		$file_today = "show_note_".date("d_m_Y").".txt";
		$dir_fil = $_SERVER['DOCUMENT_ROOT'] ."/assets/api/".$todayd;
		 if (!file_exists($dir_fil)) {
			mkdir($dir_fil, 0777, true);
		}  
		$dir_file = $_SERVER['DOCUMENT_ROOT'] ."/assets/api/".$todayd.'/'.$file_today; 
		 if(file_exists($dir_file)){
			 $fh = fopen($dir_file, 'a');
			 fwrite($fh, $start_page. "\r\n");
		}else{
			 $fh = fopen($dir_file, 'w');
			 fwrite($fh, $start_page."\r\n");
		} 
		$headers = getallheaders();
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE); //convert JSON into array
		
		if(array_key_exists('Encryptedd',$headers)){
		
			$fh = fopen($dir_file, 'a');
			$message = 'getting header.'.$headers['Encryptedd'];
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			$fh = fopen($dir_file, 'a');
			$message = 'getting input and converting it to Array from Json.';
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			if(isset($headers['Encryptedd']) && !empty($headers['Encryptedd'])){
				$device_imei = cleanQueryParameter($headers['Encryptedd']);
			}else{
				$errorCode = '3';
				$errMsg = "Please Enter the details";
				setError($errorCode,$errMsg);
				$encrypted = cleanQueryParameter($headers['Encryptedd']);
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$errMsg."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				die();
			}
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the details";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}

		if(isset($input['user_id']) && !empty($input['user_id'])){
			$user_id = cleanQueryParameter($input['user_id']);
			$user = $this->ion_auth->user($user_id)->row();
			if(!empty($user)){
				$user_id = cleanQueryParameter($input['user_id']);				
			}else{
				$errorCode = '9';
				$errMsg = "Invalid user";
				setError($errorCode,$errMsg);
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$errMsg."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				die();	
			}
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the user_id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		$get_note_for_user = $this->note_model->get_note_for_user($user_id);
		if(!empty($get_note_for_user)){
			if($get_note_for_user){
				$login["errCode"] = "-1";
				$login["errMsg"] = $get_note_for_user;
				print(json_encode($login));
				$fh = fopen($dir_file, 'a');
				$message = 'result in login array.';
				fwrite($fh,"\r\n".$message."\r\n");
				fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
			}else{
				$login["errMsg"]["msg"] = "No Data Availabe";
				$login["errCode"] = "2";
				$fh = fopen($dir_file, 'a');
				$message = 'result in login array.';
				fwrite($fh,"\r\n".$message."\r\n");
				fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				print(json_encode($login));	
			}
		}else{
			$login["errMsg"]["msg"] = "No Data Availabe";
			$login["errCode"] = "3";
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			print(json_encode($login));		
			
		}
	}

	public function add_note()
	{	
		$todayd = date("M-Y");
		$today = date("Y-m-d H:i:s");
		$start_page = '<--------------------Start_Page  '.$today.'-------------------->';
		$end_page = '<--------------------End_Page  '.$today.'-------------------->';
		$file_today = "add_note_".date("d_m_Y").".txt";
		$dir_fil = $_SERVER['DOCUMENT_ROOT'] ."/assets/api/".$todayd;
		 if (!file_exists($dir_fil)) {
			mkdir($dir_fil, 0777, true);
		}  
		$dir_file = $_SERVER['DOCUMENT_ROOT'] ."/assets/api/".$todayd.'/'.$file_today; 
		 if(file_exists($dir_file)){
			 $fh = fopen($dir_file, 'a');
			 fwrite($fh, $start_page. "\r\n");
		}else{
			 $fh = fopen($dir_file, 'w');
			 fwrite($fh, $start_page."\r\n");
		} 
		$headers = getallheaders();
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE); //convert JSON into array
		
		if(array_key_exists('Encryptedd',$headers)){
		
			$fh = fopen($dir_file, 'a');
			$message = 'getting header.'.$headers['Encryptedd'];
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			$fh = fopen($dir_file, 'a');
			$message = 'getting input and converting it to Array from Json.';
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			if(isset($headers['Encryptedd']) && !empty($headers['Encryptedd'])){
				$device_imei = cleanQueryParameter($headers['Encryptedd']);
			}else{
				$errorCode = '3';
				$errMsg = "Please Enter the details";
				setError($errorCode,$errMsg);
				$encrypted = cleanQueryParameter($headers['Encryptedd']);
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$errMsg."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				die();
			}
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the details";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(isset($input['user_id']) && !empty($input['user_id'])){
			$user_id = cleanQueryParameter($input['user_id']);
			$user = $this->ion_auth->user($user_id)->row();
			if(!empty($user)){
				$user_id = cleanQueryParameter($input['user_id']);				
			}else{
				$errorCode = '9';
				$errMsg = "Invalid user";
				setError($errorCode,$errMsg);
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$errMsg."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				die();	
			}
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the user_id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(isset($input['note']) && !empty($input['note'])){
			$note = cleanQueryParameter($input['note']);
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the note";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		$add_note = $this->note_model->add_note($user_id,$note);
		
		if($add_note){
			$login["errCode"] = "-1";
			$login["errMsg"] = "Your Note added successfully";
			print(json_encode($login));
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
		}else{
			$login["errMsg"]["msg"] = "fail to add the note";
			$login["errCode"] = "2";
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			print(json_encode($login));	
		}	
	}
	
	public function edit_note()
	{	
		$todayd = date("M-Y");
		$today = date("Y-m-d H:i:s");
		$start_page = '<--------------------Start_Page  '.$today.'-------------------->';
		$end_page = '<--------------------End_Page  '.$today.'-------------------->';
		$file_today = "edit_note_".date("d_m_Y").".txt";
		$dir_fil = $_SERVER['DOCUMENT_ROOT'] ."/assets/api/".$todayd;
		 if (!file_exists($dir_fil)) {
			mkdir($dir_fil, 0777, true);
		}  
		$dir_file = $_SERVER['DOCUMENT_ROOT'] ."/assets/api/".$todayd.'/'.$file_today; 
		 if(file_exists($dir_file)){
			 $fh = fopen($dir_file, 'a');
			 fwrite($fh, $start_page. "\r\n");
		}else{
			 $fh = fopen($dir_file, 'w');
			 fwrite($fh, $start_page."\r\n");
		} 
		$headers = getallheaders();
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE); //convert JSON into array
		
		if(array_key_exists('Encryptedd',$headers)){
		
			$fh = fopen($dir_file, 'a');
			$message = 'getting header.'.$headers['Encryptedd'];
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			$fh = fopen($dir_file, 'a');
			$message = 'getting input and converting it to Array from Json.';
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			if(isset($headers['Encryptedd']) && !empty($headers['Encryptedd'])){
				$device_imei = cleanQueryParameter($headers['Encryptedd']);
			}else{
				$errorCode = '3';
				$errMsg = "Please Enter the details";
				setError($errorCode,$errMsg);
				$encrypted = cleanQueryParameter($headers['Encryptedd']);
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$errMsg."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				die();
			}
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the details";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(isset($input['note']) && !empty($input['note'])){
			$notes["note"] = cleanQueryParameter($input['note']);
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the note";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(isset($input['user_id']) && !empty($input['user_id'])){
			$notes["user_id"] = cleanQueryParameter($input['user_id']);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$notes["user_id"]."\r\n");
			fclose($fh);
			$user = $this->ion_auth->user($notes["user_id"])->row();
			if(!empty($user)){
				$notes["user_id"] = cleanQueryParameter($input['user_id']);				
			}else{
				$errorCode = '9';
				$errMsg = "Invalid user";
				setError($errorCode,$errMsg);
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$errMsg."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				die();	
			}
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the user_id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(isset($input['notes_id']) && !empty($input['notes_id'])){
			$notes["notes_id"] = cleanQueryParameter($input['notes_id']);
			$note = $this->note_model->get_note_id_one($notes["notes_id"]);
			if(!empty($note)){
				$notes["notes_id"] = cleanQueryParameter($input['notes_id']);				
			}else{
				$errorCode = '9';
				$errMsg = "Invalid note";
				setError($errorCode,$errMsg);
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$errMsg."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				die();	
			}
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the user_id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		$notes["updated_on"] = date("Y-m-d H:i:s");
		$update_note = $this->note_model->update_note($notes["notes_id"],$notes);
		
		if($update_note){
			$login["errCode"] = "-1";
			$login["errMsg"] = "note updated successfully";
			print(json_encode($login));
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
		}else{
			$login["errMsg"]["msg"] = "fail to update note";
			$login["errCode"] = "2";
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			print(json_encode($login));	
		}	
	}
}