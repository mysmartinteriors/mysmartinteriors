<?php

defined('BASEPATH') or exit ('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
use Cashfree\Cashfree;
use Cashfree\Model\CreateOrderRequest;
use Cashfree\Model\CustomerDetails;
use Cashfree\Model\OrderMeta;
// use Cashfree\Model\

class Checkout extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		// $this->load->model("usermodel","",true);
		// $this->load->model("cartmodel","",true);
		// $this->load->model("adminmodel","",true);

	}

	public function initialize_cashfree() {
        // Set Cashfree credentials
        Cashfree::$XClientId = '69840976689cac79aa7b79fc08904896';//LIVE
        Cashfree::$XClientSecret = 'cfsk_ma_prod_2daeed7715afcb89811858688b4cd8ed_d280cc62';//LIVE
		Cashfree::$XEnvironment = Cashfree::$PRODUCTION;

		// Cashfree::$XClientId = 'TEST102073792924a2850f35797aecba97370201';
        // Cashfree::$XClientSecret = 'cfsk_ma_test_e02fd24a9c017da85aee9d2cc951e9a3_efcdccd7';
        // Cashfree::$XEnvironment = Cashfree::$SANDBOX; // or Cashfree::$PRODUCTION for live environment
    }

	function check_payment_status(){
		$orderId = $this->input->post('orderId');
		$cashfree = new Cashfree();
		$this->initialize_cashfree();
        $x_api_version = "2022-09-01";
		try {
			$response = $cashfree->PGOrderFetchPayments($x_api_version, $orderId);
			$processedResponse = $this->processPaymentResponse($response);
			echo json_encode($processedResponse);
		} catch (Exception $e) {
			echo json_encode(['status'=>'fail', 'message'=>'Payment Network Error. Please try again later']);
		}
	}


	private function processPaymentResponse($response) {
		if (is_array($response) && isset($response[0]) && is_array($response[0]) && isset($response[0][0])) {
			$paymentEntity = $response[0][0];
			
			// Extract payment method details
			$paymentMethod = $paymentEntity->getPaymentMethod();
			$paymentMethodDetails = $this->extractPaymentMethodDetails($paymentMethod);
			
			$paymentDetails = [
				'status'=>'success',
				'cf_payment_id' => $paymentEntity->getCfPaymentId(),
				'order_id' => $paymentEntity->getOrderId(),
				'entity' => $paymentEntity->getEntity(),
				'is_captured' => $paymentEntity->getIsCaptured(),
				'order_amount' => $paymentEntity->getOrderAmount(),
				'payment_group' => $paymentEntity->getPaymentGroup(),
				'payment_currency' => $paymentEntity->getPaymentCurrency(),
				'payment_amount' => $paymentEntity->getPaymentAmount(),
				'payment_time' => $paymentEntity->getPaymentTime(),
				'payment_completion_time' => $paymentEntity->getPaymentCompletionTime(),
				'payment_status' => $paymentEntity->getPaymentStatus(),
				'payment_message' => $paymentEntity->getPaymentMessage(),
				'payment_method' => $paymentMethodDetails,
			];
			return $paymentDetails;
		} else {
			return ['status'=>'fail', 'error' => 'Invalid response structure'];
		}
	}
	
	private function extractPaymentMethodDetails($paymentMethod) {
		$details = [];
		
		if ($paymentMethod->getCard()) {
			$details['card'] = $paymentMethod->getCard();
		}
		
		if ($paymentMethod->getNetbanking()) {
			$details['netbanking'] = $paymentMethod->getNetbanking();
		}
		
		if ($paymentMethod->getUpi()) {
			$details['upi'] = $paymentMethod->getUpi();
		}
		
		if ($paymentMethod->getApp()) {
			$app = $paymentMethod->getApp();
			$details['app'] = [
				'channel' => $app->getChannel(),
				'provider' => $app->getProvider(),
				'phone' => $app->getPhone(),
			];
		}
		
		if ($paymentMethod->getCardlessEmi()) {
			$details['cardless_emi'] = $paymentMethod->getCardlessEmi();
		}
		
		if ($paymentMethod->getPaylater()) {
			$details['paylater'] = $paymentMethod->getPaylater();
		}
		
		if ($paymentMethod->getEmi()) {
			$details['emi'] = $paymentMethod->getEmi();
		}
		
		if ($paymentMethod->getBanktransfer()) {
			$details['banktransfer'] = $paymentMethod->getBanktransfer();
		}
		
		return $details;
	}

    public function initiate() {
		$userId = get_userId();
		$order_data = $this->input->post();
		$userDataApi = $this->curl->execute('customers/'.$userId, 'GET');
		if($userDataApi['status']=='success' && !empty($userDataApi['data_list']) && !empty($order_data) && $order_data['actualAmountToPay']>0){
			$customer_data = $userDataApi['data_list'];
			$cashfree = new Cashfree();
			$this->initialize_cashfree();
			$x_api_version = "2022-09-01";
			$create_orders_request = new CreateOrderRequest();
			// if($userId==1 || $userId==2 || $userId==4 || $userId==5 || $userId==6 || $userId==10 || $userId==13){
			// 	$create_orders_request->setOrderAmount(1);
			// }else{
				$create_orders_request->setOrderAmount($order_data['actualAmountToPay']);
			// }
			$create_orders_request->setOrderCurrency("INR");
			$customer_details = new CustomerDetails();
			$customer_details->setCustomerId($customer_data['id'].'Nalaa00');
			$customer_details->setCustomerPhone($customer_data['phone']);
			$customer_details->setCustomerEmail($customer_data['email']);
			$orderId = generateRandom();
			$order_meta = new OrderMeta();
			$create_orders_request->setCustomerDetails($customer_details);
			$create_orders_request->setOrderMeta($order_meta);
			$result = $cashfree->PGCreateOrder($x_api_version, $create_orders_request);
			$orderEntity = $result[0]; // The order entity object
			$paymentSessionId = $orderEntity->getPaymentSessionId();
			$paymentOrderId = $orderEntity->getOrderId();
			if ($paymentSessionId && $paymentSessionId) {
				echo json_encode(['status'=>'success', 'paymentDetails'=>['status'=>'success', 'sessionId'=>$paymentSessionId, 'orderId'=>$paymentOrderId]]);
			} else {
				echo json_encode(['status'=>'fail', 'sessionId'=>'', 'orderId'=>'']);
			}
		}else{
			echo json_encode(['status'=>'fail', 'message'=>'Successful Payment Initiation']);
		}
    }

	public function response() {
		$orderId = $_GET['orderId'];
		$curl = curl_init();		
		curl_setopt_array($curl, [
		  CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/$orderId",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => [
			"accept: application/json",
			"x-api-version: 2023-08-01",
			"x-client-id: TEST102073792924a2850f35797aecba97370201",
			"x-client-secret: cfsk_ma_test_e02fd24a9c017da85aee9d2cc951e9a3_efcdccd7"
		  ],
		]);
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  print_R(json_decode($response, true));
		}
    }

    public function notify() {
		log_message('error', 'Printing data from notify URL from GET Req');
		log_message('error', print_r($_GET, true));
		log_message('error', 'Printing data from notify URL from POST Req');
		log_message('error', print_r($_POST, true));
        // Handle the payment notification from Cashfree
        // You can update the payment status in your database here
    }

	function index()
	{
		$data = $this->admin->commonEmptyFiles();
		$data['title'] = "Checkout & Shipping";
		$userId = get_userId();
		if (!empty ($userId)) {
			$data['addressQ'] = $this->curl->execute('shipping_address', 'GET', array('customerId' => $userId));
			$data['userData'] = $this->curl->execute('customers/'.$userId, 'GET');
			$orderTotal = $this->curl->execute("cart/cart_total/$userId", "GET");
			$data['orderTotal'] = array();
			if ($orderTotal['status'] == 'success' && !empty ($orderTotal['data_list'])) {
				$data['orderTotal'] = $orderTotal['data_list'];
			}
			$cartData = $this->curl->execute("cart", "GET", array("shopping_cart-customerId" => $userId));
			if ($cartData['status'] == 'success' && !empty ($cartData['data_list'])) {
				$data['cartQ'] = $cartData['data_list'];
			}
			$deliveryAddress = $this->curl->execute("delivery_address", "GET");
			if ($deliveryAddress['status'] == 'success' && !empty ($deliveryAddress['data_list'])) {
				$data['deliveryQ'] = $deliveryAddress['data_list'];
			}
		}
		if (!is_uLogged()) {
			$data['tblId'] = 'chktloginBody';
			$data['activeBody'] = 'login';
		} else {
			$data['tblId'] = 'shipaddressTbl';
			$data['activeBody'] = 'shipping';
		}
		if (is_orderAddr()) {
			$data['tblId'] = 'chktpaymentBody';
			$data['activeBody'] = 'payment';
		}
		// print_R($data);exit();
		$this->load->view("checkout/checkout_view", $data); 
	}

	function get_loginbody()
	{
		$data = array();
		$str = $this->load->view("checkout/checkout_login_tbl", $data, true);
		$value = array(
			'str' => $str
		);
		echo json_encode($value);
	}

	function get_shipaddrData()
	{
		$data = array();
		$userId = get_userId();
		$data['addressQ'] = $this->curl->execute('shipping_address', 'GET', array('customerId' => $userId));
		$orderTotal = $this->curl->execute("cart/cart_total/$userId", "GET");
		if ($orderTotal['status'] == 'success' && !empty ($orderTotal['data_list'])) {
			$data['orderTotal'] = $orderTotal['data_list'];
		}
		$str = $this->load->view("checkout/checkout_address_tbl", $data, true);
		$value = array(
			'str' => $str
		);
		echo json_encode($value);
	}

	function get_payoptions()
	{
		$data = array();
		$str = $this->load->view("checkout/checkout_payment_tbl", $data, true);
		$value = array(
			'str' => $str
		);
		echo json_encode($value);
	}

	function placeorder()
	{
		$input = $this->input->post();
		$userId = get_userId();
		if (!empty ($input) && !empty ($userId)) {
			$orderTotal = $this->curl->execute("cart/cart_total/$userId", "GET");
			if ($orderTotal['status'] == 'success' && !empty ($orderTotal['data_list'])) {
				$data['orderTotal'] = $orderTotal['data_list'];
			}
			$cartData = $this->curl->execute("cart", "GET", array("shopping_cart-customerId" => $userId));
			if ($cartData['status'] == 'success' && !empty ($cartData['data_list'])) {
				$data['cartQ'] = $cartData['data_list'];
			}
			$deliveryAddress = $this->curl->execute("delivery_address", "GET");
			if ($deliveryAddress['status'] == 'success' && !empty ($deliveryAddress['data_list'])) {
				$data['deliveryQ'] = $deliveryAddress['data_list'];
			}
			$data['deliveryCharge'] = $input['delId'];
			$str = $this->load->view("checkout/total_order_view", $data, true);
			$value = array(
				'str' => $str
			);
			echo json_encode($value);
		}
	}

	function select_shipaddr()
	{
		$input = $this->input->post();
		$addressId = $this->input->post('addressId');
		if ($addressId != '') {
			$this->save_checkout($input);
			$orderdata = array(
				'abvorderaddrId' => $addressId
			);
			$this->session->set_userdata($orderdata);
			$result = 'success';
			$msg = 'Redirect to payment';
		} else {
			$result = 'fail';
			$msg = 'Failed to initiate shipping!';
		}
		$value = array(
			'result' => $result,
			'msg' => $msg
		);
		echo json_encode($value);
	}

	function save_checkout(){
		$input = $this->input->post();
		$data = array();
		$data['payment_method'] = $input['payment_method'];
		$data['deduct_wallet_amount'] = $input['deduct_wallet_amount'];
		$data['deduct_wallet_bonus'] = $input['deduct_wallet_bonus'];
		$userId = get_userId();
		$dataList = array();
		if ($userId) {
			$data['selectedAddress'] = $this->input->post('selectedAddress');
			$data['selectedAddressLatLong'] = $this->input->post('selectedAddressLatLong');
			$data['userId'] = $userId;
			$data['deliveryDate'] = $this->input->post('deliveryDate');
			if($data['payment_method']=='pay_online'){
				$data['paymentDetails'] = !empty($input['paymentDetails'])?$input['paymentDetails']:'';
			}
			$orderdata = $this->curl->execute("orders", "POST", $data);
			if ($orderdata['status'] == 'success' && !empty ($orderdata['data_list'])) {
				$dataList = $orderdata['data_list'];
				$status = $orderdata['status'];
				$message = $orderdata['message'];
			} else {
				$status = $orderdata['status'];
				$message = $orderdata['message'];
			}
			$value = array(
				'status' => $status,
				'message' => $message,
				'paymentMethod' => $input['payment_method'],
				'dataList' => $dataList,
			);
			echo json_encode($value);
		} else {
			redirect(base_url() . 'checkout');
			exit;
		}
	}

	function payamount(){ 
		$orderId = $this->input->get('oId');
		$userId = get_userId(); 
		$mobile = get_uMobile();
		$data = array();
		if (!empty ($userId) && !empty ($mobile)) {
			$orderApi = $this->curl->execute("orders/$orderId", "GET");
			if ($orderApi['status'] == 'success' && !empty ($orderApi['data_list'])) {
				$orderData = $orderApi['data_list'];
				if($orderData['actualAmountToPay']>0){
					$userApi = $this->curl->execute('customers/' . $userId, 'GET');
					if ($userApi['status'] == 'success' && !empty ($userApi['data_list'])) {
						$data['userData'] = $userApi['data_list'];
						$data['orderData'] = $orderData;
						$this->load->view("checkout/payment_view", $data);
					} else {
						redirect(base_url());
					}
				}else{
					redirect(base_url().'account/myorders');
				}
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}


	function payorderamount(){
		$orderId = $this->input->get('oId');
		$userId = get_userId(); 
		$mobile = get_uMobile();
		$data = array();
		if (!empty ($userId) && !empty ($mobile)) {
			$orderApi = $this->curl->execute("orders", "GET", array('orderId'=>$orderId));
			if ($orderApi['status'] == 'success' && !empty ($orderApi['data_list'][0])) {
				$orderData = $orderApi['data_list'][0];
				if($orderData['actualAmountToPay']>0){
					$userApi = $this->curl->execute('customers/' . $userId, 'GET');
					if ($userApi['status'] == 'success' && !empty ($userApi['data_list'])) {
						$data['userData'] = $userApi['data_list'];
						$data['orderData'] = $orderData;
						$this->load->view("checkout/payment_view", $data);
					} else {
						redirect(base_url());
					}
				}else{
					redirect(base_url().'account/myorders');
				}
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}

	function pay_status()
	{
		$page_data = $this->admin->commonEmptyFiles();
		$page_data['title'] = 'Payment status';
		$session_sid = $this->session->userdata('nalaaOrganicOrderPayId');
		$session_fid = $this->session->userdata('nalaaOrganicOrderPayIdOne');
		$paid_amount = 0;
		$payment_orderid = '';
		$payment_id = '';
		$payment_gateway_json = '';
		$page_data["status"] = "failed";
		$page_data["message"] = "unknown error occured";
		$gateway_message = '';
		$order_status = '';
		$customerId = $orderId = $amount = $student_type = '';
		$success = false;
		$error = "Access denied";
		if(isset ($_POST['encResp'])) {
			$encResp = $_POST['encResp'];
			$working_key='04716BADDDEE5F1738E6476B905EFF9C';//Shared by CCAVENUES
			$explode_data = explode('&', decrypt($encResp, $working_key));
			$dataSize = sizeof($explode_data);

			for ($i = 0; $i < $dataSize; $i++) {
				$information = explode('=', $explode_data[$i]);
				if ($i == 3) {
					$order_status = $information[1];
				}
				if ($i == 27) {
					$customerId = $information[1];
				}
				if ($i == 28) {
					$orderId = $information[1];
				}
				if ($i == 29) {
					$amount = $information[1];
				}
				if ($i == 30) {
					$student_type = $information[1];
				}
				if ($i == 1) {
					$payment_id = $information[1];
				}	
				if ($i == 0) {
					$payment_orderid = $information[1];
				}
			}
			$payment_gateway_json = json_encode($explode_data);
			if(!empty($customerId) && $customerId){
				$customerApi = $this->curl->execute('customers/'.$customerId, 'GET');
				if($customerApi['status']=='success' && !empty($customerApi['data_list'])){
					$row_data = $customerApi['data_list'];
					$sessData = array(
						'gt_uId'=>$row_data['id'],
						'gt_uName' => $row_data['firstName'],
						'gt_uFullName' => $row_data['firstName'].' '.$row_data['lastName'],
						'gt_uEmail' => $row_data['email'],
						'gt_uMobile' => $row_data['phone'],
						'gt_isULogged'=>1
					);
					$this->session->set_userdata($sessData);
				}
			}
			$page_data['order_status'] = $order_status;
			if ($order_status === "Success") {
				if (!empty ($customerId) && !empty ($orderId)) {
					$page_data['message'] = "Your transaction is successful. We have received the payment of Rs." . $amount . ". It may take upto 30 minutes(maximum) to get updated in the portal.";
					$page_data['status'] = 'success';
					// $get_student=$this->db->get_where('students_ug',array('id'=>$studentId))->row_array();
					$get_order = $this->curl->execute('orders/' . $orderId, 'GET');
					if ($get_order['status'] == 'success' && !empty ($get_order)) {
						$data = array(
							'payment_id' => $payment_id,
							'pay_order_id' => $payment_orderid,
							'pay_signature' => '',
							'pay_status' => 1,
							'pay_amount' => $amount,
							'payment_gateway_json' => $payment_gateway_json,
							'pay_date' => cur_datetime()
						);
						$updateDb = $this->curl->execute('orders/' . $orderId, 'PUT', $data);
						if ($updateDb['status'] == 'success' && !empty ($updateDb['data_list'])) {
						}
						$this->session->unset_userdata('nalaaOrganicOrderPayId');
						$this->session->unset_userdata('nalaaOrganicOrderPayIdOne');
					}
				}else{
					$page_data['message'] = "Your transaction is unsuccessful😒. Please try again later";
					$page_data['status'] = 'fail';
				}
			} else if ($order_status === "Aborted") {
				$page_data['message'] = "Your transaction is aborted. Please retry.";
				$page_data['status'] = 'fail';
			} else if ($order_status === "Failure") {
				$page_data['message'] = "Your transaction is failed. However, the transaction has been declined.";
				$page_data['status'] = 'fail';
			} else {
				$page_data['message'] = "Security Error. Illegal access detected";
				$page_data['status'] = 'fail';
			}
		} else {
			$page_data['message'] = "Sorry, we have not recieved any response from the payment gateway. Please check the status after 30 minutes.";
			$page_data['status'] = 'fail';
		}
		$this->load->view("checkout/payment_status_view", $page_data);
	}



	function payorderfee(){
		$orderId = $this->input->get('oId');
		$data = array();
		$orderApi = $this->curl->execute("orders/$orderId", "GET");
		if ($orderApi['status'] == 'success' && !empty ($orderApi['data_list'])) {
			$orderData = $orderApi['data_list'];
			if($orderData['actualAmountToPay']>0){
				$userApi = $this->curl->execute('customers/' . $orderData['customerId'], 'GET');
				if ($userApi['status'] == 'success' && !empty ($userApi['data_list'])) {
					$data['userData'] = $userApi['data_list'];
					$data['orderData'] = $orderData;
					$this->load->view("checkout/order_payment_view", $data);
				} else {
					redirect(base_url());
				}
			}else{
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}


	function mobile_order_pay_status()
	{
		$page_data = $this->admin->commonEmptyFiles();
		$page_data['title'] = 'Payment status';
		$session_sid = $this->session->userdata('nalaaOrganicOrderPayId');
		$session_fid = $this->session->userdata('nalaaOrganicOrderPayIdOne');
		$paid_amount = 0;
		$payment_orderid = '';
		$payment_id = '';
		$payment_gateway_json = '';
		$page_data["status"] = "failed";
		$page_data["message"] = "unknown error occured";
		$gateway_message = '';
		$order_status = '';
		$customerId = $orderId = $amount = $student_type = '';
		$success = false;
		$error = "Access denied";
		if(isset ($_POST['encResp'])) {
			$encResp = $_POST['encResp'];
			// $working_key = '0D2FFC91AB0D3DE34307F156A15C2A08';
			$working_key='04716BADDDEE5F1738E6476B905EFF9C';//Shared by CCAVENUES
			$explode_data = explode('&', decrypt($encResp, $working_key));
			$dataSize = sizeof($explode_data);

			for ($i = 0; $i < $dataSize; $i++) {
				$information = explode('=', $explode_data[$i]);
				if ($i == 3) {
					$order_status = $information[1];
				}
				if ($i == 27) {
					$customerId = $information[1];
				}
				if ($i == 28) {
					$orderId = $information[1];
				}
				if ($i == 29) {
					$amount = $information[1];
				}
				if ($i == 30) {
					$student_type = $information[1];
				}
				if ($i == 1) {
					$payment_id = $information[1];
				}	
				if ($i == 0) {
					$payment_orderid = $information[1];
				}
			}
			$payment_gateway_json = json_encode($explode_data);
			$page_data['order_status'] = $order_status;
			if ($order_status === "Success") {
				if (!empty ($customerId) && !empty ($orderId)) {
					// $get_student=$this->db->get_where('students_ug',array('id'=>$studentId))->row_array();
					$get_order = $this->curl->execute('orders/' . $orderId, 'GET');
					if ($get_order['status'] == 'success' && !empty ($get_order)) {
						$data = array(
							'payment_id' => $payment_id,
							'pay_order_id' => $payment_orderid,
							'pay_signature' => '',
							'pay_status' => 1,
							'pay_amount' => $amount,
							'payment_gateway_json' => $payment_gateway_json,
							'pay_date' => cur_datetime()
						);
						// print_r($data);exit();
						// print_r("HI");exit();
						$updateDb = $this->curl->execute('orders/' . $orderId, 'PUT', $data);
						// print_r($updateDb);exit();
						$page_data['message'] = "Your transaction is successful. We have received the payment of Rs." . $amount . ". It may take upto 30 minutes(maximum) to get updated in the portal.";
						$page_data['status'] = 'success';
						if ($updateDb['status'] == 'success' && !empty ($updateDb['data_list'])) {
						}
						$this->session->unset_userdata('nalaaOrganicOrderPayId');
						$this->session->unset_userdata('nalaaOrganicOrderPayIdOne');
					}
				}else{
					$page_data['status'] = 'fail';
					$page_data['message'] = "Your transaction is unsuccessful😒. Please try again later";
				}
			} else if ($order_status === "Aborted") {
				$page_data['status'] = 'fail';
				$page_data['message'] = "Your transaction is aborted. Please retry.";
			} else if ($order_status === "Failure") {
				$page_data['status'] = 'fail';
				$page_data['message'] = "Your transaction is failed. However, the transaction has been declined.";
			} else {
				$page_data['status'] = 'fail';
				$page_data['message'] = "Security Error. Illegal access detected";
			}
		} else {
			$page_data['status'] = 'fail';
			$page_data['message'] = "Sorry, we have not recieved any response from the payment gateway. Please check the status after 30 minutes.";
		}
		$this->load->view("checkout/order_payment_status_view", $page_data);
	} 

	function order_pay_status()
	{
		$page_data = $this->admin->commonEmptyFiles();
		$page_data['title'] = 'Payment status';
		$session_sid = $this->session->userdata('nalaaOrganicOrderPayId');
		$session_fid = $this->session->userdata('nalaaOrganicOrderPayIdOne');
		$paid_amount = 0;
		$payment_orderid = '';
		$payment_id = '';
		$payment_gateway_json = '';
		$page_data["status"] = "failed";
		$page_data["message"] = "unknown error occured";
		$gateway_message = '';
		$order_status = '';
		$customerId = $orderId = $amount = $student_type = '';
		$success = false;
		$error = "Access denied";
		if(isset ($_POST['encResp'])) {
			$encResp = $_POST['encResp'];
			$working_key = '0D2FFC91AB0D3DE34307F156A15C2A08';
			$explode_data = explode('&', decrypt($encResp, $working_key));
			$dataSize = sizeof($explode_data);

			for ($i = 0; $i < $dataSize; $i++) {
				$information = explode('=', $explode_data[$i]);
				if ($i == 3) {
					$order_status = $information[1];
				}
				if ($i == 27) {
					$customerId = $information[1];
				}
				if ($i == 28) {
					$orderId = $information[1];
				}
				if ($i == 29) {
					$amount = $information[1];
				}
				if ($i == 30) {
					$student_type = $information[1];
				}
				if ($i == 1) {
					$payment_id = $information[1];
				}	
				if ($i == 0) {
					$payment_orderid = $information[1];
				}
			}
			$payment_gateway_json = json_encode($explode_data);
			$page_data['order_status'] = $order_status;
			if ($order_status === "Success") {
				if (!empty ($customerId) && !empty ($orderId)) {
					// $get_student=$this->db->get_where('students_ug',array('id'=>$studentId))->row_array();
					$get_order = $this->curl->execute('orders/' . $orderId, 'GET');
					if ($get_order['status'] == 'success' && !empty ($get_order)) {
						$data = array(
							'payment_id' => $payment_id,
							'pay_order_id' => $payment_orderid,
							'pay_signature' => '',
							'pay_status' => 1,
							'pay_amount' => $amount,
							'payment_gateway_json' => $payment_gateway_json,
							'pay_date' => cur_datetime()
						);
						$updateDb = $this->curl->execute('orders/' . $orderId, 'PUT', $data);
						$page_data['message'] = "Your transaction is successful. We have received the payment of Rs." . $amount . ". It may take upto 30 minutes(maximum) to get updated in the portal.";
						$page_data['status'] = 'success';
						if ($updateDb['status'] == 'success' && !empty ($updateDb['data_list'])) {
						}
						$this->session->unset_userdata('nalaaOrganicOrderPayId');
						$this->session->unset_userdata('nalaaOrganicOrderPayIdOne');
					}
				}else{
					$page_data['status'] = 'fail';
					$page_data['message'] = "Your transaction is unsuccessful😒. Please try again later";
				}
			} else if ($order_status === "Aborted") {
				$page_data['message'] = "Your transaction is aborted. Please retry.";
			} else if ($order_status === "Failure") {
				$page_data['message'] = "Your transaction is failed. However, the transaction has been declined.";
			} else {
				$page_data['message'] = "Security Error. Illegal access detected";
			}
		} else {
			$page_data['status'] = 'fail';
			$page_data['message'] = "Sorry, we have not recieved any response from the payment gateway. Please check the status after 30 minutes.";
		}
		$this->load->view("checkout/payment_status_view", $page_data);
	}


}