<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contact_us extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("productsmodel","",true);
		// $this->load->model("categorymodel","",true);
	}

	function index()
	{
		$data = $this->admin->commonFiles('contact_us');
		$data['title'] = "Contact Us | My Smart Interiors";
		$this->load->view("public/contact_us/contact_us_view", $data);
	}

	function get_a_quote()
	{
		$str = $this->load->view("public/contact_us/add_view", '', true);
		echo json_encode(['message' => $str, 'status' => 'success']);
	}

}