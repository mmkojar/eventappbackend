<?php
 
class Event_selection extends Public_Controller
{
	protected $data = array();
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	public function index($id=null,$qr_id=null,$scan_by=null)
	{
		if($id==null||$qr_id==null||$scan_by==null){
			exit('Invalid URL');
		}

		$this->data['list'] = [
			'is_hotel_check_in' => 'Hotel Check in',
			'is_welcome_gift' => 'Welcome Gift',
			'is_golden_temple' => 'Golden Temple ',
			'is_sada_pind' => 'Sada pind ',
			'is_wagah_border' => 'Attari Border',
			'is_return_gift' => 'Return Gift',
		];
		$getScanned = $this->db->query('SELECT * FROM qr_scan_entries where user_id='.$id)->row_array();
		// echo "<pre>";
		// print_r($getScanned);
		// die();
		$this->data['result'] = $getScanned;
		$this->data['uid'] = $id;
		$this->data['qr_id'] = $qr_id;
		$this->data['scan_by'] = $scan_by;
		$this->load->view('event_selection_view',$this->data);
	}
	
	
	public function add () {
		
		// print_r($_POST);
		// die();
		if(count($_POST) < 4) {
			$this->session->set_flashdata('event_er','Please select all checkbox');
			redirect('select_event/'.$_POST['user_id'].'/'.$_POST['qr_id'].'/'.$_POST['scan_by']);
		}
		$data = array(
			'user_id' => $_POST['user_id'],
			'qr_id' => $_POST['qr_id'],
			'is_hotel_check_in' => isset($_POST['is_hotel_check_in'])&&$_POST['is_hotel_check_in']=='on'?1:0,
			'is_welcome_gift' => isset($_POST['is_welcome_gift'])&&$_POST['is_welcome_gift']=='on'?1:0,
			'is_return_gift' => isset($_POST['is_return_gift'])&&$_POST['is_return_gift']=='on'?1:0,
			'is_sada_pind' => isset($_POST['is_sada_pind'])&&$_POST['is_sada_pind']=='on'?1:0,
			'is_golden_temple' => isset($_POST['is_golden_temple'])&&$_POST['is_golden_temple']=='on'?1:0,
			'is_wagah_border' => isset($_POST['is_wagah_border'])&&$_POST['is_wagah_border']=='on'?1:0,
			'scan_by' => $_POST['scan_by'],
		);
		$chkexits = $this->db->query('SELECT * FROM qr_scan_entries where user_id='.$_POST['user_id'])->result_array();
		if($chkexits) {
			$this->db->update("qr_scan_entries", $data, array('user_id' => $_POST['user_id']));
		} else {
			$this->db->insert('qr_scan_entries', $data);
		}
		

		$id = $this->db->insert_id();
		$this->session->set_flashdata('event_sc','Data Submitted');
		redirect('select_event/'.$_POST['user_id'].'/'.$_POST['qr_id'].'/'.$_POST['scan_by']);

	}

}