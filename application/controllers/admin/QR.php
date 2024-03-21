<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// ini_set('max_execution_time', 0); 
 
class QR extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();
		$this->load->model('QR_model');
		if($this->ion_auth->in_group('members') || $this->ion_auth->in_group('security'))
		{
		  $this->session->set_flashdata('error','You are not allowed to visit the Message Notification page');
		  redirect('admin','refresh');
		}
	}
 
	public function index()
	{
		$this->data['dttable_tab'] = 'dt_table';
		$this->data['current_tab'] = 'qr_code';
		$this->data['tbl_name'] = 'QR/qr_list';
		$this->load->helper('form');
		$this->data['page_title'] = 'All QR Codes';
		// $this->data['qr_codes'] = $this->QR_model->qr_codes();
		
		$this->render('admin/qr_codes/list_qr_code_view');
	}
	
	public function qr_entries() {
		
		$this->data['dttable_tab'] = 'dt_table';
		$this->data['current_tab'] = 'qr_code_entries';
		$this->data['tbl_name'] = 'QR/qr_entries_list';
		$this->load->helper('form');
		$this->data['page_title'] = 'QR Codes Entries';
		// $this->data['qr_codes'] = $this->QR_model->qr_codes();
		
		$this->render('admin/qr_codes/qr_event_entries_view');
	}
	
	public function qr_entries_list()
	{
		$list = $this->QR_model->get_datatables('2');
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $qr) {
			$no++;
			$row = array();
			$row[] = $i;
			$row[] = $qr->first_name.' '.$qr->last_name;
			$row[] = $qr->is_hotel_check_in=='1'?'Yes':'No';
			$row[] = $qr->is_welcome_gift=='1'?'Yes':'No';
			$row[] = $qr->is_return_gift=='1'?'Yes':'No';
			$row[] = $qr->is_sada_pind=='1'?'Yes':'No';
			$row[] = $qr->is_golden_temple=='1'?'Yes':'No';
			$row[] = $qr->is_wagah_border=='1'?'Yes':'No';
			$row[] = $qr->scan_by;
			$row[] = date('jS-M-Y',strtotime($qr->created_at));
			$i++;
			$data[] = $row;
		}
		
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->QR_model->count_all('qr_scan_entries','2'),
				"recordsFiltered" => $this->QR_model->count_filtered('2'),
				"data" => $data,
			);
		
		//output to json format
		echo json_encode($output);
	}

	public function qr_list()
	{
		$list = $this->QR_model->get_datatables('1');
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $qr) {
			$no++;
			$row = array();
			$row[] = $i;
			$row[] = $qr->first_name.' '.$qr->last_name;
			$row[] = '<img src="'.base_url().$qr->filename.'">';
			$row[] = ($qr->status == '1') ? anchor('admin/QR/updateStatus/'.$qr->id.'/0','Active','class="badge badge-success text-white"') : anchor('admin/QR/updateStatus/'.$qr->id.'/1','Inactive','class="badge badge-danger text-white"');
			$row[] = date('jS-M-Y',strtotime($qr->created_on));
			// $row[] = anchor('admin/QR/edit/'.$qr->id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"')." ".anchor('admin/QR/delete/'.$qr->id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove"');
			$row[] = anchor('admin/QR/delete/'.$qr->id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"');
			
			$i++;
			$data[] = $row;
		}
		
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->QR_model->count_all('qr_code','1'),
				"recordsFiltered" => $this->QR_model->count_filtered('1'),
				"data" => $data,
			);
		
		//output to json format
		echo json_encode($output);
	}
	
	public function create()
	{
		$this->data['current_tab'] = 'qr_code';
		$this->data['page_title'] = 'Add QR';
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_id[]','Name','required');
		// $this->form_validation->set_rules('user_id','Name','trim|required|is_unique[qr_code.user_id]');
		// $this->form_validation->set_rules('status','Status','trim|required');

		if($this->form_validation->run()===FALSE)
		{
			$this->data['users'] = $this->QR_model->get_users();
			$this->load->helper('form');
			$this->render('admin/qr_codes/create_qr_view');
		}
		else
		{
			unset($_POST['submit'],$_POST['status']);
			$upload_dir = './assets/upload/QR/';
			// $user_id = $this->input->post('user_id');

			for($i=0; $i<count($_POST['user_id']); $i++) {
				
				$user = $this->QR_model->get_users($_POST['user_id'][$i]);
							
				$uniqid = substr($user['username'],0,5).''.substr(uniqid('', false),8);
				$status = 1;
				$filename = $upload_dir.''.$uniqid.'.png';
				$contents = $uniqid.','.$_POST['user_id'][$i].','.$user['username'];
				
				$this->load->helper('qr');
				generate_qr($contents, $filename);

				$data = array(
					'uniq_id' => $uniqid,
					'user_id' => $_POST['user_id'][$i],
					'filename' => $filename,
					// 'size' => $size,
					'status' => $status,
					'created_on' => date("Y-m-d H:i:s")
				);

				$this->QR_model->register($data);	
			}		
			$this->session->set_flashdata('success','QR Added Successfully');
			redirect('admin/QR','refresh');
		}
	}

	public function updateStatus($id,$st) {
				
   		$data = ['status' => $st];
   		
   		$this->QR_model->update($id,$data);
		
		$this->session->set_flashdata('success','Status Updated');
		redirect('admin/QR','refresh');
	}

	/* public function edit($id = NULL)
	{
		$id = $this->input->post('id') ? $this->input->post('id') : $id;

		$this->data['page_title'] = 'Edit QR';
		$this->load->library('form_validation');
		$this->form_validation->set_rules('status','Status','trim|required');	
		
		if($this->form_validation->run()===FALSE){
						
			if($qr_code = $this->QR_model->qr_code('id',(int) $id))
			{
				$this->data['qr_code'] = $qr_code;
			}
			$this->load->helper('form');
			$this->render('admin/qr_codes/edit_qr_view');
		
		}else{
			
			$data = array(
				'status' => $this->input->post('status')
			);
			
			$this->QR_model->update($id, $data);

			redirect('admin/QR','refresh');
		}
	} */


	public function delete($id = NULL)
	{
		if(is_null($id))
		{
			$this->session->set_flashdata('error','There\'s no QR Code to delete');
		}
		else
		{
			$user = $this->QR_model->qr_code('id',$id);
			if(file_exists($user['filename'])) {
				unlink($user['filename']);
			}
			$this->QR_model->delete_($id);
		}
		$this->session->set_flashdata('success', 'QR Successfully Deleted');
		redirect('admin/QR','refresh');
	}

}