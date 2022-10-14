<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
class Login extends MY_Controller
{
 
function __construct()
{
	parent::__construct();
	$this->load->model('api_login_model');
	// $this->load->model('otp_verification_model');
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
		
		if(array_key_exists('encryptedd',$headers)){
		
			$fh = fopen($dir_file, 'a');
			$message = 'getting header.'.$headers['encryptedd'];
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			$fh = fopen($dir_file, 'a');
			$message = 'getting input and converting it to Array from Json.';
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			
			/*if(isset($headers['encryptedd']) && !empty($headers['encryptedd'])){
				$otp_device["device_imei"] = cleanQueryParameter($headers['encryptedd']);
			}else{
				$errorCode = '3';
				$errMsg = "Please Enter the details";
				setError($errorCode,$errMsg);
			}*/
			if($headers['encryptedd'] !== 'api-token'){
				/*$errorCode = '3';
				$errMsg = "Please Enter encryptedd value";
				setError($errorCode,$errMsg);*/
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

		if(isset($input['device_notification_id']) && !empty($input['device_notification_id'])){
			$otp_device["device_notification_id"] = cleanQueryParameter($input['device_notification_id']);
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the device_notification_id';
			print_r(json_encode($response));
			die();
			/*$errorCode = '3';
			$errMsg = "Please Enter the device_notification_id";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();*/
		}
		
		if(isset($input['devicetype']) && !empty($input['devicetype'])){
			$otp_device["devicetype"] = cleanQueryParameter($input['devicetype']);
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the devicetype';
			print_r(json_encode($response));
			die();
			/*$errorCode = '3';
			$errMsg = "Please Enter the devicetype";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();*/
		}

		if(isset($input['identity']) && !empty($input['identity'])){
			$identity = cleanQueryParameter($input['identity']);
		}else{
		    $response['status'] = "false";
			$response['message'] = 'Please Enter the identity';
			print_r(json_encode($response));
			die();
			/*$errorCode = '3';
			$errMsg = "Please Enter the identity";
			setError($errorCode,$errMsg);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$errMsg."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			die();*/
		}
		
		$fh = fopen($dir_file, 'a');
		$message = 'checking the login using Ion auth.';
		fwrite($fh,"\r\n".$message."\r\n");
		fclose($fh);
		
		$login = $this->api_login_model->login_mobile($identity);
		if(!empty($login)){
			if($login["status"] == "true"){
			    // $response['status'] = true;
			    // $response['message'] = 'User Found';
			   // $response['data'] = [$login];
				$otp_device["user_id"] = $login['data']['user_id'];
				$otp_device["status"] = 1;
				$check_device = $this->api_login_model->check_for_user_register_device($otp_device["device_notification_id"],$otp_device["user_id"]);
				
				if(!$check_device){
				    $register_device = $this->api_login_model->register_device($otp_device);
				}
				else {
				    
                    $getdevicetoken = $this->api_login_model->get_device_token('device_notification_id',$otp_device["device_notification_id"]);
                    
                    $upd_device['device_notification_id'] = $otp_device['device_notification_id'];
                    $upd_device['user_id'] = $otp_device['user_id'];
                    $upd_device['devicetype'] = $otp_device['devicetype'];
                    if($getdevicetoken) {
                      if($getdevicetoken['user_id'] !== $otp_device["user_id"]) {
                          $query = $this->db->query('DELETE FROM devices where user_id='.$getdevicetoken['user_id']);
                          if($query) {
                             $checkUserID = $this->api_login_model->get_device_token('user_id',$otp_device["user_id"]);
                             if($checkUserID){
                                $this->api_login_model->update_device($upd_device);
                             }
                             else {
                               $this->api_login_model->register_device($otp_device);
                             }
                          }
                      }
                      else {
                         $this->api_login_model->update_device($upd_device);
                      }
                    }
                    else {
                      $this->api_login_model->update_device($upd_device);
                    }
				   
				}
				
				// $result['success'] = $response;
				// $login['errMsg']["msg"] = "User found";
				print(json_encode($login));
				$fh = fopen($dir_file, 'a');
				$message = 'result in login array.';
				fwrite($fh,"\r\n".$message."\r\n");
				fwrite($fh,"\r\n  ErrCode ".$login['status']."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
			}else{
				$fh = fopen($dir_file, 'a');
				$message = 'result in login array.';
				fwrite($fh,"\r\n".$message."\r\n");
				fwrite($fh,"\r\n  ErrCode ".$login['status']."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
				print(json_encode($login));
			}
		}else{
			$fh = fopen($dir_file, 'a');
			$message = 'login falied.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode ".$login['status']."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			print(json_encode($login));	
		}
	}

	
}