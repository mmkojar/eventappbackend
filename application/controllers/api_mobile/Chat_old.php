<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Chat extends MY_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('chat_model');
		$this->load->helper('notification');
		$this->load->helper('random_string');
		$this->load->library('ion_auth');
		$this->load->model('ion_auth_model');
	}

	public function index(){	
		$todayd = date("M-Y");
		$today = date("Y-m-d H:i:s");
		$start_page = '<--------------------Start_Page  '.$today.'-------------------->';
		$end_page = '<--------------------End_Page  '.$today.'-------------------->';
		$file_today = "get_all_".date("d_m_Y").".txt";
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
				$otp_device["device_imei"] = cleanQueryParameter($headers['Encryptedd']);
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
			$errMsg = "Please Enter the details";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(isset($input['receiver_id']) && !empty($input['receiver_id'])){
			$receiver_id = cleanQueryParameter($input['receiver_id']);
			$receiver = $this->ion_auth->user($receiver_id)->row();
			if(!empty($receiver)){
				$receiver_id = cleanQueryParameter($input['receiver_id']);				
			}else{
				$errorCode = '9';
				$errMsg = "Invalid receiver";
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
			$errMsg = "Please Enter the receiver id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(isset($input['start']) && !empty($input['start'])){
			$start = cleanQueryParameter($input['start']);
		}else{
			$start = "0";
		}
		
		$get_all_chat_history = $this->chat_model->get_all_chat_history($receiver_id, $user_id, $start);
		if(!empty($get_all_chat_history)){
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode \r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			$login["errCode"] = '-1';
			$login["errMsg"] = $get_all_chat_history;
			print(json_encode($login));
		}else{
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode \r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			$login["errCode"] = '2';
			$login["errMsg"]["msg"] = "No Data Found";
			print(json_encode($login));
		}
	}
	
	
	public function send_a_message(){
		$todayd = date("M-Y");
		$today = date("Y-m-d H:i:s");
		$start_page = '<--------------------Start_Page  '.$today.'-------------------->';
		$end_page = '<--------------------End_Page  '.$today.'-------------------->';
		$file_today = "chat_message_read_".date("d_m_Y").".txt";
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
				$otp_device["device_imei"] = cleanQueryParameter($headers['Encryptedd']);
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
			$errMsg = "Please Enter the details";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(isset($input['receiver_id']) && !empty($input['receiver_id'])){
			$receiver_id = cleanQueryParameter($input['receiver_id']);
			$receiver = $this->ion_auth->user($receiver_id)->row();
			$device_noti = $this->chat_model->get_device($receiver_id);
			$device_notify[] = $device_noti["device_notification_id"];
			if(!empty($receiver)){
				$receiver_id = cleanQueryParameter($input['receiver_id']);				
			}else{
				$errorCode = '9';
				$errMsg = "Invalid receiver";
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
			$errMsg = "Please Enter the receiver id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(isset($input['message']) && !empty($input['message'])){
			$message = cleanQueryParameter($input['message']);
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the message";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		$login = $this->chat_model->send_chat_message($receiver_id, $user_id, $message);
		if(!empty($login)){
			$title = "New Message";
			$type = "message";
			if($device_notify){
				sendFCM( $message,$title, $device_notify,$type);
			}
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			$errorCode = '-1';
			$errMsg = "message send successfully";
			setError($errorCode,$errMsg);
		}else{
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode \r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			$errorCode = '9';
			$errMsg = "message sending failed";
			setError($errorCode,$errMsg);
		}
	}
	
	public function send_meeting_request(){
		$todayd = date("M-Y");
		$today = date("Y-m-d H:i:s");
		$start_page = '<--------------------Start_Page  '.$today.'-------------------->';
		$end_page = '<--------------------End_Page  '.$today.'-------------------->';
		$file_today = "send_meeting_request_".date("d_m_Y").".txt";
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
				$otp_device["device_imei"] = cleanQueryParameter($headers['Encryptedd']);
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
				$data["requested_by_name"] = $user->first_name ." ".$user->last_name;
				$data["requested_by_company"] = $user->company;
				$data["requested_by_email"] = $user->email;
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
			$errMsg = "Please Enter the details";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(isset($input['receiver_id']) && !empty($input['receiver_id'])){
			$receiver_id = cleanQueryParameter($input['receiver_id']);
			$receiver = $this->ion_auth->user($receiver_id)->row();
			if(!empty($receiver)){
				$data["requested_to_name"] = $receiver->first_name ." ".$receiver->last_name;
				$data["requested_to_company"] = $receiver->company;
				$data["requested_to_email"] = $receiver->email;
				$receiver_id = cleanQueryParameter($input['receiver_id']);				
			}else{
				$errorCode = '9';
				$errMsg = "Invalid receiver";
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
			$errMsg = "Please Enter the receiver id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		if(!empty($receiver)){
			$this->load->helper('email');
			$html=$this->load->view("email/request_for_meeting_view",$data,true);
			$send = send_request($data["requested_to_email"],$html);
			if($send){
				$fh = fopen($dir_file, 'a');
				$message = 'result in login array.';
				fwrite($fh,"\r\n".$message."\r\n");
				fwrite($fh,"\r\n  ErrCode\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				$errorCode = '-1';
				$errMsg = "meeting request send Successfully";
				setError($errorCode,$errMsg);
			}else{
				$fh = fopen($dir_file, 'a');
				$message = 'result in login array.';
				fwrite($fh,"\r\n".$message."\r\n");
				fwrite($fh,"\r\n  ErrCode\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				$errorCode = '5';
				$errMsg = "meeting request sending failed";
				setError($errorCode,$errMsg);
			}
		}else{
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode \r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			$errorCode = '2';
			$errMsg = "not data found";
			setError($errorCode,$errMsg);
		}
	}
		
}