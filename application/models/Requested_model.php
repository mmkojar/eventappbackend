<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requested_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	 
	 var $table = 'attendee';
	 
	 
	 
	 var $column = array(
			'0' => 'attendee.attendee_id',
			'1' => 'first_name',
			'2' => 'email',
			'3' => 'membership_id',
			'4' => 'transaction.chargetotal',
			'5' => 'status_attendee',
			'6' => 'requested_on'
		);
	 
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
	
	 public function _get_datatables_query()
	{
		$this->db->select("attendee.*,transaction.ipgTransactionId,transaction.approval_code,transaction.status,transaction.chargetotal");
		$this->db->from("attendee");
		$this->db->join("transaction", "transaction.attendee_id = attendee.attendee_id" ,"left");
		$this->db->where("status_attendee =","Pending");
		$i = 0;
		$where = '';
		if($_POST['search']['value']){
			$where .= '(';
		}
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value']){
				
				//($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
				($i===0) ? $where .= '`'.$item.'` LIKE "%'.$_POST["search"]["value"].'%" ' : $where .= 'OR `'.$item.'` LIKE "%'.$_POST["search"]["value"].'%" ';
			
			}
				$column[$i] = $item;
				$i++;
		}
		if($_POST['search']['value']){
			$where .= ')';
			$this->db->where($where);
		}
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
		
		//print_r($this->db->queries);
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	public function get_attendees($id){
		$this->db->select("attendee.*");
		$this->db->from("attendee");
		$this->db->where('attendee_id',$id);
		$query=$this->db->get();
		return $query->row_array();
	}
	
	public function get_attendees_id($id){
		$this->db->select("attendee.*");
		$this->db->from("attendee");
		$this->db->where('attendee_request_id',$id);
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function get_attend($id){
		$this->db->select("attendee.*,users.id");
		$this->db->from("attendee");
		$this->db->join("users", "users.email = attendee.email" ,"inner");
		$this->db->where('attendee_id',$id);
		$query=$this->db->get();
		return $query->row_array();
	}
	
	public function update_requested($id, array $data)
	{

		$attendee = $this->get_attendees($id);
		
		$this->db->trans_begin();

		$this->db->update("attendee", $data, array('attendee_id' => $attendee["attendee_id"]));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			$this->session->set_flashdata('error','Unable to Update the request');
			
			return FALSE;
		}

		$this->db->trans_commit();

		return TRUE;
	}
	
	/**
	* delete_user
	*
	* @return bool
	* @author Phil Sturgeon
	**/
	public function delete_attendee($attendee_id)
	{

		$this->db->trans_begin();

		$delete = $this->db->delete('attendee', array('attendee_id' => $attendee_id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}
	
}
