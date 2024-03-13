<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Gallery extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('gallery_model');
		// $this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('imageupload');
		$this->data['current_tab'] = 'gallery';
	}

    public function index($user_id = null)
	{
		$this->data['dttable_tab'] = 'dt_table';
		$this->data['tbl_name'] = 'gallery/gallery_list';
		$this->data['page_title'] = 'gallerys';
		if(isset($user_id) && !empty($user_id)){
			$this->data['user_id'] = $user_id;		  
		}
		 
	  $this->render('admin/gallery/list_gallery_view');
	}
	
	public function gallery_list()
	{
		$list = $this->gallery_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$src = base_url()."".str_replace("./", "", $requested->image);
			$no++;
			$row = array();
			$row[] = $requested->id;
			$row[] = $requested->title;
			$row[] = '<img src="'.$src.'" height="100" width="100">';
			$row[] = $requested->status == '1' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
			$row[] = date('jS-M-Y',strtotime($requested->created_on));
			$row[] = anchor('admin/gallery/edit/'.$requested->id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.anchor('admin/gallery/delete/'.$requested->id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"');
			$i++;
			$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->gallery_model->count_all(),
			"recordsFiltered" => $this->gallery_model->count_filtered(),
			"data" => $data,
		);
		
		//output to json format
		echo json_encode($output);
	}

	public function create()
	{
		$this->data['page_title'] = 'Add Images';
        
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('title','Title','trim|required');
		// $this->form_validation->set_rules('image','Image','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/gallery/create_gallery_view');
		}
		else
		{	
			
			$register_data["image"] ="";
			$upload_dir = './assets/upload/images/gallery/';
			
			if(!empty($_FILES['image'])) {
				if($_FILES['image']['name'] != "" || $_FILES['image']['name'] != null){
					$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
					$file_name=date("dmY").time().'_'.$_FILES['image']['name'];
                    
					$fileUpload = ImageUpload("image",$file_name,$upload_dir);
					if($fileUpload['status'] == '0') {
						$this->session->set_flashdata('error',$fileUpload['msg']);
						$this->render('admin/gallery/create_gallery_view');
					}
					else {
						$register_data["title"] = $this->input->post('title');
						$register_data["image"] = $upload_dir."".str_replace(' ','_',$file_name);

						$this->gallery_model->register_gallery($register_data);
						$this->session->set_flashdata('success','Images Added Successfully');
						redirect('admin/gallery','refresh');
					}
									
				}else{
					$register_data["image"] = "";
				}
			}

            // Multiple Upload
            /* foreach ($_FILES['image']['name'] as $key => $image) {
                $_FILES['images[]']['name']= $_FILES['image']['name'][$key];
                $_FILES['images[]']['type']= $_FILES['image']['type'][$key];
                $_FILES['images[]']['tmp_name']= $_FILES['image']['tmp_name'][$key];
                $_FILES['images[]']['error']= $_FILES['image']['error'][$key];
                $_FILES['images[]']['size']= $_FILES['image']['size'][$key];
                
                $fileName = $title .'_'. $image;
    
                $images[] = $fileName;
    
                $config['file_name'] = $fileName;
    
                $this->upload->initialize($config);
    
                if ($this->upload->do_upload('images[]')) {
                    $this->upload->data();
                } else {
                    return false;
                }
            } */
            
		}
	}

	public function edit($id)
	{
		$this->data['page_title'] = 'Edit Image';		
		
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('title','Title','trim|required');
		// $this->form_validation->set_rules('image','Image','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->data['result'] = $this->gallery_model->get_gallery($id);
			$this->render('admin/gallery/edit_gallery_view');
		}
		else
		{
			$register_data["image"] = "";
			$upload_dir = './assets/upload/images/gallery/';
			
			if(!empty($_FILES['image'])) {
				if($_FILES['image']['name'] != "" || $_FILES['image']['name'] != null){
					$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
					$file_name=date("dmY").time().'_'.$_FILES['image']['name'];
											
					$fileUpload = ImageUpload("image",$file_name,$upload_dir);
					if($fileUpload['status'] == '0') {
						$this->session->set_flashdata('error',$fileUpload['msg']);
						redirect('admin/gallery/edit/'.$id,'refresh');
					}
					if(file_exists($this->input->post('hidden_image'))) {
						unlink($this->input->post('hidden_image'));
					}
					$register_data["image"] = $upload_dir."".str_replace(' ','_',$file_name);				
				}else{
					$register_data["image"] = $this->input->post('hidden_image');
				}
			}		

			$register_data["title"] = $this->input->post('title');
			$register_data["status"] = $this->input->post('status');
			$register_data["updated_on"] = date("Y-m-d H:i:s");

			$this->gallery_model->update($id, $register_data);
			$this->session->set_flashdata('success','gallery Updated Successfully');
			redirect('admin/gallery','refresh');
		}
	}

	public function delete($id = NULL)
	{
		if(is_null($id))
		{
			$this->session->set_flashdata('error','There\'s no gallery to delete');
		}
		else
		{
			$user = $this->gallery_model->get_gallery($id);			
			if(file_exists($user['image'])) {
				unlink($user['image']);
			}
			$this->gallery_model->delete_($id);
		}
		redirect('admin/gallery','refresh');
	}		
}