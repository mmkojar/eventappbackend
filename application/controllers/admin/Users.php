<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Users extends Admin_Controller
{
 
  function __construct()
  {
    parent::__construct();
    $this->load->helper('imageupload');
	  $this->data['current_tab'] = 'users';	
	  if($this->ion_auth->in_group('user'))
    {
      $this->session->set_flashdata('error','You are not allowed to visit the Users page');
      redirect('admin','refresh');
    }
  }
 	
	 public function index($group_id = NULL)
	{
		$this->data['dttable_tab'] = 'dt_table';
		$this->data['tbl_name'] = 'users/users_list';
		if($this->ion_auth->in_group('sub admin')){
			$group_id = "3";
		}
		$this->data['page_title'] = 'Users';
		
		//$this->data['users'] = $this->ion_auth->users($group_id)->result();
		$this->render('admin/users/list_users_view');
	}
	
	public function users_list()
	{
		$groups = array("1","2");
		$list = $this->ion_auth->get_datatables($groups);
		
		$i = 1;
// 		print_r($list);
// 		die();
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $user) {
		        if($user->gid !== '1' || $user->id == $this->ion_auth->user()->row()->id) {
		            $actionBtns = anchor('admin/users/edit/'.$user->id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.
				                anchor('admin/users/delete/'.$user->id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon delete" onclick="return confirm(\'Are You Sure ?\')" ');
		        }
		        else {
		            $actionBtns = "";
		        }
				$no++;
				$row = array();
				$row[] = $i;
				$row[] = $user->username;
				$row[] = $user->first_name.' '.$user->last_name;
				$row[] = $user->email;
				$row[] = $user->company;
				$row[] = $user->phone;
				$row[] = ($user->gid == '1' ? '<span class="text-info">Admin</span>' : '<span class="text-warning">User</span>');
			    $row[] = ($user->active == '1' ? '<span class="badge badge-success text-white">Active</span>' : '<span class="badge badge-danger text-white">Inactive</span>');
				$row[] = date('jS-M-Y',strtotime($user->created_on));
				$row[] = $actionBtns;
				$i++;
				$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->ion_auth->count_all($groups),
			"recordsFiltered" => $this->ion_auth->count_filtered($groups),
			"data" => $data,
		);			
		
		
		//output to json format
		echo json_encode($output);
	}
 
  public function create()
	{
	  $this->data['page_title'] = 'Create user';
	  $this->load->library('form_validation');
	  $this->form_validation->set_rules('first_name','First name','trim|required');
	  $this->form_validation->set_rules('last_name','Last name','trim|required');
	  $this->form_validation->set_rules('company','Company','trim');
	  $this->form_validation->set_rules('phone','Phone','trim|required|regex_match[/^[0-9]{10}$/]|is_unique[users.phone]');
	  $this->form_validation->set_rules('username','Username','trim|required|is_unique[users.username]');
	  $this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[users.email]');
	  $this->form_validation->set_rules('password','Password','required');
	  $this->form_validation->set_rules('password_confirm','Password confirmation','required|matches[password]');
	  $this->form_validation->set_rules('groups[]','Groups','required|integer');
	  $this->form_validation->set_rules('city','City','trim|required');
	 
	  if($this->form_validation->run()===FALSE)
	  {
        $this->data['groups'] = $this->ion_auth->groups()->result();
        //$this->data['malls'] = $this->malls_model->get_malls();
        $this->load->helper('form');
        $this->render('admin/users/create_user_view');
	  }
	  else
	  {       
			  $upload_dir = './assets/upload/images/users/';
        $fileUploadError = [];
        if($_FILES['user_image']['name'] != "" || $_FILES['user_image']['name'] != null){
          $ext = pathinfo($_FILES['user_image']['name'], PATHINFO_EXTENSION);
          $file_name=date("dmY").time().'_'.$_FILES['user_image']['name'];

          $fileUpload = ImageUpload("user_image",$file_name,$upload_dir);          
          array_push($fileUploadError,$fileUpload);
        }else{
          $fileUploadError[0] = ['status'=>'1' ,'msg' => 'File Uploaded'];
          $file_name = "user.png";
        }

        if($fileUploadError[0]['status'] == '0') {
          $this->session->set_flashdata('error',$fileUploadError[0]['msg']);
          $this->data['groups'] = $this->ion_auth->groups()->result();
          $this->load->helper('form');
          $this->render('admin/users/create_user_view');
        }
        else {
          $username = $this->input->post('username');
          $email = $this->input->post('email');
          $password = $this->input->post('password');
          $group_ids = $this->input->post('groups');
        
          $additional_data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'company' => $this->input->post('company'),
            'phone' => $this->input->post('phone'),
            'city' => $this->input->post('city'),
            'user_image' => $upload_dir."".str_replace(' ','_',$file_name)
          );

          $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids);
          $this->session->set_flashdata('success',$this->ion_auth->messages());
          redirect('admin/users','refresh');
        }
	  }
	}
	 
