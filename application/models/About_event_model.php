<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About_event_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	
	 var $table = 'about_event';
	 
	 
	 
	 var $column = array(
	 	
			'0' => 'about_id',
			'1' => 'about_heading',
			'2' => 'about_msg',
			'3' => 'created_on',
			'4' => 'status',
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
		$this->db->select("about_event.*");
		$this->db->from("about_event");
		$this->db->where("status =", 1);
		
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

	public function register_about_event(array $register_data)
	{

		$data = array(
			'about_heading'   => $register_data["about_heading"],
			'about_msg'   => $register_data["about_msg"],				
			'status'   => 1,		     
		    'created_on' => date("Y-m-d H:i:s"),
		    'updated_on' => date("Y-m-d H:i:s")
		);	

		$this->db->insert('about_event', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}

	public function get_aboutEvent($id = null){
		if($id){
			$this->db->select("about_event.*");
			$this->db->from("about_event");
			$this->db->where("about_id",$id);		
		}
		else{
			$this->db->select("about_event.*");
			$this->db->from("about_event");					
		}
		$query = $this->db->get();
		return $query->row_array();
		
	}

	public function update($id, array $data)
	{

		$about_event = $this->get_aboutEvent($id);
		
		$this->db->trans_begin();

		$this->db->update("about_event", $data, array('about_id' => $about_event["about_id"]));

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
		$this->db->delete("about_event", array('about_id' => $id));


		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('Delete Failed');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('success', 'Delete Successful');
		return TRUE;
	}

    public function get_about_api($id=FALSE){
		$this->db->select("about_event.*");
		$this->db->from("about_event");
		if($id) {
		    $this->db->where("about_id ",$id);
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
