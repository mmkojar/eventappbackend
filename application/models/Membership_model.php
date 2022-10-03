<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Membership_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	
	 var $table = 'membership';
	 
	 
	 
	 var $column = array(
			'0' => 'membership_id',
			'1' => 'company_name',
			'2' => 'membership_number',
			'3' => 'created_on'
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
		$this->db->select(" membership.*");
		$this->db->from("membership");
		
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

	
	
	public function get_memberships(){
		$this->db->select("membership.*");
		$this->db->from("membership");
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function get_registration_fees_type(){
		$this->db->select("registration_fees_type.*");
		$this->db->from("registration_fees_type");
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function get_membership_id($membership_id){
		$this->db->select("membership.*");
		$this->db->from("membership");
		$this->db->where("membership_id",$membership_id);
		$query=$this->db->get();
		return $query->row_array();
	}
	
	/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function register_membership(array $register_data)
	{
		//Attendee table.
		$data = array(
			'company_name'   => $register_data["company_name"],
			'membership_number'   => $register_data["membership_number"],
		    'created_on' => date("Y-m-d H:i:s")
		);

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('membership', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}

	
	public function update($id, array $data)
	{

		$membership = $this->get_membership_id($id);
		
		$this->db->trans_begin();

		$this->db->update("membership", $data, array('membership_id' => $membership["membership_id"]));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			 $this->session->set_flashdata('error','update_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('success','updated successful');
		return TRUE;
	}
	
	/**
	* delete_user
	*
	* @return bool
	* @author Phil Sturgeon
	**/
	public function delete_membership($membership_id)
	{

		$this->db->trans_begin();

		$delete = $this->db->delete('membership', array('membership_id' => $membership_id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}

}