public function edit($user_id = NULL)
{
  $user_id = $this->input->post('user_id') ? $this->input->post('user_id') : $user_id;
  $this->data['page_title'] = 'Edit user';
  $this->load->library('form_validation');
  
  $this->form_validation->set_rules('first_name','First name','trim');
  $this->form_validation->set_rules('last_name','Last name','trim');
  $this->form_validation->set_rules('company','Company','trim');
  $this->form_validation->set_rules('phone','Phone','trim');
  $this->form_validation->set_rules('username','Username','trim|required');
  $this->form_validation->set_rules('email','Email','trim|required|valid_email');
  $this->form_validation->set_rules('password','Password','min_length[6]');
  $this->form_validation->set_rules('password_confirm','Password confirmation','matches[password]');
  $this->form_validation->set_rules('groups[]','Groups','required|integer');
  $this->form_validation->set_rules('user_id','User ID','trim|integer|required');
  $this->form_validation->set_rules('city','City','trim|required');
  $this->form_validation->set_rules('status','Status','required');
 
  if($this->form_validation->run() === FALSE)
  {
    if($user = $this->ion_auth->user((int) $user_id)->row())
    {
      $this->data['user'] = $user;
    }
    else
    {
      $this->session->set_flashdata('message', 'The user doesn\'t exist.');
      redirect('admin/users', 'refresh');
    }
    $this->data['groups'] = $this->ion_auth->groups()->result();
    $this->data['usergroups'] = array();
    if($usergroups = $this->ion_auth->get_users_groups($user->id)->result())
    {
      foreach($usergroups as $group)
      {
        $this->data['usergroups'][] = $group->id;
      }
    }
    $this->load->helper('form');
    $this->render('admin/users/edit_user_view');
  }
  else
  {   
      $hidden_image = $this->input->post('hidden_image');
      $upload_dir = './assets/upload/images/users/';
      $fileUploadError = [];
      if($_FILES['user_image']['name'] != "" || $_FILES['user_image']['name'] != null){
        $ext = pathinfo($_FILES['user_image']['name'], PATHINFO_EXTENSION);
        $file_name=date("dmY").time().'_'.$_FILES['user_image']['name'];

        $fileUpload = ImageUpload("user_image",$file_name,$upload_dir);          
        array_push($fileUploadError,$fileUpload);
        if($fileUploadError[0]['status'] !== '0') {
          if($hidden_image !== './assets/upload/images/users/user.png') {
            if(file_exists($hidden_image)) {
              unlink($hidden_image);
            }
          }
        }
        $filenametoupload = $upload_dir."".$file_name;
      }else{
        $fileUploadError[0] = ['status'=>'1' ,'msg' => 'File Uploaded'];
        $filenametoupload = $hidden_image ? $hidden_image : $upload_dir."".'user.png';
      }

    // $user_id = $this->input->post('user_id');
    if($fileUploadError[0]['status'] == '0') {
      $this->session->set_flashdata('error',$fileUploadError[0]['msg']);
      redirect('admin/users/edit/'.$user_id,'refresh');
    }
    else {      
      $new_data = array(
        'username' => $this->input->post('username'),
        'email' => $this->input->post('email'),
        'phone' => $this->input->post('phone'),
        'first_name' => $this->input->post('first_name'),
        'last_name' => $this->input->post('last_name'),
        'company' => $this->input->post('company'),
        'city' => $this->input->post('city'),
        'active' => $this->input->post('status'),
        'user_image' => str_replace(' ','_',$filenametoupload)
      );
      
      if(strlen($this->input->post('password'))>=6) $new_data['password'] = $this->input->post('password');
  
      $this->ion_auth->update($user_id, $new_data);
  
      //Update the groups user belongs to
      $groups = $this->input->post('groups');
      if (isset($groups) && !empty($groups))
      {
        $this->ion_auth->remove_from_group('', $user_id);
        foreach ($groups as $group)
        {
          $this->ion_auth->add_to_group($group, $user_id);
        }
      }
  
      $this->session->set_flashdata('success',$this->ion_auth->messages());
      redirect('admin/users','refresh');
    }
  }
}
 
public function delete($user_id = NULL)
{
  if(is_null($user_id))
  {
    $this->session->set_flashdata('message','There\'s no user to delete');
  }
  else
  {
	if($this->ion_auth->in_group('admin'))
	{
    $hidden_image = $this->ion_auth->user($user_id)->row()->user_image;
    if($hidden_image !== './assets/upload/images/users/user.png') {
      if(file_exists($hidden_image)) {
        unlink($hidden_image);
      }
    }
		$this->ion_auth->delete_user($user_id);    
		$this->session->set_flashdata('success', $this->ion_auth->messages());
	}else{
		$this->session->set_flashdata('message1','A request Has been Sent to Administrator to Delete the following content.');
	}
  }
  redirect('admin/users','refresh');
}


public function activate($user_id = NULL)
{
  if(is_null($user_id))
  {
    $this->session->set_flashdata('message','There\'s no user to Activate');
  }
  else
  {
	$user = $this->ion_auth->user((int) $user_id)->row();
    $this->ion_auth->activate($user_id,$user->activation_code);
    $this->session->set_flashdata('success',$this->ion_auth->messages());
  }
  redirect('admin/users','refresh');
}

public function deactivate($user_id = NULL)
{
  if(is_null($user_id))
  {
    $this->session->set_flashdata('message','There\'s no user to Activate');
  }
  else
  {
    $this->ion_auth->deactivate($user_id);
    $this->session->set_flashdata('error',$this->ion_auth->messages());
  }
  redirect('admin/users','refresh');
}

}