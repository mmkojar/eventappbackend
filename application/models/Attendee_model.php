<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendee_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	
	 var $table = 'attendee';
	 
	 
	 
	 var $column = array(
			'0' => 'attendee_id',
			'1' => 'first_name',
			'2' => 'email',
			'3' => 'membership_id',
			'4' => 'status_attendee',
			'5' => 'attendee_id',
			'6' => 'publish_auth'
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
		$this->db->select(" attendee.*");
		$this->db->from("attendee");
		$this->db->where("status_attendee =","Approve");
		
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

	
	
	public function get_attendees(){
		$this->db->select("attendee.*");
		$this->db->from("attendee");
		$query=$this->db->get();
		return $query->result_array();
	}
	
	
	public function get_attendees_to_publish(){
		$this->db->select("attendee.attendee_id,attendee.family_name, attendee.first_name, attendee.company_name, attendee.address, attendee.telephone, attendee.email, attendee.mobile, attendee.attendee_image, attendee.attendee_interested, attendee.attendee_business_type, attendee.membership_id,attendee.location,users.id as user_id");
		$this->db->from("attendee");
		$this->db->join("users", "users.email = attendee.email" ,"left");
		$this->db->where("publish_auth_final","Yes");
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function get_attendees_id($attendee_id){
		$this->db->select("attendee.*,registration_fees_type.registration_fees_type_id,registration_fees_type.company_type,registration_fees_type.delegate_type,registration_fees_type.member_type,registration_fees_type.registration_fees,registration_fees_type.currency,registration_fees_type.taxes,registration_fees_type.transaction_fee,transaction.chargetotal,transaction.status,transaction.approval_code");
		$this->db->from("attendee");
		$this->db->join("registration_fees_type", "registration_fees_type.registration_fees_type_id = attendee.registration_fees_type_id" ,"inner");
		$this->db->join("transaction", "transaction.attendee_id = attendee.attendee_id" ,"left");
		$this->db->where("attendee.attendee_id",$attendee_id);
		$query=$this->db->get();
		return $query->row_array();
	}
	
	public function get_attendees_with_member_by_id($attendee_id){
		$this->db->select("attendee.*,registration_fees_type.registration_fees_type_id,registration_fees_type.company_type,registration_fees_type.delegate_type,registration_fees_type.member_type,registration_fees_type.registration_fees,registration_fees_type.currency,registration_fees_type.taxes,registration_fees_type.transaction_fee");
		$this->db->from("attendee");
		$this->db->join("registration_fees_type", "registration_fees_type.registration_fees_type_id = attendee.registration_fees_type_id" ,"inner");
		$this->db->where("attendee_id",$attendee_id);
		$query=$this->db->get();
		return $query->row_array();
	}
	
	public function get_attendee_with_member_by_id($attendee_request_id){
		$this->db->select("attendee.*,registration_fees_type.registration_fees_type_id,registration_fees_type.company_type,registration_fees_type.delegate_type,registration_fees_type.member_type,registration_fees_type.registration_fees,registration_fees_type.currency,registration_fees_type.taxes,registration_fees_type.transaction_fee");
		$this->db->from("attendee");
		$this->db->join("registration_fees_type", "registration_fees_type.registration_fees_type_id = attendee.registration_fees_type_id" ,"inner");
		$this->db->where("attendee_request_id",$attendee_request_id);
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function get_registration_fees_type(){
		$this->db->select("registration_fees_type.*");
		$this->db->from("registration_fees_type");
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function checkmembership($membership){
		$this->db->select("membership.membership_id,membership.company_name,membership.membership_number,count(attendee.membership_id) as member_count");
		$this->db->from("membership");
		$this->db->join("attendee", "attendee.membership_id = membership.membership_number" ,"left");
		$this->db->where("membership.membership_number",$membership);
		$query=$this->db->get();
		return $query->row_array();
	}
	
	public function checkattendee($membership){
		$this->db->select("attendee.membership_id,attendee.status_attendee");
		$this->db->from("attendee");
		$this->db->where("attendee.membership_id",$membership);
		$query=$this->db->get();
		return $query->result_array();
	}
	
	public function get_transaction_id($attendee_id){
		$this->db->select("transaction.*");
		$this->db->from("transaction");
		$this->db->where("transaction.attendee_id",$attendee_id);
		$query=$this->db->get();
		return $query->result_array();
	}
	
	
	public function getattendee_by_id_api($user_id){
		$this->db->select("attendee.attendee_id,attendee.family_name,attendee.first_name,attendee.company_name,attendee.mobile,attendee.attendee_image,attendee.attendee_interested,attendee.attendee_business_type,users.id,users.username,registration_fees_type.registration_fees_type_id,registration_fees_type.company_type,registration_fees_type.delegate_type,registration_fees_type.member_type");
		$this->db->from("attendee");
		$this->db->join("registration_fees_type", "registration_fees_type.registration_fees_type_id = attendee.registration_fees_type_id" ,"inner");
		$this->db->join("users", "users.email = attendee.email" ,"left");
		$this->db->where("users.id",$user_id);
		$this->db->where("attendee.status_attendee","Approve");
		$query=$this->db->get();
		return $query->row_array();
	}
	
	public function get_attendee_by_id_api($attendee_id){
		$this->db->select("attendee.attendee_id,attendee.family_name,attendee.first_name,attendee.company_name,attendee.mobile,attendee.attendee_image,attendee.attendee_interested,attendee.attendee_business_type,registration_fees_type.registration_fees_type_id,registration_fees_type.company_type,registration_fees_type.delegate_type,registration_fees_type.member_type");
		$this->db->from("attendee");
		$this->db->join("registration_fees_type", "registration_fees_type.registration_fees_type_id = attendee.registration_fees_type_id" ,"inner");
		$this->db->where("attendee.attendee_id",$attendee_id);
		$this->db->where("attendee.status_attendee","Approve");
		$query=$this->db->get();
		return $query->row_array();
	}
	
	
	public function get_attendee_id($attendee_id){
		$this->db->select("attendee.*");
		$this->db->from("attendee");
		$this->db->where("attendee.attendee_id",$attendee_id);
		$this->db->where("attendee.status_attendee","Approve");
		$query=$this->db->get();
		return $query->row_array();
	}
	
	/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	 
	public function register_attendee(array $register_data)
	{
		//Attendee table.
		$myinput = $register_data["dob"];
		$sqldate = date('Y-m-d',strtotime($myinput));
		// echo $sqldate; 

		$data = array(
			'attendee_request_id'   => $register_data["attendee_request_id"],
			//'family_name'   => $register_data["family_name"],
			'first_name'   => $register_data["first_name"],
			'company_name'   => $register_data["company_name"],
			'address'   => $register_data["address"],
			'country'   => $register_data["country"],
			'state'   => $register_data["state"],
			'city'   => $register_data["city"],
			'postal_code'   => $register_data["postal_code"],
			'dob'   => $sqldate,
			'gst'   => $register_data["gst"],
			'telephone'   => $register_data["telephone"],
			'email'   => $register_data["email"],
			'mobile'   => $register_data["mobile"],
			'website'   => $register_data["website"],
			'attendee_image'   => $register_data["attendee_image_url"],
			//'attendee_spouse_family_name'   => $register_data["attendee_spouse_family_name"],
			'attendee_spouse_first_name'   => $register_data["attendee_spouse_first_name"],
			'attendee_spouse_image'   => $register_data["attendee_spouse_image_url"],
			'attendee_interested'   => 	$register_data["attendee_interested"],
			'attendee_business_type'   => $register_data["attendee_business_type"],
			'registration_fees_type_id'   => $register_data["registration_fees_type_id"],
			'membership_id'   => $register_data["membership_id"],
			//'image_proof_url'   => $register_data["image_proof_url"],
			'lunch_day_1'   =>  $register_data["lunch_day_1"],
			'publish_auth'   => $register_data["publish_auth"],
			'attendee_participation'   => $register_data["attendee_participation"],
			'attendee_agree'   => $register_data["attendee_agree"],
			'status_attendee'   => $register_data["status_attendee"],
		     'requested_on' => date("Y-m-d H:i:s"),
		     'created_on' => date("Y-m-d H:i:s"),
		     'updated_on' => date("Y-m-d H:i:s")
		);
		/*$data = array(
			'company'   => $register_data["company"],
			'noc'   => $register_data["noc"],
			'nopc'   => $register_data["nopc"],
			'street_add1'   => $register_data["address1"],
			'street_add2'   => $register_data["address2"],
			'city'   => $register_data["address3"],
			'state'   => $register_data["address4"],
			'zip_code'   => $register_data["address5"],	
			'country'   => $register_data["address6"],
			'date_incorp'   => $register_data["dincorp"],
			'tel_country_code'   => $register_data["telnum1"],
			'tel_city_code'   => $register_data["telnum2"],
			'tel_phone_no'   => $register_data["telnum3"],
			'fax_country_code'   => $register_data["faxnumber1"],
			'fax_city_code'   => $register_data["faxnumber2"],
			'fax_phone_no'   => $register_data["faxnumber3"],
			'emil_add'   => $register_data["email"],
			'firm_assoc'   => $register_data["name_firm"],
			'pan_num'   => $register_data["pan_card"],
			'gst_num'   => $register_data["gst_num"],
			'gender'   => $register_data["gender"],
			'last_name'   => $register_data["last_name"],
			'first_name'   => $register_data["first_name"],
			'middle_name'   => $register_data["middle_name"],
			'designation'   => $register_data["desig"],
			'mobile_ccode'   => $register_data["mob_num1"],
			'mobile_number'   => $register_data["mob_num2"],
			'addit_contact'   => $register_data["add_contact"],
		    'cont_email' => $register_data["email_contact"],
		    'Non_Ferrous_Metals' => $register_data["ferrous_metals"],
		    'Textiles' => $register_data["textiles"],
		    'Ferrous'   => $register_data["Ferrous"],
			'Paper'   => $register_data["Paper"],
			'Stainless_steel'   => $register_data["stainless_steel"],
			'Plastics'   => $register_data["Plastics"],
			'Recycled_glass'   => $register_data["recycled_glass"],
			'Electronic_waste'   => $register_data["electronic_waste"],
			'tyres'   => $register_data["Tyres"],
			'Sectors'   => $register_data["Sectors"],
		    'dmem_assoc' => $register_data["dmemassoc"],
		    'appl_mrai' => $register_data["app_name_reom"],
		    'other_info' => $register_data["otherinfo_sheet"],
		    'photo_certif'   => $register_data["photo_cert"],
			// 'photo_certif'   => $register_data["photo_cert1"],
			'photo_pancard'   => $register_data["photo_pancard"],
			// 'photo_pancard'   => $register_data["photo_pancard1"],
			'total_amt'   => $register_data["annul_mem"],
			'total_fee'   => $register_data["tot_fees"],
			'payment_mode'   => $register_data["payment_mode"]
		);*/
		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('attendee', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}
	
	public function register_attendee_transaction(array $register_data)
	{
		//Attendee table.
		$data = array(
			'ipgTransactionId'   => $register_data["ipgTransactionId"],
			'approval_code'   => $register_data["approval_code"],
			'timezone'   => $register_data["timezone"],
			'response_hash'   => $register_data["response_hash"],
			'attendee_id'   => $register_data["oid"],
			'txntype'   => $register_data["txntype"],
			'txndatetime'   => $register_data["txndatetime"],
			'reason'   => $register_data["fail_reason"],
			'chargetotal'   => $register_data["chargetotal"],
			'status'   => $register_data["status"],
			'created_on' => date("Y-m-d H:i:s")
		);

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data

		$this->db->insert('transaction', $data);

		$id = $this->db->insert_id();

		return (isset($id)) ? $id : FALSE;
	}
	
	public function update_transaction($id, array $data)
	{

		$transaction = $this->get_transaction_id($id);
		
		$data = array(
			'ipgTransactionId'   => $register_data["ipgTransactionId"],
			'approval_code'   => $register_data["approval_code"],
			'timezone'   => $register_data["timezone"],
			'response_hash'   => $register_data["response_hash"],
			'attendee_id'   => $register_data["oid"],
			'txntype'   => $register_data["txntype"],
			'txndatetime'   => $register_data["txndatetime"],
			'reason'   => $register_data["fail_reason"],
			'chargetotal'   => $register_data["chargetotal"],
			'status'   => $register_data["status"],
			'updated_on' => date("Y-m-d H:i:s")
		);
		
		$this->db->trans_begin();

		$this->db->update("transaction", $data, array('transaction_id' => $transaction["transaction_id"]));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			 $this->session->set_flashdata('message','update_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('message','update_successful');
		return TRUE;
	}
	
	public function update($id, array $data)
	{

		$attendee = $this->get_attendees_id($id);
		
		$this->db->trans_begin();

		$this->db->update("attendee", $data, array('attendee_id' => $attendee["attendee_id"]));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			 $this->session->set_flashdata('message','update_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('message','update_successful');
		return TRUE;
	}

	/**
	* delete_user
	*
	* @return bool
	* @author Phil Sturgeon
	**/
	public function delete_attendee($id)
	{
		$this->db->trans_begin();

		// delete user from users table should be placed after remove from group
		$this->db->delete("attendee", array('attendee_id' => $id));


		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('delete_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('delete_successful');
		return TRUE;
	}
}
