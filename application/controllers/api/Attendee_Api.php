<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Attendee_Api extends Public_Controller
{
 
  function __construct()
  {
    parent::__construct();
	$this->load->model('attendee_model');
	$this->load->model('requested_model');
	$this->load->library('ion_auth');
	$this->load->library('PayPal');
	$this->load->helper('random_string');
	$this->load->helper('email');
	$this->load->helper('ipg');
  }
 
	public function create()
	{
		$this->load->helper('url');
		$mrai_url =  $this->config->item('mrai_url');
		$webbase_url =  $this->config->item('webbase_url');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		$todayd = date("M-Y");
		$today = date("Y-m-d H:i:s");
		$start_page = '<--------------------Start_Page  '.$today.'-------------------->';
		$end_page = '<--------------------End_Page  '.$today.'-------------------->';
		$file_today = "create_attendee_".date("d_m_Y").".txt";
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
		if(!empty($_POST)) 
		{
			// get all post data in one nice array
			foreach ($_POST as $key => $value) 
			{
				if($value == null ||$value == ""){
					$register_data[$key] = "";
				}else if($key == "attendee_interested"){
					if(!empty($value)){
						$register_data[$key] = implode(', ',$value);				
					}else{
						$register_data[$key] = "";
					}
				}else if($key == "attendee_business_type"){
					if(!empty($value)){
					$register_data[$key] = implode(', ',$value);				
					}else{
						$register_data[$key] = "";
					}
				}else if($key == "others"){
					if(!empty($value)){
						if(empty($register_data["attendee_interested"])){
							$register_data["attendee_interested"] = $value;
						}else{
							$register_data["attendee_interested"] = $register_data["attendee_interested"] .", ". $value;
						}
					}
				}else{
					$register_data[$key] = $value;
				}
			}

			if(!empty($_FILES)){
				
			foreach ($_FILES as $key => $value) 
			{
				$upload_dir = './assets/upload/images/attendee/';
				$upload_dir1 = './assets/upload/images/attendee_spouse/';
				$upload_dir2 = './assets/upload/images/youngturk/';
				if (!is_dir($upload_dir)) {
					mkdir($upload_dir);
				}	
				if (!is_dir($upload_dir1)) {
					mkdir($upload_dir1);
				}
				if (!is_dir($upload_dir2)) {
					mkdir($upload_dir2);
				}				
				if($key == "attendee_image"){
					if($_FILES['attendee_image']['name'] != "" || $_FILES['attendee_image']['name'] != null){
						$ext = pathinfo($_FILES['attendee_image']['name'], PATHINFO_EXTENSION);
						$file_name=date("dmY").time().$_FILES['attendee_image']['name'];
						$url = $this->image_upload("attendee_image",$file_name,$upload_dir);
						$register_data["attendee_image_url"] = $upload_dir."".$file_name;						
					}else{
						$returnArr['errCode'] = '2';
						$returnArr['errMsg'] = "unable to upload the file";
						print(json_encode($returnArr));
						die();
					}
				}else if($key == "attendee_spouse_image"){
					if($_FILES['attendee_spouse_image']['name'] != "" || $_FILES['attendee_spouse_image']['name'] != null){
						$ext = pathinfo($_FILES['attendee_spouse_image']['name'], PATHINFO_EXTENSION);
						$file_name=date("dmY").time().$_FILES['attendee_spouse_image']['name'];
						$url = $this->image_upload("attendee_spouse_image",$file_name,$upload_dir1);
						$register_data["attendee_spouse_image_url"] = $upload_dir1."".$file_name;					
					}else{
						$register_data["attendee_spouse_image_url"] = "";
					}
				}else if($key == "image_proof"){
					if($_FILES['image_proof']['name'] != "" || $_FILES['image_proof']['name'] != null){
						$ext = pathinfo($_FILES['image_proof']['name'], PATHINFO_EXTENSION);
						$file_name= date("dmY").time().$_FILES['image_proof']['name'];
						$url = $this->image_upload("image_proof",$file_name,$upload_dir2);
						$register_data["image_proof_url"] = $upload_dir2."".$file_name;					
					}else{
						$register_data["image_proof_url"] = "";
					}
				}
			}
			$register_data["status_attendee"] = "Pending";
			$register_data["attendee_request_id"] = "";
			
			$create = $this->attendee_model->register_attendee($register_data);
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n creating attendee details \r\n");
			fclose($fh);
				if($create){
					if($register_data["registration_fees_type_id"] != 17){
						$url = $webbase_url."/payment_redirect/".$create;
						$returnArr['errCode'] = '-1';
						$returnArr['errMsg'] = $url;
						$fh = fopen($dir_file, 'a');
						fwrite($fh,"\r\n".$returnArr['errMsg']."\r\n");
						$message = $end_page;
						fwrite($fh,"\r\n".$message."\r\n");
						fclose($fh);
					}else{
						$returnArr['errCode'] = '17';
						$returnArr['errMsg'] = "User Registered Successfully But Payment is pending";
						
						$attendee_Data =  $this->requested_model->get_attendees($create);
						$email = $attendee_Data["email"];
						$var["name"] = $attendee_Data["first_name"]." ".$attendee_Data["family_name"];
						
						$html=$this->load->view("email/user_off_template_view",$var,true);
						
						$send_mail = send_mail($email,$html);
						
						$var["name"] = $attendee_Data["first_name"]." ".$attendee_Data["family_name"];
						$var["company_name"] = $attendee_Data["company_name"];
						$email = "";
						
						$html=$this->load->view("email/admin_template_view",$var,true);
						
						$send_mail1 = send_mail_to_admin($email,$html);
						
						$fh = fopen($dir_file, 'a');
						fwrite($fh,"\r\n".$returnArr['errMsg']."\r\n");
						$message = $end_page;
						fwrite($fh,"\r\n".$message."\r\n");
						fclose($fh);
					}
				}else{
					$returnArr['errCode'] = '2';
					$returnArr['errMsg'] = "Unsuccessfully";
					$fh = fopen($dir_file, 'a');
					fwrite($fh,"\r\n".$returnArr['errMsg']."\r\n");
					$message = $end_page;
					fwrite($fh,"\r\n".$message."\r\n");
					fclose($fh);
				}
				
			}else{
				$returnArr['errCode'] = '2';
				$returnArr['errMsg'] = "Files not available";
				$fh = fopen($dir_file, 'a');
				fwrite($fh,"\r\n".$returnArr['errMsg']."\r\n");
				$message = $end_page;
				fwrite($fh,"\r\n".$message."\r\n");
				fclose($fh);
			}
		
		}else{
			$returnArr['errCode'] = '2';
			$returnArr['errMsg'] = "details not filled";
			$fh = fopen($dir_file, 'a');
			fwrite($fh,"\r\n".$returnArr['errMsg']."\r\n");
			$message = $end_page;
			fwrite($fh,"\r\n".$message."\r\n");
			fclose($fh);
		}
		print(json_encode($returnArr));
	
	}
	
	
	public function payment_redirect($create_id = NULL){
		$this->load->helper('url');
		$mrai_url =  $this->config->item('mrai_url');
		$webbase_url =  $this->config->item('webbase_url');
		
		$create_id = $this->input->post('create_id') ? $this->input->post('create_id') : $create_id;
		
		$user_main = $this->attendee_model->get_attendees_with_member_by_id($create_id);
		if(!empty($user_main)){
			
			if($user_main["currency"] == "USD"){
				$paypal = new DPayPal(); //Create an object
				//Now we will call SetExpressCheckout API operation. All available parameters for this method are available here https://developer.paypal.com/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/
				$requestParams = array(
					'RETURNURL' => $webbase_url."/payment_paypal_success", //Enter your webiste URL here
					'CANCELURL' => $webbase_url."/payment_paypal_failed"//Enter your website URL here
				);
				
				$orderParams = array(
					'LOGOIMG' => "http://mrai.org.in/site/assets/files/1017/logo.png", //You can paste here your logo image URL
					"MAXAMT" => "5000", //Set max transaction amount
					"NOSHIPPING" => "1", //I do not want shipping
					"ALLOWNOTE" => "0", //I do not want to allow notes
					"BRANDNAME" => "MRAI",
					"SOLUTIONTYPE" => "Sole",
					"GIFTRECEIPTENABLE" => "0",
					"GIFTMESSAGEENABLE" => "0"
				);
				
				$amount = $user_main["registration_fees"] + $user_main["taxes"]+$user_main["transaction_fee"];
				$name = $user_main["company_type"] + $user_main["delegate_type"] + $user_main["member_type"];
				
				$item = array(
					'PAYMENTREQUEST_0_AMT' => $amount,
					'PAYMENTREQUEST_0_CURRENCYCODE' => 'USD',
					'PAYMENTREQUEST_0_ITEMAMT' => $amount,
					'L_PAYMENTREQUEST_0_NAME0' => "MRAI Membership fees",
					'L_PAYMENTREQUEST_0_DESC0' => $name,
					'L_PAYMENTREQUEST_0_AMT0' => $amount,
					'L_PAYMENTREQUEST_0_QTY0' => '1',
					"PAYMENTREQUEST_0_INVNUM" => $create_id //- This field is useful if you want to send your internal transaction ID
				);
				
				//Now you will be redirected to the PayPal to enter your customer data
				
				//After that, you will be returned to the RETURN URL 
				$response = $paypal->SetExpressCheckout($requestParams + $orderParams + $item);
				if (is_array($response) && $response['ACK'] == 'Success') { //Request successful
					//Now we have to redirect user to the PayPal
					$token = $response['TOKEN'];

					header('Location: https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . urlencode($token));
				} else if (is_array($response) && $response['ACK'] == 'Failure') {
					redirect($mrai_url."/register#registerUnsuccessful",true);
					//var_dump($response);
					exit;
				}
				
			}else{
				// $this->data['payment_gateway']["responseSuccessURL"] =  $webbase_url."/payment_success"; //Need to change as per location of response page
				$this->data['payment_gateway']["responseSuccessURL"] =  "http://console.mrai.org.in/api/Attendee_Api/payment_success";
				//$this->data['payment_gateway']["responseFailURL"] = $webbase_url."/payment_failed";//Need to change as per location of response page
				$this->data['payment_gateway']["responseFailURL"] = "http://console.mrai.org.in/api/Attendee_Api/payment_failed";
				$amount = $user_main["registration_fees"] + $user_main["taxes"] + $user_main["transaction_fee"];
				$this->data['payment_gateway']["chargetotal"] = $amount;
				// $this->data['payment_gateway']["chargetotal"] = "1";
				$this->data['payment_gateway']["txntype"] = "sale";
				$this->data['payment_gateway']["currency"] = "INR";
				$this->data['payment_gateway']["mode"] = "payonly";
				$this->data['payment_gateway']["storename"] = "3396034392";
				$this->data['payment_gateway']["sharedsecret"] = "TexiNH?9HmJ:D";
				$this->data['payment_gateway']["oid"] = $user_main["attendee_id"];
				
				$this->render('admin/payment/payment_view');
			}
		}else{
			redirect($mrai_url."/register#registerUnsuccessful",true);
		}
	}
	
	
	public function payment_success()
	{
		$this->load->helper('url');
		$mrai_url =  $this->config->item('mrai_url');
		
		if(!empty($_POST)) 
		{
			// get all post data in one nice array
			foreach ($_POST as $key => $value) 
			{
				if($value == null ||$value == "")
					$register_data[$key] = "";
				else
					$register_data[$key] = $value;
			}
			$register_data["fail_reason"] = "";
			
			$attendee_Data =  $this->requested_model->get_attendees($register_data["oid"]);
			$email = $attendee_Data["email"];
			$var["name"] = $attendee_Data["first_name"]." ".$attendee_Data["family_name"];
			$this->load->helper('email');
			$html=$this->load->view("email/payment_template_view",$var,true);
			$send_mail = send_mail($email,$html);
			$this->load->helper('email');
			$email = "";
			$html=$this->load->view("email/payment_template_view",$var,true);
			$send_mail1 = send_mail_to_admin($email,$html);
			
			/* $send_mail = send_mail($register_data["attendee_id"]);
			$send_mail1 = send_mail_to_admin($register_data["attendee_id"]); */
			
			$registration = $this->attendee_model->register_attendee_transaction($register_data);
			
			redirect($mrai_url."/register#thankYouSuccessful",true);
		}else{
			redirect($mrai_url."/register#thankYouSuccessful",true);
		}
		
	}
	
	public function payment_failed()
	{
		$this->load->helper('url');
		$mrai_url =  $this->config->item('mrai_url');
		if(!empty($_POST)) 
		{
			// get all post data in one nice array
			foreach ($_POST as $key => $value) 
			{
				if($value == null ||$value == "")
					$register_data[$key] = "";
				else
					$register_data[$key] = $value;
			}
			$registration = $this->attendee_model->register_attendee_transaction($register_data);
			
			$attendee_Data =  $this->requested_model->get_attendees($register_data["oid"]);
			$email = $attendee_Data["email"];
			$var["name"] = $attendee_Data["first_name"]." ".$attendee_Data["family_name"];
			$var["reason"] = $register_data["fail_reason"];
			$this->load->helper('email');
			$html=$this->load->view("email/payment_rejected_template_view",$var,true);
			$send_mail = send_mail($email,$html);
			$this->load->helper('email');
			$email = "";
			$html=$this->load->view("email/payment_rejected_template_view",$var,true);
			$send_mail1 = send_mail_to_admin($email,$html);
			
			redirect($mrai_url."/register#registerUnsuccessful",true);
		}else{
			redirect($mrai_url."/register#registerUnsuccessful",true);
		}
		
	}
	
	
	public function payment_paypal_success()
	{
		$token=$_GET["token"];
		$paypal = new DPayPal();
		$requestParams = array('TOKEN' => $token);
		
		$response = $paypal->GetExpressCheckoutDetails($requestParams);
		if(in_array('PAYERID',$response)){
			$payerId=$response["PAYERID"];//Payer id returned by paypal			
		}else{
			$payerId= $_GET["PayerID"];;
		}
		//Create request for DoExpressCheckoutPayment
		$requestParams=array(
			"TOKEN"=>$token,
			"PAYERID"=>$payerId,
			"PAYMENTREQUEST_0_AMT"=>$response['AMT'],//Payment amount. This value should be sum of of item values, if there are more items in order
			"PAYMENTREQUEST_0_CURRENCYCODE"=>"USD",//Payment currency
			"PAYMENTREQUEST_0_ITEMAMT"=>$response['AMT']//Item amount
		);
		$transactionResponse=$paypal->DoExpressCheckoutPayment($requestParams);//Execute transaction
		
		$this->load->helper('url');
		$mrai_url =  $this->config->item('mrai_url');		
		
		$register_data["ipgTransactionId"] = $response['TOKEN'];
		$register_data["oid"] = $response['INVNUM'];
		$register_data["chargetotal"] = $response['AMT'];
		if($transactionResponse['PAYMENTINFO_0_ACK'] == "Success" && $transactionResponse['ACK'] == "Success"){				
			$register_data["status"] = "APPROVED";
			$register_data["fail_reason"] = "Transaction Successfully and Payment Confirmed by User";
			$paypal1 = new DPayPal();
			$responsee = $paypal1->GetExpressCheckoutDetails($requestParams);
			$register_data["approval_code"] = $responsee['CHECKOUTSTATUS'];
			//$register_data["approval_code"] = "Payment Action Completed";
		}else{
			$register_data["fail_reason"] = $transactionResponse['L_SHORTMESSAGE0'];
			$register_data["status"] = "FAILED";
			$register_data["approval_code"] = $response['CHECKOUTSTATUS'];
		}
		
		$register_data["txndatetime"] = date("Y-m-d H:i:s");
		$register_data["timezone"] = "";
		$register_data["txntype"] = "sale";
		$register_data["response_hash"] = $response["TOKEN"];
		$check = $this->attendee_model->get_transaction_id($response['INVNUM']);
		if(!empty($check)){
			$registration = $this->attendee_model->update_transaction($response['INVNUM'],$register_data);
		}else{			
			$registration = $this->attendee_model->register_attendee_transaction($register_data);
		}
			
		if(is_array($transactionResponse) && $transactionResponse["ACK"]=="Success"){//Payment was successfull
			//Successful Payment
		
			/* $send_mail = send_mail($register_data["oid"]);
			
			$send_mail1 = send_mail_to_admin($register_data["oid"]); */
			
			$attendee_Data =  $this->requested_model->get_attendees($register_data["oid"]);
			$email = $attendee_Data["email"];
			$var["name"] = $attendee_Data["first_name"]." ".$attendee_Data["family_name"];
			$this->load->helper('email');
			$html=$this->load->view("email/payment_template_view",$var,true);
			$send_mail = send_mail($email,$html);
			$this->load->helper('email');
			$email = "";
			$html=$this->load->view("email/payment_template_view",$var,true);
			$send_mail1 = send_mail_to_admin($email,$html);
		
			redirect($mrai_url."/register#thankYouSuccessful",true);
		}
		else{
			
			$attendee_Data =  $this->requested_model->get_attendees($register_data["oid"]);
			$email = $attendee_Data["email"];
			$var["name"] = $attendee_Data["first_name"]." ".$attendee_Data["family_name"];
			$var["reason"] = $register_data["fail_reason"];
			$this->load->helper('email');
			$html=$this->load->view("email/payment_rejected_template_view",$var,true);
			$send_mail = send_mail($email,$html);
			$this->load->helper('email');
			$email = "";
			$html=$this->load->view("email/payment_rejected_template_view",$var,true);
			$send_mail1 = send_mail_to_admin($email,$html);
			
			redirect($mrai_url."/register#registerUnsuccessful",true);
		}
	}
	
	public function payment_paypal_failed()
	{
		$token=$_GET["token"];
		$paypal = new DPayPal();
		$requestParams = array('TOKEN' => $token);
		
		$response = $paypal->GetExpressCheckoutDetails($requestParams);
		if(in_array('PAYERID',$response)){
			$payerId=$response["PAYERID"];//Payer id returned by paypal			
		}else{
			$payerId= $_GET["PayerID"];;
		}
		
		//Create request for DoExpressCheckoutPayment
		$requestParams=array(
			"TOKEN"=>$token,
			"PAYERID"=>$payerId,
			"PAYMENTREQUEST_0_AMT"=>$response['AMT'],//Payment amount. This value should be sum of of item values, if there are more items in order
			"PAYMENTREQUEST_0_CURRENCYCODE"=>"USD",//Payment currency
			"PAYMENTREQUEST_0_ITEMAMT"=>$response['AMT']//Item amount
		);
		$transactionResponse=$paypal->DoExpressCheckoutPayment($requestParams);//Execute transaction
	
		$this->load->helper('url');
		$mrai_url =  $this->config->item('mrai_url');		
		
		$register_data["ipgTransactionId"] = $response['TOKEN'];
		$register_data["oid"] = $response['INVNUM'];
		$register_data["chargetotal"] = $response['AMT'];
		
		if($transactionResponse['PAYMENTINFO_0_ACK'] == "Success " && $transactionResponse['ACK'] == 'Success '){				
			$register_data["status"] = "APPROVED";
		}else{
			$register_data["status"] = "FAILED";
		}
		
		$register_data["fail_reason"] = $transactionResponse['L_SHORTMESSAGE0'];
		$register_data["approval_code"] = $response['CHECKOUTSTATUS'];
		$register_data["txndatetime"] = date("Y-m-d H:i:s");
		$register_data["timezone"] = "";
		$register_data["txntype"] = "sale";
		$register_data["response_hash"] = $response["TOKEN"];
		
		$check = $this->attendee_model->get_transaction_id($response['INVNUM']);
		if(!empty($check)){
			$registration = $this->attendee_model->update_transaction($response['INVNUM'],$register_data);
		}else{			
			$registration = $this->attendee_model->register_attendee_transaction($register_data);
		}
		
			
		if(is_array($transactionResponse) && $transactionResponse["ACK"]=="Success"){//Payment was successfull
			//Successful Payment
			
			
			/* $send_mail = send_mail($register_data["oid"]);
			
			$send_mail1 = send_mail_to_admin($register_data["oid"]); */
		
			redirect($mrai_url."/register#registerUnsuccessful",true);
		}
		else{
			$attendee_Data =  $this->requested_model->get_attendees($register_data["oid"]);
			$email = $attendee_Data["email"];
			$var["name"] = $attendee_Data["first_name"]." ".$attendee_Data["family_name"];
			$var["reason"] = $register_data["fail_reason"];
			$this->load->helper('email');
			$html=$this->load->view("email/payment_rejected_template_view",$var,true);
			$send_mail = send_mail($email,$html);
			$this->load->helper('email');
			$email = "";
			$html=$this->load->view("email/payment_rejected_template_view",$var,true);
			$send_mail1 = send_mail_to_admin($email,$html);
			
			redirect($mrai_url."/register#registerUnsuccessful",true);
		}
	}
	
	public function membership_check()
	 {
		if(!empty($_POST["membership_id"])) 
		{
			$membership = $_POST["membership_id"];
			$check = $this->attendee_model->checkmembership($membership);
			if(empty($check["membership_id"]))
			{
				$returnArr['errCode'] = '2';
				$returnArr['errMsg'] = "Is Not member to MRAI";
			}
			else
			{
					$count = 0;
				if($check["member_count"] > 0){
					$checkatt = $this->attendee_model->checkattendee($membership);
					foreach($checkatt as $key => $value){
						if($value["status_attendee"] == "Approve"){
							 $count++;
							if ($count == "2") {
								break;
							}
						}
					}
					if($count <= "2"){
							$check["msg"] = "Is a member to MRAI";
							$check["count"] = $count;
							$returnArr['errCode'] = '-1';
							$returnArr['errMsg'] = $check; 
						} else{
							$check["msg"] = "You are a MRAI member But registration is subject to approval";	
							//$check["msg"] = "Is a member to MRAI";
							$check["count"] = $count;
							$returnArr['errCode'] = '-1';
							$returnArr['errMsg'] = $check; 
						}
				}else{
					$check["msg"] = "Is a member to MRAI";
					$check["count"] = $count;
					$returnArr['errCode'] = '-1';
					$returnArr['errMsg'] = $check; 
				}
			}
		}else{
			$returnArr['errCode'] = '2';
			$returnArr['errMsg'] = "Unsuccessfully";
		}
		print(json_encode($returnArr));
	}
	
	public function checkvalidation()
	 {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters(' ', ' ');
		
		if((!empty($_POST["member_email"])) && (!empty($_POST["type"]))) 
		{
			$member_email = $_POST["member_email"];
			$type = $_POST["type"];
			$this->form_validation->set_data(array(
				'member_email'    =>  $member_email
			));
			if($type == "phone"){
				$this->form_validation->set_rules('member_email','member email','is_unique[attendee.mobile]');
			}else if($type == "email"){
				$this->form_validation->set_rules('member_email','member email','is_unique[attendee.email]');
			}
			
			if($this->form_validation->run()===FALSE)
			{
				$returnArr['errCode'] = '2';
				$returnArr['errMsg'] = validation_errors();
			}
			else
			{
				$returnArr['errCode'] = '-1';
				$returnArr['errMsg'] = "Is a member to MRAI";
			}
		}else{
			$returnArr['errCode'] = '2';
			$returnArr['errMsg'] = "Unsuccessfully";
		}
		print(json_encode($returnArr));
	}
	
	
	 function image_upload($input_file_name,$file_name,$path)
    {
        $this->load->library('upload');
        $config['file_name'] =$file_name; 
        $config['upload_path'] =$path;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['overwrite'] = false;
        $config['remove_spaces'] = true;
        $this->upload->initialize($config); 
        if (!$this->upload->do_upload($input_file_name))
        {
			return false;
        }
    }
	
}