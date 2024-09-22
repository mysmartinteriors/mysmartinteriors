<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Error_404 extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		$this->load->model("adminmodel","",true);
		$this->admin->nocache(); 
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Error_404";
		$this->load->view("admin/error-page/error_404_view",$data);
    }
}