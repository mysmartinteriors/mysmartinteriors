<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	function __construct(){
		parent::__construct();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("productsmodel","",true);
		// $this->load->model("categorymodel","",true);
	}

	function index() {
	    $data = $this->admin->commonFiles();
		$data['title']="Blog Nalaa Organics";
		$this->load->view("blog_view",$data);
    }

}