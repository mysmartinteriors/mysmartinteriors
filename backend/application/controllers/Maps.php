<?php

require APPPATH . 'libraries/REST_Controller.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;    

class Maps extends REST_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('excelvalidation');
		$this->lang->load('response', 'english');
		$this->table= 'customers';
		$this->model_name='customersmodel';
		$this->load->model($this->model_name, "", true);
    }

       

    /**

     * Get All Data from this method.

     *

     * @return Response

    */

	public function index_get($id = 0)
	{
		$message = "success";
        if(!empty($id)){
            $data = $this->db->get_where("customers", ['id' => $id])->row_array();
			if(empty($data)){
				$message = $this->lang->line('no_result_found');
			}
			$data = array('details'=>$data);
        }else{			
			$data = $this->Mydb->do_search( $this->table,$this->model_name);
			if(empty($data['data_list'])){
				$message = $this->lang->line('no_result_found');
			}			
        }
		$value  = withSuccess($message,$data);
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
			'address' => ['Address','required']
		];
		$message = [
			'is_unique' => 'The %s is already exists',
		];	
		Validator::setMessage($message);
		Validator::make($rules);

		if (!Validator::fails()){
            Validator::error();
        }else{
			$address = $input['address'];
            $this->load->library('google_maps');
            $updateMapData = $this->google_maps->get_geocode($address);
			$value  = withSuccess($this->lang->line('customer_created_success'),array('details'=>$updateMapData));
			$this->response($value, REST_Controller::HTTP_OK);
		}
    } 
	
	
	public function register_post(){
		$input = $this->input->post();
		$rules = [
			'firstName' => ['First Name','required|max_length[200]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'email' => ['Email','required|valid_email'],
			'phone' => ['Phone','required'],
			'password' => ['Password', 'required|max_length[20]|min_length[4]'],
		];
		$message = [
			'is_unique' => 'The %s is already exists',
		];	
		Validator::setMessage($message);
		Validator::make($rules);
		if (!Validator::fails()){
			Validator::error();
		}else{
			$data = array(
				'firstName' => $input['firstName'],
				'email' => $input['email'],
				'phone' => $input['phone'],
				'password' => $input['password'],
				'token' => $input['token'],
				'type' => "Registered",
				'createdDate' => cur_date_time(),
				'updatedDate' => cur_date_time(),
				'status' => '2',
			);
			$emaildata = array();
			$emaildata['email_heading'] = "User Activation Link";
			$emaildata['name'] = $data['firstName'];
			$emaildata['url_link'] = "https://sartdev.in/demo_sites/nalaaorganics/interface/account/activation/".$input['token'];
			$txt=$this->load->view("email-templates/customers/account-activation-view",$emaildata,true);
			//$mailsend = email_forward('1',$emaildata,"user");
			$mailsend=$this->admin->sendmail($txt, $emaildata['email_heading'], $data['email']);
			// print_r($mailsend);exit();
			$ins_id=0;
			if($mailsend==1){
					$this->db->insert("customers",$data);
					$id=$this->db->insert_id();
					$value = withSuccess("Please check your email, we have sent you an account activation link...");
					// $result = $this->db->get_where('customers',array('id'=>$id))->row_array();
				}else{
					$result = "fail";
					$value = withErrors("Unable to Register! Try after sometime...");
			}
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}

	public function authentication_get(){
		$input = $this->get();
		// print_R($input);exit();
		$uid =$input['email'];
		$pwd =$input['password'];
		$sql="SELECT * FROM customers WHERE (phone='$uid' OR email='$uid') AND password='$pwd'";
		$query=$this->db->query($sql);
		$result['details'] =$query->row_array();
		$value  = withSuccess('success',$result);
		$this->response($value, REST_Controller::HTTP_OK);
	}
     
	
	public function register_guest_post(){
		$input = $this->input->post();
		$rules = [
			'type' => ['Guest Type','required'],
			'status' => ['Status','required'],
		];
		$message = [
			'is_unique' => 'The %s is already exists',
		];	
		Validator::setMessage($message);
		Validator::make($rules);
		if (!Validator::fails()){
			Validator::error();
		}else{
			$data = array(
				'type' => $input['type'],
				'status' => $input['status'],
			);	
			$this->db->insert("customers",$data);
			$id=$this->db->insert_id();
			$value = withSuccess("Guest Registered Successfully", array('details'=>array('id'=>$id)));
			$this->response($value, REST_Controller::HTTP_OK);
		}
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
		if(!empty($input['firstName'])){
			$rules['firstName'] = ['First Name','required|max_length[20]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['firstName'] = $input['firstName'];
		}
		if(!empty($input['lastName'])){
			$rules['lastName'] = ['Last Name','required|max_length[20]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['lastName'] = $input['lastName'];
		}
		if(!empty($input['email'])){
			$rules['email'] = ['Email','required|valid_email|edit_unique[customers.email.id.'.$id.']'];
			$data['email'] = $input['email'];
		}
		if(!empty($input['phone'])){
			$rules['phone'] = ['Phone','required|numeric|min_length[10]|max_length[10]'];
			$data['phone'] = $input['phone'];
		}			
		if(!empty($input['password'])){
			$rules['password'] = ['Password','required'];
			$data['password'] = $input['password'];
		}		
		if(!empty($input['address'])){
			$rules['address'] = ['Address','required|min_length[5]|max_length[200]'];
			$data['address'] = $input['address'];
		}
		if(!empty($input['city'])){
			$rules['city'] = ['City','required|max_length[200]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['city'] = $input['city'];
		}
		if(!empty($input['state'])){
			$rules['state'] = ['State','required|max_length[200]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['state'] = $input['state'];
		}
		if(!empty($input['postalCode'])){
			$rules['postalCode'] = ['Postal Code','required|numeric|min_length[6]|max_length[6]'];
			$data['postalCode'] = $input['postalCode'];
		}
		if(!empty($input['status'])){
			$rules['status'] = ['Status','required'];
			$data['status'] = $input['status'];
		}
		if(!empty($input['planId'])){
			$rules['planId'] = ['planId','required'];
			$data['planId'] = $input['planId'];
		}
		if(!empty($input['subscriptionAmount'])){
			$rules['subscriptionAmount'] = ['subscriptionAmount','required'];
			$data['subscriptionAmount'] = $input['subscriptionAmount'];
		}
		if(!empty($input['subscriptionPoints'])){
			$rules['subscriptionPoints'] = ['subscriptionPoints','required'];
			$data['subscriptionPoints'] = $input['subscriptionPoints'];
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
		
		if(!empty($data)){
			$data['updatedDate'] = cur_date_time();
			
			$is_update = $this->Mydb->update_table_data('customers', array('id'=>$id), $data);
			$q = $this->db->last_query();
			$result['details'] = $this->Mydb->get_table_data('customers',array('id'=>$id));
			if($is_update>0){
				$value  = withSuccess($this->lang->line('Customer Updated Successfully'),$result);
			}else{
				$value  = withErrors($this->lang->line('failed_to_update'),$result);
			}       
		}else{
			$value  = withErrors($this->lang->line('failed_to_update'));
		}
		$this->response($value, REST_Controller::HTTP_OK);      

    }

	public function forgotpassword_put($id){
		$rules = array();
		$data = array();
		
        $input = $this->put();
		if(!empty($input['password'])){
			$rules['password'] = ['Password','required'];
			$data['password'] = $input['password'];
		}
		if(!empty($input['email'])){
			$rules['email'] = ['Email','required'];
			$datas['email'] = $input['email'];
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
		if(!empty($data)){
			$data['updatedDate'] = cur_date_time();

			$is_update = $this->Mydb->update_table_data('customers', array('id'=>$id), $data);
			$result = $this->Mydb->get_table_data('customers',array('id'=>$id));
			// print_R($result);exit();
			if($is_update>0){
				$emaildata = array();
			$emaildata['email_heading'] = "User Forgot Password";
			$emaildata['name'] = $result[0]['firstName'];
			$emaildata['password'] = $result[0]['password'];
			// $data['email']
			// $emaildata['url_link'] = "https://sartdev.in/demo_sites/nalaaorganics/interface/account/activation/".$input['token'];
			$txt=$this->load->view("email-templates/customers/forgot-password-view",$emaildata,true);
			//$mailsend = email_forward('1',$emaildata,"user");
			$mailsend=$this->admin->sendmail($txt, $emaildata['email_heading'], $datas['email']);
			if($mailsend==1){


				$value  = withSuccess('Your temporary password is emailed to you.',array("details" =>$result));
			}else{
				$value  = withErrors('Password is failed to email you.');
			}       
		}else{
			$value  = withErrors("Unable to initiate email service! Try after sometime....");
		}
	}else{
		$value  = withErrors("Failed to Update");
	}
		$this->response($value, REST_Controller::HTTP_OK);     

	}

	/**

     * Import data from  file using this method.

     *

     * @return Response

    */


	/**

     * Import data from  file using this method.

     *

     * @return Response

    */
	
	
	public function import_post()
    {		
		$input = $this->post();
		//$rules = [
			//'file_path' => ['Excel File','required'],
			//'created_by' => ['Created By','required|numeric']
		//];
		//Validator::make($rules);
		//if (!Validator::fails()){
          //  Validator::error();
        //}else{
			$excel_validation = new Excelvalidation();
			
			if (isset($input['file_path'])) {
				if (file_exists($input['file_path'])){		
					$file=$input['file_path'];
					$ext_array = array(
						'xlsx',
						'xls'
					);
					$ext=pathinfo($file, PATHINFO_EXTENSION);
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
						$update_data=array();
						$colum_heading = array('Customer Code','Name','Email','Phone','Address');
						$get_excel_heading = $excel_validation->get_excel_heading($spreadsheet);			
						$check_excel_heading = $excel_validation->check_excel_heading($colum_heading,$get_excel_heading);

						if(!empty($check_excel_heading)){
							$excel_validation->excelheading_error();	
						}
						if(isset($input['created_by'])){
							$data['created_by']=$input['created_by'];
						}
						if(isset($input['overwrite'])){
							$overwrite=$input['overwrite'];
						}	
						$excelSheet = $spreadsheet->getActiveSheet();
						$spreadSheetAry = $excelSheet->toArray();
						$sheetCount = count($spreadSheetAry);
						for ($i = 1; $i <= $sheetCount -1; $i ++) {
							$data['customer_code'] = createSlug($spreadSheetAry[$i][0]);
							$data['name'] = $spreadSheetAry[$i][1];
							$data['email'] = $spreadSheetAry[$i][2];
							$data['phone'] = $spreadSheetAry[$i][3];
							$data['address'] = $spreadSheetAry[$i][4];
							$data['status']=18;
							$check_user = $this->customersmodel->check_duplicate_customer($data);
							if(empty($check_user)){
								$data['created_at'] = cur_date_time();
								$res_id = $this->Mydb->insert_table_data('customers',$data);
								++$count;
							}else{				
								$dup_data = '{'.$data['customer_code'].' - '.$data['name'].'}<br/>';
								array_push($duplicate_data,$dup_data);
								if($overwrite=='yes'){
									$data['updated_by'] = $data['created_by'];
									$data['updated_at'] = cur_date_time();									
									$res_id = $this->Mydb->update_table_data('customers', array('id'=>$check_user['id']), $data);
									++$update_count;
								}
							}
							
						}				
						if ($this->db->trans_status() === FALSE) {
							//print_r($this->db->last_query());
							$this->db->trans_rollback();
							$value = withErrors($this->lang->line('excel_upload_error'));
						}else{
							$this->db->trans_commit();
							$msg = "";				
							$msg .= "Total number of datas found in uploaded file: " . ($sheetCount - 1) . "<br/>Number of datas added: " . $count.'<br/>';	
							if(!empty($duplicate_data) && $update_count==0){
								$msg .="Number of datas skipped: ".count($duplicate_data)."<br/>";
							}
							if($update_count>0){
								$msg .="Number of datas updated: ".$update_count."<br/>";
							}	
							if(!empty($duplicate_data) && $update_count==0){
								$msg .= $excel_validation->add_index_error($duplicate_data,$this->lang->line('duplicate_customer_data'));
							}else{
								$msg .= $excel_validation->add_index_error($duplicate_data,'Duplicate customers updated');
							}
							$value = withSuccess($msg);
						}
						$this->response($value, REST_Controller::HTTP_OK);				
					}else{
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
		//}
    }	
    
	
	
	
	/**

     * Export data from  file using this method.

     *

     * @return Response

    */

	public function export_get(){
		$rand        = rand(1234, 9898);
        $presentDate = date('d_m_Y_h_i_s_A');
        $file_name    = "report_customers_". $presentDate . ".xlsx";
		$data = $this->Mydb->do_search( $this->table,$this->model_name,true);
		
		if(!empty($data['data_list'])){
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			$sheet->setCellValue('A1', 'Customer Code');
			$sheet->setCellValue('B1', 'Name');
			$sheet->setCellValue('C1', 'Email');
			$sheet->setCellValue('D1', 'Phone');		
			$sheet->setCellValue('E1', 'Address');	
			$sheet->setCellValue('F1', 'Created');	
			$sheet->setCellValue('G1', 'Created By');	
			$sheet->setCellValue('H1', 'Updated');	
			$sheet->setCellValue('I1', 'Updated By');

			$count = 2;

			foreach($data['data_list'] as $row)
			{
				$sheet->setCellValue('A' . $count, $row->customer_code);
				$sheet->setCellValue('B' . $count, $row->name);
				$sheet->setCellValue('C' . $count, $row->email);
				$sheet->setCellValue('D' . $count, $row->phone);
				$sheet->setCellValue('E' . $count, $row->address);
				$sheet->setCellValue('F' . $count, custom_date('d-m-Y h:i:s A',$row->created_at));
				$sheet->setCellValue('G' . $count, $row->created_username);
				if(!empty($row->updated_at)){
					$updated_at=custom_date('d-m-Y h:i:s A',$row->updated_at);
				}else{
					$updated_at='';
				}
				$sheet->setCellValue('H' . $count, $updated_at);
				$sheet->setCellValue('I' . $count, $row->updated_username);
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


	function index_delete($id){
		$getData = $this->db->get_where('customers', array('id'=>$id))->row_array();
		if(empty($getData)){
			$value = withErrors('Customer Not Found');
		}else{
			$sql="SELECT 1 
            FROM (
                SELECT customerId FROM shopping_cart
                UNION ALL
                SELECT customerId FROM support_enquiries
            ) a WHERE customerId = '$id'";
			$res = $this->db->query($sql)->num_rows();
			if($res){
				$value = withErrors('Customer is Linked with other datas like cart, support etc.....Cannot remove');
			}else{
				$delete = $this->db->delete('customers', array('id'=>$id));
				if($delete> 0){
					$value = withSuccess('Customer Deleted Successfully',array('details'=>$getData));
				}else{
					$value = withErrors('Couldn\'t Add Customer');
				}
			}
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}	

	function dashboard_bookings_get($uId){
		$sql = "SELECT * FROM orders_booking
				WHERE customerId='$uId'
				ORDER BY createdDate DESC LIMIT 5";
		$result = array();
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$result = $query->result_array();
		}	
		$value = withSuccess('Success', array('details'=>$result));
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function my_bookings_get($id){
		$sql = "SELECT p.productName,p.CGST,p.SGST,p.productImage,p.productURL,
				c.text as categoryName,bd.qty,bd.price,bd.createdDate,bd.tax,bd.total
				FROM products p
				LEFT JOIN categories c
				ON p.categoryId=c.id
				INNER JOIN orders_booking_details bd
				ON bd.productId=p.productId
				WHERE bd.orderId='$id'";
		$query=$this->db->query($sql);
		return $query;
	}

	function send_otp_get($mobile=''){
		if(strlen($mobile)==10){
			$otp = generate_otp();
			$whatsapp_text = "Dear Customer, ".PHP_EOL;
			$whatsapp_text.="Your OTP For NalaaOrganic Verification is $otp".PHP_EOL;
			$whatsapp_text.="Thank You".PHP_EOL;
			$send_whatsapp = send_whatsapp($mobile, $whatsapp_text);
			// $send_whatsapp = array('status')
			$send_whatsapp = array('status'=>'success');
			if($send_whatsapp['status']=='success'){
				$findCustomer = $this->db->get_where('customers', array('phone'=>$mobile))->row_array();
				if(!empty($findCustomer)){
					//checking if the customer Status ==2 And ProfileStep == 2
					if($findCustomer['status']==2 && $findCustomer['profileStep']==2){
						$this->db->where(array('id'=>$findCustomer['id']));
						$updateDb = $this->db->update('customers', [
							'otp'=>$otp, 
							'status'=>2, 
							'profileStep'=>2,
							'otp_verification'=>'NO', 
							'otp_count'=>!empty($findCustomer['otp_count'])?($findCustomer['otp_count']+1):1,
							'updatedDate' => cur_date_time(),
						]);
					}else{
						$this->db->where(array('id'=>$findCustomer['id']));
						$updateDb = $this->db->update('customers', [
							'otp'=>$otp, 
							'otp_verification'=>'NO', 
							'otp_count'=>!empty($findCustomer['otp_count'])?($findCustomer['otp_count']+1):1,
							'updatedDate' => cur_date_time(),
						]);
					}
					
				}else{
					$updateDb = $this->db->insert('customers', [
						'phone'=>$mobile, 
						'otp'=>$otp, 
						'otp_verification'=>'NO', 
						'otp_count'=>1,
						'token'=>$this->randomCodenum(10),
						'profileStep' => 1,
						'type'=>'Mobile App',
						'createdDate' => cur_date_time()
					]);
				}
				$value = withSuccess('OTP is sent to your mobile number '.$mobile);
			}else{
				$value = withErrors('Unable to send OTP, Please try again later');
			}
		}else{
			$value = withErrors('Invalid Mobile Number Detected');
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


	function verifyOTP_post(){
		$raw_input_stream = $this->input->raw_input_stream;
		$input = json_decode($raw_input_stream, true);
		if(!empty($input['mobileNumber']) && !empty($input['otp'])){
			$rowData = $this->db->get_where($this->table, array('phone'=>$input['mobileNumber'], 'otp'=>$input['otp']))->row_array();
			if(!empty($rowData)){
				if($rowData['status']==2 && $rowData['profileStep']==2){
					$updateDb= $this->db->update($this->table, array(
						'otp_verification'=>'YES',
						'updatedDate'=>cur_date_time(),
					), array('id'=>$rowData['id']));
				}else{
					$updateDb= $this->db->update($this->table, array(
						'otp_verification'=>'YES',
						'updatedDate'=>cur_date_time(),
						'status'=>1,
						'profileStep'=>1
					), array('id'=>$rowData['id']));
				}
				if($updateDb){
					$rowData = $this->db->get_where($this->table, array('phone'=>$input['mobileNumber'], 'otp'=>$input['otp']))->row_array();
					$value = withSuccess('OTP Verified Successfully', array('details'=>$rowData));
				}else{
					$value = withErrrors('OTP Update Failed');
				}
				$this->response($value, REST_Controller::HTTP_OK);
			}else{
				$value = withErrors('Invalid OTP');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		}else{
			$value = withErrors('Invalid OTP');
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}


	function save_addresses_post(){
		$raw_input_stream = $this->input->raw_input_stream;
		$input = json_decode($raw_input_stream, true);
		if(!empty($input['userId'])){
			$userId = $input['userId'];
			$defaultAddress = (isset($input['defaultAddress']) && $input['defaultAddress']=='on')?1:0;
			$rowsData = $this->db->get_where('shipping_address', array('customerId'=>$input['userId']))->result_array();
			if(!empty($rowsData) && $defaultAddress){
				foreach($rowsData as $row){
					if($row['pri_address']==1){
						$this->db->update('shipping_address', array('pri_address'=>0), array('id'=>$row['id']));
					}
				}
			}
			$insert= $this->db->insert('shipping_address', [
				'customerId'=>$userId,
				'name'=>$input['fullName'],
				'phone'=>$input['mobileNumber'],
				'address'=>$input['flat']. ', '.$input['area']. ', '.$input['landmark'],
				'city'=>$input['city'],
				'state'=>$input['state'],
				'country'=>'India',
				'postalCode'=>$input['pincode'],
				'createdDate'=>cur_date_time(),
				'pri_address'=>$defaultAddress?1:0
				]);
			$insertId=$this->db->insert_id();
			if($insert){
				$rowData = $this->db->get_where('shipping_address', array('id'=>$insertId))->row_array();
				$value = withSuccess('Address Added Successfully', array('details'=>$rowData));
			}else{
				$value = withErrrors('Address Couldn\'t be updated');
			}
			if(!empty($rowData)){
				$this->response($value, REST_Controller::HTTP_OK);
			}else{
				$value = withErrors('Invalid OTP');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		}else{
			$value = withErrors('Invalid User ID');
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}


	function save_addresses_put($id){
		$raw_input_stream = $this->input->raw_input_stream;
		$input = json_decode($raw_input_stream, true);
		if(!empty($id)){
			$rowData = $this->db->get_where('shipping_address', array('id'=>$id))->row_array();
			if(!empty($rowData)){
				$update= $this->db->update('shipping_address', [
					'name'=>$input['name'],
					'phone'=>$input['mobileNumber'],
					'address'=>$input['address'],
					'city'=>$input['city'],
					'state'=>$input['state'],
					'country'=>'India',
					'postalCode'=>$input['postalCode'],
					'createdDate'=>cur_date_time()
				], ['id'=>$id]);
				$value = $update?withSuccess('Address Updated Successfully'):withErrors('Address Couldn\'t Be Updated');
			}else{
				$value = withErrors('No Address Found');
			}
		}else{
			$value = withErrors('Invalid Address Data');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


	function shipping_address_get($id){
		$getUser = $this->db->get_where('customers', ['id'=>$id])->row_array();
		if(!empty($getUser)){
			$getData = $this->db->get_where('shipping_address', array('customerId'=>$id))->result_array();
			if(!empty($getData)){
				$value = withSuccess('Success', array('details'=>$getData));
			}else{
				$value = withErrors('No Shipping Addresses Found');
			}
		}else{
			$value = withErrors('Invalid User ID');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


	function saveProfile_put($id){
		$raw_input_stream = $this->input->raw_input_stream;
		$input = array();
		if(!empty($raw_input_stream)){
			$input = json_decode($raw_input_stream, true);
		}
		if(!empty($input)){
			$userRow = $this->db->get_where($this->table, array('id'=>$id))->row_array();
			if(!empty($userRow)){
				$userData = array(
					'firstName'=>!empty($input['firstName'])?$input['firstName']:$userRow['firstName'],
					'lastname'=>!empty($input['lastName'])?$input['lastName']:$userRow['lastName'],
					'email'=>!empty($input['email'])?$input['email']:$userRow['email'],
					'profileStep'=>2,
					'status'=>2
				);
				$update = $this->db->update($this->table, $userData, array('id'=>$id));
				if($update){
					$userRow = $this->db->get_where($this->table, array('id'=>$id))->row_array();
					$value = withSuccess('Profile Saved successfully', array('details'=>$userRow));
					$this->response($value, REST_Controller::HTTP_OK);
				}else{
					$value = withErrors('Unable to Update Profile');
					$this->response($value, REST_Controller::HTTP_OK);
				}
			}else{
				$value = withErrors('Invalid Profile');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		}else{
			$value = withErrors('Invalid Request');
			$this->response($value, REST_Controller::HTTP_OK);
		}

	}


	function saveMainAddress_put($id){
		$raw_input_stream = $this->input->raw_input_stream;
		$input = array();
		if(!empty($raw_input_stream)){
			$input = json_decode($raw_input_stream, true);
		}
		if(!empty($input)){
			$userRow = $this->db->get_where($this->table, array('id'=>$id))->row_array();
			if(!empty($userRow)){
				$userData = array(
					'address'=>!empty($input['address'])?$input['address']:$userRow['address'],
					'postalCode'=>!empty($input['postalCode'])?$input['postalCode']:$userRow['postalCode'],
					'city'=>!empty($input['city'])?$input['city']:$userRow['city'],
					'state'=>!empty($input['state'])?$input['state']:$userRow['state'],
					'country'=>!empty($input['country'])?$input['country']:$userRow['country'],
					'profileStep'=>2
				);
				$update = $this->db->update($this->table, $userData, array('id'=>$id));
				if($update){
					$userRow = $this->db->get_where($this->table, array('id'=>$id))->row_array();
					$value = withSuccess('Address Saved successfully', array('details'=>$userRow));
					$this->response($value, REST_Controller::HTTP_OK);
				}else{
					$value = withErrors('Unable to Update Profile');
					$this->response($value, REST_Controller::HTTP_OK);
				}
			}else{
				$value = withErrors('Invalid Profile');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		}else{
			$value = withErrors('Invalid Request');
			$this->response($value, REST_Controller::HTTP_OK);
		}

	}


	function mainShippingAddress_put($id){
		if(!empty($id)){
			$userRow = $this->db->get_where('shipping_address', array('id'=>$id))->row_array();
			if(!empty($userRow)){
				$this->db->update('shipping_address', array('pri_address'=>0), array('customerId'=>$userRow['customerId']));
				$update = $this->db->update('shipping_address', array('pri_address'=>1), array('id'=>$id));
				if($update){
					$userRow = $this->db->get_where('shipping_address', array('id'=>$id))->row_array();
					$value = withSuccess('Primary Shipping Address Updated Successfully', array('details'=>$userRow));
					$this->response($value, REST_Controller::HTTP_OK);
				}else{
					$value = withErrors('Unable to Update Shipping Address');
					$this->response($value, REST_Controller::HTTP_OK);
				}
			}else{
				$value = withErrors('Invalid Address');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		}else{
			$value = withErrors('Invalid Request');
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}


	function save_profile_post($id){
		$raw_input_stream = $this->input->raw_input_stream;
		$input = array();
		if(!empty($raw_input_stream)){
			$input = json_decode($raw_input_stream, true);
		}
		if(empty($input)){
			$value = withErrors('No Data Found For Update');
			$this->response($value, REST_Controller::HTTP_OK);
		}
		$getRow = $this->db->get_where('customers', array('id'=>$id))->row_array();
		if(empty($getRow)){
			$value = withErrors('Profile Data Not Found');
			$this->response($value, REST_Controller::HTTP_OK);
		}
		// save the data
		$input['country'] = 'India';
		$updateData = $this->db->update('customers', $input, array('id'=>$id));
		$getData = $this->db->get_where('customers', array('id'=>$id))->row_array();
		if(!empty($getData)){
			$value = withSuccess('Profile Updated Successfully', array('data_list'=>$getData));
		}else{
			$value = withErrrors('Profile Couldn\'t Update');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

}