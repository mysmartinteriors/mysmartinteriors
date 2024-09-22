<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends CI_Controller {

	function __construct(){
		parent::__construct();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("productsmodel","",true);
		// $this->load->model("categorymodel","",true);
	}

	function index() {
	    $data = $this->admin->commonFiles();
		$data['title']="Nalaa Organic";
		$this->load->view("privacy_policy",$data);
    }

	function terms_conditions() {
	    $data = $this->admin->commonFiles();
		$data['title']="Nalaa Organic";
		$this->load->view("terms_conditions",$data);
    }

	function faqs() {
	    $data = $this->admin->commonFiles();
		$data['title']="Nalaa Organic";
		$this->load->view("faq",$data);
    }

	function refund_policy() {
	    $data = $this->admin->commonFiles();
		$data['title']="Nalaa Organic";
		$this->load->view("refund_policy",$data);
    }

}