<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Speaker_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	
	 var $table = 'speaker';
	 	 	
	 var $column = array(
	 	
			'0' => 'id',
			'1' => 'name',
			'2' => 'image',
			'3' => 'company_name',
			'4' => 'designation',
			'5' => 'status',
			'6' => 'created_on',
			'7' => 'updated_on',
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

	public function register_speaker(array $register_data)
	{

		$data = array(
			'name'   => $register_data["name"],
			'company_name'   => $register_data["company_name"],
			'designation'   => $register_data["designation"],
			'image'   => $register_data["speaker_image_url"],
			'status'   => 1,
		);	

		$this->db->insert('speaker', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}

	public function get_speaker($id){
		$this->db->select("speaker.*");
		$this->db->from("speaker");
		$this->db->where("id",$id);
		$query=$this->db->get();
		return $query->row_array();
	}

	public function update($id, array $data)
	{

		// $speaker = $this->get_speaker($id);
		$this->db->trans_begin();

		$this->db->update("speaker", $data, array('id' => $id));

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
		$this->db->delete("speaker", array('id' => $id));


		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('Delete Failed');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('success', 'speaker Delete Successful');
		return TRUE;
	}

	public function get_speaker_api($id=FALSE) {
		$this->db->select("speaker.*");
		$this->db->from("speaker");
		$this->db->where('status',1);
		if($id) {
		    $this->db->where("id",$id);
		}
		$query=$this->db->get();
		$rowcount =  $query->num_rows();
		$result = $id ? $query->row_array() : $query->result_array();
		if($rowcount > 0) {
	        $response['status'] = "true";
	        $response['message'] = 'Data Found';
	        $response['data'] = $result;
	    }
	    else {
	        $response['status'] = "false";
	        $response['message'] = 'No Data Found';
	    }
		return $response;
		
	}

}
