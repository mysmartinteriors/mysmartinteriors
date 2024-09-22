<?php

require APPPATH . 'libraries/REST_Controller.php'; 

class External_services extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->checks_table= 'workorder_profiles_checks';
        $this->checks_model='workorder_profiles_checks_model';
		$this->reports_model='report_generate_model';
        $this->load->model(array($this->checks_model,$this->reports_model,'check_execution_model')); 
        $this->lang->load('response', 'english');
		
		$this->api_user_id='86';
		$this->created_by_type='87';
    }  

	/**

     * Get check details by id from this method.

     *

     * @return Response

    */

    public function checks_get($id = 0)
    {
    	if(empty($id)){
			$value  = withErrors('Check id is required');
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		$get_check = $this->Mydb->get_single_result($id,$this->checks_table,$this->checks_model);
		if(empty($get_check['details'])){
			$value  = withErrors('Check id '.$id.' is incorrect. Data not found');
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		$check_data=$get_check['details'];
		$base_url=base_url();

		$output_param = array(
			'services_category_name'=>$check_data['services_category_name'],
			'services_category_code'=>$check_data['services_category_code'],
			'services_name'=>$check_data['services_name'],
			'customers_name'=>$check_data['customers_name'],
			'customer_branches_name'=>$check_data['customer_branches_name'],
			'workorder_code' => $check_data['workorder_code'],
			'profile_code' => $check_data['workorders_profiles_code'],
			'check_code' => strval($check_data['code']),
			'check_id' => strval($id),
			'status' => $check_data['status_name'],
			'ref_id' => $check_data['workorders_profiles_ref_id'],
			'name' => $check_data['workorders_profiles_name'],
			'phone' => $check_data['workorders_profiles_phone'],	
			'email' => $check_data['workorders_profiles_email'],
			//'return_url'=>'http://192.168.0.31/SARTBizExcel/backend/external_services/crc_status/'.$check_id
		);

		$input_json = json_decode($check_data['input_json'], true);
		foreach ($input_json as $json_data) {
			if (!empty($json_data['shareToVendor']) && strtolower($json_data['shareToVendor']) == 'yes' && !empty($json_data['name']) && !empty($json_data['userData'])) {
				if ($json_data['type'] == 'text' && !empty($json_data['dateFormat'])) {
					$output_param[$json_data['name']] = custom_date($json_data['dateFormat'], implode($json_data['userData']));
				} else {
					$output_param[$json_data['name']] = implode($json_data['userData']);
				}
			}
		}
		if(!empty($output_param['serial_no'])){
			$output_param['serial_no']=$check_data['services_code'].'_'.$output_param['serial_no'];
		}

		if($check_data['services_category_code']=='crc'){

			if(empty($check_data['search_schema_id'])){
				$value  = withSuccess('Search schema not defined for the customer branch.',$data_to_api);
				$this->response($value, REST_Controller::HTTP_OK);
				exit();
			}
			
			$get_schema=$this->db->get_where('search_schemas',array('id'=>$check_data['search_schema_id']))->row_array();
			if(empty($get_schema) || empty($get_schema['search_schema'])){
				$value  = withSuccess('Search schema not found in database.',$data_to_api);
				$this->response($value, REST_Controller::HTTP_OK);
				exit();
			}
			$output_param['search_schema']=preg_replace('!\\r?\\t?\\n!', "",$get_schema['search_schema']);
			$output_param['return_url']=$base_url.'external_services/crc_status/'.$id;
			$output_param['return_type']='PUT';
			$output_param['user_domain']=base_url();
			if(!empty($check_data['updated_username'])){
				$output_param['user_name']=$check_data['updated_username'];
			}else{
				$output_param['user_name']=$check_data['created_username'];
			}
		}
		$data_to_api = array('check_data' => $output_param);	
		

		$value  = withSuccess('Check data found.',$data_to_api);
		$this->response($value, REST_Controller::HTTP_OK);
		exit();
    }

    
    /**

     * Accept & update CRC check status from this method.

     *

     * @return Response

    */

    public function crc_status_put($id = 0)
    {
		$data=array();
		$input=$this->put();
		
		if(empty($id)){
			$value  = withErrors('Check id is required');
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		if(empty($input)){
			$value  = withErrors('No data found to update.');
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		
		$get_check = $this->Mydb->get_single_result($id,$this->checks_table,$this->checks_model);
		if(empty($get_check['details'])){
			$value  = withErrors('Check id '.$id.' is incorrect. Data not found');
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		$check_data=$get_check['details'];
		
				
		$log_file_data=array(
			'batch_id'=>$check_data['batch_id'],
			'check_id'=>$check_data['id'],
			'check_code'=>$check_data['code'],
			'profile_name'=>$check_data['workorders_profiles_name'],
			'username'=>'CaseQuest',
			'inputs'=>json_encode($this->put()),
			'message'=>'Updating check from CaseQuest to CRM'
		);
		
		$log_file_name=$check_data['services_name'];
		
		$status_name=$check_data['status_name'];
		
		if($check_data['services_category_code']!='crc'){
			$value  = withErrors('This check category is '.$check_data['services_category_code'].' and not CRC. Please verify the check id '.$id);
			
			$log_file_data['outputs']=json_encode($value);
			write_log_file($log_file_name,$log_file_data);
			
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		//print_r($check_data['status']);exit();
		
		if(($check_data['status']<4 || $check_data['status']>7) && $check_data['status']!=9 && $check_data['status']!=6){
			$value  = withErrors('Since the check '.$id.' is in '.$check_data['status_name'].' stage, not allowed to update.');
			
			$log_file_data['outputs']=json_encode($value);
			write_log_file($log_file_name,$log_file_data);
			
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}	
			
		
		$input['date_of_verification']=cur_date_time();
		//convert the json inputs to array
		$input=array_flatten($input);
		
		if(!empty($input['message'])){
			$input['execution_message']=$input['message'];
		}else{
			$input['execution_message']='success';
		}
		
		if(!empty($check_data['output_json'])){
			$output_json=replace_html_json($check_data['output_json'],$input);
			$json_array=json_decode($output_json['json_data'],true);
		}else{
			$json_array=$input;
		}
		//print_r(json_encode($json_array));exit();
		
		//print_r($json_array);exit();
		
		$up_status='success';
		$data['status']=$check_data['status'];
		$data=array(
			'updated_at'=>cur_date_time(),
			'allow_status'=>0
		);		
		
		//print_r($input);exit();
		
		if(!empty($input['execution_status'])){
			$execution_status=strtolower($input['execution_status']);
			$status_name=ucwords(str_replace('_',' ',$input['execution_status']));
			
			if($execution_status=='manual_intervention'){
				$data['status']=7;
				$data['allow_status']=1;
			}else if($execution_status=='already_checked'){
				$data['status']=5;
			}else if($execution_status=='complete'){
				if(!empty($input['report_status'])){
					$report_status=strtolower($input['report_status']);
					$status_name.=' - '.ucwords(str_replace('_',' ',$report_status));
					
					if($report_status=='insufficiency'){
						$data['status']=3;
					}else if($report_status=='no_record'){
						$data['status']=6;
						$data['execution_status']=97;
					}else if($report_status=='record_found'){
						if(!empty($input['report_file'])){
							$data['execution_status']=98;
							
							$save_path='uploads/';
							$save_path.=create_slug($check_data['workorder_code']).'/';
							$save_path.=create_slug($check_data['workorders_profiles_code']).'/';
							$save_path.=create_slug($check_data['check_code']).' - ';
							$save_path.=create_slug($check_data['services_name']).'/';
							
							$file_name=create_slug($check_data['check_code']);	
				
							$upload_res=upload_report_file($input['report_file'],$save_path,$file_name,'.pdf','base64');
							if($upload_res['status']=='success'){
								$data['status']=8;
								
								$save_report_data=array(
									'workorder_profiles_checks_id'=>$check_data['id'],
									'workorder_profiles_id'=>$check_data['workorder_profiles_id'],
									'workorders_id'=>$check_data['workorders_id'],
									'userId'=>$this->api_user_id,
									'report_type'=>'pdf',
									'report_url'=>$upload_res['file_url']
								);
								$this->save_check_report($save_report_data);
								
								$logData=array(
									'workorders_id'=>$check_data['workorders_id'],
									'workorder_profiles_id'=>$check_data['workorder_profiles_id'],
									'workorder_profiles_checks_id'=>$check_data['id'],
									'created_by'=>$this->api_user_id,
									'description'=>'Generated the pdf report for - '.$check_data['services_name'].' ('.$check_data['code'].') from the profile '.$check_data['workorders_profiles_name'].' ('.$check_data['workorders_profiles_code'].'). Stored the report in the respective folder.'
								);
								$this->save_check_log($logData);
								
							}else{
								$value  = withErrors('Unable to download the report, please try again.');
								
								$log_file_data['outputs']=json_encode($value);
								write_log_file($log_file_name,$log_file_data);
								
								$this->response($value, REST_Controller::HTTP_OK);
								exit();
							}
						}else{
							$value  = withErrors('Please upload the red report.');
							
							$log_file_data['outputs']=json_encode($value);
							write_log_file($log_file_name,$log_file_data);
								
							$this->response($value, REST_Controller::HTTP_OK);
							exit();
						}
					}else if($report_status=='manual_intervention'){
						$data['status']=7;
						$data['allow_status']=1;
					}else{
						$data['status']=$check_data['status'];
						$data['allow_status']=1;
					}				
				}else{
					$value  = withErrors('Report status is required for completed execution.');
					
					$log_file_data['outputs']=json_encode($value);
					write_log_file($log_file_name,$log_file_data);
					
					$this->response($value, REST_Controller::HTTP_OK);
					exit();
				}
			}
		}else{
			$value  = withErrors('Execution status is required.');
			
			$log_file_data['outputs']=json_encode($value);
			write_log_file($log_file_name,$log_file_data);
			
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		
		if($up_status=='success'){
			unset($json_array['report_file']);
			$data['output_json']=json_encode($json_array);
			$is_update=$this->Mydb->update_table_data($this->checks_table,array('id'=>$id),$data);
			
			if($is_update>0){
				$message='Updated the check status successfully';
				if(!empty($input['batch_id'])){
					$message.=' with batch id '.$input['batch_id'];
				}
				
				$logData=array(
					'workorders_id'=>$check_data['workorders_id'],
					'workorder_profiles_id'=>$check_data['workorder_profiles_id'],
					'workorder_profiles_checks_id'=>$check_data['id'],
					'created_by'=>$this->api_user_id,
					'description'=>'Updated the check as '.$status_name.' from CaseQuest '
				);
				$logData['description'].='('.$input['message'].') ';

				$logData['description'].='-'.$check_data['services_name'].' ('.$check_data['code'].') from the profile '.$check_data['workorders_profiles_name'].' ('.$check_data['workorders_profiles_code'].')';

				$this->save_check_log($logData);
				
				$value  = withSuccess($message);
				$new_check_data = $this->Mydb->get_single_result($check_data['id'], $this->checks_table, $this->checks_model);
				$this->check_execution_model->send_notification($new_check_data['details']);
			}else{
				$value  = withErrors('Unable to update, database error occured.');
			}
			$log_file_data['outputs']=json_encode($value);
			write_log_file($log_file_name,$log_file_data);
			
			if(!empty($input['batch_id']) && $input['status']=='success'){
				$get_batch=$this->db->get_where('workorder_exec_batches',array('id'=>$input['batch_id']))->row_array();
				if(!empty($get_batch)){
					
					$get_batch_count=$this->check_execution_model->get_batch_check_counts($input['batch_id']);
					
					$batch_balanace=$get_batch_count['total_count']-$get_batch_count['pending_count'];
					$batch_data=array('updated_at'=>cur_date_time());
					$batch_data['response_count']=$batch_balanace;
						
					if($get_batch_count['pending_count']==0){
						$batch_data['response_count']=$get_batch_count['total_count'];
						$batch_data['finished_at']=cur_date_time();
						$batch_data['status']=1;
					}
					if(!empty($input['message'])){
						$batch_data['description']=$input['message'];
					}
					//print_r($get_batch_count);exit();
					$this->Mydb->update_table_data('workorder_exec_batches',array('id'=>$input['batch_id']),$batch_data);
					
					$log_file_data['message']='Batch completed '.$batch_balanace.' out of '.$get_batch['request_count'].' case(s)';
					write_log_file($log_file_name,$log_file_data);
				}
			}
			
			$this->response($value, REST_Controller::HTTP_OK);
		}else{
			$value  = withErrors('Unable to update the status, some unknown error occured.');
			
			$log_file_data['outputs']=json_encode($value);
			write_log_file($log_file_name,$log_file_data);
			
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		
      
    }
	
	
	/**

     * Accept & update Address check status from this method.

     *

     * @return Response

    */

    public function sanchar_status_put($id = 0)
    {
		$data=array();
		$input=$this->put();
		
		//print_r($input);exit();
		
		if(empty($id)){
			$value  = withErrors('Check id is required');
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		if(empty($input)){
			$value  = withErrors('No data found to update.');
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		
		$get_check = $this->Mydb->get_single_result($id,$this->checks_table,$this->checks_model);
		if(empty($get_check)){
			$value  = withErrors('Check id is incorrect. Data not found');
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		$check_data=$get_check['details'];
		//print_r($check_data);exit();
		
		$log_file_data=array(
			'batch_id'=>$check_data['batch_id'],
			'check_id'=>$check_data['id'],
			'check_code'=>$check_data['code'],
			'profile_name'=>$check_data['workorders_profiles_name'],
			'username'=>'Sanchar',
			'inputs'=>json_encode($input),
			'message'=>'Updating check from Sanchar to CRM'
		);
		
		$log_file_name='sanchar';
		
		if($check_data['services_category_code']!='address'){
			$value  = withErrors('This check is not for sanchar execution. Please verify the check_id.');
			
			$log_file_data['outputs']=json_encode($value);
			write_log_file($log_file_name,$log_file_data);
			
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		
		$allowed_stages=array(1,2,3,4,5,6,13,14);
		
		if(!in_array($check_data['status'],$allowed_stages)){
			$value  = withErrors('Since the check is in '.$check_data['status_name'].' status, not allowed to update.');
			
			$log_file_data['outputs']=json_encode($value);
			write_log_file($log_file_name,$log_file_data);
			
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		
		//convert the json inputs to array
		//$input=array_flatten($input);
		//print_r($input);exit();
		
		$json_array=$input;
	
		$json_array['date_of_verification']=cur_date_time();
		$up_status='success';
		$data=array(
			'updated_at'=>cur_date_time()
		);
		$data['status']=$check_data['status'];
		
		if(!empty($input['output_json'])){
			$data['output_json']=stripslashes(json_encode($input['output_json'],JSON_UNESCAPED_SLASHES));
		}
		if(!empty($input['upload_json'])){
			$data['output_uploads_json']=stripslashes(json_encode($input['upload_json'],JSON_UNESCAPED_SLASHES));
		}
		
		
		if(!empty($input['form_uploads'])){
			foreach($input['form_uploads'] as $uploads){
				
				$save_path = 'uploads/workorders/';
				$save_path .= create_slug($check_data['workorder_code']) . '/';
				$save_path .= create_slug($check_data['workorders_profiles_code']) . '/';
				$save_path .= create_slug($check_data['check_code']) . ' - ';
				$save_path .= create_slug($check_data['services_name']) . '/';
				
				$where_upload=array(
					'workorder_profiles_checks_id'=>$check_data['id'],
					'workorder_profiles_id'=>$check_data['workorder_profiles_id'],
					'workorders_id'=>$check_data['workorders_id'],
					'uploader_type'=>75,
					'data_input_type'=>'output',
					'file_input_name'=>$uploads['file_input_name']
				);
				$get_upload=$this->db->get_where('workorder_profiles_checks_uploads',$where_upload)->row_array();
				
				//print_r($get_upload);exit();
				
				$new_file_name=md5(randomCodenum(5).get_micro_time()).'.'.$uploads['file_ext']; 
									
				$upload_arr=array(
					'workorder_profiles_checks_id'=>$check_data['id'],
					'workorder_profiles_id'=>$check_data['workorder_profiles_id'],
					'workorders_id'=>$check_data['workorders_id'],
					'uploader_type'=>75,
					'data_input_type'=>'output',
					'uploader_id'=>$this->api_user_id,
					'file_label'=>$uploads['file_label'],
					'file_input_name'=>$uploads['file_input_name'],
					'file_ext'=>$uploads['file_ext'],
					'file_name'=>$new_file_name,
					'orig_name'=>$uploads['file_name'],
					'file_size'=>$uploads['file_size'],
					'base_path'=>'',
					'file_path'=>$save_path,
					'latlong'=>$uploads['geo_location'],
					'created_at'=>$uploads['created_at'],
					'created_by'=>$this->api_user_id,
					'updated_at'=>$uploads['created_at'],
					'updated_by'=>$this->api_user_id,
					'is_valid'=>1
				);
				
				
				$upload_res=upload_report_file($uploads['file_url'],$save_path,$new_file_name,$uploads['file_ext'],'url');
				// print_r($upload_res);exit();
				

				if($upload_res['status']=='success'){
					if(!empty($get_upload)){
						unset($upload_arr['created_at'],$upload_arr['created_by']);
						$this->Mydb->update_table_data('workorder_profiles_checks_uploads',$where_upload,$upload_arr);
					}else{
						unset($upload_arr['updated_at'],$upload_arr['updated_by']);
						$this->Mydb->insert_table_data('workorder_profiles_checks_uploads',$upload_arr);
					}
				}
				
				$log_file_data=array(
					'batch_id'=>$check_data['batch_id'],
					'check_id'=>$check_data['id'],
					'check_code'=>$check_data['code'],
					'profile_name'=>$check_data['workorders_profiles_name'],
					'username'=>'Sanchar',
					'inputs'=>json_encode($upload_arr),
					'outputs'=>json_encode($upload_res),
					'message'=>'Log for uploading file'
				);				
				write_log_file($log_file_name,$log_file_data);
			}
		}
		
		if(!empty($input['execution_status'])){
			$execution_status=strtolower($input['execution_status']);
			
			if($execution_status=='submitted'){
				$data['status']=4;
				$data['allow_status']=1;
				$data['insuff_comments']='';
				
				$logData=array(
					'workorders_id'=>$check_data['workorders_id'],
					'workorder_profiles_id'=>$check_data['workorder_profiles_id'],
					'workorder_profiles_checks_id'=>$check_data['id'],
					'created_by'=>$this->api_user_id,
					'description'=>'Received the status from Sanchar as Submitted for - '.$check_data['services_name'].' ('.$check_data['code'].') from the profile '.$check_data['workorders_profiles_name'].' ('.$check_data['workorders_profiles_code'].')'
				);
				$this->save_check_log($logData);
				
			}else if($execution_status=='completed'){
				$data['status']=6;
				$data['allow_status']=0;
				$data['insuff_comments']='';

				$logData=array(
					'workorders_id'=>$check_data['workorders_id'],
					'workorder_profiles_id'=>$check_data['workorder_profiles_id'],
					'workorder_profiles_checks_id'=>$check_data['id'],
					'created_by'=>$this->api_user_id,
					'description'=>'Received the execution output from Sanchar and status as Completed for - '.$check_data['services_name'].' ('.$check_data['code'].') from the profile '.$check_data['workorders_profiles_name'].' ('.$check_data['workorders_profiles_code'].')'
				);
				$this->save_check_log($logData);				
				
			}else if($execution_status=='insufficiency'){
				$data['status']=3;
				$data['allow_status']=0;
				$data['insuff_comments']='';
				
				if(!empty($input['message'])){
					$data['insuff_comments']=$input['message'];
				}

				$logData=array(
					'workorders_id'=>$check_data['workorders_id'],
					'workorder_profiles_id'=>$check_data['workorder_profiles_id'],
					'workorder_profiles_checks_id'=>$check_data['id'],
					'created_by'=>$this->api_user_id,
					'description'=>'Received the status from Sanchar as Insufficency'
				);
				if(!empty($input['message'])){
					$logData['description'].=' with comments '.$input['message'];
				}				
				$logData['description'].=$check_data['services_name'].' ('.$check_data['code'].') from the profile '.$check_data['workorders_profiles_name'].' ('.$check_data['workorders_profiles_code'].')';
				
				$this->save_check_log($logData);				
				
			}else{
				$value  = withErrors('Unknown execution status. Received as '.$input['execution_status']);
				
				$log_file_data['outputs']=json_encode($value);
				write_log_file($log_file_name,$log_file_data);
			
				$this->response($value, REST_Controller::HTTP_OK);
				exit();
			}
		}else{
			$value  = withErrors('Execution status is required.');
			
			$log_file_data['outputs']=json_encode($value);
			write_log_file($log_file_name,$log_file_data);
			
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}
		
		if($up_status=='success'){
			
			unset($input['output_json']);
			unset($input['upload_json']);
			//unset($input['form_uploads']);
			unset($input['execution_status']);
			unset($input['message']);
			
			$data['report_json']=json_encode($input);
			
			$is_update=$this->Mydb->update_table_data($this->checks_table,array('id'=>$id),$data);
			
			if($is_update>0){
				$value  = withSuccess('Check data has been synced to CRM successfully.');
				
				$new_check_data = $this->Mydb->get_single_result($id, $this->checks_table, $this->checks_model);
				$this->check_execution_model->send_notification($new_check_data['details']);
				
				$log_file_data['outputs']=json_encode($value);
				write_log_file($log_file_name,$log_file_data);
			
				$this->response($value, REST_Controller::HTTP_OK);
			}else{
				$value  = withErrors('Unable to update, database error occured.');
				
				$log_file_data['outputs']=json_encode($value);
				write_log_file($log_file_name,$log_file_data);
				
				$this->response($value, REST_Controller::HTTP_OK);
			}
		}else{
			$value  = withErrors('Unable to update the status, some unknown error occured.');
			
			$log_file_data['outputs']=json_encode($value);
			write_log_file($log_file_name,$log_file_data);
			
			$this->response($value, REST_Controller::HTTP_OK);
			exit();
		}		
		
	}
	
	
	function save_check_log($data){
		$data['created_at']=cur_date_time();
		$data['ip_address']=getRealIpAddr();
		$data['created_by_type']=$this->created_by_type;
		$data['username']='api';
		$this->Mydb->insert_table_data('workorders_log',$data);
	}
	
	function save_check_report($data){
		$where=array(
			'workorder_profiles_checks_id'=>$data['workorder_profiles_checks_id'],
			'workorder_profiles_id'=>$data['workorder_profiles_id'],
			'workorders_id'=>$data['workorders_id'],
			'report_type'=>$data['report_type']
		);
		$get_data=$this->db->get_where('workorder_profiles_checks_reports',$where)->row_array();
		if(!empty($get_data)){
			$data['updated_at']=cur_date_time();
			$data['updated_by']=$data['userId'];
			unset($data['userId']);
			$this->Mydb->update_table_data('workorder_profiles_che0cks_reports',array('id'=>$get_data['id']),$data);
		}else{
			$data['created_at']=cur_date_time();
			$data['created_by']=$data['userId'];	
			unset($data['userId']);			
			$this->Mydb->insert_table_data('workorder_profiles_checks_reports',$data);
		}
	}
	
}