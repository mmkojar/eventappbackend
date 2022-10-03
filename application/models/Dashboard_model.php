<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Dashboard_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');
	}

	public function get_total_users() {
		$this->db->select("users.*");
		$this->db->from("users");
		$this->db->join("users_groups", "users_groups.user_id = users.id" ,"left");
		$this->db->join("groups", "groups.id = users_groups.group_id" ,"left");
        $this->db->where('groups.name !=','admin');
		$query=$this->db->get();
		return $query->num_rows();
	}

	public function get_count($table){
		$this->db->select($table.".*");
		$this->db->from($table);
		$query=$this->db->get();
		return $query->num_rows();
	}
	
	public function get_order($user_id){
		$this->db->select("transaction.order_status,COUNT(transaction.transaction_id) as Count");
		$this->db->from("transaction");
		$this->db->where('transaction.user_id =',$user_id); 
		$this->db->group_by('transaction.order_status'); 
		$query=$this->db->get();
		//print_r($this->db->last_query());
		return $query->result_array();
	}
	
	public function get_order_all(){
		$this->db->select("transaction.order_status,COUNT(transaction.order_status) as Count");
		$this->db->from("transaction");
		$this->db->group_by('transaction.order_status'); 
		//$this->db->order_by('transaction.order_placed', 'asc');
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function get_malls(){
		$this->db->select("mall_details.*");
		$this->db->from("mall_details");
		$this->db->where('mall_details.status','Active');
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function get_query_search($users_id,$mall_id,$startdate,$enddate){
		
		//$this->db->select("transaction.order_status,COUNT(transaction.order_status) as Count");
		//$this->db->from("transaction");
		
		$this->db->select("transaction.order_status,WEEK(`transaction`.`updated_on`) weeks,COUNT(transaction.transaction_id) as Count");
		$this->db->from("transaction");
		
		if(trim($mall_id)){
			$this->db->join("users", "users.id = transaction.user_id");
			$this->db->join("mall_details", "mall_details.mall_id = users.mall_id");
		}else{
			//var_dump($users_id);			
		}
		
		if(trim($users_id)){
			$this->db->where('user_id',$users_id);
		}else{
			//var_dump($users_id);			
		}
		
		$where = '';
		
		if(!empty($startdate) && empty($enddate)){
			
			$where .= "`transaction`.`updated_on` > '".$startdate."'";
		
		}else if(!empty($enddate) && empty($startdate)){
			
			$where .= "`transaction`.`updated_on` < '".$enddate."'";
		
		}else if(!empty($enddate) && empty(!$startdate)){
		
			$where .= "(`transaction`.`updated_on` BETWEEN '".$startdate."' AND '".$enddate."')";		
		
		}else{
			
			$where .= "`transaction`.`updated_on` > DATE_SUB(NOW(), INTERVAL 4 WEEK)";	
		
		}
		
		if(!empty($where))
			$this->db->where($where);
		
		if(trim($mall_id)){
			if($mall_id != 'all'){
				$this->db->where("mall_details.mall_id", $mall_id);
			}
		}
		$this->db->where('transaction.order_status',"Delivered");
		$this->db->group_by('transaction.order_status'); 
		$this->db->group_by('WEEK(`transaction`.`updated_on`)'); 
		//$this->db->order_by('transaction.order_placed', 'asc');
		$query=$this->db->get();
		//print_r($this->db->last_query());
		return $query->result_array();
	
	}
	
	public function get_query_search_one($users_id,$startdate,$enddate){
		
		$this->db->select("transaction.order_status,WEEK(`transaction`.`updated_on`) weeks,COUNT(transaction.transaction_id) as Count");
		$this->db->from("transaction");
		
		
		$where = '';
		
		if(!empty($enddate) && !empty($startdate)){
			$where .= "(`transaction`.`updated_on` BETWEEN '".$startdate."' AND '".$enddate."')";		
		}else{
			$where .= "`transaction`.`updated_on` > DATE_SUB(NOW(), INTERVAL 4 WEEK)";		
		}
		
		if(!empty($where))
			$this->db->where($where);
		
		if(trim($users_id)){
			$this->db->where('transaction.user_id',$users_id);
		}
		
		$this->db->where('transaction.order_status',"Delivered");
		$this->db->group_by('WEEK(`transaction`.`updated_on`)'); 
		//$this->db->group_by('transaction.order_status');
		$query=$this->db->get();
		//print_r($this->db->last_query());
		return $query->result_array();
	
	}
	
	public function get_order_count_search($users_id,$startdate,$enddate){
		$this->db->select("transaction.order_status,COUNT(transaction.order_status) as Count");
		$this->db->from("transaction");
		
		if(trim($users_id)){
			$this->db->where('user_id',$users_id);
		}
		
		$where = '';
		
		if(!empty($startdate) && empty($enddate)){
			
			$where .= "`transaction`.`updated_on` > '".$startdate."'";
		
		}else if(!empty($enddate) && empty($startdate)){
			
			$where .= "`transaction`.`updated_on` < '".$enddate."'";
		
		}else if(!empty($enddate) && empty(!$startdate)){
		
			$where .= "(`transaction`.`updated_on` > '".$startdate."' AND `transaction`.`updated_on` < '".$enddate."')";		

		}
		if(!empty($where))
			$this->db->where($where);
		
		$this->db->group_by('transaction.order_status'); 
		//$this->db->order_by('transaction.order_placed', 'asc');
		$query=$this->db->get();
		//print_r($this->db->last_query());
		return $query->result_array();
	}
	
	public function get_order_count($search,$users_id,$mall_id,$startdate,$enddate){
		
		$this->db->select("transaction.order_status,COUNT(transaction.order_status) as Count");
		if($search != 'no'){
			$this->db->select("users.username");
		}
		$this->db->from("transaction");
		if(trim($mall_id)){
			$this->db->join("users", "users.id = transaction.user_id");
			$this->db->join("mall_details", "mall_details.mall_id = users.mall_id");
		}else{
			//var_dump($users_id);			
		}
		
		if(trim($users_id)){
			$this->db->where('user_id',$users_id);
		}
		if($search != 'no'){
		$where = '';
		
			if(!empty($startdate) && empty($enddate)){
				
				$where .= "`transaction`.`updated_on` > '".$startdate."'";
			
			}else if(!empty($enddate) && empty($startdate)){
				
				$where .= "`transaction`.`updated_on` < '".$enddate."'";
			
			}else if(!empty($enddate) && empty(!$startdate)){
			
				$where .= "(`transaction`.`updated_on` BETWEEN '".$startdate."' AND '".$enddate."')";		
			
			}else{
				
				$where .= "`transaction`.`updated_on` > DATE_SUB(NOW(), INTERVAL 4 WEEK)";	
			
			}
			
			if(!empty($where))
				$this->db->where($where);
			
		}
		$this->db->group_by('transaction.order_status'); 
		//$this->db->order_by('transaction.order_placed', 'asc');
		$query=$this->db->get();
		//print_r($this->db->last_query());
		return $query->result_array();
	}
	
	public function excel_upload_list($user_id,$username,$new_name,$type){
		$data = array(
		    'user_id'      => $user_id,
		    'username'      => $username,
			'filename'      => $new_name,
		    'type'      => $type,
		    'status' => "Active",
		    'created_on' => date("Y-m-d H:i:s")
		);

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('excel_upload_list', $data);
		
		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
		
	}	
	
}
