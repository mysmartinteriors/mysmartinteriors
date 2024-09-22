<?php
class logout extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->admin->nocache();
		// $this->load->model("adminmodel","",true);
	}
	
	function index(){

		$this->session->sess_destroy();
		redirect(base_url().'admin/login');
	}
}