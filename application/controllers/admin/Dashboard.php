<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
 
  function __construct()
  {
    parent::__construct();
	$this->data['current_tab'] = 'dashboard';
	$this->load->model('dashboard_model');
	$this->load->helper('form');
  }
 
	public function index()
	{
		if($this->ion_auth->in_group('members')){
			
			$this->render('admin/dashboard_user_view');
		}else{
			$group_id = '3';
			$this->data['users'] = $this->ion_auth->users($group_id)->result();
			$this->data['total_users'] = $this->dashboard_model->get_total_users();
			$this->data['active_users'] = $this->dashboard_model->get_count('devices');
			$this->data['total_qr'] = $this->dashboard_model->get_count('qr_code');
			$this->data['total_polls'] = $this->dashboard_model->get_count('polls');
			$this->render('admin/dashboard_view');
		}
	}
  
}