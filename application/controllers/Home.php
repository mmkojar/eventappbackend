<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Admin_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
      function __construct()
      {
        parent::__construct();
        $data["before_head"] = '';
		$data["page_title"] = 'Tech';
    	$this->data['current_tab'] = 'dashboard';
    	$this->load->model('dashboard_model');
      }
	 
	public function index()
	{
		
		$this->data['total_users'] = $this->dashboard_model->get_total_users();
		$this->data['active_users'] = $this->dashboard_model->get_count('devices');
		$this->data['total_qr'] = $this->dashboard_model->get_count('qr_code');
		$this->data['total_polls'] = $this->dashboard_model->get_count('polls');
		$this->render('admin/dashboard_view');
	}
}
