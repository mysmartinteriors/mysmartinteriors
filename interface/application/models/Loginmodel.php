<?php
class loginmodel extends CI_Model{
//Admin authentication	
	function getUserAuth($uname,$pwd)
	{
	
		$this->db->where("(email='".$uname."' )");
		$this->db->where('password',$pwd);
	
		$query=$this->db->get('user');
	//$this->output->enable_profiler(TRUE);
		return $query;
		
	}
	

	function unset_only(){
		$user_data = $this->session->all_userdata();

		foreach ($user_data as $key => $value) {
			if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
				$this->session->unset_userdata($key);
			}
		}
		
	}
}	