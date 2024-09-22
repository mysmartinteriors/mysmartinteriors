<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rewards extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	function index() {
	    $data = $this->admin->commonFiles();
		$data['title']="Rewards - Nalaa Organic";
		$this->load->view("reward_view",$data);
    }

}