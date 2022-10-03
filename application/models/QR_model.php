<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class QR_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	 
	 var $table = 'qr_code';	
	 
	 var $column = array(
			'0' => 'qr_code.id',
			'1' => 'user_id',
			'2' => 'filename',
			// '3' => 'size',
			'3' => 'status',
			'4' => 'qr_code.created_on'
		);
	 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		//$this->load->model('notification_model');
		$this->load->helper('random_string');
		$this->load->helper('notification');
		$this->lang->load('ion_auth');
	}
	
	 public function _get_datatables_query()
	{
		$this->db->select("qr_code.*,users.first_name,users.last_name");
		$this->db->from('qr_code');
		$this->db->join("users", "users.id = qr_code.user_id" ,"left");
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
		$this->db->from('qr_code');
		return $this->db->count_all_results();
	}
	
	public function qr_code($tid,$id){
		$this->db->select(" qr_code.*");
		$this->db->from("qr_code");
		$this->db->where($tid,$id);
		$query=$this->db->get();
		return $query->row_array();
	}

	public function check_qr_entries($user_id) {
		$query = $this->db->query("select * from user_qr_entries where user_id=".$user_id." ORDER BY id DESC LIMIT 1");
		return $query->result_array();
	}

	public function insert_entries($data) {
		$this->db->insert('user_qr_entries', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}

	public function get_users($id=FALSE) {
		$this->db->select("users.*,groups.name as type,groups.id as gid");
		$this->db->from("users");
		$this->db->join("users_groups", "users_groups.user_id = users.id" ,"left");
		$this->db->join("groups", "groups.id = users_groups.group_id" ,"left");
		if($id) {
        	$this->db->where('users.id',$id);
        	$res = $this->db->get();
        	return $res->row_array();
		}
		else {
			$this->db->where('users_groups.group_id','2');
	        $res = $this->db->get();
	        return $res->result_array();
		}
        
	}
	
	/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function register($data)
	{

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('qr_code', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}

	
	public function update($id, array $data)
	{
		
		$this->db->trans_begin();

		$this->db->update("qr_code", $data, array('id' => $id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			 $this->session->set_flashdata('error','Update Failed');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('success','Update Successful');
		return TRUE;
	}

	/**
	* delete_user
	*
	* @return bool
	* @author Phil Sturgeon
	**/
	public function delete_($id)
	{
		$this->db->trans_begin();

		// delete user from users table should be placed after remove from group
		$this->db->delete("qr_code", array('id' => $id));
        $this->db->delete("user_qr_entries", array('user_id' => $id));

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
