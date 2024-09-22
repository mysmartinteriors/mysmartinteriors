<?php

require APPPATH . 'libraries/REST_Controller.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Checks extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = 'workorder_profiles_checks';
        $this->model_name = 'vendor_checks_model';
        $this->load->model($this->model_name, "", true);
        $this->load->model('workorder_profiles_checks_model', "", true);
        $this->lang->load('response', 'english'); 
		$this->load->model('notification_settings_model', "", true);
    }

    /**
 
     * Get All Data from this method.

     *

     * @return Response

     */

    public function index_get($id = 0)
    {

        $message = "success";
        $data = array();
        if (!empty($id)) {
            $data = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
        } else {
            $data = $this->Mydb->do_search($this->table, $this->model_name);
        }
        if (!empty($data)) {
            $value  = withSuccess($message, $data);
        } else {
            $value  = withSuccess($this->lang->line('no_result_found'));
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }

    /**

     * update Data from this method.

     *

     * @return Response

     */


    public function index_put($id = 0)
    {
        $input = $this->put();
        $rules = array();
        $data = array();
		
        if (empty($id)) {
            $value = withErrors('Id is required');
            $this->response($value, REST_Controller::HTTP_OK);
        }
		
		if (empty($input['updated_by'])) {
            $value = withErrors('Vendor id is required');
            $this->response($value, REST_Controller::HTTP_OK);
        }


        if (!empty($input)) {
			$where=array('id' => $id,'executor_id'=>$input['updated_by'],'execution_type'=>55);
            $get_data = $this->db->get_where($this->table,$where)->row_array();
            if (!empty($get_data)) {
				
				$logData=array(
					'workorders_id' => $get_data['workorders_id'],
					'workorder_profiles_id' => $get_data['workorder_profiles_id'],
					'workorder_profiles_checks_id' => $get_data['id'],
					'created_by'=>$input['updated_by']
				);
				//print_r($get_data['status']);exit();
				
				if (isset($input['execution_status'])) {
					$data['execution_status'] = $input['execution_status'];
				}
				if (isset($input['status_comments'])) {
					$data['status_comments'] = $input['status_comments'];
				}
				
				if($get_data['status']==3 || $get_data['status']==4 || $get_data['status']==9){					
				
					if (isset($input['output_json'])) {
						$data['updated_at'] = cur_date_time();
						$data['output_json'] = $input['output_json'];
						$check_json = json_validate($data['output_json']);
						if (!empty($check_json)) {
							$value  = withErrors('Invalid Output JSON - ' . $check_json);
							$this->response($value, REST_Controller::HTTP_OK);
							exit();
						}
						$data['status'] = $input['status'];
						$data['updated_by'] = $input['updated_by'];
						$data['updated_by_type'] = 55;
					} 
					if (isset($input['input_json'])) {
						$data['updated_at'] = cur_date_time();
						$data['input_json'] = $input['input_json'];
						$data['insuff_comments'] = '';
						$data['status'] = '';
						if (isset($input['insuff_comments'])) {
							$data['insuff_comments'] = $input['insuff_comments'];
						}
						if (isset($input['status'])) {
							$data['status'] = $input['status'];
						}
						$data['updated_by'] = $input['updated_by'];
						$data['updated_by_type'] = 55;
						$check_json = json_validate($data['input_json']);
						if (!empty($check_json)) {
							$value  = withErrors('Invalid Input JSON - ' . $check_json);
							$this->response($value, REST_Controller::HTTP_OK);
							exit();
						}
						json_validate($data['input_json']);
					}
					
					if(!empty($input['status'])){
						if($input['status']==6){
							$data['executed_at']=cur_date_time();
						}
					}
					$is_update = $this->Mydb->update_table_data('workorder_profiles_checks', array('id' => $id), $data);
					$date_type = '';
					if ($input['status'] == 3) {
						$date_type = 'status_insufficiency';
						
						$logData['description']='submitted the check as insufficiency';
					} else if ($input['status'] == 6) {
						$date_type = 'submitted_at';
						
						$logData['description']='submitted the check as completed';
					}
			
					$date_data = array(
						'id' => $id,
						'date_type' => $date_type,
						'date_value' => cur_date_time(),
						'userId' => $input['updated_by']
					);
					// $this->save_date_log($date_data);
 
					if ($is_update > 0) {					
						
						$this->vendor_checks_model->save_check_log($logData);
						
						$result = $this->Mydb->get_single_result($id, $this->table, 'workorder_profiles_checks_model');
						$value  = withSuccess($this->lang->line($this->table . '_updated_success'), $result);
						//print_r($result);exit();
						$this->send_notification($result['details']);
					} else {
						$value  = withErrors('Unable to update wokorders profile check, something wrong with data input!');
					}
				}else{
					$value  = withErrors('Since check is not in executable stage, cannot be updated.');
					$this->response($value, REST_Controller::HTTP_OK);
				}
            } else {
                $value  = withErrors($this->lang->line($this->table . '_not_found'));
            }
        } else {
            $value  = withErrors($this->lang->line('no_data_for_update'));
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }

    public function export_get($id = 0)
    {
        $userId = $this->input->get('vendorId');
        $date_type = $this->input->get('date_type');
        $date_range = $this->input->get('daterange');
        $services_id = $this->input->get('services_id');
        $from = '';
        $to = '';
        if (!empty($date_range)) {
            $fromTime = substr($date_range, 0, 10);
            $toTime = substr($date_range, 13, 10);
            $fromTime = $fromTime != "" ? custom_date("Y-m-d", $fromTime) : "";
            $toTime = $toTime != "" ? custom_date("Y-m-d", $toTime) : "";
            $from = $fromTime . ' 00:00:00';
            $to = $toTime . ' 23:59:59';
        }
        $data = $this->vendor_checks_model->get_export($userId, $date_type, $from, $to, $services_id);

        $rand = rand(1234, 9898);
        $presentDate = date('d_m_Y_h_i_s_A');
        $file_name    = "export_" . $presentDate . ".xlsx";
        $message = "success";
		
		// $excel_labels=array('Reference ID','Candidate Name','Check Type','Allocated At','Response Status');
		// $excel_fields=array('workorders_profiles_ref_id','workorders_profiles_name','services_name','allocated_at','execution_status_name');
		$excel_labels=array('Check ID', 'Reference ID', 'Candidate Name','Check Type','Allocated At','Response Status');
		$excel_fields=array('vendor_check_id', 'workorders_profiles_ref_id', 'workorders_profiles_name','services_name','allocated_at','execution_status_name');
			
        if (!empty($data)) {
			
			foreach($data as $row){
				if(!empty($row['input_json'])){
					$input_json = json_decode($row['input_json'], true);
				}
			}
			
			foreach($data as $row){
				if(!empty($row['output_json'])){
					$output_json = json_decode($row['output_json'], true);
				}
			}
			if(!empty($input_json)){
				foreach ($input_json as $input_json_data){
					if(!empty($input_json_data['name'])){
						array_push($excel_labels,$input_json_data['label']);
						array_push($excel_fields,$input_json_data['name']);
					}
				}
			}
			if(!empty($output_json)){
				foreach ($output_json as $json_data){
					if(!empty($json_data['name'])){
						array_push($excel_labels,$json_data['label']);
						array_push($excel_fields,$json_data['name']);
					}
				}
			}
		
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			for($i=0;$i<count($excel_labels);$i++){
				$col_name=Coordinate::stringFromColumnIndex($i+1);
				$sheet->setCellValue($col_name.'1', $excel_labels[$i]);
			}
			
			$count = 2;
			foreach($data as $row)
			{
				if(!empty($row['input_json'])){
					$row+=get_json_key_value($row['input_json']);
				}
				
				if(!empty($row['output_json'])){
					$row+=get_json_key_value($row['output_json']);
				}
				
				for($j=0;$j<count($excel_fields);$j++){
					if(!empty($row[$excel_fields[$j]])){
						$cellData=$row[$excel_fields[$j]];
					}else{
						$cellData='';
					}
					//print_r($cellData);exit;
					$cell_name=Coordinate::stringFromColumnIndex($j+1); // A2
					$sheet->setCellValue($cell_name.$count, $cellData); // A2(Ref_id)
				}
				$count++;
			}

            $writer = new Xlsx($spreadsheet);
            $filePath = 'reports/' . $file_name;
            $writer->save($filePath);
            $res =  array(
                'filename' => $file_name,
                'url' => base_url().$filePath
            );
            $result = array('details' => $res);
            $value = withSuccess($this->lang->line('report_generated_successfully'), $result);
        } else {
            $value = withErrors($this->lang->line('no_result_found'));
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }

    // function save_date_log($data)
    // {
    //     $date_data = array(
    //         'data_id' => $data['id'],
    //         'data_table' => $this->table,
    //         'date_type' => $data['date_type'],
    //         'date_value' => $data['date_value']
    //     );
    //     $userId = 0;
    //     if (isset($data['userId'])) {
    //         $userId = $data['userId'];
    //     }
    //     $where = array(
    //         'data_id' => $data['id'],
    //         'data_table' => $this->table,
    //         'date_type' => $data['date_type']
    //     );
    //     $get_data = $this->db->get_where('datetime_data', $where)->row_array();
    //     if (!empty($get_data)) {
    //         $date_data['updated_at'] = cur_date_time();
    //         $date_data['updated_by'] = $userId;
    //         $this->Mydb->update_table_data('datetime_data', array('id' => $get_data['id']), $date_data);
    //     } else {
    //         $date_data['created_at'] = cur_date_time();
    //         $date_data['created_by'] = $userId;
    //         $this->Mydb->insert_table_data('datetime_data', $date_data);
    //     }
    // }
	
	
	public function json_data_put($id = 0)
    {
        $input = $this->put();
        $rules = array();
        $data = array();
		
        if (empty($id)) {
            $value = withErrors('Id is required');
            $this->response($value, REST_Controller::HTTP_OK);
        }
		
		if (!empty($input['input_json'])) {
            $data['input_json']=$input['input_json'];
        }
		if (!empty($input['output_json'])) {
            $data['output_json']=$input['output_json'];
        }
		
		if (!empty($input['output_uploads_json'])) {
            $data['output_uploads_json']=$input['output_uploads_json'];
        }
		if (!empty($input['input_uploads_json'])) {
            $data['input_uploads_json']=$input['input_uploads_json'];
        }
		$is_update = $this->Mydb->update_table_data('workorder_profiles_checks', array('id' => $id), $data);
		if($is_update>0){
			$value  = withSuccess('Updated successfully');
		}else{
			$value  = withErrors('Failed to update, something went wrong.');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}
	
	function test_notify_get($id){
		$check_data = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
		$result=$this->send_notification($check_data['details']);
		// print_r($result);exit();
		$value  = withSuccess('Success',$result);
		$this->response($value, REST_Controller::HTTP_OK);
	}
	
	function send_notification($check_data){
		
		if(!empty($check_data['executor_id'])){
			$get_vendor=$this->db->get_where('vendors',array('id'=>$check_data['executor_id']))->row_array();
		}
		
		// $email_data = array(
			// 'description'=>'You have a '.$check_data['status_name'].' check',
			// 'to_name'=>'',
			// 'to_email'=>''
		// );
		
		// if(!empty($get_vendor['name'])){
			// $email_data['description'].=' by '.$get_vendor['name'];
		// }
		// if(!empty($check_data['execution_status_name'])){
			// $email_data['description'].=' with case response as '.$check_data['execution_status_name'];
		// }
		
		$interface_url=get_interface_url();
			
		// $description='<p>'.$email_data['description'].'</p></br>';
			
		// $mail_subject='CRM Check -'.$check_data['services_name'].' - '.$check_data['code'].'-'.$check_data['status_name'];
		// $description.='<p>Customer: '.$check_data['customers_name'].'</p></br>';
		// $description.='<p>Check ID: '.$check_data['code'].'</p></br>';
		// $description.='<p>Vendor ID: '.$check_data['vendor_check_id'].'</p></br>';
		// $description.='<p>Name: '.$check_data['workorders_profiles_name'].'</p></br>';
		// $description.='<p>Check Type: '.$check_data['services_name'].'</p></br>';		
		// if(!empty($get_vendor['name'])){
			// $description.='<p>Vendor Name: '.$get_vendor['name'].'</p></br>';
		// }
		$reference_url='checks#workorder_profiles_checks-code='.$check_data['code'];
		
		// $email_data['description']=$description;
		// $email_data['reference_url']=$interface_url.$reference_url;
		
		$where_owners=array(
			'customers_id'=>$check_data['customers_id'],
			'customer_branches_id'=>$check_data['customer_branches_id'],
			'customer_branches_persons_id'=>$check_data['customer_branches_persons_id'],
			'services_id'=>$check_data['services_id']
		);
		$get_owners=$this->notification_settings_model->get_owners($where_owners);

		$subject=$check_data['status_name'].', from the vendor '.$get_vendor['name'].' for the component '.$check_data['services_name'].' for the check '.$check_data['code'].' ('. $check_data['workorders_profiles_ref_id'] .', '.$check_data['workorders_profiles_name'].')';
		
		if(!empty($get_owners)){
			$to_email=array();
			$insert_data=array();
			foreach($get_owners as $row){
				array_push($to_email,$row['email']);
				$insert_data[]=array(
					'dataId'=>$check_data['id'],
					'reference_id'=>$row['id'],
					'reference_type'=>54,
					'reference_status'=>$check_data['status'],
					'module'=>'workorders_profiles_checks',
					'action'=>$check_data['status_name'],
					'title'=>$subject,
					'reference_url'=>$reference_url,
					'is_seen'=>1,
					'created_at'=>cur_date_time()
				);
			}
			$this->db->insert_batch('notifications',$insert_data);
			//$email_data['to_name']='User';
			//$str=$this->load->view('email-templates/common_view',$email_data,true);
			//$this->admin->sendmail($str,$subject,$to_email);		
				
		}
		return true;
	}
	
	function get_check_owners($check_data){
		if(!empty($check_data['services_id']) && !empty($check_data['notify_users'])){
			$notify_users=json_decode($check_data['notify_users']);
			$users=implode(',',$notify_users);
			$sql="SELECT id,login_id,email FROM
				users WHERE id IN('".$users."')";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}
	
}
