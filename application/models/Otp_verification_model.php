<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Otp_verification_model extends CI_Model
{
	public $identity;

public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_otp($otp,$device_imei)
	{
		$this->db->select("do.*");
		$this->db->from("device_otp do");
		$this->db->where('do.otp_code',$otp);
		$this->db->where('do.device_imei',$device_imei);
		$this->db->order_by('do.created_on',"desc");
		$this->db->limit(1);
		$query=$this->db->get();
		return $query->row_array();
	}	
	
	public function get_old_otp($user_id,$device_imei)
	{
		$this->db->select("do.*");
		$this->db->from("device_otp do");
		// if($user_id == 707 || $user_id == "707"){
			$where = "TIMESTAMPDIFF(DAY, otp_send_time, CONVERT_TZ(NOW(),'+08:00','+05:30')) < 10";
		// }else{
			// $where = "TIMESTAMPDIFF(MINUTE, otp_send_time, CONVERT_TZ(NOW(),'+08:00','+05:30')) < 30";
		// }
		$this->db->where($where); 
		$this->db->where('do.user_id',$user_id);
		$this->db->where('do.device_imei',$device_imei);
		$this->db->where('do.otp_use', "unused");
		$this->db->order_by('do.created_on',"desc");
		$this->db->limit(1);
		$query=$this->db->get();
		//print_r($this->db->last_query());
		return $query->row_array();
	}

	public function get_otp_id($id){
		$this->db->select("do.*");
		$this->db->from("device_otp do");
		$this->db->where('do.device_otp_id',$id);
		$query=$this->db->get();
		return $query->row_array();
	}

	public function update($id, array $data)
	{

		$device_otp = $this->get_otp_id($id);
		
		$this->db->trans_begin();

		$this->db->update("device_otp", $data, array('device_otp_id' => $device_otp["device_otp_id"]));
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}

}