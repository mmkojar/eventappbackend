<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_login_model extends CI_Model
{
	public $identity;

    public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->load->model('ion_auth_model');
		$this->lang->load('ion_auth');
		
		/**
		* Identity
		*
		* @var string
		**/
		
		
		//initialize data
		$this->identity_column = $this->config->item('identity', 'ion_auth');
	}

    public function login_mobile($identity, $remember=FALSE)
	{
		if (empty($identity))
		{
			//$returnArr['errCode'] = '1';
			//$returnArr['errMsg']['msg'] = 'Blank User Name';
			$returnArr['status'] = "false";
			$returnArr['message'] = 'Blank User Name';
			$returnArr['data'] = [];
			return $returnArr;
		}

		$query = $this->db->select($this->identity_column . ',emp_code, first_name, last_name ,email, id, phone, city, user_image, active, last_login')
				  ->where('emp_code', $emp_code)
				//   ->or_where('phone', $identity)
				  ->limit(1)
				  ->order_by('id', 'desc')
				  ->get('users');

		/* if($this->ion_auth_model->is_time_locked_out($identity))
		{
			// Hash something anyway, just to take up time
			$this->ion_auth_model->hash_password($password);
			$returnArr['errCode'] = '2';
			$returnArr['errMsg']['msg'] = 'Session Expires ';
			return $returnArr;
		} */

		if ($query->num_rows() === 1)
		{
			$user = $query->row();
			//$password = $this->ion_auth_model->hash_password_db($user->id, $password);

			/* if ($password === TRUE)
			{ */
				if ($user->active == 0)
				{
					//$returnArr['errCode'] = '7';
					//$returnArr['errMsg']['msg'] = 'User is Not Active';
					$returnArr['status'] = "false";
					$returnArr['message'] = 'User is Not Active';
					$returnArr['data'] = [];
					return $returnArr;
				}else{
					$groups  = $this->ion_auth_model->get_user_groups($user->id);
					// if($groups["name"] != 'admin'){
							$this->ion_auth_model->update_last_login($user->id);
							$this->load->helper('random_string');
							$otp = gen_string(6,'','d');
							$returnArr["otp"] = $otp;
							$returnArr["user_id"] = $user->id;
							$returnArr["identity"] = $identity;
							$returnArr["first_name"] = $user->first_name;
							$returnArr["last_name"] = $user->last_name;
							$returnArr["email"] = $user->email;
							$returnArr["phone"] = $user->phone;
							$returnArr["city"] = $user->city;
							$returnArr["image"] = $user->user_image;
							$returnArr['group'] = $groups["name"];
							$response["status"] = 'true';
			                $response['message'] = 'User Found';
			                $response['data'] = $returnArr;
							//$returnArr['errCode'] = '-1';
							//$returnArr['success'] = $details;
							return $response;	
					/* }else{
						$returnArr['status'] = "false";
						$returnArr['message'] = 'Admin is not allowed';
						$returnArr['data'] = [];
						return $returnArr;
					} */
					
				}
			/* }else{
				$returnArr['errCode'] = '4';
				$returnArr['errMsg']['msg'] = 'Invalid Username Or Password';
				return $returnArr;
			} */
		}else{
			// Hash something anyway, just to take up time
			//$this->ion_auth_model->hash_password($password);
			$this->ion_auth_model->increase_login_attempts($identity);
			$returnArr['status'] = "false";
			$returnArr['message'] = 'No User Found';
			$returnArr['data'] = [];
			/*$returnArr['errCode'] = '3';
			$returnArr['errMsg']['msg'] = 'No User Found';*/
			return $returnArr;
			
		}

	}
	
	public function login($identity, $password, $remember=FALSE)
	{
		if (empty($identity) || empty($password))
		{
			$returnArr['errCode'] = '1';
			$returnArr['errMsg'] = 'Blank User Name And Password';
			return $returnArr;
		}

		$query = $this->db->select($this->identity_column . ', email, id, password, active, last_login')
				  ->where($this->identity_column, $identity)
				  //->where('t_login','No')
				  ->limit(1)
				  ->order_by('id', 'desc')
				  ->get('users');

		if($this->ion_auth_model->is_time_locked_out($identity))
		{
			// Hash something anyway, just to take up time
			$this->ion_auth_model->hash_password($password);
			$returnArr['errCode'] = '2';
			$returnArr['errMsg'] = 'Session Expires ';
			return $returnArr;
		}

		if ($query->num_rows() === 1)
		{
			$user = $query->row();

			$password = $this->ion_auth_model->hash_password_db($user->id, $password);

			if ($password === TRUE)
			{
				if ($user->active == 0)
				{
					$returnArr['errCode'] = '7';
					$returnArr['errMsg'] = 'User is Not Active';
					return $returnArr;
				}else{
					$groups  = $this->ion_auth_model->get_user_groups($user->id);
					if($groups["name"] == 'user'){
							$this->ion_auth_model->update_last_login($user->id);
							$returnArr['errCode'] = '-1';
							$returnArr['errMsg'] = 'Login Successful';
							return $returnArr;	
					}else{
						$returnArr['errCode'] = '5';
						$returnArr['errMsg'] = 'Your user is not allowed';
						return $returnArr;
					}
					
				}
			}else{
				$returnArr['errCode'] = '4';
				$returnArr['errMsg'] = 'Invalid Username Or Password';
				return $returnArr;
			}
		}else{
			// Hash something anyway, just to take up time
			$this->ion_auth_model->hash_password($password);
			$this->ion_auth_model->increase_login_attempts($identity);
			
			$returnArr['errCode'] = '3';
			$returnArr['errMsg'] = 'No User Found';
			return $returnArr;
			
		}

	}
	
	public function update_login_status($user_id, $device_id,$type, array $data)
	{
		
		$groups  = $this->ion_auth_model->get_user_groups($user_id);
		
		if($groups["id"] == '5' || $groups["id"] == '4' || $groups["id"] == '1'){
			
			$this->db->trans_begin();

			$this->db->update("users", $data, array('id' => $user_id));

			if ($this->db->trans_status() === FALSE)
			{
				
				$this->db->trans_rollback();
				return FALSE;
			}
			$this->db->trans_commit();
			
			return TRUE;
			
		}else{
			
			return false;
			
		}
	}	

