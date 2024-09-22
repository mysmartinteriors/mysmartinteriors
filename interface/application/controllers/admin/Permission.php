	<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends CI_Controller {

	function __construct(){
		parent::__construct();
		check_admin_session();
		$this->load->model("adminmodel","",true);
		$this->admin->nocache();
	}
	
    public function index() {

        $data = $this->admin->commonadminFiles();
		$data['title']="Access Denied!";
		$this->load->view("admin/permissionView",$data);
    }

  
}
