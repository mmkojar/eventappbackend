<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Otp_verification extends MY_Controller
{
 
function __construct()
{
	parent::__construct();
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
		$file_today = "otp_".date("d_m_Y").".txt";
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

		if(isset($input['otp']) && !empty($input['otp'])){
			$otp = cleanQueryParameter($input['otp']);
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the otp";
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
		
		$otp_verify = $this->otp_verification_model->get_otp($otp,$otp_device["device_imei"]);
		if(!empty($otp_verify)){
			if($otp_verify["otp_use"] == "unused"){
				$to_time = time();
				$from_time = strtotime($otp_verify["otp_send_time"]);
				$main_time = round(abs($to_time - $from_time) / 60);
				/* $timenow = time();
				$chktime = strtotime($otp_verify["otp_send_time"]); */
				if($main_time > 30) {
					$otp_verification['errCode'] = "4";
					$otp_verification['errMsg']["msg"] = "OTP has been expired";
				}else {
					$data = array(
						'otp_use' => "used",
						'updated_on' => date("Y-m-d H:i:s")
					);
					$update = $this->otp_verification_model->update($otp_verify["device_otp_id"],$data);
					$otp_verification['errCode'] = "-1";
					$otp_verification['errMsg']["msg"] = "login Successfull.";
				}
			}else{
				$otp_verification['errCode'] = "3";
				$otp_verification['errMsg']["msg"] = "OTP already been used.";
			}
		}else{
			$otp_verification['errCode'] = "2";
			$otp_verification['errMsg']["msg"] = "Invalid OTP";
		}

		$fh = fopen($dir_file, 'a');
		$message = 'result in login array.';
		fwrite($fh,"\r\n".$message."\r\n");
		fwrite($fh,"\r\n  ErrCode ".$otp_verification['errMsg']["msg"]."\r\n");
		$message = $end_page;
		fwrite($fh,"\r\n".$message."\r\n");
		fclose($fh);
		print(json_encode($otp_verification));		
	}
}