<?php
//require APPPATH . 'libraries/REST_Controller.php';

class Php_exec extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->load->database();    
		// Set table name 
		$this->table = 'workorder_profiles_checks';
		$this->workorders_table = 'workorders';
		$this->workorder_profiles_table = 'workorder_profiles';
		$this->customers_table = 'customers';
		$this->customer_branches_table = 'customer_branches';
		$this->plans_table = 'plans';

		$this->reports_table = 'workorder_profiles_checks_reports';

		$this->services_table = 'services';
		$this->users_table = 'users';
		$this->lookups_table = 'lookups';

		$this->workflow_table = 'workflow';
		$this->log_table = "workorders_log";

		$this->model_name = 'workorder_profiles_checks_model';
		$this->report_model_name = 'report_generate_model';
		
		$this->profile_model='workorder_profiles_model';
		
		$this->load->model($this->profile_model, "", true);
		$this->load->model($this->model_name, "", true);
		$this->load->model('report_generate_model', "", true);
		$this->load->model('check_execution_model', "", true);
		
		$this->report_batch_table='report_exec_batches';
		$this->exec_batch_table='workorder_exec_batches';

		$this->load->model('notification_settings_model', "", true);

		$this->base_url=get_backend_url();


	}
	
	// function email(){
	// 	$email_data = array(
	// 		'description'=>'You have new check',
	// 		'to_name'=>'Chethan',
	// 		'to_email'=>'chethan@sartechsoft.com',
	// 		'reference_url'=>base_url().'vendor/check/view?id=1'
	// 	);
	// 	$this->load->view('email-templates/common_view',$email_data);
	// }

	function email(){
		$email_data = array(
			'description'=>'You have new check',
			'to_name'=>'Chethan',
			'to_email'=>'chethan@sartechsoft.com',
			'reference_url'=>$this->base_url.'vendor/check/view?id=1'
		);
		$this->load->view('email-templates/common_view',$email_data);
	}



	public function bulk_report($batch_id)
	{
		$get_batch=$this->db->get_where($this->report_batch_table,array('id'=>$batch_id,'data_table_name'=>$this->table))->row_array();
		if(!empty($get_batch)){
			$count=0;
			$success=0;
			$failed=0;
			
			$ids=json_decode($get_batch['input_data'],true);
			$response_data=array();
			
			for($i=0;$i<count($ids);$i++){
				$response=$this->report_generate_model->generate_check_report($ids[$i],$get_batch['created_by'],$get_batch['report_templates_id']);
				$count++;
				if($response['status']=='success'){
					$success++;
				}else{
					$failed++;
				}
				
				$response_msg=$response['id'].' - '.$response['code'].' - '.date('d-m-Y h:i:s A').' - '.$response['status'].' - '.$response['message'];
				array_push($response_data,$response_msg);				
					
				$data=array(
					'response_count'=>$count+1,
					'updated_at'=>cur_date_time(),
					'response_data'=>json_encode($response_data)
				);
				$this->Mydb->update_table_data($this->report_batch_table,array('id'=>$batch_id),$data);
			}
			
			$final_data=array(
				'response_count'=>$count,
				'updated_at'=>cur_date_time()
			);
				
			if($success==$get_batch['request_count']){
				$final_data['finished_at']=cur_date_time();
				$final_data['status']=1;
			}else{
				$final_data['status']=-1;
			}			
			$this->Mydb->update_table_data($this->report_batch_table,array('id'=>$batch_id),$final_data);
			
			return true;
			exit();			
		}
		return true;
		exit();
	}
	
	
	function bulk_crc_execute($batch_id,$base_url){
		if(!empty($batch_id)){
			$result=$this->check_execution_model->run_crc_execute($batch_id,$base_url);
			//print_r($result);
		}		
		return true;
		exit();
	}
	
	function bulk_execute($batch_id){
		log_message('debug', 'Bulk execute request received');
		$get_batch=$this->db->get_where($this->exec_batch_table,array('id'=>$batch_id))->row_array();
		if(!empty($get_batch)){
			$count=0;
			$success=0;
			$failed=0;
			
			$ids=json_decode($get_batch['input_data'],true);
			$response_data=array();
			
			for($i=0;$i<count($ids);$i++){
				$response=$this->check_execution_model->execute_check($ids[$i],$get_batch['created_by'], '', '', false);
				$count++;
				if($response['status']=='success'){
					$success++;
				}else{
					$failed++;
				}
				
				//$response_msg=$response['id'].' - '.$response['code'].' - '.date('d-m-Y h:i:s A').' - '.$response['status'].' - '.$response['message'];
				//array_push($response_data,$response_msg);				
					
				$data=array(
					'response_count'=>$count+1,
					'updated_at'=>cur_date_time(),
					'output_data'=>json_encode($response_data)
				);
				$this->Mydb->update_table_data($this->exec_batch_table,array('id'=>$batch_id),$data);
			}
			
			$final_data=array(
				'response_count'=>$count,
				'updated_at'=>cur_date_time()
			);
				
			if($success==$get_batch['request_count']){
				$final_data['finished_at']=cur_date_time();
				$final_data['status']=1;
				
				$workorder=$this->db->get_where('workorders',array('id'=>$get_batch['workorders_id']))->row_array();
				$service=$this->db->get_where('services',array('id'=>$get_batch['services_id']))->row_array();
				
				$notify_data=array(
					'id'=>$get_batch['workorders_id'],
					'table_name'=>'workorders',
					'model_name'=>'workorders_model',
					'action'=>'check_execution',
					'services_id'=>$get_batch['services_id'],
					'description'=>'completed '.$success.'/'.$get_batch['request_count'].' checks execution in the workorder '.$workorder['code'].' for the component '.$service['name'],
				);
				$this->notification_settings_model->custom_notification($notify_data);
			}else{
				$final_data['status']=-1;
			}	

			$this->Mydb->update_table_data($this->exec_batch_table,array('id'=>$batch_id),$final_data);
			
			return true;
			exit();			
		}
		return true;
		exit();
	}
	

	public function bulk_profile_report_old($batch_id)
	{
		$get_batch=$this->db->get_where($this->report_batch_table,array('id'=>$batch_id,'data_table_name'=>$this->workorder_profiles_table))->row_array();
		log_message('debug', 'Called the bulk profile report generation.');
		if(!empty($get_batch)){
			$count=0;
			$success=0;
			$failed=0;
			
			$ids=json_decode($get_batch['input_data'],true);
			$response_data=array();
			
			for($i=0;$i<count($ids);$i++){
				log_message('debug', 'Calling the function generate_profile_report() inside report_generate_model');
				$response=$this->report_generate_model->generate_profile_report($ids[$i],$get_batch['created_by'],$get_batch['report_templates_id']);
				$count++;
				if($response['status']=='success'){
					$success++;
				}else{
					$failed++;
				}
				
				$response_msg=$response['id'].' - '.$response['code'].' - '.date('d-m-Y h:i:s A').' - '.$response['status'].' - '.$response['message'];
				array_push($response_data,$response_msg);				
					
				$data=array(
					'response_count'=>$count+1,
					'updated_at'=>cur_date_time(),
					'response_data'=>json_encode($response_data)
				);
				$this->Mydb->update_table_data($this->report_batch_table,array('id'=>$batch_id),$data);
			}
			
			$final_data=array(
				'response_count'=>$count,
				'updated_at'=>cur_date_time()
			);
				
			if($success==$get_batch['request_count']){
				$final_data['finished_at']=cur_date_time();
				$final_data['status']=1;
			}else{
				$final_data['status']=-1;
			}			
			$this->Mydb->update_table_data($this->report_batch_table,array('id'=>$batch_id),$final_data);
			
			return true;
			exit();			
		}
		return true;
		exit();
	}	
	public function bulk_profile_report($batch_id)
	{
		// log_message('debug', 'Request received with the Batch ID '.$batch_id);
		$get_batch=$this->db->get_where($this->report_batch_table,array('id'=>$batch_id,'data_table_name'=>$this->workorder_profiles_table))->row_array();
		if(!empty($get_batch)){
			$count=0;
			$success=0;
			$failed=0;
			
			$ids=json_decode($get_batch['input_data'],true);
			$ids = array($ids);
			$response_data=array();
			
			for($i=0;$i<count($ids);$i++){
				// log_message('debug', 'Request sent to generate_profile_report with the Batch ID '.$batch_id);
				$response=$this->report_generate_model->generate_profile_report($ids[$i],$get_batch['created_by'],$get_batch['report_templates_id']);
				$count++;
				if($response['status']=='success'){
					$success++;
				}else{
					$failed++;
				}
				
				$response_msg=$response['id'].' - '.$response['code'].' - '.date('d-m-Y h:i:s A').' - '.$response['status'].' - '.$response['message'];
				array_push($response_data,$response_msg);				
					
				$data=array(
					'response_count'=>$count+1,
					'updated_at'=>cur_date_time(),
					'response_data'=>json_encode($response_data)
				);
				$this->Mydb->update_table_data($this->report_batch_table,array('id'=>$batch_id),$data);
			}
			
			$final_data=array(
				'response_count'=>$count,
				'updated_at'=>cur_date_time()
			);
				
			if($success==$get_batch['request_count']){
				$final_data['finished_at']=cur_date_time();
				$final_data['status']=1;				

				$workorder=$this->db->get_where('workorders',array('id'=>$get_batch['workorders_id']))->row_array();

				$notify_data=array(
					'id'=>$get_batch['workorders_id'],
					'table_name'=>'workorders',
					'model_name'=>'workorders_model',
					'action'=>'check_report_generation',
					'description'=>'completed '.$success.'/'.$get_batch['request_count'].' profile report generation in the workorder '.$workorder['code'],
				);
				$this->notification_settings_model->custom_notification($notify_data);

			}else{
				$final_data['status']=-1;
			}			
			$this->Mydb->update_table_data($this->report_batch_table,array('id'=>$batch_id),$final_data);
			
			return true;
			exit();			
		}
		return true;
		exit();
	}	


	public function check_report($id, $updated_by, $report_id, $upload_file_url){
		log_message('debug', 'Request Received by check_report function in Php_exec');
		$file_url = str_replace("\\", "", base64_decode($upload_file_url."="));

		$generate_report=$this->report_generate_model->generate_index_check_report($id, $updated_by, $report_id, 'pdf', str_replace("\\", "", base64_decode($upload_file_url."=")));	
		log_message('debug', 'Request Finished by check_report function in Php_exec');
		log_message('debug', print_R($generate_report, true));

		// return $generate_report;
		return true;
		exit();
	}


	
	

}
