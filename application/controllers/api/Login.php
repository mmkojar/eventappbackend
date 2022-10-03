<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
 
class Login extends MY_Controller
{
 
function __construct()
{
	parent::__construct();
	$this->load->model('api_login_model');
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
		
	
		$fh = fopen($dir_file, 'a');
		$message = 'getting input and converting it to Array from Json.';
		fwrite($fh,"\r\n".$message."\r\n");
		fclose($fh);
			
		
		if(isset($_POST['identity']) && !empty($_POST['identity'])){
			$identity = cleanQueryParameter($_POST['identity']);
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
		
		if(isset($_POST['password']) && !empty($_POST['password'])){
			$password = cleanQueryParameter($_POST['password']);
		}else{
			$errorCode = '3';
			$errMsg = "Please Enter the mac id";
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
		
		$login = $this->api_login_model->login($identity, $password);
		if(!empty($login)){
			$fh = fopen($dir_file, 'a');
			$message = 'result in login array.';
			fwrite($fh,"\r\n".$message."\r\n");
			fwrite($fh,"\r\n  ErrCode ".$login['errCode']."\r\n");
			/* fwrite($fh,"\r\n  ErrMsg ".$login['errMsg']."\r\n"); */
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
			print(json_encode($login));
		}
	}	

}