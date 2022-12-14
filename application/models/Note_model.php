<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Note_model extends CI_Model
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

	public function get_note_for_user($user_id,$start = NULL){
		$this->db->select("notes.*");
		$this->db->from("notes");
		$this->db->where("notes.user_id", $user_id);
		if($start) {
			$this->db->order_by('notes.notes_id', 'DESC');
		    $this->db->limit("1");
		}
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function get_note_id($notes_id,$user_id){
		$this->db->select("notes.*");
		$this->db->from("notes");
		$this->db->where("notes.notes_id", $notes_id);
		$this->db->where("notes.user_id", $user_id);
		$query=$this->db->get();
		return $query->row_array();
	}
	
	public function get_note_id_one($notes_id){
		$this->db->select("notes.*");
		$this->db->from("notes");
		$this->db->where("notes.notes_id", $notes_id);
		$query=$this->db->get();
		return $query->row_array();
	}
	
	/*
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 *
	*/
	 
	public function add_note($user_id,$note)
	{
		//Attendee table.
		$data = array(
			'user_id'   => $user_id,
			'note'   => $note,
		    'created_on' => date("Y-m-d H:i:s")
		);

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('notes', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}
	
	public function update_note($notes_id, array $data)
	{

		$note = $this->get_note_id($notes_id,$data["user_id"]);
		if($note) {
			$this->db->trans_begin();

			$this->db->update("notes", $data, array('notes_id' => $note["notes_id"],'user_id' => $data["user_id"]));

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return FALSE;
			}

			$this->db->trans_commit();
			return TRUE;
		}
		else {
			return FALSE;
		}		
	}

	public function delete_($id)
	{
		$this->db->trans_begin();
		
		$result = $this->db->delete("notes", array('notes_id' => $id));
        
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('error','Delete Failed');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('success','Delete Successful');
		return TRUE;
	}
}
