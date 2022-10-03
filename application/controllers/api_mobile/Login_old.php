<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Login extends MY_Controller
{
 
function __construct()
{
	parent::__construct();
	$this->load->model('api_login_model');
	$this->load->model('otp_verification_model');
	$this->load->helper('notification');
	$this->load->helper('random_string');
}

public function index()
	{	
		$todayd = date("M-Y");
		$today = date("Y-m-d H:i:s");
		$start_page = '<--------------------Start_Page  '.$today.'-------------------->';
		$end_page = '<--------------------End_Page  '.$today.'-------------------->';
		$file_today = "login_".date("d_m_Y").".txt";
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

		if(isset($input['device_notification_id']) && !empty($input['device_notification_id'])){
			$otp_device["device_notification_id"] = cleanQueryParameter($input['device_notification_id']);
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the device_notification_id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}

		if(isset($input['identity']) && !empty($input['identity'])){
			$identity = cleanQueryParameter($input['identity']);
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the identity";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();
		}
		
		$fh = fopen($dir_file, 'a');
		$message = 'checking the login using Ion auth.';
		fwrite($fh,"\r\n".$message."\r\n");
		fclose($fh);
		
		$login = $this->api_login_model->login_mobile($identity);
		if(!empty($login)){
			if($login["errCode"] == "-1"){
				$oldotp = $this->otp_verification_model->get_old_otp($login["errMsg"]["user_id"],$otp_device["device_imei"]);
				//print_r($oldotp);
				if(!empty($oldotp)){
					$otp_device["email"] = $login["errMsg"]["email"];
					$otp_device["otp"] = $oldotp["otp_code"];
					$otp_device["user_id"] = $login["errMsg"]["user_id"];
					$login["errMsg"]["otp"] = $oldotp["otp_code"];
					$otp_device["status"] = 1;
				}else{
					$otp_device["email"] = $login["errMsg"]["email"];
					$otp_device["otp"] = $login["errMsg"]["otp"];
					$otp_device["user_id"] = $login["errMsg"]["user_id"];
					$otp_device["status"] = 1;
					$register = $this->api_login_model->register_otp_device($otp_device);
				}
				$this->load->helper('email');
				$html=$this->load->view("email/otp_send_view",$otp_device,true);
				$send = send_otp($otp_device["email"],$html);

				$login['errMsg']["msg"] = "user found and otp is send to the mobile number or email";
				print(json_encode($login));
				$fh = fopen($dir_file, 'a');
				$message = 'result in login array.';
				fwrite($fh,"\r\n".$message."\r\n");
				fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
			}else{
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
			$fh = fopen($dir_file, 'a');
			$message = 'login falied.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			print(json_encode($login));	
		}
		
	}
}