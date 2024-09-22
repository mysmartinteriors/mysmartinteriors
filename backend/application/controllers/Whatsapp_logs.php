<?php

require APPPATH . 'libraries/REST_Controller.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Whatsapp_logs extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('excelvalidation');
		$this->lang->load('response', 'english');
		$this->table = 'customers';
		$this->model_name = 'whatsapplogsmodel';
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
		$data = array();
		if (!empty($id)) {
			$data = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
			if (!empty($data['details'])) {
				$orderDetails = $this->get_referrals($data['details']['id']);
				$data['details']['referrals_count'] = $orderDetails;
			}
		} else {
			$data = $this->Mydb->do_search($this->table, $this->model_name);
			if (!empty($data['data_list'])) {
				foreach ($data['data_list'] as $key => $value) {
					$orderDetails = $this->get_referrals($value->id);
					$data['data_list'][$key]->referrals_count = $orderDetails;
				}
			}
		}
		if (!empty($data)) {
			$value = withSuccess($message, $data);
		} else {
			$value = withSuccess($this->lang->line('no_result_found'));
		}

		$this->response($value, REST_Controller::HTTP_OK);
	}
	private function get_referrals($customerId) 
	{
		$q = "SELECT count(id) as ref_counts FROM customers WHERE customers.refered_by='$customerId'";
		$result = $this->db->query($q)->row_array();
		return $result['ref_counts'];
	}



	function referrals_get($customer_id = 0)
	{
		$data = [];
		if (!$customer_id) {
			$value = withErrors('No Customer ID Present');
			$this->response($value, REST_Controller::HTTP_OK);
		} else {
			$customer = $this->db->get_where('customers', ['id' => $customer_id])->row_array();
			if (!empty($customer)) {
				$data['customer_data'] = $customer;
				$referrals_up_to_7_levels = [];
				$referrals_up_to_7_levels = $this->recursive_refs($customer_id, 1, 7, $referrals_up_to_7_levels);
				$data['referral_data'] = $referrals_up_to_7_levels;
				$value = withSuccess('Referral Data Fetched successfully', ['data_list'=>$data]);
			} else {
				$value = withErrors('No Customer Found for the given details');
			}
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}
	
	private function recursive_refs($customerId, $current_level = 1, $max_level = 7, &$result = [])
	{
		// Base case: if current level exceeds max level, return the result
		if ($current_level > $max_level) {
			return $result;
		}
	
		// Initialize the current level in the result if not already initialized
		if (!isset($result["level$current_level"])) {
			$result["level$current_level"] = [];
		}
	
		// Query to fetch referrals for the given customerId
		$q = "SELECT customers.* FROM customers WHERE customers.refered_by='$customerId'";
		$query = $this->db->query($q);
		$referrals = $query->result_array();

		foreach($referrals as $key=>$val){
			$qq = "SELECT * FROM user_subscription WHERE user_id='".$val['id']."' AND pay_status='1'";
			$numRows = $this->db->query($qq)->num_rows();			
			$referrals[$key]['is_subscribed'] = $numRows;
		}
	
		// Store the current level referrals in the result
		$result["level$current_level"] = array_merge($result["level$current_level"], $referrals);
	
		// For each referral, fetch their referrals up to the max level
		foreach ($referrals as $referral) {
			$this->recursive_refs($referral['id'], $current_level + 1, $max_level, $result);
		}
	
		return $result;
	}
	


	/**
	 
	 * Get the list of customers from this method.
	 
	 *

	 * @return Response

	*/

	function list_get()
	{
		$message = "success";
		$data = $this->db->order_by('name', 'ASC')->get_where("customers")->result_array();
		if (empty($data)) {
			$message = $this->lang->line('no_result_found');
		}
		$data = array('data_list' => $data);
		$value = withSuccess($message, $data);
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
			'firstName' => ['First Name', 'required|max_length[20]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'lastName' => ['Last Name', 'max_length[200]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'email' => ['Email', 'required|valid_email|max_length[120]|is_unique[customers.email]'],
			'phone' => ['Phone', 'required|max_length[10]|min_length[10]|numeric|is_unique[customers.phone]'],
			'password' => ['Password', 'required|max_length[20]|min_length[4]'],
			'address' => ['Address', 'required|max_length[200]|min_length[10]'],
			'city' => ['City', 'required|max_length[100]|min_length[3]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'state' => ['State', 'required|max_length[100]|min_length[3]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'postalCode' => ['Pincode', 'required|numeric|min_length[6]|max_length[6]'],
			'status' => ['Status', 'required|numeric']
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
				'firstName' => $input['firstName'],
				'lastName' => $input['lastName'],
				'email' => $input['email'],
				'phone' => $input['phone'],
				'password' => $input['password'],
				'address' => $input['address'],
				'city' => $input['city'],
				'state' => $input['state'],
				'postalCode' => $input['postalCode'],
				'status' => $input['status'],
				'createdDate' => cur_date_time()
			);
			if (isset($input['type'])) {
				$data['type'] = $input['type'];
			}
			if (isset($input['refered_by'])) {
				$data['refered_by'] = $input['refered_by'];
			}
			$id = $this->Mydb->insert_table_data('customers', $data);
			$result = $this->db->get_where('customers', array('id' => $id))->row_array();
			$value = withSuccess($this->lang->line('customer_created_success'), array('details' => $result));
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}


	public function register_post()
	{
		$input = $this->input->post();
		$rules = [
			'firstName' => ['First Name', 'required|max_length[200]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'lastName' => ['Last Name', 'max_length[200]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'email' => ['Email', 'required|valid_email|is_unique[customers.email]'],
			'phone' => ['Phone', 'required|is_unique[customers.phone]'],
			'password' => ['Password', 'required|max_length[20]|min_length[4]'],
		];
		$message = [
			'is_unique' => 'The %s is already exists',
		];
		Validator::setMessage($message);
		Validator::make($rules);
		if (!Validator::fails()) {
			Validator::error();
		} else {
			$reference_code = $input['referral_code'];
			$referredUserId = '';
			if (!empty($reference_code)) {
				$q = $this->db->get_where('customer_references', array('token' => $reference_code))->row_array();
				if (!empty($q)) {
					$referredUserId = $q['customer_id'];
					$update = $this->db->update('customer_references', array('status' => 2), array('id' => $q['id']));
				}
			}
			$password = $input['password'];
			// if(!empty($password)){
			// 	$password = password_hash($password,PASSWORD_DEFAULT);
			// }

			$data = array(
				'firstName' => $input['firstName'],
				'lastName' => $input['lastName'],
				'email' => $input['email'],
				'phone' => $input['phone'],
				'refered_by' => $referredUserId,
				'password' => $password,
				'token' => $input['token'],
				'type' => "Registered",
				'createdDate' => cur_date_time(),
				'updatedDate' => cur_date_time(),
				'status' => '2'
			);
			$emaildata = array();
			$activationUrl = 'https://nalaaorganic.com/account/activation/';
			$interfaceUrl = $this->db->get_where('web_settings', array('data_type' => 'interface_url'))->row_array();
			if (!empty($interfaceUrl) && !empty($interfaceUrl['data_value'])) {
				$activationUrl = $interfaceUrl['data_value'] . 'account/activation/';
			}
			$emaildata['email_heading'] = "User Activation Link";
			$emaildata['name'] = $data['firstName'];
			$emaildata['url_link'] = $activationUrl . $input['token'];
			$txt = $this->load->view("email-templates/customers/account-activation-view", $emaildata, true);
			$mailsend = $this->admin->sendmail($txt, $emaildata['email_heading'], $data['email']);
			$ins_id = 0;
			if ($mailsend == 1) {
				$this->db->insert("customers", $data);
				$id = $this->db->insert_id();
				$value = withSuccess("Please check your email, we have sent you an account activation link...");
				// $result = $this->db->get_where('customers',array('id'=>$id))->row_array();
			} else {
				$result = "fail";
				$value = withErrors("Unable to Register! Try after sometime...");
			}
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}

	public function authentication_get()
	{
		$input = $this->get();
		$uid = $input['email'];
		$pwd = $input['password'];
		$sql = "SELECT * FROM customers WHERE (phone='$uid' OR email='$uid') AND password='$pwd'";
		$query = $this->db->query($sql);
		$result['details'] = $query->row_array();
		$value = withSuccess('success', $result);
		$this->response($value, REST_Controller::HTTP_OK);
	}


	public function register_guest_post()
	{
		$input = $this->input->post();
		$rules = [
			'type' => ['Guest Type', 'required'],
			'status' => ['Status', 'required'],
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
				'type' => $input['type'],
				'status' => $input['status'],
			);
			$this->db->insert("customers", $data);
			$id = $this->db->insert_id();
			$value = withSuccess("Guest Registered Successfully", array('details' => array('id' => $id)));
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
		if (!empty($input['firstName'])) {
			$rules['firstName'] = ['First Name', 'required|max_length[20]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['firstName'] = $input['firstName'];
		}
		if (!empty($input['lastName'])) {
			$rules['lastName'] = ['Last Name', 'required|max_length[20]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['lastName'] = $input['lastName'];
		}
		if (!empty($input['email'])) {
			$rules['email'] = ['Email', 'required|valid_email|edit_unique[customers.email.id.' . $id . ']'];
			$data['email'] = $input['email'];
		}
		if (!empty($input['phone'])) {
			$rules['phone'] = ['Phone', 'required|numeric|min_length[10]|max_length[10]'];
			$data['phone'] = $input['phone'];
		}
		if (!empty($input['password'])) {
			$rules['password'] = ['Password', 'required'];
			$data['password'] = $input['password'];
		}
		if (!empty($input['address'])) {
			$rules['address'] = ['Address', 'required|min_length[5]|max_length[200]'];
			$data['address'] = $input['address'];
		}
		if (!empty($input['city'])) {
			$rules['city'] = ['City', 'required|max_length[200]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['city'] = $input['city'];
		}
		if (!empty($input['state'])) {
			$rules['state'] = ['State', 'required|max_length[200]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['state'] = $input['state'];
		}
		if (!empty($input['postalCode'])) {
			$rules['postalCode'] = ['Postal Code', 'required|numeric|min_length[6]|max_length[6]'];
			$data['postalCode'] = $input['postalCode'];
		}
		if (!empty($input['status'])) {
			$rules['status'] = ['Status', 'required'];
			$data['status'] = $input['status'];
		}
		if (!empty($input['planId'])) {
			$rules['planId'] = ['planId', 'required'];
			$data['planId'] = $input['planId'];
		}
		if (!empty($input['subscriptionAmount'])) {
			$rules['subscriptionAmount'] = ['subscriptionAmount', 'required'];
			$data['subscriptionAmount'] = $input['subscriptionAmount'];
		}
		if (!empty($input['subscriptionPoints'])) {
			$rules['subscriptionPoints'] = ['subscriptionPoints', 'required'];
			$data['subscriptionPoints'] = $input['subscriptionPoints'];
		}

		$message = [
			'edit_unique' => 'The %s is already exists',
		];

		Validator::setMessage($message);

		if (array_filter($input)) {
			if (!empty($rules)) {
				Validator::make($rules);
			}
			if (!Validator::fails()) {
				Validator::error();
			}
		}

		if (!empty($data)) {
			$data['updatedDate'] = cur_date_time();
			$customer = $this->db->get_where('customers', array('id' => $id))->row_array();
			if (!empty($customer)) {
				if (isset($input['status']) && !$input['status']) {
					$data['status'] = 0;
				}
				$is_update = $this->Mydb->update_table_data('customers', array('id' => $id), $data);
				$q = $this->db->last_query();
				if ($is_update > 0) {
					if (isset($data['planId']) && isset($data['subscriptionAmount']) && isset($data['subscriptionPoints'])) {
						$customer = $this->db->get_where('customers', array('id' => $id))->row_array();
						$message = "Dear " . $customer['firstName'] . ' ' . $customer['lastName'] . ", " . PHP_EOL . PHP_EOL . "The wallet purchase of " . $data['subscriptionAmount'] . " is successful. Your Current wallet amount is " . $customer['subscriptionAmount'] . " and wallet points is " . $customer['subscriptionPoints'] . PHP_EOL . "Thank You" . PHP_EOL . "Nalaa Organic";
						$whatsapp_message = "Dear " . $customer['firstName'] . ' ' . $customer['lastName'] . ",

The wallet purchase of " . $data['subscriptionAmount'] . " is successful. Your current wallet amount is " . $customer['subscriptionAmount'] . " and wallet points is " . $customer['subscriptionPoints'] . "

Thank You
Nalaa Organic";
						$send_sms = send_sms($customer['phone'], $message);
						$send_whatsapp = send_whatsapp($customer['phone'], $whatsapp_message);
					}
					$result['details'] = $this->Mydb->get_table_data('customers', array('id' => $id));
					$value = withSuccess($this->lang->line('Customer Updated Successfully'), $result);
				} else {
					$value = withErrors($this->lang->line('failed_to_update'));
				}
			} else {
				$value = withErrors($this->lang->line('failed_to_update'));
			}
		} else {
			$value = withErrors($this->lang->line('failed_to_update'));
		}
		$this->response($value, REST_Controller::HTTP_OK);

	}

	public function forgotpassword_put($id)
	{
		$rules = array();
		$data = array();

		$input = $this->put();
		if (!empty($input['password'])) {
			$rules['password'] = ['Password', 'required'];
			$data['password'] = $input['password'];
		}
		if (!empty($input['email'])) {
			$rules['email'] = ['Email', 'required'];
			$datas['email'] = $input['email'];
		}
		$message = [
			'edit_unique' => 'The %s is already exists',
		];

		Validator::setMessage($message);

		if (array_filter($input)) {
			if (!empty($rules)) {
				Validator::make($rules);
			}
			if (!Validator::fails()) {
				Validator::error();
			}
		}
		if (!empty($data)) {
			$data['updatedDate'] = cur_date_time();

			$is_update = $this->Mydb->update_table_data('customers', array('id' => $id), $data);
			$result = $this->Mydb->get_table_data('customers', array('id' => $id));
			// print_R($result);exit();
			if ($is_update > 0) {
				$emaildata = array();
				$emaildata['email_heading'] = "User Forgot Password";
				$emaildata['name'] = $result[0]['firstName'];
				$emaildata['password'] = $result[0]['password'];
				$interfaceUrl = 'https://nalaaorganic.com/';
				$urlData = $this->db->get_where('web_settings', array('data_type' => 'interface_url'))->row_array();
				if (!empty($urlData) && !empty($urlData['data_value'])) {
					$interfaceUrl = $urlData['data_value'];
				}
				$emaildata['interfaceUrl'] = $interfaceUrl;
				// $data['email']
				// $emaildata['url_link'] = "https://sartdev.in/demo_sites/nalaaorganics/interface/account/activation/".$input['token'];
				$txt = $this->load->view("email-templates/customers/forgot-password-view", $emaildata, true);
				//$mailsend = email_forward('1',$emaildata,"user");
				// print_r($txt);
				// exit();
				$mailsend = $this->admin->sendmail($txt, $emaildata['email_heading'], $datas['email']);
				if ($mailsend == 1) {
					$value = withSuccess('Your temporary password is emailed to you.', array("details" => $result));
				} else {
					$value = withErrors('Password is failed to email you.');
				}
			} else {
				$value = withErrors("Unable to initiate email service! Try after sometime....");
			}
		} else {
			$value = withErrors("Failed to Update");
		}
		$this->response($value, REST_Controller::HTTP_OK);

	}


	/**

	 * Delete data from this method.

	 *

	 * @return Response

	*/


	function index_delete($id)
	{
		$getData = $this->db->get_where('customers', array('id' => $id))->row_array();
		if (empty($getData)) {
			$value = withErrors('Customer Not Found');
		} else {
			$sql = "SELECT 1 
            FROM (
                SELECT customerId FROM shopping_cart
                UNION ALL
                SELECT customerId FROM support_enquiries
            ) a WHERE customerId = '$id'";
			$res = $this->db->query($sql)->num_rows();
			if ($res) {
				$value = withErrors('Customer is Linked with other datas like cart, support etc.....Cannot remove');
			} else {
				$delete = $this->db->delete('customers', array('id' => $id));
				if ($delete > 0) {
					$value = withSuccess('Customer Deleted Successfully', array('details' => $getData));
				} else {
					$value = withErrors('Couldn\'t Add Customer');
				}
			}
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function dashboard_bookings_get($uId)
	{
		$sql = "SELECT * FROM orders_booking
				WHERE customerId='$uId'
				ORDER BY createdDate DESC LIMIT 5";
		$result = array();
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
		}
		$value = withSuccess('Success', array('details' => $result));
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function my_bookings_get($id)
	{
		$sql = "SELECT p.productName,p.CGST,p.SGST,p.productImage,p.productURL,
				c.text as categoryName,bd.qty,bd.price,bd.createdDate,bd.tax,bd.total
				FROM products p
				LEFT JOIN categories c
				ON p.categoryId=c.id
				INNER JOIN orders_booking_details bd
				ON bd.productId=p.productId
				WHERE bd.orderId='$id'";
		$query = $this->db->query($sql);
		return $query;
	}

	function send_otp_get($mobile = '')
	{
		if (strlen($mobile) == 10) {
			$otp = generate_otp();
			$sms_txt = "Dear Customer, " . PHP_EOL;
			$sms_txt .= "Your OTP For NalaaOrganic Verification is $otp" . PHP_EOL;
			$sms_txt .= "Thank You" . PHP_EOL;
			$whatsapp_text = "Dear Customer,

Your Nalaa Organics Verification number is $otp.

Thank You.";
			// $send_whatsapp = send_whatsapp($mobile, $whatsapp_text);
			$send_sms = send_sms($mobile, $sms_txt);
			$findCustomer = $this->db->get_where('customers', array('phone' => $mobile))->row_array();
			// print_r($findCustomer);exit();
			if (!empty($findCustomer)) {
				//checking if the customer Status ==2 And ProfileStep == 2
				if ($findCustomer['status'] == 2 && $findCustomer['profileStep'] == 2) {
					$this->db->where(array('id' => $findCustomer['id']));
					$updateDb = $this->db->update('customers', [
						'otp' => $otp,
						'status' => 2,
						'profileStep' => 2,
						'otp_verification' => 'NO',
						'otp_count' => !empty($findCustomer['otp_count']) ? ($findCustomer['otp_count'] + 1) : 1,
						'updatedDate' => cur_date_time(),
					]);
				} else {
					$this->db->where(array('id' => $findCustomer['id']));
					$updateDb = $this->db->update('customers', [
						'otp' => $otp,
						'otp_verification' => 'NO',
						'otp_count' => !empty($findCustomer['otp_count']) ? ($findCustomer['otp_count'] + 1) : 1,
						'updatedDate' => cur_date_time(),
					]);
				}
			} else {
				$updateDb = $this->db->insert('customers', [
					'phone' => $mobile,
					'otp' => $otp,
					'otp_verification' => 'NO',
					'otp_count' => 1,
					'token' => $this->randomCodenum(10),
					'profileStep' => 1,
					'type' => 'Mobile App',
					'createdDate' => cur_date_time()
				]);
			}
			$value = withSuccess('OTP is sent to your mobile number ' . $mobile);
		} else {
			$value = withErrors('Invalid Mobile Number Detected');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}



	function randomCodenum($num)
	{
		$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < $num; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}


	function login_post()
	{
		$raw_input_stream = $this->input->raw_input_stream;
		$input = json_decode($raw_input_stream, true);
		if (!empty($input['uid']) && !empty($input['pwd'])) {
			// $rowData = $this->db->get_where($this->table, array('phone'=>$input['mobileNumber'], 'otp'=>$input['otp']))->row_array();
			$uid = $input['uid'];
			$pwd = $input['pwd'];
			$q = "SELECT * FROM customers WHERE email='$uid' OR phone='$uid'";
			$rowData = $this->db->query($q)->row_array();
			if (empty($rowData)) {
				$value = withErrors('Invalid Credentials');
				$this->response($value, REST_Controller::HTTP_OK);
			}
			if ($input['pwd'] != $rowData['password']) {
				$value = withErrors('Invalid Credentials');
				$this->response($value, REST_Controller::HTTP_OK);
			}
			if (!empty($rowData)) {
				if ($rowData['status'] == 2 && $rowData['profileStep'] == 2) {
					$updateDb = $this->db->update($this->table, array(
						'otp_verification' => 'YES',
						'updatedDate' => cur_date_time(),
					), array('id' => $rowData['id']));
				} else {
					$updateDb = $this->db->update($this->table, array(
						'otp_verification' => 'YES',
						'updatedDate' => cur_date_time(),
						'status' => 1,
						'profileStep' => 1
					), array('id' => $rowData['id']));
				}
				if ($updateDb) {
					// $rowData = $this->db->get_where($this->table, array('phone'=>$input['mobileNumber'], 'otp'=>$input['otp']))->row_array();
					$value = withSuccess('Login Successfull', array('details' => $rowData));
				} else {
					$value = withErrrors('Login Unsuccessful');
				}
				$this->response($value, REST_Controller::HTTP_OK);
			} else {
				$value = withErrors('Invalid Credentials');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		} else {
			$value = withErrors('Invalid Credentials');
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}

	function verifyOTP_post()
	{
		$raw_input_stream = $this->input->raw_input_stream;
		$input = json_decode($raw_input_stream, true);
		if (!empty($input['mobileNumber']) && !empty($input['otp'])) {
			$rowData = $this->db->get_where($this->table, array('phone' => $input['mobileNumber'], 'otp' => $input['otp']))->row_array();
			if (!empty($rowData)) {
				if ($rowData['status'] == 2 && $rowData['profileStep'] == 2) {
					$updateDb = $this->db->update($this->table, array(
						'otp_verification' => 'YES',
						'updatedDate' => cur_date_time(),
					), array('id' => $rowData['id']));
				} else {
					$updateDb = $this->db->update($this->table, array(
						'otp_verification' => 'YES',
						'updatedDate' => cur_date_time(),
						'status' => 1,
						'profileStep' => 1
					), array('id' => $rowData['id']));
				}
				if ($updateDb) {
					$rowData = $this->db->get_where($this->table, array('phone' => $input['mobileNumber'], 'otp' => $input['otp']))->row_array();
					$value = withSuccess('OTP Verified Successfully', array('details' => $rowData));
				} else {
					$value = withErrrors('OTP Update Failed');
				}
				$this->response($value, REST_Controller::HTTP_OK);
			} else {
				$value = withErrors('Invalid OTP');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		} else {
			$value = withErrors('Invalid OTP');
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}


	function save_addresses_post()
	{
		$raw_input_stream = $this->input->raw_input_stream;
		$input = json_decode($raw_input_stream, true);
		if (!empty($input['userId'])) {
			$userId = $input['userId'];
			$defaultAddress = (isset($input['defaultAddress']) && $input['defaultAddress'] == 'on') ? 1 : 0;
			$rowsData = $this->db->get_where('shipping_address', array('customerId' => $input['userId']))->result_array();
			if (!empty($rowsData) && $defaultAddress) {
				foreach ($rowsData as $row) {
					if ($row['pri_address'] == 1) {
						$this->db->update('shipping_address', array('pri_address' => 0), array('id' => $row['id']));
					}
				}
			}
			$get_geocode = get_geocode($input['flat'] . ', ' . $input['area'] . ', ' . $input['landmark'].', '.$input['state'].', '.$input['city'].' - '.$input['pincode']. ', '.'India');
			$insert = $this->db->insert('shipping_address', [
				'customerId' => $userId,
				'name' => $input['fullName'],
				'latitude' => !empty($get_geocode['latitude'])?$get_geocode['latitude']:'',
				'longitude' => !empty($get_geocode['longitude'])?$get_geocode['longitude']:'',
				'phone' => $input['mobileNumber'],
				'address' => $input['flat'] . ', ' . $input['area'] . ', ' . $input['landmark'],
				'city' => $input['city'],
				'state' => $input['state'],
				'country' => 'India',
				'postalCode' => $input['pincode'],
				'createdDate' => cur_date_time(),
				'pri_address' => $defaultAddress ? 1 : 0
			]);
			$insertId = $this->db->insert_id();
			if ($insert) {
				$rowData = $this->db->get_where('shipping_address', array('id' => $insertId))->row_array();
				$value = withSuccess('Address Added Successfully', array('details' => $rowData));
			} else {
				$value = withErrrors('Address Couldn\'t be updated');
			}
			if (!empty($rowData)) {
				$this->response($value, REST_Controller::HTTP_OK);
			} else {
				$value = withErrors('Invalid OTP');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		} else {
			$value = withErrors('Invalid User ID');
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}


	function save_addresses_put($id)
	{
		$raw_input_stream = $this->input->raw_input_stream;
		$input = json_decode($raw_input_stream, true);
		if (!empty($id)) {
			$defaultAddress = (isset($input['defaultAddress']) && $input['defaultAddress'] == 'on') ? 1 : 0;
			$rowsData = $this->db->get_where('shipping_address', array('customerId' => $input['userId']))->result_array();
			if (!empty($rowsData) && $defaultAddress) {
				foreach ($rowsData as $row) {
					if ($row['pri_address'] == 1) {
						$this->db->update('shipping_address', array('pri_address' => 0), array('id' => $row['id']));
					}
				}
			}
			$rowData = $this->db->get_where('shipping_address', array('id' => $id))->row_array();
			if (!empty($rowData)) {
				$get_geocode = get_geocode($input['address'].', '.$input['state'].', '.$input['city'].' - '.$input['postalCode']. ', '.'India');
				$update = $this->db->update('shipping_address', [
					'name' => $input['name'],
					'phone' => $input['phone'],
					'address' => $input['address'],
					'city' => $input['city'],
					'latitude' => !empty($get_geocode['latitude'])?$get_geocode['latitude']:'',
					'longitude' => !empty($get_geocode['longitude'])?$get_geocode['longitude']:'',
					'state' => $input['state'],
					'country' => 'India',
					'postalCode' => $input['postalCode'],
					'createdDate' => cur_date_time(),
					'pri_address' => $defaultAddress ? 1 : 0
				], ['id' => $id]);
				$value = $update ? withSuccess('Address Updated Successfully') : withErrors('Address Couldn\'t Be Updated');
			} else {
				$value = withErrors('No Address Found');
			}
		} else {
			$value = withErrors('Invalid Address Data');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


	function shipping_address_get($id)
	{
		$getUser = $this->db->get_where('customers', ['id' => $id])->row_array();
		if (!empty($getUser)) {
			$getData = $this->db->get_where('shipping_address', array('customerId' => $id, 'status'=>'64'))->result_array();
			if (!empty($getData)) {
				$value = withSuccess('Success', array('details' => $getData));
			} else {
				$value = withErrors('No Shipping Addresses Found');
			}
		} else {
			$value = withErrors('Invalid User ID');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


	function saveProfile_put($id)
	{
		$raw_input_stream = $this->input->raw_input_stream;
		$input = array();
		if (!empty($raw_input_stream)) {
			$input = json_decode($raw_input_stream, true);
		}
		if (!empty($input)) {
			$userRow = $this->db->get_where($this->table, array('id' => $id))->row_array();
			if (!empty($userRow)) {
				$userData = array(
					'firstName' => !empty($input['firstName']) ? $input['firstName'] : $userRow['firstName'],
					'lastname' => !empty($input['lastName']) ? $input['lastName'] : $userRow['lastName'],
					'email' => !empty($input['email']) ? $input['email'] : $userRow['email'],
					'profileStep' => 2,
					'status' => 2
				);
				$update = $this->db->update($this->table, $userData, array('id' => $id));
				if ($update) {
					$userRow = $this->db->get_where($this->table, array('id' => $id))->row_array();
					$value = withSuccess('Profile Saved successfully', array('details' => $userRow));
					$this->response($value, REST_Controller::HTTP_OK);
				} else {
					$value = withErrors('Unable to Update Profile');
					$this->response($value, REST_Controller::HTTP_OK);
				}
			} else {
				$value = withErrors('Invalid Profile');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		} else {
			$value = withErrors('Invalid Request');
			$this->response($value, REST_Controller::HTTP_OK);
		}

	}


	function saveMainAddress_put($id)
	{
		$raw_input_stream = $this->input->raw_input_stream;
		$input = array();
		if (!empty($raw_input_stream)) {
			$input = json_decode($raw_input_stream, true);
		}
		if (!empty($input)) {
			$userRow = $this->db->get_where($this->table, array('id' => $id))->row_array();
			if (!empty($userRow)) {
				$userData = array(
					'address' => !empty($input['address']) ? $input['address'] : $userRow['address'],
					'postalCode' => !empty($input['postalCode']) ? $input['postalCode'] : $userRow['postalCode'],
					'city' => !empty($input['city']) ? $input['city'] : $userRow['city'],
					'state' => !empty($input['state']) ? $input['state'] : $userRow['state'],
					'country' => !empty($input['country']) ? $input['country'] : $userRow['country'],
					'profileStep' => 2
				);
				$update = $this->db->update($this->table, $userData, array('id' => $id));
				if ($update) {
					$userRow = $this->db->get_where($this->table, array('id' => $id))->row_array();
					$value = withSuccess('Address Saved successfully', array('details' => $userRow));
					$this->response($value, REST_Controller::HTTP_OK);
				} else {
					$value = withErrors('Unable to Update Profile');
					$this->response($value, REST_Controller::HTTP_OK);
				}
			} else {
				$value = withErrors('Invalid Profile');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		} else {
			$value = withErrors('Invalid Request');
			$this->response($value, REST_Controller::HTTP_OK);
		}

	}


	function mainShippingAddress_put($id)
	{
		if (!empty($id)) {
			$userRow = $this->db->get_where('shipping_address', array('id' => $id))->row_array();
			if (!empty($userRow)) {
				$this->db->update('shipping_address', array('pri_address' => 0), array('customerId' => $userRow['customerId']));
				$update = $this->db->update('shipping_address', array('pri_address' => 1), array('id' => $id));
				if ($update) {
					$userRow = $this->db->get_where('shipping_address', array('id' => $id))->row_array();
					$value = withSuccess('Primary Shipping Address Updated Successfully', array('details' => $userRow));
					$this->response($value, REST_Controller::HTTP_OK);
				} else {
					$value = withErrors('Unable to Update Shipping Address');
					$this->response($value, REST_Controller::HTTP_OK);
				}
			} else {
				$value = withErrors('Invalid Address');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		} else {
			$value = withErrors('Invalid Request');
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}


	function save_profile_post($id)
	{
		$raw_input_stream = $this->input->raw_input_stream;
		$input = array();
		if (!empty($raw_input_stream)) {
			$input = json_decode($raw_input_stream, true);
		}
		if (empty($input)) {
			$value = withErrors('No Data Found For Update');
			$this->response($value, REST_Controller::HTTP_OK);
		}
		$reference_code = isset($input['token']) ? $input['token'] : '';
		$referredUserId = '';
		if (!empty($reference_code)) {
			$q = $this->db->get_where('customer_references', array('token' => $reference_code))->row_array();
			if (!empty($q)) {
				$referredUserId = $q['customer_id'];
				$update = $this->db->update('customer_references', array('status' => 2), array('id' => $q['id']));
			}
		}
		$getRow = $this->db->get_where('customers', array('id' => $id))->row_array();
		if (empty($getRow)) {
			$value = withErrors('Profile Data Not Found');
			$this->response($value, REST_Controller::HTTP_OK);
		}
		if (isset($input['token'])) {
			unset($input['token']);
			$input['refered_by'] = $referredUserId;
		}
		// save the data
		$input['country'] = 'India';
		$updateData = $this->db->update('customers', $input, array('id' => $id));
		$getData = $this->db->get_where('customers', array('id' => $id))->row_array();
		if (!empty($getData)) {
			$value = withSuccess('Profile Updated Successfully', array('data_list' => $getData));
		} else {
			$value = withErrrors('Profile Couldn\'t Update');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


	function settleReferrals_get($id)
	{
		$data = array();
		$dateRange = getCurrentMonthDateRange();
		$startDate = $dateRange['start_date'];
		$endDate = $dateRange['end_date'];
		$dataQuery = "SELECT customer_reference_amount.* 
						FROM customer_reference_amount
						WHERE customer_reference_amount.createdDate BETWEEN '$startDate' AND '$endDate' AND customer_reference_amount.customer_id = '$id' AND customer_reference_amount.status='1'";
		$referralComissions = $this->db->query($dataQuery)->result_array();
		$data['daterange'] = $startDate . ' to ' . $endDate;
		$totalReferralComission = 0;
		if (!empty($referralComissions)) {
			$data['referralComissions'] = $referralComissions;
			foreach ($referralComissions as $row) {
				$totalReferralComission += $row['amount'];
			}
			$data['totalReferralComission'] = $totalReferralComission;
			$value = withSuccess('Referral Comission Settled for the daterange of ' . $startDate . ' to ' . $endDate, array('data_list' => $data));
		} else {
			$value = withErrors('No Referral Earnings Found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function settleRepurchases_put($id)
	{
		$dateRange = getCurrentMonthDateRange();
		$startDate = $dateRange['start_date'];
		$endDate = $dateRange['end_date'];
		$dataQuery = "SELECT customer_repurchase_amount.* 
						FROM customer_repurchase_amount
						WHERE customer_repurchase_amount.createdDate BETWEEN '$startDate' AND '$endDate' AND customer_repurchase_amount.customer_id = '$id' AND customer_repurchase_amount.status='1'";
		$res = $this->db->query($dataQuery)->result_array();
		if (!empty($res)) {
			foreach ($res as $row) {
				$update = $this->db->update('customer_repurchase_amount', array('status' => 2), array('id' => $row['id']));
			}
			$value = withSuccess('Repurchase Comission Settled for the daterange of ' . $startDate . ' to ' . $endDate);
		} else {
			$value = withErrors('No Referral Earnings Found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function settleRepurchase_get($id)
	{
		$data = array();
		$dateRange = getCurrentMonthDateRange();
		$startDate = $dateRange['start_date'];
		$endDate = $dateRange['end_date'];
		$dataQuery = "SELECT customer_repurchase_amount.* 
						FROM customer_repurchase_amount
						WHERE customer_repurchase_amount.createdDate BETWEEN '$startDate' AND '$endDate' AND customer_repurchase_amount.customer_id = '$id' AND customer_repurchase_amount.status='1'";
		$referralComissions = $this->db->query($dataQuery)->result_array();
		$data['daterange'] = $startDate . ' to ' . $endDate;
		$totalReferralComission = 0;
		if (!empty($referralComissions)) {
			$data['referralComissions'] = $referralComissions;
			foreach ($referralComissions as $row) {
				$totalReferralComission += $row['amount'];
			}
			$data['totalReferralComission'] = $totalReferralComission;
			$value = withSuccess('Success', array('data_list' => $data));
		} else {
			$value = withErrors('No Referral Earnings Found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function checkmail_post()
	{
		$raw_input_stream = $this->input->raw_input_stream;
		$input = array();
		if (!empty($raw_input_stream)) {
			$input = json_decode($raw_input_stream, true);
		}
		$email = isset($input['email']) ? $input['email'] : '';
		$id = $input['userId'];
		// $row = $this->db->get_where('customers', array('email'=>$email))->row_array();
		$q = "SELECT * FROM customers WHERE email='$email' AND id NOT IN ('$id')";
		$row = $this->db->query($q)->row_array();
		if (!empty($row)) {
			$value = withSuccess('Email already exists');
		} else {
			$value = withErrors('Email doesn\'t exists');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function checkrefcode_post()
	{
		$raw_input_stream = $this->input->raw_input_stream;
		$input = array();
		if (!empty($raw_input_stream)) {
			$input = json_decode($raw_input_stream, true);
		}
		$email = isset($input['refcode']) ? $input['refcode'] : '';
		$row = $this->db->get_where('customer_references', array('token' => $email, 'status' => 1))->row_array();
		if (!empty($row)) {
			$value = withSuccess('Reference Code is Correct');
		} else {
			$value = withErrors('Reference Code doesn\'t exists');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function delete_account_put($id)
	{
		if (empty($id)) {
			$this->response(withErrors('Invalid Request, Please try logging out and login again'), REST_Controller::HTTP_OK);
		}
		$getPendingOrders = $this->db->get_where('orders', array('customerId' => $id, 'status' => '25'))->result_array();
		if (!empty($getPendingOrders)) {
			$this->response(withErrors('Cannot delete your account. Your few orders are pending, Please cancel or reach out to us through Support Enquiries for further details'), REST_Controller::HTTP_OK);
		} else {
			// Logic to delete an account and update the order
			// Update the existing orders to deleteCustomer
			$q = "UPDATE orders SET customerId='19' WHERE customerId='$id'";
			$res = $this->db->query($q);
			// Delete customer & Shipping Address
			$deleteCustomer = $this->db->delete('customers', array('id' => $id));
			$qShippingAddress = "UPDATE shipping_address SET customerId='19' WHERE customerId='$id'";
			$deleteShippingAddress = $this->db->query($qShippingAddress);
			$this->response(withSuccess('Your account is deleted permanently, Thank you for joining the Nalaa Organic'), REST_Controller::HTTP_OK);
		}
	}

	function update_wallet_amount_put($id)
	{
		$data = $this->put();
		if (empty($data['amount']) || empty($data['walletPoints'])) {
			$this->response(withErrors('Unknow request, Please try again later'), REST_Controller::HTTP_OK);
		}
		$userdata = $this->db->get_where('customers', array('id' => $id))->row_array();
		if (empty($userdata)) {
			$this->response(withErrors('Unknow request, Please try again later'), REST_Controller::HTTP_OK);
		}
		$amount = $data['amount'] + $userdata['subscriptionAmount'];
		$walletAmount = $data['walletPoints'] + $userdata['subscriptionPoints'];
		$update = $this->db->update('customers', array('subscriptionAmount' => $amount, 'subscriptionPoints' => $walletAmount), array('id' => $id));
		if ($update) {
			$this->response(withSuccess('Wallet Updated Successfully'), REST_Controller::HTTP_OK);
		} else {
			$this->response(withErrors('Something went wrong, Please try again later'), REST_Controller::HTTP_OK);
		}
	}


}