<?php

defined('BASEPATH') or exit('No direct script access allowed');

class About_us extends CI_Controller
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
		$data = $this->admin->commonFiles('about_us');
		$data['title'] = "About | My Smart Interiors";
		$this->load->view("public/about_us/about_us_view", $data);
	}

}