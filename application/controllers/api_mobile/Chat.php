<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
class Chat extends MY_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('chat_model');
		$this->load->model('Api_login_model');
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
		
		if(array_key_exists('encryptedd',$headers)){
		
			$fh = fopen($dir_file, 'a');
			$message = 'getting header.'.$headers['encryptedd'];
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			$fh = fopen($dir_file, 'a');
			$message = 'getting input and converting it to Array from Json.';
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			if($headers['encryptedd'] !== 'api-token'){
				$response['status'] = "false";
				$response['message'] = 'Please Enter encryptedd value';
				print_r(json_encode($response));
				die();
			}
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the details';
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
		
		/*if(isset($input['device_notification_id']) && !empty($input['device_notification_id'])){
			$device_notification_id = cleanQueryParameter($input['device_notification_id']);
			$device = $this->Api_login_model->check_for_register_device($device_notification_id,$user_id);
			if(!empty($device)){
				$device_notification_id = cleanQueryParameter($input['device_notification_id']);				
			}else{
			    $response['status'] = "false";
    			$response['message'] = 'Invalid Device Notification Id';
    			print_r(json_encode($response));
    			die();
			}
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the Device Notification Id';
			print_r(json_encode($response));
			die();
		}*/
		
		/*if(isset($input['receiver_id']) && !empty($input['receiver_id'])){
			$receiver_id = cleanQueryParameter($input['receiver_id']);
			$receiver = $this->ion_auth->user($receiver_id)->row();
			if(!empty($receiver)){
				$receiver_id = cleanQueryParameter($input['receiver_id']);				
			}else{
			    $response['status'] = "false";
    			$response['message'] = 'Invalid receiver';
    			print_r(json_encode($response));
    			die();
			}
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the receiver id';
			print_r(json_encode($response));
			die();
		}*/
		
		if(isset($input['start']) && !empty($input['start'])){
			$start = cleanQueryParameter($input['start']);
		}else{
			$start = "0";
		}
		$receiver_id = isset($input['receiver_id']) ? $input['receiver_id'] : '';
// 		$device = $this->Api_login_model->get_device_token($user_id);
// 		$device_notification_id = $device['device_notification_id'];
		$get_all_chat_history = $this->chat_model->get_all_chat_history($receiver_id, $user_id, $start);
		if(!empty($get_all_chat_history)){
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode \r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			$response['status'] = "true";
			$response['message'] = 'Data Found';
			$response['data'] = $get_all_chat_history;
			print_r(json_encode($response));
			/*$login["errCode"] = '-1';
			$login["errMsg"] = $get_all_chat_history;
			print(json_encode($login));*/
		}else{
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode \r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			$response['status'] = "false";
			$response['message'] = 'No Data Found';
			$response['data'] = [];
			print_r(json_encode($response));
			die();
			/*$login["errCode"] = '2';
			$login["errMsg"]["msg"] = "No Data Found";
			print(json_encode($login));*/
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
		
		if(array_key_exists('encryptedd',$headers)){
		
			$fh = fopen($dir_file, 'a');
			$message = 'getting header.'.$headers['encryptedd'];
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			$fh = fopen($dir_file, 'a');
			$message = 'getting input and converting it to Array from Json.';
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			if($headers['encryptedd'] !== 'api-token'){
				/*$errorCode = '3';
				$errMsg = "Please Enter encryptedd value";
				setError($errorCode,$errMsg);
				die();*/
				$response['status'] = "false";
    			$response['message'] = 'Please Enter encryptedd value';
    			print_r(json_encode($response));
    			die();
			}
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the details';
			print_r(json_encode($response));
			die();
			/*$errorCode = '3';
			$errMsg = "Please Enter the details";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();*/
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
				/*$errorCode = '9';
				$errMsg = "Invalid user";
				setError($errorCode,$errMsg);
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$errMsg."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				die();	*/
			}
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the User Id';
			print_r(json_encode($response));
			die();
			/*$errorCode = '3';
			$errMsg = "Please Enter the User Id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();*/
		}
		
		/*if(isset($input['device_notification_id']) && !empty($input['device_notification_id'])){
			$device_notification_id = cleanQueryParameter($input['device_notification_id']);
			$device = $this->Api_login_model->check_for_register_device($device_notification_id,$user_id);
			if(!empty($device)){
				$device_notification_id = cleanQueryParameter($input['device_notification_id']);				
			}else{
			    $response['status'] = "false";
    			$response['message'] = 'Invalid Device Notification Id';
    			print_r(json_encode($response));
    			die();
			}
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the Device Notification Id';
			print_r(json_encode($response));
			die();
		}*/
		
		if(isset($input['receiver_id']) && !empty($input['receiver_id'])){
			$receiver_id = cleanQueryParameter($input['receiver_id']);
			$receiver = $this->ion_auth->user($receiver_id)->row();
			$device_noti = $this->chat_model->get_device($receiver_id);
			if(!empty($receiver)){
			    $device_notify[] = $device_noti["device_notification_id"];
				$receiver_id = cleanQueryParameter($input['receiver_id']);				
			}else{
			    $response['status'] = "false";
    			$response['message'] = 'Invalid receiver';
    			print_r(json_encode($response));
    			die();
				/*$errorCode = '9';
				$errMsg = "Invalid receiver";
				setError($errorCode,$errMsg);
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$errMsg."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				die();*/
			}
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the receiver Id';
			print_r(json_encode($response));
			die();
			/*$errorCode = '3';
			$errMsg = "Please Enter the receiver id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();*/
		}
		
		if(isset($input['message']) && !empty($input['message'])){
			$message = cleanQueryParameter($input['message']);
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the message';
			print_r(json_encode($response));
			die();
			/*$errorCode = '3';
			$errMsg = "Please Enter the message";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();*/
		}
        // $device = $this->Api_login_model->get_device_token($user_id);
        // $device_notification_id = $device['device_notification_id'];
		$login = $this->chat_model->send_chat_message($receiver_id, $user_id, $message);
		if(!empty($login)){
			$title = $user->first_name ." ". $user->last_name;
			$type = "message";
			$sender_name = $user->first_name ." ". $user->last_name;
			if($device_notify){
				if(($device_noti["devicetype"]) == 'android'){
					sendMessage($message,$title, $device_notify,$type,$user_id, $receiver_id, $sender_name);				
				}else{
					sendPushNotificationToFCMSever($device_notify,$user_id, $receiver_id,$message,$title,$sender_name);	
				}
			}
			/*$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			$errorCode = '-1';
			$errMsg = "message send successfully";
			setError($errorCode,$errMsg);*/
			$response['status'] = "true";
			$response['message'] = 'message send successfully';
			$response['data'] = $this->chat_model->get_all_chat_history($receiver_id, $user_id, 'latest');
			print_r(json_encode($response));
		}else{
			/*$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode \r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			$errorCode = '9';
			$errMsg = "message sending failed";
			setError($errorCode,$errMsg);*/
			$response['status'] = "false";
			$response['message'] = 'message sending failed';
			$response['data'] = [];
			print_r(json_encode($response));
			die();
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
		
		if(array_key_exists('encryptedd',$headers)){
		
			$fh = fopen($dir_file, 'a');
			$message = 'getting header.'.$headers['encryptedd'];
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			$fh = fopen($dir_file, 'a');
			$message = 'getting input and converting it to Array from Json.';
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			if($headers['encryptedd'] !== 'api-token'){
				$errorCode = '3';
				$errMsg = "Please Enter encryptedd value";
				setError($errorCode,$errMsg);
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
				// $data["requested_by_company"] = $user->company;
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
				// $data["requested_to_company"] = $receiver->company;
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
		
		/*if(!empty($receiver)){
			$this->load->helper('email');
			$html=$this->load->view("email/request_for_meeting_view",$data,true);
			$send = send_request($data["requested_to_email"],$html,$data["requested_by_email"],$data["requested_by_name"]);
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
		}*/
	}
	
	public function chat_history(){	
		$todayd = date("M-Y");
		$today = date("Y-m-d H:i:s");
		$start_page = '<--------------------Start_Page  '.$today.'-------------------->';
		$end_page = '<--------------------End_Page  '.$today.'-------------------->';
		$file_today = "chat_history_".date("d_m_Y").".txt";
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
		
		if(array_key_exists('encryptedd',$headers)){
		
			$fh = fopen($dir_file, 'a');
			$message = 'getting header.'.$headers['encryptedd'];
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			$fh = fopen($dir_file, 'a');
			$message = 'getting input and converting it to Array from Json.';
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
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
			/*$errorCode = '3';
			$errMsg = "Please Enter the details";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();*/
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
				/*$errorCode = '9';
				$errMsg = "Invalid user";
				setError($errorCode,$errMsg);
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$errMsg."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				die();*/
			}
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the user_id';
			print_r(json_encode($response));
			die();
			/*$errorCode = '3';
			$errMsg = "Please Enter the details";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();*/
		}
		
		$get_chat_history = $this->chat_model->get_chat_history($user_id);
		if($get_chat_history){
			if(!empty($get_chat_history)){
				/*$fh = fopen($dir_file, 'a');
				$message = 'result in login array.';
				fwrite($fh,"\r\n".$message."\r\n");
				fwrite($fh,"\r\n  ErrCode \r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				$login["errCode"] = '-1';
				$login["errMsg"] = $get_chat_history;
				print(json_encode($login));*/
				$response['status'] = "true";
    			$response['message'] = 'Data Found';
    			$response['data'] = $get_chat_history;
    			print_r(json_encode($response));
			}else{
				/*$fh = fopen($dir_file, 'a');
				$message = 'result in login array.';
				fwrite($fh,"\r\n".$message."\r\n");
				fwrite($fh,"\r\n  ErrCode \r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				$login["errCode"] = '2';
				$login["errMsg"]["msg"] = "No message Found";
				print(json_encode($login));*/
				$response['status'] = "false";
    			$response['message'] = 'No Data Found';
    			$response['data'] = [];
    			print_r(json_encode($response));
    			die();
			}
		}else{
			/*$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode \r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			$login["errCode"] = '2';
			$login["errMsg"]["msg"] = "please try again";
			print(json_encode($login));*/
			
			$response['status'] = "false";
			$response['message'] = 'please try again';
			$response['data'] = [];
			print_r(json_encode($response));
			die();
		}
	}
	
}