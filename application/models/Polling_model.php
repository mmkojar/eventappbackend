<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Polling_model extends CI_Model
{
	
	public function get() {
		$this->db->select("p.*,GROUP_CONCAT(pa.choice ORDER BY pa.id) as choice, p.id as poll_id");
		$this->db->from("polls p");
		$this->db->join("poll_answers pa", "pa.poll_id = p.id" ,"inner");
		// $this->db->where("p.status",'1');
		$this->db->group_by('p.id');
		$query=$this->db->get();
		return $query->result_array();
	}

	public function vote_result($id) {

		$check_polls = $this->db->query("select * from polls where id=".$id);
		$res = $check_polls->row();
		if($res) {
			$check_polls = $this->db->query("select * from poll_answers where poll_id=".$id." ORDER BY votes DESC");
			$poll_answers = $check_polls->result_array();		
			return ['poll_answers' => $poll_answers, 'title' => $res->title];
		}
	}
	
	public function check_user_votes($user_id, $pid) {
	    $query = $this->db->query('select * from poll_users_votes where user_id='.$user_id.' && poll_id='.$pid);
		return $query->row();
	}

	public function get_polls_api($id=NULL) {
    
		if($id) {
		    $this->db->select("polls.title,poll_answers.id as paid,poll_answers.poll_id as pid,poll_answers.choice,poll_answers.votes");
    		$this->db->from("polls");
    		$this->db->join("poll_answers", "poll_answers.poll_id = polls.id" ,"inner");
    		$this->db->where("polls.id",$id);
    		$this->db->order_by('poll_answers.votes', 'DESC');
		}
		else {
		    $this->db->select("p.*,GROUP_CONCAT(pa.choice ORDER BY pa.id) as choice, p.id as poll_id");
    		$this->db->from("polls p");
    		$this->db->join("poll_answers pa", "pa.poll_id = p.id" ,"inner");
    		$this->db->where("p.status",'1');
    		$this->db->group_by('p.id');
		}
		$query=$this->db->get();
		$rowcount =  $query->num_rows();
    	$result = [];
    	$total_votes = 0;
    	foreach ($query->result_array() as $row) {
    		$id && ($total_votes += $row['votes']);
    	}
    	foreach ($query->result_array() as $row) {
    		$id && ($row['total_votes'] = $total_votes);
    		array_push($result,$row);
    	}
        return $result;
	}

	public function update_votes($poll_id,$data) {

		$this->db->trans_begin();

		$this->db->where('id', $poll_id);
		$this->db->set('votes', 'votes+1', FALSE);
		$done = $this->db->update("poll_answers");
		if($done) {
			$this->db->insert('poll_users_votes',$data);
		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			$response['status'] = "false";
	        $response['message'] = 'Update Failed';
	        return $response;
		}

		$this->db->trans_commit();

		$response['status'] = "true";
        $response['message'] = 'Update Successful';
        return $response;
	}

	public function update_status($id,$status) {

		$this->db->trans_begin();

		$this->db->update("polls", $status, array('id' => $id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('error','Update Failed');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('success','Update Successful');
		return TRUE;
	}
    
    public function delete_($id)
	{
		$this->db->trans_begin();

		// delete user from users table should be placed after remove from group
		$result = $this->db->delete("polls", array('id' => $id));
        if($result) {
            $this->db->delete("poll_answers", array('poll_id' => $id));
            $this->db->delete("poll_users_votes", array('poll_id' => $id));
        }

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('error','Delete Failed');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->session->set_flashdata('success','Delete Successful');
		return TRUE;
	}
}
