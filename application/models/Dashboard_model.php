<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Dashboard_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');
	}

	public function get_total_users() {
		$this->db->select("users.*");
		$this->db->from("users");
		$this->db->join("users_groups", "users_groups.user_id = users.id" ,"left");
		$this->db->join("groups", "groups.id = users_groups.group_id" ,"left");
        $this->db->where('groups.name !=','admin');
		$query=$this->db->get();
		return $query->num_rows();
	}

	public function get_count($table){
		$this->db->select($table.".*");
		$this->db->from($table);
		$query=$this->db->get();
		return $query->num_rows();
	}	
}
