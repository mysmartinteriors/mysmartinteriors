<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH.'vendor/autoload.php';

class Account extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('cookie');
		$this->admin->nocache();
	}

	public function index() {
		redirect(base_url().'account/dashboard');
    }

    function login() {
    	if(is_uLogged() && get_userId()){
    		redirect(base_url().'account/dashboard');
    	}else{
			$data=array();
			$data = $this->admin->commonFiles();
			$data['title'] = "Customer Login";
	        $this->load->view("user/login_page_view",$data);
	    }
    }

    function popup_login() {
		$data=array();
        $str=$this->load->view("user/login_modal_view",$data,true);        
        $value=array(
            'str'=>$str
        );
         echo json_encode($value);
    }

    function auth(){
		$email=$this->input->post("email");
		$password=$this->input->post("password");
		$callback = $this->input->post('callback');
		$apidata=$this->curl->execute('customers/authentication', 'GET', array('email'=>$email, 'password'=>$password));
		if ($apidata['status']=='success' && !empty($apidata['data_list'])){
			$row_data = $apidata['data_list'];
			if($row_data['status']==2){
				$value=array(
					'result'=>"fail",
					'urldirect'=>"",
					'msg'=>"Your account is inactive!"
				);
				echo json_encode($value);
				exit;
			}
			if($row_data["status"]== 0){
				$value=array(
					'result'=>"fail",
					'urldirect'=>"",
					'msg'=>"Your account is inactive!"
				);
				echo json_encode($value);
				exit;
			}
			if(!empty(get_userId())){
				$cartApi = $this->curl->execute("cart/".get_userId(), 'PUT', array('customerId'=>$row_data['id']));
			}	
			$user_data = $this->session->all_userdata();
			if(!empty($user_data)){
				foreach ($user_data as $key => $value) {
					if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
						$this->session->unset_userdata($key);
					}
				}
			}
			$userdata=array(			
				'gt_uId'=>$row_data['id'],
				'gt_uName' => $row_data['firstName'],
				'gt_uFullName' => $row_data['firstName'].' '.$row_data['lastName'],
				'gt_uEmail' => $row_data['email'],
				'gt_uMobile' => $row_data['phone'],
				'gt_isULogged'=>1,
				'gt_refCode'=>$row_data['reference_code']
			);
			$this->session->set_userdata($userdata);
			if($callback==""){
				$urldirect = base_url().'account/dashboard';
			}else{
				$urldirect = base_url().$callback;
			}			
			$result="success";
			$msg = "Login success!!! Please wait to direct";				
		}else{
			$result="fail";
			$urldirect = "";
			$msg = "Username or Password wrong";
		}		
		$value=array(
			'result'=>$result,
			'urldirect'=>$urldirect,
			'msg'=>$msg,
		);
		echo json_encode($value);
	}

	function checkemail(){
		$email=$this->input->post("email");
		$q=0;
		$email=strtolower($email);
		$result = "true";
		if($email){
			$apidata=$this->curl->execute('customers', 'GET', array('email'=>$email));
			if($apidata['status']=='success' && !empty($apidata['data_list'])){
				$result = "false";
			}
		}
		echo $result;
	}


	function handleOAuth(){
		$input = $this->input->post();
		// print_r($input);exit();
		if(isset($input['credential'])){
			$id_token = $input['credential'];
			$client = new Google_Client(['client_id' => '346360535258-b0712geelkfomiiuc1l1ior3u5c357ng.apps.googleusercontent.com']);  // Specify the CLIENT_ID of the app that accesses the backend
			$payload = $client->verifyIdToken($id_token);
			// print_r($payload);exit();
			if ($payload) {
				$email = $payload['email'];
				$customer = $this->curl->execute('customers', 'GET', array('customers-email'=>$email));
				if($customer['status']=='success' && !empty($customer['data_list'][0])){
					$row_data = $customer['data_list'][0];
					$userdata=array(			
						'gt_uId'=>$row_data['id'],
						'gt_uName' => $row_data['firstName'],
						'gt_uFullName' => $row_data['firstName'].' '.$row_data['lastName'],
						'gt_uEmail' => $row_data['email'],
						'gt_uMobile' => $row_data['phone'],
						'gt_isULogged'=>1,
						// 'gt_refCode'=>$row_data['reference_code']
					);
					$this->session->set_userdata($userdata);
					redirect(base_url());
				}else{
					$data = $this->admin->commonFiles();
					$data['title'] = "Customer Login";
					$this->load->view('user/account_not_found_view', $data);
				}
			} else {
				$data = $this->admin->commonFiles();
				$data['title'] = "Customer Login";
				$this->load->view('user/account_not_found_view');
			}
		}else{
			redirect(base_url());
		}
	}


	function checkphone(){
		$phone=$this->input->post("phone");
		$q=0;
		$result = "true";
		if($phone){
			$apidata=$this->curl->execute('customers', 'GET', array('phone'=>$phone));
			if($apidata['status']=='success' && !empty($apidata['data_list'])){
				$result = "false";
			}
		}
		echo $result;
	}


	function checkreferral(){
		$token=$this->input->post("referral_code");
		$q=0;
		if(!empty($token)){
			$apidata=$this->curl->execute('customers/checkrefcodeWeb', 'POST', array('refcode'=>$token)); 
			if($apidata['status']=='success'){
				// if($apidata['data_list'][0]['status']==1){
				// 	$result = "true";
				// }else{
				// 	$result = 'false';
				// }
				$result = "true";
			}else{
				$result = "false";
			}
		}else{
			$result = "true";
		}
		// print_R($result);exit();
		echo $result;
	}

	function register(){
		$data['firstName']=$this->input->post("firstName");
		$data['lastName']=$this->input->post("lastName");
		$data['email']=$this->input->post("email");
		$data['phone']=$this->input->post("phone");
		$data['password']=$this->input->post("password"); 
		$data['token']=$this->admin->randomCodenum(10);
		$data['referral_code'] = $this->input->post('referral_code');
		$data['type']= 'Registered';
		$data['createdDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
		$data['updatedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
		$data['status']=2;
		$apidata = $this->curl->execute('customers/register', 'POST', $data); 
		$value = array(
			'status'=>$apidata['status'],
			'message'=>$apidata['message']
		);
		echo json_encode($value);
		return;
	}	

	function activation(){
		$token = $this->uri->segment(3);
		$data = $this->admin->commonFiles();
		$data['title'] = "User Activation Status";
		$data['breadcrumb1'] = "Account";
		$data['breadcrumb2'] = "Activation";
		$data['statusHead'] = "User Activation Status";
		$apidata = $this->curl->execute('customers', 'GET', array('customers-token'=>$token));
		// print_R($apidata);exit();
		// $get_user = $this->usermodel->get_user_token($token);
		// $row = $get_user->row_array();
		if($apidata['status']=='success' && !empty($apidata['data_list'])){
			$row = $apidata['data_list'][0];
			if($row['status']==1){
				$data['message'] = "Your account is already active!!!";
				$data['status'] = "fail";
			}else if($row['status']==0){
				$data['message'] = "Your account has been deactivated! please contact us";
				$data['status'] = "fail";
			}else{
				$userId = $row['id'];
				// print_R($userId);exit();
				$userData['status'] = 1;
				$update = $this->curl->execute("customers/$userId", "PUT", $userData);
				if($update['status']=='success'){
					$data['message'] = "Your account has been activated successfully.";
					$data['status'] = 'success';
				}else{
					$data['message'] = "Internal Server Error, Please Try again";
					$data['status'] = 'fail';
				}
			}
		}else{
			$data['message'] = "Sorry, We didn\'t find your account to activate";
			$data['status'] = "fail";
		}
		$this->load->view("user/user_msg_view",$data);
	}

	function forgotpassword(){
		$emaildata['newpassword'] = $this->admin->randomCodenum(8); 
		$emaildata['email'] = $this->input->post('email'); 
		$get_user = $this->curl->execute("customers", "GET", array("customers-email" => $emaildata['email']));
		// print_R($get_user);exit();
		if($get_user['status'] == 'success' && !empty($get_user['data_list'])){
			$get_user = $get_user['data_list'][0];
			$cid = $get_user['id'];
			$update_data = $this->curl->execute("customers/forgotpassword/$cid", "PUT", array('password'=>$emaildata['newpassword'], 'email' => $emaildata['email']));
			// print_R($update_data);exit();
			if($update_data['status'] == 'success' && !empty($update_data['data_list'])){
				$msg = $update_data['message'];
				$status = $update_data['status'];
			}else{
				$msg = $update_data['message'];
				$status = $update_data['status'];
			}
		}else{
			$msg = $get_user['message'];
				$status = $get_user['status'];
		}
			
		$value = array(
            'msg' => $msg,
            'status' => $status
        );
        echo json_encode($value);
	}

	function logout(){		
		$this->session->sess_destroy();
		redirect(base_url());
	}

	function dashboard() {
		if(is_uLogged() && get_userId()){
			$customerId=get_userId();
			$where = array('shipping_address-customerId'=>$customerId,'shipping_address-pri_address'=>1);
			$data=array();
			$data = $this->admin->commonFiles();
			$data['title'] = "Account Dashboard";
			$data['userQ']=$this->curl->execute("customers/$customerId", "GET");
			$data['billAddrQ']=$this->curl->execute('shipping_address','GET', $where);
			$data['bookOrderQ']=$this->curl->execute("customers/dashboard_bookings/$customerId", "GET");
	        $this->load->view("user/dashboard_view",$data); 
   		}else{
   			redirect(base_url().'account/login');
   		}
    }

	function myprofile() {
		if(is_uLogged() && get_userId()){
			$customerId=get_userId();
			$data=array();
			$data = $this->admin->commonFiles();
			$data['title'] = "Account Profile";
			$data['userQ']=$this->curl->execute("customers/$customerId", "GET");
			$where = array('customerId'=>get_userId());
			$data['billAddrQ']=$this->curl->execute('shipping_address','GET', $where);
	        $this->load->view("user/myprofile_view",$data);
   		}else{
   			redirect(base_url().'account/login');
   		}
    }    

	function change_pass() {
		if(is_uLogged() && get_userId()){
			$customerId=get_userId();
			$data=array();
			$data = $this->admin->commonFiles();
			$data['title'] = "Change Password";
			$data['userQ']=$this->curl->execute("customers/$customerId", "GET");
	        $this->load->view("user/change_password_view",$data);
   		}else{
   			redirect(base_url().'account/login');
   		}
    }
	
	function update_password(){
		if(is_uLogged() && get_userId()){
			$status = 'fail';
			$customerId=$this->input->post("customerId");
			$old_pass=$this->input->post("old_pass");
			$new_pass=$this->input->post("new_pass");
			// $where = array('customerId' => $customerId, 'password'=>$old_pass);
			$apidata=$this->curl->execute("customers/$customerId", 'GET');
			if($apidata['status']=='success' && !empty($apidata['data_list'])){
				if($apidata['data_list']['password']== $old_pass){
					$data['password']=$new_pass;
					$data['updatedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
					$where = array('customerId' => $customerId);
					$apidata=$this->curl->execute("customers/$customerId", "PUT", $data);
					if($apidata['status']=='success' && !empty($apidata['data_list'])){
						$status = "success";
						$message = "Your account password has been updated successfully";
						//insert log data
						// $logData['action']='update';
						// $logData['description']='updated their account password';
						// $logData['dataId']=$customerId;
						// $logData['module']='customers';
						// $logData['table_name']='customers';
						// insert_uLog($logData);
					}else{
						$status = $apidata['status'];
						$message = $apidata['message'];
					}
				}else{
					$status = "fail";
					$message = "Wrong Old Password";
				}
			}else{				
				$message = "Invalid Password!";
			}
			 echo json_encode(array('status'=>$status, 'message'=>$message));
	    }else{
   			redirect(base_url().'account/login');
   		}
	}
	
	function saveprofile(){
		if(is_uLogged() && get_userId()){
			$data['id']=$this->input->post("customerId");
			$data['firstName']=$this->input->post("firstName");
			$data['lastName']=$this->input->post("lastName");
			$data['email']=$this->input->post("email");
			$data['phone']=$this->input->post("phone");
			$data['address']=$this->input->post("address");
			$data['city']=$this->input->post("city");
			$data['state']=$this->input->post("state");
			$data['country']=$this->input->post("country");
			$data['postalCode']=$this->input->post("postalCode");
			$data['updatedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
			$apidata = $this->curl->execute("customers/".get_userId(), "PUT", $data);
			// print_R($apidata);exit();
			$value = array('status'=>$apidata['status'], 'message'=>$apidata['message']);
			echo json_encode($value);
	    }else{
   			redirect(base_url().'account/login');
   		}
	}

	function myorders() {
		if(is_uLogged() && get_userId()){
			$customerId=get_userId();
			$data=array();
			$data = $this->admin->commonFiles();
			$data['title'] = "User Orders";
			$data['bookOrderQ']=$this->curl->execute("orders", "GET", array("orders-customerId" =>$customerId, 'perpage'=>1000));
			// print_R($data['bookOrderQ']);exit();
	        $this->load->view("user/myorders_view",$data); 
   		}else{
   			redirect(base_url().'account/login');
   		}
    }

    function get_booking(){
		$data=array();
    	$id=$this->input->post('id');
    	$data['status']=$this->input->post('status');
		if(is_uLogged() && get_userId() && $id){
			$orders = $this->curl->execute("orders/$id", "GET");
			// $data['orderQ']=$this->usermodel->get_my_bookings($id);
			if($orders['status'] == 'success' && !empty($orders['data_list'])){
				$data['orderQ'] = $orders['data_list'];
				$result='success';
	        	$str=$this->load->view("user/booking_details_view",$data,true);				
			}else{
				$result='fail';
				$str='Unable to fetch your orders, please hit back!';
			}
			//print_r($this->db->last_query());
	        $value = array('msg' => $str,'result'=>$result);
	        echo json_encode($value);
   		}else{
   			redirect(base_url().'account/login');
   		}
    }

    function myaddresses(){
		if(is_uLogged() && get_userId()){
			$where = array('customerId'=>get_userId(), 'shipping_address-status'=>'64');
			$data=array();
			$data = $this->admin->commonFiles();
			$data['title'] = "My Shipping Addresses";
			$data['billAddrQ']=$this->curl->execute('shipping_address','GET', $where);
	        $this->load->view("user/shipping_address_view",$data);
   		}else{
   			redirect(base_url().'account/login');
   		}
    }

    function shipaddress(){
    	$data=array();
    	$customerId=get_userId();
    	$addressId=$this->input->post("id");
		$data['addressQ'] = array('status'=>'fail', 'data_list'=>array());
		if(!empty($addressId)){
			$data['addressQ']=$this->curl->execute("shipping_address/$addressId", 'GET', array('customerId'=>$customerId));	
		}
        $str=$this->load->view("user/addedit_shipping_address",$data,true);        
        $value=array(
            'str'=>$str
        );
         echo json_encode($value);
    }

    function saveshipaddr(){
		$input = $this->input->post();
		// print_R($this->input->post());exit();
		$id = $this->input->post('addressId');
		$customerId = $this->input->post("customerId");
		$data['customerId']=$this->input->post("customerId");
		$data['name']=$this->input->post("name");
		$data['phone']=$this->input->post("phone");
		$data['apartment']=$this->input->post("apartment");
		$data['address']=$this->input->post("address");
		$data['city']=$this->input->post("city");
		$data['state']=$this->input->post("state");
		$data['country']=$this->input->post("country");
		$data['postalCode']=$this->input->post("postalCode");
		$data['updatedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
		if(isset($input["is_primary"]) && $input["is_primary"]=='on'){
			$data['pri_address'] = 1;
		}else{
			$data['pri_address'] = 2;
		}
		$status = "";
		$message = "";
		if(!empty($id)){
			$address_data = $this->curl->execute("shipping_address", "GET", array("shipping_address-customerid" => $customerId));
			$update_data = [];
			if($address_data['status'] == 'success' && !empty($address_data['data_list'])){
					$apidata = $this->curl->execute("shipping_address/$id", 'PUT', $data);
					if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
						$status = $apidata['status'];
						$message = $apidata['message'];
					}else{
						$status = $apidata['status'];
						$message = $apidata['message'];
					}
			}else{
					$status = $update_data['status'];
					$message = $update_data['message'];
			}
		}else{
			$apidata = $this->curl->execute('shipping_address', 'POST', $data);
			if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
				$status = $apidata['status'];
				$message = $apidata['message'];
			}else{
				$status = $apidata['status'];
				$message = $apidata['message'];
			}
		}
		$value = array('status'=>$status, 'message'=>$message);
		echo json_encode($value);
    }

    function del_shipaddr(){
    	if(is_uLogged() && get_userId()){
    		$result=0;
	    	$customerId=get_userId();
	    	$addressId=$this->input->post("id");
			$result = $this->curl->execute("shipping_address/$addressId", "DELETE");
	    	if($result['status'] == 'success' && !empty($result['data_list'])){
				$status = $result['status'];
				$message = $result['message'];
			}else{
				$status = $result['status'];
				$message = $result['message'];
			}
			$value = array("status" => $status, "message" => $message);
	         echo json_encode($value);
	         exit;
        }else{
   			redirect(base_url().'account/login');
   		}
    }

    function pri_shipAddr(){
    	if(is_uLogged() && get_userId()){
    		$result=0;
	    	$customerId=get_userId();
	    	$addressId=$this->input->post("id");
			$apidata = $this->curl->execute("shipping_address/primary_address/$customerId", 'PUT', ['addressId'=>$addressId]);
	        $value=array(
	            'result'=>$apidata['status']
	        );
	         echo json_encode($value);
	         exit;
        }else{
   			redirect(base_url().'account/login');
   		}
    }

    function mytickets() {
		if(is_uLogged() && get_userId()){
			$customerId=get_userId();
			$data=array();
			$data = $this->admin->commonFiles();
			$data['title'] = "Support Tickets";
	        $this->load->view("user/tickets/support_tickets_view",$data);
   		}else{
   			redirect(base_url().'account/login');
   		}
    }

    function get_tickets() {
		$data=array();
		$where = array('customerId'=>get_userId());
		$data['ticketQ']=$this->curl->execute('support','GET', $where);
        $str=$this->load->view("user/tickets/support_tickets_tbl_view",$data,true);
        $value=array('str'=>$str);
        echo json_encode($value);
    }
    
    function viewticket(){
    	$data=array();
    	$id=$this->input->post('id');
    	$code=$this->input->post('code');
    	$customerId=get_userId();
		$where = array('customerId'=>$customerId,'id'=>$id,'code'=>$code);
		$data['mainQ']=$this->adminmodel->get_table_data('support_enquiries',$where,'*',true);
		if($data['mainQ']->num_rows()>0){
			$data['tblQ']=$this->usermodel->get_ticket_comments($id);
			$msg=$this->load->view("user/tickets/ticket_details_view",$data,true);
			$status='success';
		}else{
			$msg='Unable to fecth your ticket details!';
			$status='fail';
		}        
        $value=array('msg'=>$msg,'status'=>$status);
        echo json_encode($value);
   	}

   	function update_ticket(){
        $data['support_id']=$this->input->post("id");
        $data['customerId']=get_userId();
        $data['subject']=  $this->input->post("subject");
        $data['message']=  $this->input->post("message");

        $data['from_type']=1;
        $data['is_read']=1;
        $data['reply_date']=get_curentTime();
		$res=0;
        $where = array('id' => $data['support_id'], 'customerId'=>$data['customerId']);
        $get_res=$this->adminmodel->get_table_data('support_enquiries',$where,'*',true);
        if($get_res->num_rows()>0){
        	$res=$this->adminmodel->insert_table_data('support_enquiries_details',$data);
        	if($res!=''){
        		$logData['action']='reply';
	            $logData['description']='customer '.$get_res->row()->email.' replied to his/her support enquiry '.$get_res->row()->code;
				$logData['dataId']=$get_res->row()->id;
	            $logData['module']='support enquiries';
	            $logData['table_name']='support_enquiries';
	            insert_uLog($logData);

	            $msg='Your reply has been sent...';
	        }else{
	            $msg="Error in updating ticket!";
	        }
        }else{
        	$msg='Unable to fetch your ticket data';
        }
        $value=array(
            'result'=>$res,
            'msg'=>$msg
        );
        echo json_encode($value);
    }

	function pdf(){
        $id=$this->uri->segment(3);
        if($id){  			
			$order_data = $this->curl->execute("orders/$id", "GET");
			if ($order_data['status'] == 'success' && !empty($order_data['data_list'])) {
				$data['order_data'] = $order_data['data_list'];
			}
			require FCPATH . '/vendor/autoload.php';
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$code = $order_data['data_list']['orderId'];
			$pdf->setTitle('Order_'.$code);
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			$pdf->setFont('times', 'BI', 20);
			$pdf->AddPage();
			$str=$this->load->view("user/pdf_view",$data,true);
			ob_end_clean();
			$pdf->writeHTML($str, false, false, true, false, '');
			$pdf->Output('Order_'.$code.'.pdf', 'I');

	    }else{
            redirect(base_url().'account/myorders');
        }
    }

	function wallets() {
		if(is_uLogged() && get_userId()){
			$customerId=get_userId();
			$data=array();
			$data = $this->admin->commonFiles();
			$data['title'] = "User Wallet";
			$data['customerQ']=$this->curl->execute("customers/$customerId", "GET");
	        $this->load->view("user/mywallet_view",$data);
   		}else{
   			redirect(base_url().'account/login');
   		}
    }

	function referals() {
		if(is_uLogged() && get_userId()){
			$customerId=get_userId();
			$data=array();
			$data = $this->admin->commonFiles();
			$data['title'] = "User Referals";
			$data['reference'] = $this->curl->execute("reference", "GET");
			$data['customerQ']=$this->curl->execute("customers/$customerId", "GET");
			$customer_reference = $this->curl->execute("customer_reference_amount", "GET", array("customer_reference_amount-customer_id" => $customerId, 'perpage'=>1000));
			// print_r($customer_reference);exit();
			if($customer_reference['status'] == 'success' && !empty($customer_reference['data_list'])){
				$data['refered_customers'] = $customer_reference['data_list'];
			}
	        $this->load->view("user/myreferal_view",$data);
   		}else{
   			redirect(base_url().'account/login');
   		}
    }

	function reference_view() {
		$data['title']="Reference"; 
		$userId=get_userId();
		$input = $this->input->post();
		if(!empty($input)){
			$customerData = $this->curl->execute("customers/$userId", "GET");
			if(!empty($customerData['data_list']) && $customerData['status'] == 'success'){
				$data['customer_data'] = $customerData['data_list'];
				$str = $this->load->view("user/refer_view", $data, true);
				$status='success';
			}else{
				$status = 'fail';
				$str = 'Invalid Request';
			}
		} else {
			$status = 'fail';
			$str = 'Invalid Request';
		}
		$value = array('message' => $str, 'status' => $status);
		echo json_encode($value);
	}

	function get_refer_input(){
		$data = array();
		$data['userId'] = get_userId();
		$data['type'] = $this->input->post('type');
		$data['message'] = $this->input->post('message');
		// print_R($data);exit();
		$str = $this->load->view('user/refer_input_view', $data, true);
		$value = array('status'=>'success', 'message'=>$str);
		echo json_encode($value);
	}

	function referNow(){
        $input = $this->input->post();
		$customers_id = get_userId();		
		$via_method = $input['type'];
		$value = $input['referValue'];
        $token = $this->admin->randomCodenum(6);	
		$data = array('customers_id'=>$customers_id, 'via_method'=>$via_method, 'referValue'=>$value, 'token'=>$token);
		$apidata = $this->curl->execute("customer_reference/referCustomer", 'POST', $data);
		if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
			$status = $apidata['status'];
			$message = $apidata['message'];
		} else {
			$status = $apidata['status'];
			$message = $apidata['message'];
		}
        $value = array('status' => $status, 'message' => $message);
        echo json_encode($value);
	}

	function check_input() 
    {
        $input = $this->input->post();
        $customers_id = $input['customers_id'];
        $via_method = $input['via_method'];
        $customers_data = $this->curl->execute('customers/' . $customers_id, 'GET');
        if ($customers_data['status'] == 'success' && !empty($customers_data['data_list'])) {
            if ($via_method == 'email') {
				if(!empty($input['email'])){
					$val = 1;
				}else{
					$val = 0;
				}
				$via_method='email';
				
            } else if($via_method=='message') {
				$val = 0;
				$via_method='message';
            }else{
				if(!empty($input['whatsapp_number'])){
					$val = 1;
				}else{
					$val = 0;
				}
				$via_method='whatsapp';
			}
            $value = array('status' => 'success', 'val' => $val, 'message' => 'success','method'=>$via_method,'data'=>$input);
        } else {
            $value = array('status' => 'fail', 'message' => 'Data Not found');
        }
        echo json_encode($value);
    }

	function share_link()
    {
        $input = $this->input->post();
		// print_r($input);exit();
        $token = $this->admin->randomCodenum(6);
        $customer_data=$this->curl->execute('customers/'.$input['data']['customers_id'],'GET');
        if($customer_data['status']=='success'){
            $customer_name=$customer_data['data_list']['firstName'];
        }else{
            $customer_name='';       
		}
        $data = array(
            'token' => $token,
            'customers_id' => $input['data']['customers_id'],
			'via_method' => $input['data']['via_method'],
			'email' => $input['data']['email'],
			'whatsapp_number' => $input['data']['whatsapp_number']
        );
        if (!empty($data)) {
            $apidata = $this->curl->execute("customer_reference", 'POST', $data);
			print_r($apidata);exit();
            
            if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
                $status = $apidata['status'];
				$message = $apidata['message'];
            } else {
                $status = $apidata['status'];
				$message = $apidata['message'];
            }

        } else {
            $status = 'fail';
            $message = 'No Data found';
        }
        $value = array('status' => $status, 'message' => $message);
        echo json_encode($value);
    }

	function repurchase(){
		if(is_uLogged() && get_userId()){
			$customerId=get_userId();
			$data=array();
			$data = $this->admin->commonFiles();
			$data['title'] = "User Referals";
			$data['reference'] = $this->curl->execute("repurchase", "GET");
			$data['customerQ']=$this->curl->execute("customers/$customerId", "GET");
			$customer_repurchase = $this->curl->execute("customer_repurchase_amount", "GET", array("customer_repurchase_amount-customer_id" => $customerId, 'perpage'=>1000));
			if($customer_repurchase['status'] == 'success' && !empty($customer_repurchase['data_list'])){
				$data['repurchase_customers'] = $customer_repurchase['data_list'];
			}
	        $this->load->view("user/myrepurchase_view",$data);
   		}else{
   			redirect(base_url().'account/login');
   		}
	}



	function referrals_level(){
		$customerId = get_userId();
        $apidata = $this->curl->execute('customers/referrals/'.$customerId, 'GET'); 
        if($apidata['status']=='success' && !empty($apidata['data_list'])){
            $data['customerData'] = $apidata['data_list']['customer_data'];
            $data['referralData'] = $apidata['data_list']['referral_data'];
        }
        $str = $this->load->view('user/referrals/referral_view', $data, true); 
		$status = 'success';
		echo json_encode(['status'=>$status, 'message'=>$str]);
    }



    function check_subscriptions(){
        $id = $this->input->post('customerId');
        $apidata = $this->curl->execute('subscription/customer_subscription', 'GET', ['user_subscription-user_id'=>$id]);
        $data = [];
        if($apidata['status']=='success' && !empty($apidata['data_list'])){
            $status = 'success';
            $data['subscriptions'] = $apidata['data_list'];
            $str = $this->load->view('user/referrals/subscriptions_view', $data, true);
        }else{
            $status = 'fail';
            $str = '';
        }
        echo json_encode(['status'=>$status, 'message'=>$str]);
    }

}