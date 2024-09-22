<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends CI_Controller
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
		$data = $this->admin->commonFiles('projects');
		$data['title'] = "Projects | My Smart Interiors";
		$this->load->view("public/projects/projects_view", $data);
	}

}