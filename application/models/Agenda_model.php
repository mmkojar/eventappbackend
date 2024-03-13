<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agenda_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	
	 var $table = 'agenda';
	 
	 
	 
	 var $column = array(
	 	
			'0' => 'agenda_id',
			'1' => 'agenda_name',
			'2' => 'agenda_date',
			'3' => 'agenda_time',
			'4' => 'speaker_name',
			'5' => 'agenda_venue',
			'6' => 'status',
			'7' => 'created_on',
			'8' => 'updated_on',
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
		$this->db->select("agenda.*,agenda_titles.name as title");
		$this->db->from("agenda");
		$this->db->join("agenda_titles","agenda_titles.id = agenda.title_id",'left');
		
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

	public function register_agenda(array $register_data)
	{

		$this->db->insert('agenda', $register_data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}

	public function get_agenda($id){
		$this->db->select("agenda.*");
		$this->db->from("agenda");
		$this->db->where("agenda_id",$id);
		$query=$this->db->get();
		return $query->row_array();
	}

	public function update($id, array $data)
	{

		$agenda = $this->get_agenda($id);
		
		$this->db->trans_begin();

		$this->db->update("agenda", $data, array('agenda_id' => $agenda["agenda_id"]));

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
		$this->db->delete("agenda", array('agenda_id' => $id));


		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('Delete Failed');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('success', 'Agenda Delete Successful');
		return TRUE;
	}

	public function get_agenda_api($id=FALSE){
		$this->db->select(" agenda.*,agenda_titles.name");
		$this->db->from("agenda");
		$this->db->join("agenda_titles", "agenda_titles.id = agenda.title_id");
		$this->db->where('status',1);
		// $this->db->where("title_id",$id);
		$query=$this->db->get();
		$rowcount =  $query->num_rows();
		$result = [];
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
		/* if($id) {
		    $this->db->where("title_id",$id);
		    $query=$this->db->get();
		    $rowcount =  $query->num_rows();
			$result = [];
		    if($rowcount > 0) {				
				// foreach($query->row_array() as $rawdata) {
				// 	// $rawdata['agenda_date'] = date('jS M, Y',strtotime($rawdata['agenda_date']));
				// 	array_push($result,$rawdata);
				// }
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
			$result = [];
		    if($rowcount > 0) {
				// foreach($query->result_array() as $rawdata) {
				// 	// // $rawdata['agenda_date'] = date('jS M, Y',strtotime($rawdata['agenda_date']));
				// 	array_push($result,$rawdata);
				// }
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
		} */
		
	}

}
