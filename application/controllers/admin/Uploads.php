<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Uploads extends Admin_Controller
{
 
  function __construct()
  {
    parent::__construct();
	$this->load->model('upload_model');
	$this->data['current_tab'] = 'uploads';
    if($this->ion_auth->in_group('members') || $this->ion_auth->in_group('security'))
    {
      $this->session->set_flashdata('error','You are not allowed to visit the Uploads page');
      redirect('admin','refresh');
    }
  }
 
    public function index($user_id = null){
		
		$this->data['page_title'] = 'Uploads';
		$this->render('admin/devices/list_excel_upload_view');
	}
	
	
	public function excel_list()
	{
		
		$list = $this->upload_model->get_datatables();
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $excel) {
			$no++;
			$row = array();
			$row[] = $i;
			/* $row[] = $excel->user_id; */
			$row[] = $excel->username;
			$row[] = $excel->filename;
			$row[] = $excel->type;
			$row[] = date('jS-M-Y',strtotime($excel->created_on));
			$row[] = $excel->status;
			$i++;
			$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->upload_model->count_all(),
			"recordsFiltered" => $this->upload_model->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
}