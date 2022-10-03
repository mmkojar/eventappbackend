<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
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
		$this->load->helper('random_string');
		$this->lang->load('ion_auth');
	}
	
	
	public function get_users($id=FALSE){
		$this->db->select("users.*,groups.name as type");
		$this->db->from("users");
		$this->db->join("users_groups", "users_groups.user_id = users.id" ,"left");
		$this->db->join("groups", "groups.id = users_groups.group_id" ,"left");
 		$this->db->join("devices", "devices.user_id = users.id" ,"inner");
		$this->db->where('groups.name !=','admin');
		if($id) {
		    $this->db->where("users.id",$id);
		    $query=$this->db->get();
		    $rowcount =  $query->num_rows();
		    if($rowcount > 0) {
		        $response['status'] = "true";
		        $response['message'] = 'User Found';
		        $response['data'] = $query->row_array();
		        return $response;
		    }
		    else {
		        $response['status'] = "false";
		        $response['message'] = 'No User Found';
		        return $response;
		    }
		}
		else {
		    
		    $query=$this->db->get();
		    $rowcount =  $query->num_rows();
		    if($rowcount > 0) {
		        $response['status'] = "true";
		        $response['message'] = 'User Found';
		        $response['data'] = $query->result_array();
		        return $response;
		    }
		    else {
		        $response['status'] = "false";
		        $response['message'] = 'No User Found';
		        return $response;
		    }
		}
		
	}
	
	public function update($id, array $data)
	{

		$this->db->trans_begin();

		$this->db->update("users", $data, array('id' => $id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			 $this->session->set_flashdata('message','update_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('message','update_successful');
		return TRUE;
	}

}
