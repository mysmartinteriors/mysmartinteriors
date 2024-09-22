<?php
class Authenticate{
  protected $CI;

  public function __construct() {
    $this->CI = & get_instance();
	  //$this->CI->load->model("Mydb", "", true);
  }
  public function check_user_login(){
      if(!$this->CI->session->userdata('USER_ID')){
		  //$ins_data['TYPE'] = 'Guest';
		  //$ins_id = $this->CI->Mydb->insert_table_data('user',$ins_data);
		  
          // $this->CI->session->set_userdata('USER_ID',session_id());
		 
      }
	
  }
  
 
  
}
?>