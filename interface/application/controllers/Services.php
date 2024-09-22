<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Services extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("productsmodel","",true);
		// $this->load->model("categorymodel","",true);
	}

	function test()
	{
		$baseurl = get_class($this);
		print_r($baseurl);
		exit();
	}

	function index()
	{
		$data = $this->admin->commonFiles('services');
		$data['title'] = "Services | My Smart Interiors";
		$this->load->view("public/services/services_view", $data);
	}

}