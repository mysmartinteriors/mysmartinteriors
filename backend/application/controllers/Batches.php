<?php 
require APPPATH . 'libraries/REST_Controller.php'; 

class Batches extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();       
        $this->workorders_table= 'workorders';
        $this->profiles_table= 'workorder_profiles';
        $this->checks_table= 'workorder_profiles_checks';
        $this->lang->load('response', 'english');
		$this->load->model(array("batches_model","check_execution_model","workorder_profiles_checks_model"), "", true);	
    }
	
	function retry_batch_post($id=0){
		if(empty($id)){
			$value = withErrors('Batch id is required');
			$this->response($value, REST_Controller::HTTP_OK); 
		}
		$inputs=$this->input->post();		
		
		$get_batch=$this->db->get_where('workorder_exec_batches',array('id'=>$id))->row_array();
		if(!empty($get_batch)){
			$get_checks=$this->batches_model->get_batch_pending_checks(json_decode($get_batch['input_data'],true));

			if(!empty($get_checks)){				
				$result=$this->check_execution_model->initiate_crc_execute(json_encode($get_checks),$inputs['user_id'],$id);
				if($result['status']=='success'){			
					$value = withSuccess($result['message']);
				}else{
					$value = withErrors($result['message']);
				}
			}else{
				$value  = withErrors("There is no pending checks to execute.");
			}
		}else{
			$value  = withSuccess('Batch not found');
		}
		$this->response($value, REST_Controller::HTTP_OK);  
	}
	
	function by_workorder_get($id=0){
		if(empty($id)){
			$value = withErrors('Workorder id is required');
			$this->response($value, REST_Controller::HTTP_OK); 
		}
		$data=$this->batches_model->get_batches_list(array('workorders_id'=>$id));
		if(!empty($data)){
			$result=array('data_list'=>$data);
			$value  = withSuccess("Success",$result);
		}else{
			$value  = withSuccess($this->lang->line('no_result_found'));
		}
		$this->response($value, REST_Controller::HTTP_OK);  
	}	
	
	function by_service_get($id=0){
		if(empty($id)){
			$value = withErrors('Service id is required');
			$this->response($value, REST_Controller::HTTP_OK); 
		}
		$inputs=$this->input->get();
		$inputs['services_id']=$id;
		$data=$this->batches_model->get_batches_list($inputs);
		if(!empty($data)){
			$result=array('data_list'=>$data);
			$value  = withSuccess("Success",$result);
		}else{
			$value  = withSuccess($this->lang->line('no_result_found'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}
	
}