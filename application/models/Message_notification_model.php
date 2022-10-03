<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message_notification_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	 
	 var $table = 'message_notification';
	 
	 
	 
	 var $column = array(
			'0' => 'msg_id',
			'1' => 'title',
			'2' => 'message',
			'3' => 'type',
			'4' => 'status',
			'5' => 'created_on'
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
		$this->db->select(" message_notification.*");
		$this->db->from("message_notification");
		
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
	
	public function get_message_notification($msg_id){
		$this->db->select(" message_notification.*");
		$this->db->from("message_notification");
		$this->db->where("msg_id",$msg_id);
		$query=$this->db->get();
		return $query->row_array();
	}
	
	public function get_all_user_to_notify(){
		$this->db->select("d1.*");
		$this->db->from("devices d1");
		$this->db->where("d1.status","1");
		$where = "d1.created_on = (SELECT MAX(d2.created_on) FROM devices d2 WHERE d2.user_id = d1.user_id)";
		$this->db->where($where);
		$query=$this->db->get();
		return $query->result_array();
	}
	
	/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function register_message_notification($title, $message, $type,$status)
	{
		// Users table.
		$data = array(
		    'title'   => $title,
		    'message'   => $message,
		    'type'      => $type,
		    'status' => $status,
		    'created_on' => date("Y-m-d H:i:s"),
		    'updated_on' => date("Y-m-d H:i:s")
		);

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('message_notification', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}

	
	public function update($id, array $data)
	{

		$message_notification = $this->get_message_notification($id);
		
		$this->db->trans_begin();

		$this->db->update("message_notification", $data, array('msg_id' => $message_notification["msg_id"]));

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
		$this->db->delete("message_notification", array('msg_id' => $id));


		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('Delete Failed');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('Delete Successful');
		return TRUE;
	}
}
