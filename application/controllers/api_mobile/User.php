<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
class User extends MY_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('api_login_model');
		$this->load->library('ion_auth');
		$this->load->model('ion_auth_model');
	}

    public function headers() {
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
	}
	
	public function get($id =FALSE)
	{	
		$this->headers();
        
        if($id) {
		    $get_users = $this->user_model->get_users($id);
			print(json_encode($get_users));
		}
		else {
		    
		    $get_users = $this->user_model->get_users();
		  
    		print(json_encode($get_users));
		}
	    
	}
	
	public function checktoken($token) {
	    
	    $this->headers();
	    
	    if(!$token) {
	        print_r(json_encode(['status'=>'false','message'=>'enter token']));
	        die();
	    }
	    $token = $this->api_login_model->get_device_token('device_notification_id',$token);
	    if($token) {
	        print_r(json_encode(['status'=>'true','data'=>$token]));
	    }
	    else {
	        print_r(json_encode(['status'=>'false','data'=>[]]));
	    }
	}
	
	public function edit($id=FALSE)
	{	
		$this->headers();
		
		if(isset($id) && !empty($id)){
			$user_id = cleanQueryParameter($id);
			$user = $this->ion_auth->user($user_id)->row();
			if(!empty($user)){
				$user_id = cleanQueryParameter($id);				
			}else{
				$response['status'] = "false";
    			$response['message'] = 'Invalid user';
    			print_r(json_encode($response));
    			die();
			}
		}else{
			$response['status'] = "false";
			$response['message'] = 'Please Enter the user_id';
			print_r(json_encode($response));
			die();
		}
		$get_users = $this->user_model->get_users($id);
// 		print_r($get_users);
		if(isset($input['image']) && !empty($input['image'])){
			if(!empty($get_users) && $get_users != null){
			    if($input['image'] != $get_users['data']['user_image']){
			        $image_base = cleanQueryParameter($input['image']);
					$image = base64_decode($image_base);
					// decoding base64 string value
					$image_name = date("dmY").time();// image name generating with random number with 32 characters
					$filename = $image_name . '.' . 'png';
					//rename file name with random number
					$path = './assets/upload/images/users/';
					if (!is_dir($path)) {
						mkdir($path);
					} 
					$data["user_image"] = $path ."/". $filename;
					$upload = file_put_contents($path . $filename, $image);
					// image is bind and upload to respective folder
					if($upload){
						$data["user_image"] = base_url().'assets/upload/images/users/'.$filename;
					}else{
						$data["user_image"] = "";
					}
			    }else{
					$data["user_image"] = $input['image'];
				}
			}else{
			    $response['status'] = "false";
    			$response['message'] = 'Invalid user id';
    			print_r(json_encode($response));
    			die();	
			}
		}
		else {
		    $data["user_image"] = $get_users['data']['user_image'];
		}
		
		$data['first_name'] = cleanQueryParameter(isset($input['first_name']) ? $input['first_name'] :  $get_users['data']['first_name']);
		$data['last_name'] = cleanQueryParameter(isset($input['last_name']) ? $input['last_name'] :  $get_users['data']['last_name']);
		$data['email'] = cleanQueryParameter(isset($input['email']) ? $input['email'] :  $get_users['data']['email']);
		$data['phone'] = cleanQueryParameter(isset($input['phone']) ? $input['phone'] :  $get_users['data']['phone']);
        $data['city'] = cleanQueryParameter(isset($input['city']) ? $input['city'] :  $get_users['data']['city']);
	
		$update = $this->user_model->update($id,$data);
		
		if($update){
			$response['status'] = "true";
			$response['message'] = 'Profile Updated';
			print_r(json_encode($response));
		}else{
			$response['status'] = "false";
			$response['message'] = 'Fail to Update Profile';
			print_r(json_encode($response));
			die();
		}	
	}
}