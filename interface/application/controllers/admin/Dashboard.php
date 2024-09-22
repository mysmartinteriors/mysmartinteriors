<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct(){
		parent::__construct();
		check_admin_session();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("dashboardmodel","",true);
		$this->admin->nocache(); 
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Dashboard";

		$counts = $this->curl->execute("analytics/dashboard_counts", "GET");
		// print_R($counts);exit();
		if($counts['status'] == 'success' && !empty($counts['data_list'])){
			$data_list = $counts['data_list'];
			$data['cats_count'] = $data_list['categories'];
			$data['prd_count'] = $data_list['products'];
			$data['cust_count'] = $data_list['customers'];
			$data['order_count'] = $data_list['orders'];
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

		//orderData
		$data['orderView'] = $this->get_orders();
		$data['whatsappLogView'] = $this->get_whatsapp_logs();


		$referralDataApi = $this->curl->execute("customer_reference_amount/customer_wise", "GET");
		if($referralDataApi['status'] == 'success' && !empty($referralDataApi['data_list'])){
			$data['customerReferralData'] = $referralDataApi['data_list'];
		}
		$this->load->view("admin/dashboard_view",$data); 
    }  


    function get_whatsapp_logs(){
		$page = 1;
    	$filterData[]=array();
    	$filterData['orderby']='DESC';
    	$filterData['perpage']='1000';
        if(!empty($page)){
            $filterData['page']=$page;
        }
		$today = new DateTime();
		// Format the dates to 'YYYY-MM-DD'
		$daterange = $today->format('Y-m-d'). ' - '.$today->format('Y-m-d');
		$filterData['date_range'] = $daterange;
    	$apidata=$this->curl->execute('whatsapp_logs',"GET",$filterData,'filter');
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
    	$str = $this->load->view("admin/whatsapp_logs/whatsapp_logs_tbl_view", $data, true);
    	// $value=array('str'=>$str,'status'=>$apidata['status']);
    	// echo json_encode($value);
		return $str;
    }

	function get_orders(){
		$page = 1;
    	$filterData[]=array();
    	$filterData['orderby']='DESC';
    	$filterData['perpage']='1000';
        if(!empty($page)){
            $filterData['page']=$page;
        }
		$today = new DateTime();
		// Format the dates to 'YYYY-MM-DD'
		$daterange = $today->format('Y-m-d'). ' - '.$today->format('Y-m-d');
		$filterData['date_range'] = $daterange;
    	$apidata=$this->curl->execute('orders',"GET",$filterData,'filter');
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
    	$str = $this->load->view("admin/orders/bookings/orders_tbl_view", $data, true);
		return $str;
	}
}
