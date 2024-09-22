<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
use Cashfree\Cashfree;
use Cashfree\Model\CreateOrderRequest;
use Cashfree\Model\CustomerDetails;
use Cashfree\Model\OrderMeta;

class Subscription extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data = array();
		$data = $this->admin->commonFiles();
		$data['title'] = "Nalaa Organic";
		$data['subscriptionQ'] = $this->curl->execute('subscription', 'GET', array('status' => 44));
		$data['customer_subscription_data'] = array();
		if (!empty(get_userId())) {
			$customerSubscriptionApi = $this->curl->execute('subscription/customer_subscription', 'GET', array('user_id' => get_userId(), 'user_subscription-status' => 1));
			if ($customerSubscriptionApi['status'] == 'success' && !empty($customerSubscriptionApi['data_list'][0])) {
				$data['customer_subscription_data'] = $customerSubscriptionApi['data_list'][0];
			}
		}
		$this->load->view("subscription/subscription_view", $data);
	}

	public function initialize_cashfree()
	{
		// Set Cashfree credentials
		Cashfree::$XClientId = '69840976689cac79aa7b79fc08904896';//LIVE
		Cashfree::$XClientSecret = 'cfsk_ma_prod_2daeed7715afcb89811858688b4cd8ed_d280cc62';//LIVE
		Cashfree::$XEnvironment = Cashfree::$PRODUCTION;

		// Cashfree::$XClientId = 'TEST102073792924a2850f35797aecba97370201';
		// Cashfree::$XClientSecret = 'cfsk_ma_test_e02fd24a9c017da85aee9d2cc951e9a3_efcdccd7';
		// Cashfree::$XEnvironment = Cashfree::$SANDBOX; // or Cashfree::$PRODUCTION for live environment
	}

	function check_payment_status()
	{
		$orderId = $this->input->post('orderId');
		$cashfree = new Cashfree();
		$this->initialize_cashfree();
		$x_api_version = "2022-09-01";
		try {
			$response = $cashfree->PGOrderFetchPayments($x_api_version, $orderId);
			$processedResponse = $this->processPaymentResponse($response);
			echo json_encode($processedResponse);
		} catch (Exception $e) {
			echo json_encode(['status' => 'fail', 'message' => 'Payment Network Error. Please try again later']);
		}
	}


	private function processPaymentResponse($response)
	{
		if (is_array($response) && isset($response[0]) && is_array($response[0]) && isset($response[0][0])) {
			$paymentEntity = $response[0][0];

			// Extract payment method details
			$paymentMethod = $paymentEntity->getPaymentMethod();
			$paymentMethodDetails = $this->extractPaymentMethodDetails($paymentMethod);

			$paymentDetails = [
				'status' => 'success',
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
			return ['status' => 'fail', 'error' => 'Invalid response structure'];
		}
	}

	private function extractPaymentMethodDetails($paymentMethod)
	{
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

	function initiate(){
		$userId = get_userId();
		$subscriptionId = $this->input->post('subscriptionId');
		if(!empty($subscriptionId)){
			$apidata = $this->curl->execute('subscription/'.$subscriptionId, 'GET');
			$userDataApi = $this->curl->execute('customers/' . $userId, 'GET');
			if($apidata['status']=='success' && $apidata['data_list'] && $userDataApi['status'] == 'success' && !empty($userDataApi['data_list']) && $apidata['data_list']['basic_amount']) {
				$customer_data = $userDataApi['data_list'];
				$cashfree = new Cashfree();
				$this->initialize_cashfree();
				$x_api_version = "2022-09-01";
				$create_orders_request = new CreateOrderRequest();
				if ($userId==6) {
					$create_orders_request->setOrderAmount(1);
				}else{
					$create_orders_request->setOrderAmount($apidata['data_list']['basic_amount']);
				}
				$create_orders_request->setOrderCurrency("INR");
				$customer_details = new CustomerDetails();
				$customer_details->setCustomerId($customer_data['id'] . 'NalaaSubscription');
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
					echo json_encode(['status' => 'success', 'paymentDetails' => ['status' => 'success', 'sessionId' => $paymentSessionId, 'orderId' => $paymentOrderId], 'subscriptionId'=>$subscriptionId]);
				} else {
					echo json_encode(['status' => 'fail', 'sessionId' => '', 'orderId' => '']);
				}
			} else {
				echo json_encode(['status' => 'fail', 'message' => 'Payment Initiation Failed']);
			}
		}else{
			echo json_encode(['status'=>'fail', 'message'=>'Unknown Subscription Selected']);
		}
	}

	function payment()
	{
		$data = $this->admin->commonFiles();
		$data['title'] = "Subscription";
		$userId = get_userId();
		$input = $this->input->post();
		if (!empty($input)) {
			$id = $input['id'];
			$planData = $this->curl->execute("subscription", "GET", array("subscription-id" => $id));
			if ($planData['status'] == 'success' && !empty($planData['data_list'])) {
				$plan_data = $planData['data_list'][0];
				$data = array(
					"customerId" => $userId,
					"planId" => $id,
					"balance" => $plan_data['wallet_points'],
					"status" => 56
				);
				$apiData = $this->curl->execute("wallets", "POST", $data);
				if ($apiData['status'] == 'success' && !empty($apiData['data_list'])) {
					$updateData = $this->curl->execute("customers/$userId", "PUT", array("planId" => $id, 'subscriptionAmount' => $plan_data['basic_amount'], 'subscriptionPoints' => $plan_data['wallet_points']));

					// New Updates for referals
					$customerData = $this->curl->execute("customers/$userId", "GET");
					if ($customerData['status'] == 'success' && !empty($customerData['data_list'])) {
						$customer_data = $customerData['data_list'];
						$refered_by = $customer_data['refered_by'];
						$reference_percentage = $this->curl->execute("reference", "GET", array("reference-code" => "r1"));
						if ($reference_percentage['status'] == 'success' && !empty($reference_percentage['data_list'])) {
							$reference_data = $reference_percentage['data_list'][0];
							$r1 = $reference_data['percentage'];
							$wallet_amount = $plan_data['basic_amount'] / 100 * $r1;
							$updateData = $this->curl->execute("customers/$refered_by", "PUT", array('subscriptionPoints' => $wallet_amount));
						}
					}
					$status = $apiData['status'];
					$message = $apiData['message'];
				} else {
					$status = $apiData['status'];
					$message = $apiData['message'];
				}
			} else {
				$status = $planData['status'];
				$message = $planData['message'];
			}
		} else {
			$status = 'fail';
			$message = 'Input Data Not Found';
		}
		$value = array(
			'status' => $status,
			'message' => $message
		);
		echo json_encode($value);
	}

	// function subscribe()
	// {
	// 	$input = $this->input->get();
	// 	$userId = get_userId();
	// 	$mobile = get_uMobile();
	// 	$userDataApi = $this->curl->execute('customers/' . $userId, 'GET');
	// 	if ($userDataApi['status'] == 'success' && !empty($userDataApi['data_list'])) {
	// 		$data['userData'] = $userDataApi['data_list'];
	// 		$subscriptionData = $this->curl->execute('subscription/' . $input['subscriptionId'], 'GET');
	// 		if ($subscriptionData['status'] == 'success' && !empty($subscriptionData['data_list'])) {
	// 			$data['subscriptionData'] = $subscriptionData['data_list'];
	// 			$payAmount = $subscriptionData['data_list']['basic_amount'];
	// 			if ($payAmount > 0) {
	// 				$subsData = [
	// 					'user_id' => $userId,
	// 					'subscription_id' => $input['subscriptionId']
	// 				];
	// 				$subscriptionApi = $this->curl->execute('subscription/customer_subscription/', 'POST', $subsData);
	// 				if ($subscriptionApi['status'] == 'success' && !empty($subscriptionApi['data_list'])) {
	// 					$data['subsData'] = $subscriptionApi['data_list'];
	// 					$userdata = array(
	// 						'nalaaOrganicOrderPayId' => $userId,
	// 						'nalaaOrganicOrderPayIdOne' => $mobile
	// 					);
	// 					$this->session->set_userdata($userdata);
	// 					$this->load->view("subscription/payment_view", $data);
	// 				} else {
	// 					$returnData = $this->admin->commonFiles();
	// 					$returnData['subscriptionQ'] = $this->curl->execute('subscription', 'GET', array('status' => 44));
	// 					$this->load->view("subscription/subscription_view", $returnData);
	// 				}
	// 			} else {
	// 				$returnData = $this->admin->commonFiles();
	// 				$returnData['subscriptionQ'] = $this->curl->execute('subscription', 'GET', array('status' => 44));
	// 				$this->load->view("subscription/subscription_view", $returnData);
	// 			}
	// 		} else {
	// 			$returnData = $this->admin->commonFiles();
	// 			$returnData['subscriptionQ'] = $this->curl->execute('subscription', 'GET', array('status' => 44));
	// 			$this->load->view("subscription/subscription_view", $returnData);
	// 		}
	// 	} else {
	// 		$returnData = $this->admin->commonFiles();
	// 		$returnData['subscriptionQ'] = $this->curl->execute('subscription', 'GET', array('status' => 44));
	// 		$this->load->view("subscription/subscription_view", $returnData);
	// 	}
	// }


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
		$customerId = $subscriptionId = $amount = $student_type = '';
		$success = false;
		$error = "Access denied";
		if (isset($_POST['encResp'])) {
			$encResp = $_POST['encResp'];
			$working_key = '04716BADDDEE5F1738E6476B905EFF9C';
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
					$subscriptionId = $information[1];
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
			if (!empty($customerId) && $customerId) {
				$customerApi = $this->curl->execute('customers/' . $customerId, 'GET');
				if ($customerApi['status'] == 'success' && !empty($customerApi['data_list'])) {
					$row_data = $customerApi['data_list'];
					$sessData = array(
						'gt_uId' => $row_data['id'],
						'gt_uName' => $row_data['firstName'],
						'gt_uFullName' => $row_data['firstName'] . ' ' . $row_data['lastName'],
						'gt_uEmail' => $row_data['email'],
						'gt_uMobile' => $row_data['phone'],
						'gt_isULogged' => 1
					);
					$this->session->set_userdata($sessData);
				}
			}
			$page_data['order_status'] = $order_status;
			if ($order_status === "Success") {
				if (!empty($customerId) && !empty($subscriptionId)) {
					$data = array(
						'payment_id' => $payment_id,
						'pay_order_id' => $payment_orderid,
						'pay_signature' => '',
						'pay_status' => 1,
						'pay_amount' => $amount,
						'payment_gateway_json' => $payment_gateway_json,
						'pay_date' => cur_datetime(),
						'status' => 1
					);
					$updateDb = $this->curl->execute('subscription/customer_subscription/' . $subscriptionId, 'PUT', $data);
					// New Updates for referals
					// Update the Reference Comission till level 7
					$customerData = $this->curl->execute("customers/$customerId", "GET");
					if ($customerData['status'] == 'success' && !empty($customerData['data_list'])) {
						$customer_data = $customerData['data_list'];
						$refered_by = $customer_data['refered_by'];
						$customerdata = $this->curl->execute("customers/$refered_by", "GET");
						if ($customerdata['status'] == 'success' && !empty($customerdata['data_list'])) {
							$customer_data = $customerdata['data_list'];
							$reference_percentage = $this->curl->execute("reference", "GET", array("reference-code" => "r1"));
							if ($reference_percentage['status'] == 'success' && !empty($reference_percentage['data_list'])) {
								$reference_data = $reference_percentage['data_list'][0];
								$r1 = $reference_data['percentage'];
								$walletAmount = $amount / 100 * $r1;
								$wallet_amount = $customer_data['subscriptionPoints'] + $walletAmount;
								$data = array(
									"customer_id" => $refered_by,
									"r1" => $customerId,
									"r1_earnings" => $walletAmount,
									"total_earnings" => $walletAmount
								);
								$apidata = $this->curl->execute("my_referals", "POST", $data);
							}
						}
					}
					$page_data['message'] = "Your transaction is successful. We have received the payment of Rs." . $amount . ". It may take upto 30 minutes(maximum) to get updated in the portal.";
					$this->session->unset_userdata('nalaaOrganicOrderPayId');
					$this->session->unset_userdata('nalaaOrganicOrderPayIdOne');
				} else {
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
			$page_data['message'] = "Sorry, we have not recieved any response from the payment gateway. Please check the status after 30 minutes.";
		}
		$this->load->view("checkout/payment_status_view", $page_data);
	}


	// for mobile Application View of the subscription

	// function mobile_subscribe()
	// {
	// 	$input = $this->input->get();
	// 	$userId = $input['user'];
	// 	$subscriptionId = $input['subscription'];
	// 	$userDataApi = $this->curl->execute('customers/' . $userId, 'GET');
	// 	if ($userDataApi['status'] == 'success' && !empty($userDataApi['data_list'])) {
	// 		$data['userData'] = $userDataApi['data_list'];
	// 		$subscriptionData = $this->curl->execute('subscription/' . $subscriptionId, 'GET');
	// 		if ($subscriptionData['status'] == 'success' && !empty($subscriptionData['data_list'])) {
	// 			$data['subscriptionData'] = $subscriptionData['data_list'];
	// 			$payAmount = $subscriptionData['data_list']['basic_amount'];
	// 			if ($payAmount > 0) {
	// 				$subsData = [
	// 					'user_id' => $userId,
	// 					'subscription_id' => $subscriptionId
	// 				];
	// 				$subscriptionApi = $this->curl->execute('subscription/customer_subscription/', 'POST', $subsData);
	// 				if ($subscriptionApi['status'] == 'success' && !empty($subscriptionApi['data_list'])) {
	// 					$data['subsData'] = $subscriptionApi['data_list'];
	// 					// $userdata = array(
	// 					// 	'nalaaOrganicOrderPayId' => $userId,
	// 					// 	'nalaaOrganicOrderPayIdOne' => $subscriptionId
	// 					// );
	// 					// setting up in db
	// 					// $this->session->set_userdata($userdata);
	// 					$this->load->view("subscription/mobile_subscription_payment_view", $data);
	// 				} else {
	// 					$this->load->view("subscription/mobile_subscription_error_view");
	// 				}
	// 			} else {
	// 				$this->load->view("subscription/mobile_subscription_error_view");
	// 			}
	// 		} else {
	// 			$this->load->view("subscription/mobile_subscription_error_view");
	// 		}
	// 	} else {
	// 		$this->load->view("subscription/mobile_subscription_error_view");
	// 	}
	// }


	// function mobile_pay_status()
	// {
	// 	$page_data = $this->admin->commonEmptyFiles();
	// 	$page_data['title'] = 'Payment status';
	// 	$session_sid = $this->session->userdata('nalaaOrganicOrderPayId');
	// 	$session_fid = $this->session->userdata('nalaaOrganicOrderPayIdOne');
	// 	$paid_amount = 0;
	// 	$payment_orderid = '';
	// 	$payment_id = '';
	// 	$payment_gateway_json = '';
	// 	$page_data["status"] = "failed";
	// 	$page_data["message"] = "unknown error occured";
	// 	$gateway_message = '';
	// 	$order_status = '';
	// 	$customerId = $subscriptionId = $amount = $student_type = '';
	// 	$success = false;
	// 	$error = "Access denied";
	// 	if (isset($_POST['encResp'])) {
	// 		$encResp = $_POST['encResp'];
	// 		// $working_key = '0D2FFC91AB0D3DE34307F156A15C2A08';
	// 		$working_key = '04716BADDDEE5F1738E6476B905EFF9C';
	// 		$explode_data = explode('&', decrypt($encResp, $working_key));
	// 		$dataSize = sizeof($explode_data);
	// 		for ($i = 0; $i < $dataSize; $i++) {
	// 			$information = explode('=', $explode_data[$i]);
	// 			if ($i == 3) {
	// 				$order_status = $information[1];
	// 			}
	// 			if ($i == 27) {
	// 				$customerId = $information[1];
	// 			}
	// 			if ($i == 28) {
	// 				$subscriptionId = $information[1];
	// 			}
	// 			if ($i == 29) {
	// 				$amount = $information[1];
	// 			}
	// 			if ($i == 30) {
	// 				$student_type = $information[1];
	// 			}
	// 			if ($i == 1) {
	// 				$payment_id = $information[1];
	// 			}
	// 			if ($i == 0) {
	// 				$payment_orderid = $information[1];
	// 			}
	// 		}
	// 		$payment_gateway_json = json_encode($explode_data);
	// 		$page_data['order_status'] = $order_status;
	// 		if ($order_status === "Success") {
	// 			if (!empty($customerId) && !empty($subscriptionId)) {
	// 				$data = array(
	// 					'payment_id' => $payment_id,
	// 					'pay_order_id' => $payment_orderid,
	// 					'pay_signature' => '',
	// 					'pay_status' => 1,
	// 					'pay_amount' => $amount,
	// 					'payment_gateway_json' => $payment_gateway_json,
	// 					'pay_date' => cur_datetime(),
	// 					'status' => 1
	// 				);
	// 				$updateDb = $this->curl->execute('subscription/customer_subscription/' . $subscriptionId, 'PUT', $data);
	// 				// print_r($updateDb);exit();
	// 				$page_data['message'] = "Your transaction is successful. We have received the payment of Rs." . $amount . ". It may take upto 30 minutes(maximum) to get updated in the portal.";
	// 				$page_data['status'] = "success";
	// 				$this->session->unset_userdata('nalaaOrganicOrderPayId');
	// 				$this->session->unset_userdata('nalaaOrganicOrderPayIdOne');
	// 			} else {
	// 				$page_data['status'] = 'fail';
	// 				$page_data['message'] = "Your transaction is unsuccessful😒. Please try again later";
	// 			}
	// 		} else if ($order_status === "Aborted") {
	// 			$page_data['status'] = 'fail';
	// 			$page_data['message'] = "Your transaction is aborted. Please retry.";
	// 		} else if ($order_status === "Failure") {
	// 			$page_data['status'] = 'fail';
	// 			$page_data['message'] = "Your transaction is failed. However, the transaction has been declined.";
	// 		} else {
	// 			$page_data['status'] = 'fail';
	// 			$page_data['message'] = "Security Error. Illegal access detected";
	// 		}
	// 	} else {
	// 		$page_data['status'] = 'fail';
	// 		$page_data['message'] = "Sorry, we have not recieved any response from the payment gateway. Please check the status after 30 minutes.";
	// 	}
	// 	$this->load->view("subscription/mobile_subscription_payment_status_view", $page_data);
	// }


	function save_subscription(){
		$input = $this->input->post();
		$userId = get_userId();
		$subscriptionId = $input['subscriptionId'];
		if ($userId && $subscriptionId) {
			$data['paymentDetails'] = !empty($input['paymentDetails'])?$input['paymentDetails']:'';
			$data['customer_id'] = $userId;
			$updateDb = $this->curl->execute('subscription/customer_subscription/' . $subscriptionId, 'PUT', $data);
			if($updateDb['status']=='success'){
				echo json_encode(['status'=>'success', 'message'=>'Subscription Purchase Success']);
			}else{
				echo json_encode(['status'=>'fail', 'message'=>'Payment Failed, Internal Server Error']);
			}
		} else {
			echo json_encode(['status'=>'fail', 'message'=>'Subscription Purchase Failed, Required Parameters Not Found']);
		}
	}
}