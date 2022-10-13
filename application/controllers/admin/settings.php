<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Admin_Controller
{
 
  function __construct()
  {
    parent::__construct();
	$this->data['current_tab'] = 'settings';
	// $this->load->model('dashboard_model');
	$this->load->helper('form');
  }
 
	public function index()
	{
		$this->render('admin/settings_view');
	}
  
}