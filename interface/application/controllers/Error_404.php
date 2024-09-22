<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Error_404 extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->admin->nocache(); 
	}
	
    public function index() {
		$data['title']="Error_404";
		$this->load->view("error_404_view",$data);
    }
}