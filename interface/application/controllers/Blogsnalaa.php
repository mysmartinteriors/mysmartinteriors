<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blogsnalaa extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	function index() {
	    $data = $this->admin->commonFiles();
		$data['title']="Blog | Nalaa Organics";
		$this->load->view("blog_view",$data);
    }
	// function test(){
	// 	print_R("THIS IS TEST");
	// 	exit();
	// }
}