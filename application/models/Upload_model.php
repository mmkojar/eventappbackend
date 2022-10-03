<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	
	var $table = 'excel_upload_list';
	 
	 var $column = array('excel_id','user_id','username','filename','type','status','created_on');
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');
	}
	
	public function _get_datatables_query($user_id = NULL)
	{
		$this->db->select("excel_upload_list.*");
		$this->db->from(" excel_upload_list");
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

	function get_datatables($user_id = NULL)
	{
		$this->_get_datatables_query($user_id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($user_id = NULL)
	{
		$this->_get_datatables_query($user_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($user_id = NULL)
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
}
