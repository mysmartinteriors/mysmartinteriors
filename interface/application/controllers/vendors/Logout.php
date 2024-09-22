<?php
class logout extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->admin->nocache();
	}
	
	function index(){
		$this->session->sess_destroy();
		redirect(base_url().'vendors/login');
	}
}