<?php

require APPPATH . 'libraries/REST_Controller.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Support extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table= 'support_enquiries';
        $this->model_name='support_model';   
        $this->load->model($this->model_name, "", true);  
        $this->load->library('excelvalidation');
        $this->lang->load('response', 'english');
    }

    /**

     * Get All Data from this method.

     *

     * @return Response

    */

    public function index_get($id = 0)
    {
      $message = "success";
      $data=array();
      if(!empty($id)){
         $data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
      }else{       
         $data = $this->Mydb->do_search($this->table,$this->model_name);
      }
      if(!empty($data)){
         $value  = withSuccess($message,$data);
      }else{
         $value  = withSuccess($this->lang->line('no_result_found'));
      }
      $this->response($value, REST_Controller::HTTP_OK);
	}
   
    /**

     * Insert data from this method.

     *

     * @return Response

    */

    public function index_post()
    {
        $input = $this->input->post();
		$rules = [
			'name' => ['Name','required'],
			'email' => ['Email','required'],
			'phone' => ['Phone Number','required'],
			'subject' => ['Subject','required'],
			'message' => ['Message','required'],
			'status' => ['Status','required']
		];
		$message = [
			'is_unique' => 'The %s is already exists',
		];	
		Validator::setMessage($message);
		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {			
			$data = array(	
				'name' =>$input['name'],
				'email' =>$input['email'],
				'phone' =>$input['phone'],
				'subject' =>$input['subject'],
				'message' =>$input['message'],
				// 'comments' =>$input['comments'],
				'submitDate' =>$input['submitDate'],
				'status' =>$input['status'],
				'customerId'=>$input['customerId'],
				'user_type'=>$input['user_type']
			);

		
			$id = $this->Mydb->insert_table_data('support_enquiries',$data);
			if($id != ''){
				$code=$this->randomCodenum(6);
				$date=getMyDbDate('%y',now());
				$data['code']="TKT".$code.$date.'-'.$id;
				$is_update = $this->Mydb->update_table_data('support_enquiries', array('id'=>$id), $data);
				$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
				$value  = withSuccess('Enquiry Created Successfully',$data);
			}else{
				$value  = withSuccess('Failed to Create Enquiry',$data);
			}
			$this->response($value, REST_Controller::HTTP_OK);
		}
    } 

    public function addSupport_post()
    {
		$raw_input_stream = $this->input->raw_input_stream;
		$input = json_decode($raw_input_stream, true);
		$data = array(	
			'name' =>$input['name'],
			'email' =>$input['email'],
			'phone' =>$input['phone'],
			'subject' =>$input['subject'],
			'message' =>$input['message'],
			// 'comments' =>$input['comments'],
			'submitDate' =>cur_date_time(),
			'status' =>$input['status'],
			'customerId'=>!empty($input['customerId'])?$input['customerId']:'',
			'user_type'=>$input['user_type']
		);	
		$id = $this->Mydb->insert_table_data('support_enquiries',$data);
		if($id != ''){
			$code=$this->randomCodenum(6);
			$date=getMyDbDate('%y',now());
			$data['code']="TKT".$code.$date.'-'.$id;
			$is_update = $this->Mydb->update_table_data('support_enquiries', array('id'=>$id), $data);
			$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
			$value  = withSuccess('Enquiry Created Successfully',$data);
		}else{
			$value  = withSuccess('Failed to Create Enquiry',$data);
		}
		$this->response($value, REST_Controller::HTTP_OK);
    } 

     function randomCodenum($num) {
		$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < $num; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}

    /**

     * Update data from this method.

     *

     * @return Response

    */

    public function index_put($id)
    {
		$rules = array();
		$data = array();
		
        $input = $this->put();
		// print_R($input);exit();

		if(!empty($input['status'])){
			$rules['status'] = ['Status','required'];
			$data['status'] = $input['status'];
		}
		if(!empty($input['comments'])){
			$rules['comments'] = ['Comments','required'];
			$data['comments'] = $input['comments'];
		}
		$message = [
			'edit_unique' => 'The %s is already exists',
		];	
		
		Validator::setMessage($message);
		
		if(array_filter($input)) {
			if(!empty($rules)){
				Validator::make($rules);	
			}		
			if (!Validator::fails()) {
				Validator::error();
			}			
		}
		$data['closedDate'] = cur_date_time();
// print_R($data);exit();
		$is_update = $this->Mydb->update_table_data('support_enquiries', array('id'=>$id), $data);
		// print_r($is_update);exit();
		$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if($is_update>0){
			$value  = withSuccess($this->lang->line('Enquiry updated success'),$result);
		}else{
			$value  = withErrors($this->lang->line('failed_to_update'),$result);
		}       
		$this->response($value, REST_Controller::HTTP_OK);
    }
  
    /**

     * Update data from this method.

     *

     * @return Response

    */

	public function custom_fields_put($id) {
		$input = $this->put();
		$data['data_fields'] = $input['data_fields'];
		$op = $this->perform_update_data_field_operation($id, $data['data_fields']);
		if($op){
			$value = withSuccess($this->lang->line('data_updated_success'));
		}else{
			$value = withErrors($this->lang->line('failed_to_update'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	public function perform_update_data_field_operation($id, $data_fields) {
		$status = 0;
		$data_fields=json_decode($data_fields,true);
		$data = $this->Mydb->get_table_data('users');
		if (empty($data[0]['data_fields'])) {
			foreach ($data_fields as $k => $v) {
				$data_key = $k;
				$data_type = $v['data_type'];
				$up_array[$data_type] = $v['data_value'];
			}
			$update_data['data_fields'] = json_encode($up_array);
			$update = $this->Mydb->update_table_data('users', array('id' => $id), $update_data);
			$status = 1;
		} else {
			foreach ($data_fields as $k => $v) {
				$data_key = $k;
				$data_type = $v['data_type'];
				$data_value = json_encode($v['data_value']);
				$sql = "UPDATE users SET data_fields = JSON_SET(data_fields, '$.$data_type', JSON_COMPACT('$data_value')) WHERE id = $id";
				$query = $this->db->query($sql);
				$status = 1;
			}
			$r_sql = "UPDATE users SET data_fields = replace(data_fields,'\\\','') WHERE id = $id";
			$query = $this->db->query($r_sql);
		}
		return $status;
	}

    /**

     * Get user by email or username for login authentication.

     *

     * @return Response

    */

    public function authentication_get()

    {
		$input = $this->get();
		$uid =$input['uid'];
		$sql="SELECT * FROM users WHERE email='$uid' OR login_id='$uid'";
		$query=$this->db->query($sql);
		$result['details'] =$query->row_array();
		$value  = withSuccess('success',$result);
		$this->response($value, REST_Controller::HTTP_OK);
    }
	
	

	
	/**

     * Import data from  file using this method.

     *

     * @return Response

    */
	
	
	public function import_post()
    {
		$input = $this->post();
		if (isset($input['file_path'])) {
			$file=$input['file_path'];
			if (file_exists($input['file_path'])){
				$excel_validation = new Excelvalidation();
				$ext_array = array(
					'xlsx',
					'xls'
				);
				$ext = pathinfo($file, PATHINFO_EXTENSION);
				if (in_array($ext, $ext_array)){
					$reader 	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					$spreadsheet 	= $reader->load($file);	
					$this->db->trans_begin();
					$data         = array();
					$count        = 0;
					$update_count = 0;
					$sheetCount = 0;					
					$overwrite='no';
					$data['created_by']=0;
					$duplicate_data = array();
					$department_not_exists = array();
					$role_not_exists = array();
					$colum_heading = array('Login ID','First Name','Last Name','Email','Mobile','Password','Temporary Address','Permanent Address','Department Name','Role Name','Class Level','Data Restriction');
					$get_excel_heading = $excel_validation->get_excel_heading($spreadsheet);
					$check_excel_heading = $excel_validation->check_excel_heading($colum_heading,$get_excel_heading);
					if(!empty($check_excel_heading)){
						$excel_validation->excelheading_error();	
					}
					$excelSheet = $spreadsheet->getActiveSheet();
					$spreadSheetAry = $excelSheet->toArray();
					$sheetCount = count($spreadSheetAry);		

					if(isset($input['created_by'])){
						$data['created_by']=$input['created_by'];
					}
					if(isset($input['overwrite'])){
						$overwrite=$input['overwrite'];
					}
					
					for ($i = 1; $i <= $sheetCount -1; $i ++) {
						$data['login_id'] = create_slug($spreadSheetAry[$i][0]);
						$data['first_name'] = $spreadSheetAry[$i][1];
						$data['last_name'] = $spreadSheetAry[$i][2];
						$data['email'] = $spreadSheetAry[$i][3];
						$data['mobile'] = $spreadSheetAry[$i][4];
						$password = $spreadSheetAry[$i][5];
						$data['temp_address'] = $spreadSheetAry[$i][6];
						$data['perma_address'] = $spreadSheetAry[$i][7];
						$department_name = $spreadSheetAry[$i][8];
						$role_name = $spreadSheetAry[$i][9];
						$data['class_level'] = $spreadSheetAry[$i][10];
						$data_restriction = strtolower($spreadSheetAry[$i][11]);
						if($data_restriction=='yes'){
							$data['data_restriction']=31;
						}else{
							$data['data_restriction']=32;
						}							
						$check_user = $this->usersmodel->check_duplicate_user($data);	
						$department_data = $this->db->get_where("departments", ['name'=>$department_name])->row_array();
						if(empty($department_data)){
							array_push($department_not_exists,$department_name);
						}else{									
							 $roles_data = $this->db->get_where("roles", ['name' => $role_name,'departments_id' => $department_data['id'] ])->row_array();
							 if(empty($roles_data)){
								array_push($role_not_exists,$role_name);
							}else{
								$data['departments_id'] = $department_data['id'];
								$data['organization_branches_id'] = $department_data['organization_branches_id'];
								$data['roles_id'] = $roles_data['id'];
								$data['password'] = $this->usersmodel->hash($password);
								$data['status']=7;
								if(empty($check_user)){									
									$data['create_type'] = 10;
									$data['created_at'] = cur_date_time();
									 $res_id = $this->Mydb->insert_table_data('users',$data);
									++$count;
								}else{					
									$dup_data = '{'.$data['login_id'].'-'.$data['email'].'}';
									array_push($duplicate_data,$dup_data);								
									if($overwrite=='yes'){
										$data['updated_by'] = $data['created_by'];
										$data['updated_at'] = cur_date_time();									
										$res_id = $this->Mydb->update_table_data('users', array('id'=>$check_user['id']), $data);
										++$update_count;
									}
								}
							}
						}
						
					}				
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$value = withErrors($this->lang->line('excel_upload_error'));
					} else {
						$this->db->trans_commit();
						$msg = "";					
						$msg .= "Total number of users found in uploaded file: " . ($sheetCount - 1) . "<br/>Number of users added : " . $count."<br/>";
						if(!empty($duplicate_data) && $update_count==0){
							$msg .="Number of users skipped: ".count($duplicate_data)."<br/>";
						}
						if($update_count>0){
							$msg .="Number of users updated: ".$update_count."<br/>";
						}	
						if(!empty($duplicate_data) && $update_count==0){
							$msg .= $excel_validation->add_index_error($duplicate_data,$this->lang->line('duplicate_user_data'));
						}
						if(!empty($duplicate_data) && $update_count>0){
							$msg .= $excel_validation->add_index_error($duplicate_data,$this->lang->line('duplicate_user_update'));
						}
						if(!empty($department_not_exists)){
							$msg .= $excel_validation->add_index_error($department_not_exists,$this->lang->line('dept_not_exist'));
						}						
						if(!empty($role_not_exists)){
							$msg .= $excel_validation->add_index_error($role_not_exists,$this->lang->line('roles_not_exist'));
						}								
						$value = withSuccess($msg);
					}
					$this->response($value, REST_Controller::HTTP_OK);
					
				} else{
					$value = withErrors("Only xlsx, xls extension file accepted!!!");
					$this->response($value, REST_Controller::HTTP_OK);
				}
			}else{
				$value = withErrors("File doesn't exists in the specified folder");
				$this->response($value, REST_Controller::HTTP_OK);
			}
		}else{
			$value = withErrors("Please provide the file path");
			$this->response($value, REST_Controller::HTTP_OK);
		}
    }	

	/**

     * Export data from  file using this method.

     *

     * @return Response

    */

	public function export_get(){
		$rand        = rand(1234, 9898);
        $presentDate = date('d_m_Y_h_i_s_A');
        $file_name    = "report_users_". $presentDate . ".xlsx";
		$data = $this->Mydb->do_search($this->table,$this->model_name,true);
		if(!empty($data['data_list'])){
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Login ID');
			$sheet->setCellValue('B1', 'First Name');
			$sheet->setCellValue('C1', 'Last Name');
			$sheet->setCellValue('D1', 'Email');
			$sheet->setCellValue('E1', 'Mobile');
			$sheet->setCellValue('F1', 'Temporary Address');
			$sheet->setCellValue('G1', 'Permanent Address');
			$sheet->setCellValue('H1', 'Department Name');
			$sheet->setCellValue('I1', 'Role Name');
			$sheet->setCellValue('J1', 'Organization Branch');
			$sheet->setCellValue('K1', 'Class Level');
			$sheet->setCellValue('L1', 'Data Restriction');
			$sheet->setCellValue('M1', 'Status');
			$sheet->setCellValue('N1', 'Created');	
			$sheet->setCellValue('O1', 'Created By');	
			$sheet->setCellValue('P1', 'Updated');	
			$sheet->setCellValue('Q1', 'Updated By');
			$count = 2;
			foreach($data['data_list'] as $row)
			{			
				$sheet->setCellValue('A' . $count, $row->login_id);
				$sheet->setCellValue('B' . $count, $row->first_name);
				$sheet->setCellValue('C' . $count, $row->last_name);
				$sheet->setCellValue('D' . $count, $row->email);
				$sheet->setCellValue('E' . $count, $row->mobile);
				$sheet->setCellValue('F' . $count, $row->temp_address);
				$sheet->setCellValue('G' . $count, $row->perma_address);
				$sheet->setCellValue('H' . $count, $row->departments_name);
				$sheet->setCellValue('I' . $count, $row->roles_name);
				$sheet->setCellValue('J' . $count, $row->organization_branches_name);
				$sheet->setCellValue('K' . $count, $row->class_level);
				$sheet->setCellValue('L' . $count, $row->restriction_name);
				$sheet->setCellValue('M' . $count, $row->status_name);
				$sheet->setCellValue('N' . $count, custom_date('d-m-Y h:i:s A',$row->created_at));
				$sheet->setCellValue('O' . $count, $row->created_username);
				if(!empty($row->updated_at)){
					$updated_at=custom_date('d-m-Y h:i:s A',$row->updated_at);
				}else{
					$updated_at='';
				}
				$sheet->setCellValue('P' . $count, $updated_at);
				$sheet->setCellValue('Q' . $count, $row->updated_username);
				$count++;
			}
			$writer = new Xlsx($spreadsheet);
			$filePath = 'reports/' . $file_name;			
			$writer->save($filePath);			
			$res =  array(
				'filename' => $file_name,
				'url' => base_url().$filePath
			);
			$result=array('details'=>$res);
			$value = withSuccess($this->lang->line('report_generated_successfully'),$result);
		}else{
			$value = withErrors($this->lang->line('no_result_found'));
		}       
		$this->response($value, REST_Controller::HTTP_OK);
	}

    /**

     * Delete data from this method.

     *

     * @return Response

    */

    public function index_delete($id=0)
    {
		$p_data = $this->db->get_where("activity_log", ['reference_id' => $id,'reference_type'=>24])->num_rows();
		if($p_data==0){
			$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
			$res = $this->Mydb->delete_table_data('users', array('id'=>$id));
			if ($res == 1)
			{	
				$value  = withSuccess($this->lang->line('user_deleted_success'),$result);
			}else if ($res == - 1451){
				$value = withErrors($this->lang->line('failed_to_delete'));
			}else{
				$value = withErrors($this->lang->line('failed_to_delete'));
			}
		}else{
			$value = withErrors($this->lang->line('user_cannot_delete'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
    }
    	
	function role_services_get($id){
		
		if(empty($id)){
			$value = withErrors('User id is required');
			$this->response($value, REST_Controller::HTTP_OK);
		}
		$result_type='all';
		if(!empty($this->input->get('result_type'))){
			$result_type=$this->input->get('result_type');
		}
		$u_data = $this->db->get_where($this->table, array('id' => $id))->row_array();
		if(!empty($u_data)){
			$result=$this->usersmodel->get_role_services($u_data,$result_type);
			//print_r($result);exit();
			$value=withSuccess('Success',array('data_list'=>$result));
			$this->response($value, REST_Controller::HTTP_OK);
		}else{
			$value = withErrors('User not found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}
	
	
	function test_import_post(){
		$input = $this->input->post();			
		$data = array(	
			'name' =>$input['name'],
			'email' =>$input['email'],
			'phone' =>$input['phone'],
			'password' => '12345',
		);
		$id = $this->Mydb->insert_table_data('user_data',$data);

		$value  = withSuccess('success');
		$this->response($value, REST_Controller::HTTP_OK);
	}

}