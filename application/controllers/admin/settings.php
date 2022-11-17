<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Admin_Controller
{
 
  function __construct()
  {
    parent::__construct();
	$this->data['current_tab'] = 'settings';
	$this->load->helper('imageupload');
	$this->load->helper('form');
	$this->load->helper('form');
  }
 
	public function index()
	{
		$data = array();
        $stmt = "SELECT a.key,a.value"
                . " FROM system_settings AS a";
        $query = $this->db->query($stmt);
        if ($query->num_rows()) {
            $data = $query->result_array();
        }
		$pusha['key'] = [];
		$pushb['value'] = [];
		foreach ($data as $key => $val) {
			array_push($pusha['key'], $val['key']);
			array_push($pushb['value'], $val['value']);
		}
		$res = array_combine($pusha['key'], $pushb['value']);

		$this->data['res'] = $res;
		$this->render('admin/settings_view');
	}
	
	public function store() {
				
		$data = $this->input->post();
		$updated = [];
		/* echo '<pre>';
		print_r($data);
		print_r($_FILES);
		die(); */
		foreach ($_FILES as $key => $value) 
		{
			$upload_dir = './assets/upload/images/icons/';
			$fileUploadError = [];
			
			if($_FILES[$key]['name'] != "" || $_FILES[$key]['name'] != null){
				$ext = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);
				$file_name=date("dmY").'_'.time().'_'.$_FILES[$key]['name'];
				$fileUpload = ImageUpload($key,$file_name,$upload_dir);
				

				$val = $upload_dir."".str_replace(' ','_',$file_name);
				array_push($fileUploadError,$fileUpload);
				array_push($updated, $fileUpload);
				
				if($fileUploadError[0]['status'] == '1') {
					$checkCount = $this->db->query("SELECT * FROM `system_settings` WHERE `key` = '$key' ");
					$select_result = $checkCount->row_array();
					$table_data=[];
					if($select_result)
					{
						foreach ($data as $dkey => $dval) {							
							if(($key == $dkey) && ($_FILES[$key]['error'] == '0')) {
								if(file_exists($dval)) {
									unlink($dval);
								}
							}
						}
						$this->db->where('key',$key);
						$this->db->update('system_settings',array('value'=>$val,'update_date'=>date('Y-m-d')));

					}
					else 
					{
						$table_data['key']    = $key;
						$table_data['value']  = $val;
						$this->db->insert('system_settings', $table_data);
					}
				}
				else{
					$this->session->set_flashdata('error',$fileUploadError[0]['msg']);
					redirect('admin/settings');
				}
			}
			// else{
			// 	foreach ($data as $key => $val) {
			// 		$this->db->where('key',$key);
			// 		$this->db->update('system_settings',array('value'=>$val));
			// 	}
			// }			
		}
		
		if($data){
			$table_data=[];
			foreach ($data as $key => $val) {
				if($key !== 'submit' && $key !== 'lc_logo' && $key !== 'du_image' && $key !== 'hp_logo' &&  $key !== 'about_file' && $key !== 'agenda_file' && $key !== 'delg_file' && $key !== 'chat_file' 
					&& $key !== 'notify_file' && $key !== 'polls_file' && $key !== 'qr_file' && $key !== 'speaker_file' && $key !== 'sponsors_file' && $key !== 'exhi_file' && $key !== 'faq_file' && $key !== 'support_file')
				{
					$table_data['key']        = $key;
					$table_data['value']      = $val;
					$table_data['update_date']  = date('Y-m-d');
					$table_data['status']       = 1;
					$checkCount = $this->db->query("SELECT * FROM `system_settings` WHERE `key` = '$key' ");
					$select_result = $checkCount->row_array();		
					array_push($updated,['status'=>'1' ,'msg' => 'Data Updated']);			
					if($select_result)
					{
						$this->db->where('key',$key);
                        $this->db->update('system_settings',array('value'=>$val));
					}
					else {
						$this->db->insert('system_settings', $table_data);
					}
				
				}
			}
		}
		if($updated[0]['status'] == '1') {
			$this->session->set_flashdata('success','Settings Updated Successfully');
			redirect('admin/settings');
		}		
	}

}