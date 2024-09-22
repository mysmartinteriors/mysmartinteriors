<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		$this->load->model("adminmodel","",true);
		$this->admin->nocache(); 
	}

	function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Calendar Events";
		$data['dataQ']=$this->adminmodel->get_admin_details(1);
		$this->load->view("admin/calendar/calendar_view",$data);
    }

}