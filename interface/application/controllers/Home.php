<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Home extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		// $this->load->library('admin');
	}

	// function test()
	// {
	// 	$mydata = ['id' => 1, 'name' => 'Dhurba'];
	// 	$mydata['title'] = 'This is test title';
	// 	print_r($mydata);
	// 	exit();
	// }

	function index()
	{
		$data = $this->admin->commonFiles('home');
		$data['title'] = "Home | My Smart Interiors";
		$this->load->view("public/home/home_view", $data);
	}

}
