<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Polling extends Admin_Controller
{
 
	function __construct()
	{
		parent::__construct();

		$this->load->model('Polling_model');
		// $this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->data['current_tab'] = 'Polling';
	}

    public function index()
	{
		$this->data['dttable_tab'] = 'jqdatatable';
	  $this->data['page_title'] = 'Polls';
	  $this->data['polls'] = $this->Polling_model->get();
	  
		 
	  $this->render('admin/polling/list_polls_view');
	}
	
	/*public function speaker_list()
	{
		$list = $this->Polling_model->get_datatables();
		
		$i = 1;
		//print_r($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $requested) {
			$src = base_url()."".str_replace("./", "", $requested->image);
			$no++;
			$row = array();
			$row[] = $requested->id;
			$row[] = $requested->name;
			$row[] = '<img src="'.$src.'" height="100" width="100">';		
			$row[] = $requested->company_name;
			$row[] = $requested->designation;
			$row[] = $requested->status == '1' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
			$row[] = date('jS-M-Y',strtotime($requested->created_on));
			$row[] = anchor('admin/speaker/edit/'.$requested->id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"').' '.anchor('admin/speaker/delete/'.$requested->id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove"');
			$i++;
			$data[] = $row;
		}
		
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Polling_model->count_all(),
			"recordsFiltered" => $this->Polling_model->count_filtered(),
			"data" => $data,
		);
		
		//output to json format
		echo json_encode($output);
	}*/

	public function create()
	{

		$this->data['page_title'] = 'Add Polls';
	  
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('title','Title','trim|required');
		$this->form_validation->set_rules('choice','Choices','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->render('admin/polling/create_polls_view');
		}
		else
		{			

			$data = array(
				'title	'   => $_POST["title"],
				'status'   => 1,
			    'created_at' => date("Y-m-d H:i:s")
			);	
			$this->db->insert('polls', $data);
			$poll_id = $this->db->insert_id();

			$choiceArray = explode(',', $_POST['choice']);
			foreach($choiceArray as $choice) {
				$choices = array(
					'poll_id' => $poll_id,
					'choice	'   => $choice,					
				    'created_at' => date("Y-m-d H:i:s")
				);
				$this->db->insert('poll_answers', $choices);	
			}
			
			$this->session->set_flashdata('success','Poll Added Successfully');
			redirect('admin/polling','refresh');
		}
	}
		
	public function view($id) {

		$result = $this->Polling_model->vote_result($id);
		
		if(!$result) {
			$this->session->set_flashdata('error','No Poll Found');
			redirect('admin/polling','refresh');
		}
		$total_votes = 0;
        foreach($result['poll_answers'] as $res) {
            $total_votes += $res['votes'];
        }
        $this->data['total_votes'] = $total_votes;
        $this->data['poll_id'] = $id;
        $this->data['poll_answers'] = $result['poll_answers'];
        $this->data['title'] = $result['title'];
        $this->render('admin/polling/result_view');
	}

	public function updateVotes($poll_id) {

		/* $this->db->select("*");
		$this->db->from("poll_answers");
		$this->db->where("poll_answers.id",$_POST['id']);
		$this->db->order_by('poll_answers.created_at', 'DESC');
		$query=$this->db->get();
		$poll = $query->row(); */
		if(!$poll_id) {
			$this->session->set_flashdata('error','No Poll Id specified');
			redirect('admin/polling','refresh');
		}
		else {
			$result = $this->Polling_model->vote_result($poll_id);
		
			if(!$result) {
				$this->session->set_flashdata('error','No Poll Found');
				redirect('admin/polling','refresh');
			}
			else {
				$user_id = $this->ion_auth->user()->row()->id;
				$query = $this->db->query('select * from poll_users_votes where user_id='.$user_id.' && poll_id='.$poll_id);
				$res = $query->num_rows();
				
				if($res == 0) {
					$user_votes = array(
						'user_id' => $user_id,
						'poll_id' => $poll_id,
						'poll_answer_id' => $_POST['id'],
						'created_at' => date('Y-m-d h:i:s')
					);
					$polls = $this->Polling_model->update_votes($_POST['id'],$user_votes);
					$this->session->set_flashdata('success','Vote Added Successfully');
					redirect('admin/polling','refresh');
				}
				else {
					$this->session->set_flashdata('error','Your Already submit this poll');
					redirect('admin/polling','refresh');
				}
				
			}						
		}
	}

	public function updateStatus($id,$st) {
				
		$status = ['status' => $st];
		$this->Polling_model->update_status($id,$status);
		$this->session->set_flashdata('success','Status Updated');
		redirect('admin/polling','refresh');
	}
	
	/*public function edit($id)
	{
		$this->data['page_title'] = 'Edit speaker';		
		
		$this->load->library('form_validation');
	  
		$this->form_validation->set_rules('name','speaker Name','trim|required');
		$this->form_validation->set_rules('company_name','Company Name','trim|required');
		$this->form_validation->set_rules('designation','Designation','trim|required');
	 
		if($this->form_validation->run()===FALSE)
		{
			//print_r(validation_errors());
			$this->load->helper('form');
			$this->data['speaker_data'] = $this->Polling_model->get_speaker($id);
			$this->render('admin/speaker/edit_speaker_view');
		}
		else
		{
			$register_data["image"] ="";
			$upload_dir = './assets/upload/images/speaker/';
			
			if(!empty($_FILES['speaker_image'])) {
				if($_FILES['speaker_image']['name'] != "" || $_FILES['speaker_image']['name'] != null){
					$ext = pathinfo($_FILES['speaker_image']['name'], PATHINFO_EXTENSION);
					$file_name=date("dmY").time().$_FILES['speaker_image']['name'];
					$this->image_upload("speaker_image",$file_name,$upload_dir);
					$register_data["image"] = $upload_dir."".$file_name;				
				}else{
					$register_data["image"] = $this->input->post('hidden_image');
				}
			}		

			$register_data["name"] = $this->input->post('name');
			$register_data["company_name"] = $this->input->post('company_name');
			$register_data["designation"] = $this->input->post('designation');
			$register_data["updated_on"] = date("Y-m-d H:i:s");

			$this->Polling_model->update($id, $register_data);
			$this->session->set_flashdata('success','speaker Updated Successfully');
			redirect('admin/speaker','refresh');
		}
	}*/

	public function delete($id = NULL)
	{
		if(is_null($id))
		{
			$this->session->set_flashdata('error','There\'s no poll to delete');
		}
		else
		{
			$this->Polling_model->delete_($id);
		}
		redirect('admin/polling','refresh');
	}

}