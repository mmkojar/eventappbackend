<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class User extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->library('ion_auth');
	$this->load->library('form_validation');
	$this->data['current_tab'] = 'users';
  }
 
  public function index()
  {
  }
 
 public function login()
{
  $this->data['page_title'] = 'Login';
  if($this->input->post())
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('identity', 'Identity', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('remember','Remember me','integer');
    if($this->form_validation->run()===TRUE)
    {
      $remember = (bool) $this->input->post('remember');
      $authlogin = $this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember);
     
      if ($authlogin)
      {
		$this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('admin', 'refresh');
      }
      else
      {
        $this->session->set_flashdata('message',$this->ion_auth->errors());
        redirect('admin/user/login', 'refresh');
      }
    }
  }else{	  
	  if($this->ion_auth->logged_in())
	  {		
		redirect('admin');
	  }
	  $this->load->helper('form');
	  $this->render('admin/login_view','master');
  }
}

	public function logout()
	{
	  $this->ion_auth->logout();
	  redirect('admin/user/login', 'refresh');
	}
	
	public function profile()
	{
	  if(!$this->ion_auth->logged_in())
	  {
		redirect('admin');
	  }
	  $this->data['page_title'] = 'User Profile';
	  $user = $this->ion_auth->user()->row();
	  $this->data['user'] = $user;
	  $this->data['current_user_menu'] = '';
	  if($this->ion_auth->in_group('admin'))
	  {
		$this->data['current_user_menu'] = $this->load->view('public/themes/default/slider', NULL, TRUE);
	  }
	 
	  $this->load->library('form_validation');
	  $this->form_validation->set_rules('first_name','First name','trim');
	  $this->form_validation->set_rules('last_name','Last name','trim');
	  $this->form_validation->set_rules('company','Company','trim');
	  $this->form_validation->set_rules('phone','Phone','trim');
	 
	  if($this->form_validation->run()===FALSE)
	  {
		$this->render('admin/user/profile_view','master');
	  }
	  else
	  {
		$new_data = array(
		  'first_name' => $this->input->post('first_name'),
		  'last_name' => $this->input->post('last_name'),
		  'company' => $this->input->post('company'),
		  'phone' => $this->input->post('phone')
		);
		if(strlen($this->input->post('password'))>=6) $new_data['password'] = $this->input->post('password');
		$this->ion_auth->update($user->id, $new_data);
	 
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('admin/user/profile','refresh');
	  }
	}
	
	
	
	public function changepassword()
	{
		$this->load->helper('form');
		 $this->load->library('form_validation');
		$this->form_validation->set_rules('old', 'old', 'required');
		$this->form_validation->set_rules('new', 'new', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', 'new_confirm', 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('admin/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			$this->render('admin/user/changepass');
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('admin/user/changepassword', 'refresh');
			}
		}
	}
	
	public function forgot()
	{
		$this->load->helper('form');
		$this->render('admin/forgot_view','master');
	}
	
	public function forgot_password()
	{
		// setting validation rules by checking whether identity is username or email
		if($this->config->item('identity', 'ion_auth') != 'email' )
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() == false)
		{
			$this->data['type'] = $this->config->item('identity','ion_auth');
			// setup the input
			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			if ( $this->config->item('identity', 'ion_auth') != 'email' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->render('admin/forgot_view','master');
		}
		else
		{
			$identity_column = $this->config->item('identity','ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if(empty($identity)) {

				if($this->config->item('identity', 'ion_auth') != 'email')
				{
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				}
				else
				{
				   $this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("admin/user/forgot_password", 'refresh');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("admin/user/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("admin/user/forgot_password", 'refresh');
			}
		}
	}
}