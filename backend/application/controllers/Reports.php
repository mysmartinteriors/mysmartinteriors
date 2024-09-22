<?php

require APPPATH . 'libraries/REST_Controller.php';


class Reports extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        // $this->table= 'sliders';
        $this->model_name='reportsmodel';   
        $this->load->model($this->model_name, "", true);  
        $this->load->library('excelvalidation');
        $this->lang->load('response', 'english');
    }

    public function customer_report_get($input = ""){
		$message = "success";
		$input = $this->input->get();
		if(!empty($input)){
			$status = $input['status'];
			$dateType = $input['dateType'];
			$from = $input['from'];
			$to = $input['to'];
		}
		$data = $this->reportsmodel->get_customer_reports($status, $dateType, $from, $to);
		// print_R($data);exit();
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$data = array('data_list'=>$data);
		$value  = withSuccess($message,$data);
		$this->response($value, REST_Controller::HTTP_OK);
	}

    public function product_report_get($input = ""){
		$message = "success";
		$input = $this->input->get();
		if(!empty($input)){
			$status = $input['status'];
			$dateType = $input['dateType'];
			$from = $input['from'];
			$to = $input['to'];
		}
		$data = $this->reportsmodel->get_product_reports($status, $dateType, $from, $to);
		// print_R($data);exit();
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$data = array('data_list'=>$data);
		$value  = withSuccess($message,$data);
		$this->response($value, REST_Controller::HTTP_OK);
	}

    public function order_report_get($input = ""){
		$message = "success";
		$input = $this->input->get();
		if(!empty($input)){
			$status = $input['status'];
			$dateType = $input['dateType'];
			$from = $input['from'];
			$to = $input['to'];
		}
		$data = $this->reportsmodel->get_order_reports($status, $dateType, $from, $to);
		// print_R($data);exit();
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$data = array('data_list'=>$data);
		$value  = withSuccess($message,$data);
		$this->response($value, REST_Controller::HTTP_OK);
	}

    public function prd_order_report_get($input = ""){
		$message = "success";
		$input = $this->input->get();
		if(!empty($input)){
			$status = $input['status'];
			$dateType = $input['dateType'];
			$from = $input['from'];
			$to = $input['to'];
		}
		$data = $this->reportsmodel->get_prd_order_reports($status, $dateType, $from, $to);
		// print_R($data);exit();
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$data = array('data_list'=>$data);
		$value  = withSuccess($message,$data);
		$this->response($value, REST_Controller::HTTP_OK);
	}

    public function customer_report_download_get(){
		$message = "success";
		$input = $this->input->get();
		if(!empty($input)){
			$status = $input['status'];
			$dateType = $input['dateType'];
			$from = $input['from'];
			$to = $input['to'];
		}
		$data = $this->reportsmodel->customer_download($status, $dateType, $from, $to);
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$data = array('data_list'=>$data);
		$value  = withSuccess($message,$data);
		$this->response($value, REST_Controller::HTTP_OK);
	}

}