<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_all_chat_history($receiver_id, $user_id, $start)
	{
		$this->db->select("cd.*, CONCAT(sender.first_name,' ',sender.last_name) AS 'Sender Name' ,sender.email As Sender Email, CONCAT(receiver.first_name,' ',receiver.last_name) AS 'Receiver Name',receiver.email As Receiver Email");
		$this->db->from("chat_detail cd");
		$this->db->join("users sender", "sender.id = cd.user_id" ,"left");
		$this->db->join("users receiver", "receiver.id = cd.receiver_id" ,"left");
		if($receiver_id) {
    		$this->db->where("cd.user_id",$user_id);
		    $this->db->where("cd.receiver_id",$receiver_id);
		    $this->db->or_where("cd.user_id",$receiver_id);
		    $this->db->where("cd.receiver_id",$user_id);
		}
		else {
		    $this->db->where("cd.user_id",$user_id);
		}
        //$this->db->where("cd.device_notification_id",$device_notification_id);
		$this->db->order_by('cd.created_on', 'DESC');
		if($start == 'latest') {
		    $this->db->order_by('cd.chat_detail_id', 'DESC');
		    $this->db->limit("1");
		}
        //$this->db->limit("10", $start);
		$query=$this->db->get();
		//print_r($this->db->last_query());
		return $query->result_array();
	}
	
	
	public function get_device($user_id){
		$this->db->select("devices.device_notification_id,devices.devicetype,devices.device_imei,users.first_name,users.last_name,users.email,users.city,users.phone");
		$this->db->from("devices");
		$this->db->join("users", "users.id = devices.user_id" ,"inner");
		$this->db->where("devices.user_id",$user_id);
		$this->db->order_by('devices.created_on', 'DESC');
		$query=$this->db->get();
		return $query->row_array();
	}
	
	public function get_chat_history($user_id){
		$query = $this->db->query("select T1.user2_id, T1.chat_detail_id, CONCAT(users.first_name,' ',users.last_name) AS 'user_name',cdate as updated_on from
				(select chat_detail.receiver_id user2_id, chat_detail.chat_detail_id, max(created_on) cdate
				from chat_detail 
				where chat_detail.user_id = ".$user_id."
				group by chat_detail.receiver_id
				union distinct
				(select  chat_detail.user_id user2_id, chat_detail.chat_detail_id, max(created_on) cdate
				from chat_detail  where chat_detail.receiver_id = ".$user_id."
				group by chat_detail.user_id)) T1
				inner join users on (users.id = T1.user2_id)
				group by T1.user2_id
				order by cdate desc");
				//print_r($this->db->last_query());
		return $query->result_array();
	}

	/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function send_chat_message($receiver_id, $user_id, $message)
	{
		// Users table.
		$data = array(
		    'user_id'   => $user_id,
		    'receiver_id'   => $receiver_id,
		    'message'   => $message,
		    'created_on' => date("Y-m-d H:i:s"),
		    'updated_on' => date("Y-m-d H:i:s")
		);
        
		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('chat_detail', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}
	
}