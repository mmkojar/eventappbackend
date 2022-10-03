<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MyCustom404Ctrl extends Admin_Controller{
    public function __construct()
    {
		parent::__construct();
		$this->data['current_tab'] = 'Error 404';
	}

    public function index(){
        $this->render('error404');
    }
}

?>