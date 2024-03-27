<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	
	 var $table = 'support';
	 	 	
	 var $column = array(
	 	
			'0' => 'id',
			'1' => 'title',
			'2' => 'title',
			'3' => 'phone',
			'4' => 'status',
			'5' => 'created_on',
			'6' => 'updated_on',
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
		$this->db->select($this->table.".*");
		$this->db->from($this->table);
		
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

	public function register_support(array $register_data)
	{
		$this->db->insert('support', $register_data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}

	public function get_support($id){
		$this->db->select("support.*");
		$this->db->from("support");
		$this->db->where("id",$id);
		$query=$this->db->get();
		return $query->row_array();
	}

	public function update($id, array $data)
	{

		// $support = $this->get_support($id);
		
		$this->db->trans_begin();

		$this->db->update("support", $data, array('id' => $id));

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

	public function delete_($id)
	{
		$this->db->trans_begin();

		// delete user from users table should be placed after remove from group
		$this->db->delete("support", array('id' => $id));


		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('Delete Failed');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('success', 'Data Delete Successful');
		return TRUE;
	}

	public function get_support_api($id=FALSE){
		$this->db->select("support.*");
		$this->db->from("support");
		$this->db->where('status',1);
		if($id) {
		    $this->db->where("id",$id);
		    $query=$this->db->get();
		    $rowcount =  $query->num_rows();
		    if($rowcount > 0) {
		        $response['status'] = "true";
		        $response['message'] = 'Data Found';
		        $response['data'] = $query->row_array();
		        return $response;
		    }
		    else {
		        $response['status'] = "false";
		        $response['message'] = 'No Data Found';
		        return $response;
		    }
		}
		else {
		    $query=$this->db->get();
		    $rowcount =  $query->num_rows();
		    if($rowcount > 0) {
		        $response['status'] = "true";
		        $response['message'] = 'Data Found';
		        $response['data'] = $query->result_array();
		        return $response;
		    }
		    else {
		        $response['status'] = "false";
		        $response['message'] = 'No Data Found';
		        return $response;
		    }
		}
		
	}

}
