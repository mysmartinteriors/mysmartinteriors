	<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Denied extends CI_Controller {

	function __construct(){
		parent::__construct();
		check_admin_session();
		$this->load->model("adminmodel","",true);
		$this->admin->nocache();
	}
	
    public function index() {

        $data = $this->admin->commonadminFiles();
		$data['title']="Action Denied!";
		$data['errorMsg']='You are not allowed to perform this action';
		$this->load->view("admin/deniedView",$data);
    }

  
}
