<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_feed_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->load->helper('random_string');
		$this->lang->load('ion_auth');
	}

	public function get_activity_feed(){
		$this->db->select("activity_feed.*,users.first_name,users.last_name,users.email,users.company,users.phone,attendee.attendee_image");
		$this->db->from("activity_feed");
		$this->db->join("users", "users.id = activity_feed.user_id" ,"inner");
		$this->db->join("attendee", "attendee.email = users.email" ,"inner");
		$this->db->order_by("activity_feed.created_on","DESC");
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function get_activity_feed_id($activity_feed_id){
		$this->db->select("activity_feed.*,users.first_name,users.last_name,users.email,users.company,users.phone,attendee.attendee_image");
		$this->db->from("activity_feed");
		$this->db->join("users", "users.id = activity_feed.user_id" ,"inner");
		$this->db->join("attendee", "attendee.email = users.email" ,"inner");
		$this->db->where("activity_feed.activity_feed_id", $activity_feed_id);
		$query=$this->db->get();
		return $query->result_array();
	}
	
	/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	 
	public function add_activity_feed($user_id,$message,$image_url)
	{
		//Attendee table.
		$data = array(
			'user_id'   => $user_id,
			'message'   => $message,
			'image_url'   => $image_url,
		    'created_on' => date("Y-m-d H:i:s"),
		    'updated_on' => date("Y-m-d H:i:s")
		);

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('activity_feed', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}
	
	public function delete_feed($user_id,$activity_feed_id)
	{
		$this->db->trans_begin();

		// delete user from users table should be placed after remove from group
		$this->db->delete("activity_feed", array('activity_feed_id' => $activity_feed_id,'user_id' => $user_id));


		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}
}
