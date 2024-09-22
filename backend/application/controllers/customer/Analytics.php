<?php 
require APPPATH . 'libraries/REST_Controller.php'; 

class Analytics extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();       
        $this->workorders_table= 'workorders';
        $this->profiles_table= 'workorder_profiles';
        $this->checks_table= 'workorder_profiles_checks';
        $this->lang->load('response', 'english');
		$this->load->model("customer_analytics_model", "", true);	
    }
	
	function workorders_count_get(){
		$message='success';
		$inputs=$this->input->get();
		if(empty($inputs['customers-id']) || empty($inputs['customer_branches-id'])){
			$value  = withErrors('Customer, Branch & Contact person ids are mandatory.');
			$this->response($value, REST_Controller::HTTP_OK);
		}			
		$where=array(
			'customers_id'=>$inputs['customers-id']
		);	
		if(!empty($inputs['customer_branches-id'])){
			$where['customer_branches_id']=$inputs['customer_branches-id'];
		}		
		if(!empty($inputs['customer_branches_persons-id'])){
			$where['customer_branches_persons_id']=$inputs['customer_branches_persons-id'];
		}
		$count=$this->db->get_where('workorders',$where)->num_rows();
		$result=array('details'=>array('count'=>$count));
		$value  = withSuccess($message,$result);
		$this->response($value, REST_Controller::HTTP_OK);
	}
	
	function profiles_count_get(){
		$message='success';
		$inputs=$this->input->get(); 
		if(empty($inputs['customers-id'])){
			$value  = withErrors('Customer id is mandatory.');
			$this->response($value, REST_Controller::HTTP_OK);
		}			
		$where=array(
			'customers_id'=>$inputs['customers-id'],
		);
		if(!empty($inputs['customer_branches-id'])){
			$where['customer_branches_id']=$inputs['customer_branches-id'];
		}		
		if(!empty($inputs['customer_branches_persons-id'])){
			$where['customer_branches_persons_id']=$inputs['customer_branches_persons-id'];
		}
		if(!empty($inputs['daterange'])){ 
			$daterange = $inputs['daterange'];
			$fromTime = substr($daterange, 0, 10);
			$toTime = substr($daterange, 13, 10);
			$fromTime = $fromTime != "" ? custom_date("Y-m-d", $fromTime) : "";
			$toTime = $toTime != "" ? custom_date("Y-m-d", $toTime) : "";
			$from = $fromTime . ' 00:00:00';
			$to = $toTime . ' 23:59:59';
			$where['from']="$from";
			$where['to']="$to";
		}
		if(!empty($inputs['year'])){
			$where['year'] = $inputs['year']; 
			$count=$this->customer_analytics_model->get_profile_monthly_counts($where);
		}else{
			$count=$this->customer_analytics_model->get_profile_counts($where);
		}
		$result=array('data_list'=>$count);
		$value  = withSuccess($message,$result);
		$this->response($value, REST_Controller::HTTP_OK);
	}

	
	function checks_count_get(){
		$message='success';
		$inputs=$this->input->get();
		if(empty($inputs['customers-id'])){
			$value  = withErrors('Customer id is mandatory.');
			$this->response($value, REST_Controller::HTTP_OK);
		}			
		$where=array(
			'customers_id'=>$inputs['customers-id'],
		);	
		if(!empty($inputs['customer_branches-id'])){
			$where['customer_branches_id']=$inputs['customer_branches-id'];
		}	
		if(!empty($inputs['customer_branches_persons-id'])){
			$where['customer_branches_persons_id']=$inputs['customer_branches_persons-id'];
		}
		$count=$this->customer_analytics_model->get_checks_counts($where);
		$result=array('data_list'=>$count);
		$value  = withSuccess($message,$result);
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function bulk_request_count_get(){
		$message='success';
		$inputs=$this->input->get();
		if(empty($inputs['customers-id'])){
			$value  = withErrors('Customer id is mandatory.');
			$this->response($value, REST_Controller::HTTP_OK);
		}			
		$where=array(
			'customers_id'=>$inputs['customers-id'],
		);	
		if(!empty($inputs['customer_branches-id'])){
			$where['customer_branches_id']=$inputs['customer_branches-id'];
		}	
		if(!empty($inputs['customer_branches_persons-id'])){
			$where['customer_branches_persons_id']=$inputs['customer_branches_persons-id'];
		}
		$count=$this->customer_analytics_model->get_bulk_request_counts($where);
		$result=array('data_list'=>$count);
		$value  = withSuccess($message,$result);
		$this->response($value, REST_Controller::HTTP_OK);
	}	

	function tickets_count_get(){
		$message='success';
		$inputs=$this->input->get();
		if(empty($inputs['customers-id']) || empty($inputs['customer_branches-id'])){
			$value  = withErrors('Customer id is mandatory.');
			$this->response($value, REST_Controller::HTTP_OK);
		}			
		$where=array(
			'customers_id'=>$inputs['customers-id'],
		);	
		if(!empty($inputs['customer_branches-id'])){
			$where['customer_branches_id']=$inputs['customer_branches-id'];
		}	
		if(!empty($inputs['customer_branches_persons-id'])){
			$where['customer_branches_persons_id']=$inputs['customer_branches_persons-id'];
		}
		$count=$this->customer_analytics_model->get_tickets_count($where);
		$result=array('data_list'=>$count);
		$value  = withSuccess($message,$result);
		$this->response($value, REST_Controller::HTTP_OK);
	}
	
	function branches_count_get(){
		$message='success';
		$inputs=$this->input->get();
		if(empty($inputs['customers-id'])){
			$value  = withErrors('Customer id is mandatory.');
			$this->response($value, REST_Controller::HTTP_OK);
		}			
		$where=array(
			'customers_id'=>$inputs['customers-id'],
		);	
		if(!empty($inputs['customer_branches-id'])){
			$where['customer_branches_id']=$inputs['customer_branches-id'];
		}	
		if(!empty($inputs['customer_branches_persons-id'])){
			$where['customer_branches_persons_id']=$inputs['customer_branches_persons-id'];
		}
		if(!empty($inputs['daterange'])){ 
			$daterange = $inputs['daterange'];
			$fromTime = substr($daterange, 0, 10);
			$toTime = substr($daterange, 13, 10);
			$fromTime = $fromTime != "" ? custom_date("Y-m-d", $fromTime) : "";
			$toTime = $toTime != "" ? custom_date("Y-m-d", $toTime) : "";
			$from = $fromTime . ' 00:00:00';
			$to = $toTime . ' 23:59:59';
			$where['from']="$from";
			$where['to']="$to";
		}
		$count=$this->customer_analytics_model->get_branches_counts($where);
		$monthly_count=$this->customer_analytics_model->get_branches_monthly_counts($where);
		$result=array('data_list'=>$count);
		$value  = withSuccess($message,$result);
		$this->response($value, REST_Controller::HTTP_OK);
	}
	
}