/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function register_otp_device(array $otp_device)
	{
		// Users table.
		$data = array(
		    'user_id'   => $otp_device["user_id"],
		    'otp_code'   => $otp_device["otp"],
		    'device_notification_id'   => $otp_device["device_notification_id"],
		    'device_imei'      => $otp_device["device_imei"],
		    'status' => $otp_device["status"],
		    'otp_send_time' => date("Y-m-d H:i:s"),
		    'updated_on' => date("Y-m-d H:i:s")
		);

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('device_otp', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}
	

	public function register_device(array $device)
	{
		// Users table.
		$data = array(
		    'user_id'   => $device["user_id"],
		    //'device_imei'   => $device["device_imei"],
		    'device_notification_id'   => $device["device_notification_id"],
		    'devicetype'   => $device["devicetype"],
		    'status' => $device["status"],
		    'updated_on' => date("Y-m-d H:i:s")
		);

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('devices', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}
	
	public function check_for_user_register_device($user_id) {
	    $this->db->select("devices.*");
		$this->db->from("devices");
	    $this->db->or_where('user_id',$user_id);
	    $query=$this->db->get();
	    return $query->row_array();
	}
	
	public function update_device(array $device)
	{
		// Users table.
		$data = array(
		    'device_notification_id'   => $device["device_notification_id"],
		    'devicetype' => $device["devicetype"]
		);

        $this->db->where('user_id',$device['user_id']);
		$this->db->update('devices', $data);
	}
	
	public function get_device_token($type,$id) {
	    $this->db->select("devices.*");
		$this->db->from("devices");
	    $this->db->where($type,$id);
	    $query=$this->db->get();
	    return $query->row_array();
	}
	
}