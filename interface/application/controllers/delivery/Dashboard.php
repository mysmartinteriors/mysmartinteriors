<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct(){
		parent::__construct();
		check_delivery_session();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("dashboardmodel","",true);
		$this->admin->nocache(); 
	}
	
    public function index() {
		$userId = User::get_deliveryId();
        $data = $this->admin->commondeliveryFiles();
		$data['title']="Dashboard";

		$counts = $this->curl->execute("analytics/dashboard_counts", "GET", array("delivery_by" => $userId));
		// print_R($counts);exit(); 
		if($counts['status'] == 'success' && !empty($counts['data_list'])){
			$data_list = $counts['data_list'];
			$data['pord_count'] = $data_list['pending_orders'];
			$data['cord_count'] = $data_list['completed_orders'];
		}

		$log_data = $this->curl->execute("analytics/log_data", "GET");
		if($log_data['status'] == 'success' && !empty($log_data['data_list'])){
			$data['logTblQ'] = $log_data['data_list'];
		}

		$enq_data = $this->curl->execute("analytics/enquiry_data", "GET");
		if($enq_data['status'] == 'success' && !empty($enq_data['data_list'])){
			$data['enqTblQ'] = $enq_data['data_list'];
		}

		$order_data = $this->curl->execute("analytics/order_data", "GET");
		if($order_data['status'] == 'success' && !empty($order_data['data_list'])){
			$data['orderTblQ'] = $order_data['data_list'];
		}

		$this->load->view("delivery/dashboard_view",$data);
    }  
}
