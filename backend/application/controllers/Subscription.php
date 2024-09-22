<?php

require APPPATH . 'libraries/REST_Controller.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Subscription extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->table = 'subscription';
		$this->customer_subscription_table = 'user_subscription';
		$this->customer_subscription_model_name = 'customer_subscription_model';
		$this->load->model($this->customer_subscription_model_name, "", true);
		$this->model_name = 'subscription_model';
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
		$data = array();
		if (!empty($id)) {
			$data = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
		} else {
			$data = $this->Mydb->do_search($this->table, $this->model_name);
		}
		if (!empty($data)) {
			$value = withSuccess($message, $data);
		} else {
			$value = withSuccess($this->lang->line('no_result_found'));
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
			'basic_amount' => ['Subscription Amount', 'required'],
			'wallet_points' => ['Wallet Amount', 'required'],
			'status' => ['Status', 'required'],
			'name' => ['Name', 'required']
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
				'basic_amount' => $input['basic_amount'],
				'wallet_points' => $input['wallet_points'],
				'status' => $input['status'],
				'name' => $input['name'],
				'createdDate' => cur_date_time()
			);

			$id = $this->Mydb->insert_table_data('subscription', $data);
			if ($id) {
				$data = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
				$value = withSuccess('Subscription Plan Created Successfully', $data);
			} else {
				$value = withSuccess('Failed to Create Subscription Plan', $data);
			}
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
		if (!empty($input['status'])) {
			$rules['status'] = ['Status', 'required'];
			$data['status'] = $input['status'];
		}
		if (!empty($input['basic_amount'])) {
			$rules['basic_amount'] = ['Subscription Amount', 'required'];
			$data['basic_amount'] = $input['basic_amount'];
		}
		if (!empty($input['wallet_points'])) {
			$rules['wallet_points'] = ['Wallet Amount', 'required'];
			$data['wallet_points'] = $input['wallet_points'];
		}
		if (!empty($input['name'])) {
			$rules['name'] = ['Name', 'required'];
			$data['name'] = $input['name'];
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
		$data['updatedDate'] = cur_date_time();
		$is_update = $this->Mydb->update_table_data('subscription', array('id' => $id), $data);
		$result = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
		if ($is_update > 0) {
			$value = withSuccess('Subscription Plan Updated Successfully', $result);
		} else {
			$value = withErrors($this->lang->line('failed_to_update'), $result);
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


	/**

		   * Delete data from this method.

		   *

		   * @return Response

		  */

	public function index_delete($id = 0)
	{
		$result = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
		$res = $this->Mydb->delete_table_data('subscription', array('id' => $id));
		if ($res == 1) {
			$value = withSuccess('Subscription Plan Deleted Successfully', $result);
		} else if ($res == -1451) {
			$value = withErrors($this->lang->line('failed_to_delete'));
		} else {
			$value = withErrors($this->lang->line('failed_to_delete'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function role_services_get($id)
	{

		if (empty($id)) {
			$value = withErrors('User id is required');
			$this->response($value, REST_Controller::HTTP_OK);
		}
		$result_type = 'all';
		if (!empty($this->input->get('result_type'))) {
			$result_type = $this->input->get('result_type');
		}
		$u_data = $this->db->get_where($this->table, array('id' => $id))->row_array();
		if (!empty($u_data)) {
			$result = $this->usersmodel->get_role_services($u_data, $result_type);
			//print_r($result);exit();
			$value = withSuccess('Success', array('data_list' => $result));
			$this->response($value, REST_Controller::HTTP_OK);
		} else {
			$value = withErrors('User not found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


	function test_import_post()
	{
		$input = $this->input->post();
		$data = array(
			'name' => $input['name'],
			'email' => $input['email'],
			'phone' => $input['phone'],
			'password' => '12345',
		);
		$id = $this->Mydb->insert_table_data('user_data', $data);

		$value = withSuccess('success');
		$this->response($value, REST_Controller::HTTP_OK);
	}



	/**

		  * Insert data from this method.

		  *

		  * @return Response

		 */

	public function customer_subscription_post()
	{
		$input = $this->input->post();
		$rules = [
			'user_id' => ['User ID', 'required|numeric'],
			'subscription_id' => ['Subscription ID', 'required|numeric'],
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
				'user_id' => $input['user_id'],
				'subscription_id' => $input['subscription_id'],
			);

			$id = $this->Mydb->insert_table_data('user_subscription', $data);
			if ($id) {
				$data = $this->db->get_where('user_subscription', array('id' => $id))->row_array();
				$value = withSuccess('Subscription Plan For The Customers Created Successfully', array('data_list' => $data));
			} else {
				$value = withSuccess('Failed to Create Subscription Plan', $data);
			}
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}



	public function customer_subscription_put($subscriptionId)
	{
		$rules = array();
		$input = $this->put();
		// getCustomerDataFirst
		$customerData = $this->db->get_where('customers', array('id' => $input['customer_id']))->row_array();
		if (!empty($customerData)) {
			//fetch subscription details
			$subscriptionDetails = $this->db->get_where('subscription', ['id'=>$subscriptionId])->row_array();
			if(!empty($subscriptionDetails)){
				$subscriptionInsertId = $this->Mydb->insert_table_data('user_subscription', ['user_id'=>$input['customer_id'], 'subscription_id'=>$subscriptionId, 'status'=>2]);
				if($subscriptionInsertId){
					$dbPaymentDetails = [];
					if(!empty($input['paymentDetails'])){
						$decodePaymentDetails = json_decode($input['paymentDetails'], true);
						$dbPaymentDetails['pResponseStatus'] = $decodePaymentDetails['status'];
						$dbPaymentDetails['pCfPaymentId'] = $decodePaymentDetails['cf_payment_id'];
						$dbPaymentDetails['pOrderId'] = $decodePaymentDetails['order_id'];
						$dbPaymentDetails['pEntity'] = $decodePaymentDetails['entity'];
						$dbPaymentDetails['pIsCaptured'] = $decodePaymentDetails['is_captured'];
						$dbPaymentDetails['pOrderAmount'] = $decodePaymentDetails['order_amount'];
						$dbPaymentDetails['paymentGroup'] = $decodePaymentDetails['payment_group'];
						$dbPaymentDetails['paymentCurrency'] = $decodePaymentDetails['payment_currency'];
						$dbPaymentDetails['paymentAmount'] = $decodePaymentDetails['payment_amount'];
						$dbPaymentDetails['paymentTime'] = $decodePaymentDetails['payment_time'];
						$dbPaymentDetails['paymentCompletedTime'] = $decodePaymentDetails['payment_completion_time'];
						$dbPaymentDetails['paymentStatus'] = $decodePaymentDetails['payment_status'];
						$dbPaymentDetails['paymentMessage'] = $decodePaymentDetails['payment_message'];
						$dbPaymentDetails['paymentMethod'] = json_encode($decodePaymentDetails['payment_method']);
						$dbPaymentDetails['reference_table'] = 'user_subscription';
						$dbPaymentDetails['reference_id'] = $subscriptionInsertId;
						$insertId = $this->Mydb->insert_table_data('payment_details', $dbPaymentDetails);
						// subscription bonus
						if (!empty($customerData['refered_by'])) {
							$amount = $subscriptionDetails['basic_amount'];
							$updateReferenceL1 = $this->update_reference_amount($customerData['refered_by'], 1, $amount, $customerData['id']);
							if ($updateReferenceL1['status'] == 'success' && !empty($updateReferenceL1['referrerId'])) {
								$updateReferenceL2 = $this->update_reference_amount($updateReferenceL1['referrerId'], 2, $amount, $customerData['id']);
								if ($updateReferenceL2['status'] == 'success' && !empty($updateReferenceL2['referrerId'])) {
									$updateReferenceL3 = $this->update_reference_amount($updateReferenceL2['referrerId'], 3, $amount, $customerData['id']);
									if ($updateReferenceL3['status'] == 'success' && !empty($updateReferenceL3['referrerId'])) {
										$updateReferenceL4 = $this->update_reference_amount($updateReferenceL3['referrerId'], 4, $amount, $customerData['id']);
										if ($updateReferenceL4['status'] == 'success' && !empty($updateReferenceL4['referrerId'])) {
											$updateReferenceL5 = $this->update_reference_amount($updateReferenceL4['referrerId'], 5, $amount, $customerData['id']);
											if ($updateReferenceL5['status'] == 'success' && !empty($updateReferenceL5['referrerId'])) {
												$updateReferenceL6 = $this->update_reference_amount($updateReferenceL5['referrerId'], 6, $amount, $customerData['id']);
												if ($updateReferenceL6['status'] == 'success' && !empty($updateReferenceL6['referrerId'])) {
													$updateReferenceL7 = $this->update_reference_amount($updateReferenceL6['referrerId'], 7, $amount, $customerData['id']);
												}
											}
										}
									}
								}
							}
						}
						$customerDataWalletAmount = $customerData['subscriptionAmount'] + $subscriptionDetails['basic_amount'];
						$customerDataWalletPoints = $customerData['subscriptionPoints'] + $subscriptionDetails['wallet_points'];
						$customerUpdate = $this->db->update('customers', array('subscriptionAmount' => $customerDataWalletAmount, 'subscriptionPoints' => $customerDataWalletPoints), array('id' => $input['customer_id']));
						$msg="Dear ".$customerData['firstName'].' '.$customerData['lastName'].",\nCongratulations!! Your purchase of ".strtoupper($subscriptionDetails['name'])." subscription of the amount ".$subscriptionDetails['basic_amount']." is success.\nYou've also got ".$subscriptionDetails['wallet_points']." subscription points. This can be used while ordering every time on Nalaa Organic Web and Apps.\nThank You.\nNalaa Organic";
						$value = withSuccess('Subscription Amount Updated Successfully');
						//Create message
					}else{
						$value = withErrors('Error, Payment Details Not Found');
						$msg="Dear ".$customerData['firstName'].' '.$customerData['lastName'].",\nYour purchase of ".strtoupper($subscriptionDetails['name'])." subscription of the amount ".$subscriptionDetails['basic_amount']." is failed. Please follow below link to purchase subscription and get discounts everytime on order.".base_url().'subscription'."\nThank You\nNalaa Organic";
					}
				}else{
					$value = withErrors('Error Updating the subscription');
					$msg="Dear ".$customerData['firstName'].",\nYour purchase of ".strtoupper($subscriptionDetails['name'])." subscription of the amount ".$subscriptionDetails['basic_amount']." is failed. Please follow below link to purchase subscription and get discounts everytime on order.".base_url().'subscription'."\nThank You\nNalaa Organic";
				}
				send_whatsapp($customerData['phone'], $msg);
				log_message('error', $msg);

			}else{
				$value = withErrors('Subscription Details Not Found');
			}
		} else {
			$value = withErrors('Error Updating the subscription, customer Not Found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}



	public function mobile_subscription_post()
	{
        $raw_input_stream = file_get_contents('php://input');
        $input = json_decode($raw_input_stream, true); 
        if (empty($input)) {
            $value = json_encode(['status' => 'fail', 'message' => 'Invalid Request, Please try again.']);
            http_response_code(400);
            echo $value;
            return;
        }
		log_message('error', 'Starting Mobile SubscriptionPUT');
		log_message('error', print_r($input, true));
		$subscriptionId = $input['subscription_id'];

		$customerData = $this->db->get_where('customers', array('id' => $input['customer_id']))->row_array();
		if (!empty($customerData)) {
			//fetch subscription details
			$subscriptionDetails = $this->db->get_where('subscription', ['id'=>$subscriptionId])->row_array();
			if(!empty($subscriptionDetails)){
				$subscriptionInsertId = $this->Mydb->insert_table_data('user_subscription', ['user_id'=>$input['customer_id'], 'subscription_id'=>$subscriptionId, 'status'=>2, 'pay_status'=>($input['pay_status']=='success')?1:0]);
				if($subscriptionInsertId){
					$dbPaymentDetails = [];
					$decodePaymentDetails = $input['paymentDetails'];
					if(!empty($input['paymentDetails'])){
						$dbPaymentDetails['pResponseStatus'] = $decodePaymentDetails['status'];
						$dbPaymentDetails['pCfPaymentId'] = $decodePaymentDetails['cf_payment_id'];
						$dbPaymentDetails['pOrderId'] = $decodePaymentDetails['order_id'];
						$dbPaymentDetails['pEntity'] = $decodePaymentDetails['entity'];
						$dbPaymentDetails['pIsCaptured'] = $decodePaymentDetails['is_captured'];
						$dbPaymentDetails['pOrderAmount'] = $decodePaymentDetails['order_amount'];
						$dbPaymentDetails['paymentGroup'] = $decodePaymentDetails['payment_group'];
						$dbPaymentDetails['paymentCurrency'] = $decodePaymentDetails['payment_currency'];
						$dbPaymentDetails['paymentAmount'] = $decodePaymentDetails['payment_amount'];
						$dbPaymentDetails['paymentTime'] = $decodePaymentDetails['payment_time'];
						$dbPaymentDetails['paymentCompletedTime'] = $decodePaymentDetails['payment_completion_time'];
						$dbPaymentDetails['paymentStatus'] = $decodePaymentDetails['payment_status'];
						$dbPaymentDetails['paymentMessage'] = $decodePaymentDetails['payment_message'];
						$dbPaymentDetails['paymentMethod'] = json_encode($decodePaymentDetails['payment_method']);
						$dbPaymentDetails['reference_table'] = 'user_subscription';
						$dbPaymentDetails['reference_id'] = $subscriptionInsertId;
						$insertId = $this->Mydb->insert_table_data('payment_details', $dbPaymentDetails);
						// subscription bonus
						if($input['pay_status']=='success'){
							if (!empty($customerData['refered_by'])) {
								$amount = $subscriptionDetails['basic_amount'];
								log_message('error', 'subscription details');
								log_message('error', print_R($subscriptionDetails, true));
								$updateReferenceL1 = $this->update_reference_amount($customerData['refered_by'], 1, $amount, $customerData['id']);
								if ($updateReferenceL1['status'] == 'success' && !empty($updateReferenceL1['referrerId'])) {
									$updateReferenceL2 = $this->update_reference_amount($updateReferenceL1['referrerId'], 2, $amount, $customerData['id']);
									if ($updateReferenceL2['status'] == 'success' && !empty($updateReferenceL2['referrerId'])) {
										$updateReferenceL3 = $this->update_reference_amount($updateReferenceL2['referrerId'], 3, $amount, $customerData['id']);
										if ($updateReferenceL3['status'] == 'success' && !empty($updateReferenceL3['referrerId'])) {
											$updateReferenceL4 = $this->update_reference_amount($updateReferenceL3['referrerId'], 4, $amount, $customerData['id']);
											if ($updateReferenceL4['status'] == 'success' && !empty($updateReferenceL4['referrerId'])) {
												$updateReferenceL5 = $this->update_reference_amount($updateReferenceL4['referrerId'], 5, $amount, $customerData['id']);
												if ($updateReferenceL5['status'] == 'success' && !empty($updateReferenceL5['referrerId'])) {
													$updateReferenceL6 = $this->update_reference_amount($updateReferenceL5['referrerId'], 6, $amount, $customerData['id']);
													if ($updateReferenceL6['status'] == 'success' && !empty($updateReferenceL6['referrerId'])) {
														$updateReferenceL7 = $this->update_reference_amount($updateReferenceL6['referrerId'], 7, $amount, $customerData['id']);
													}
												}
											}
										}
									}
								}
							}
							$customerDataWalletAmount = $customerData['subscriptionAmount'] + $subscriptionDetails['basic_amount'];
							$customerDataWalletPoints = $customerData['subscriptionPoints'] + $subscriptionDetails['wallet_points'];
							$customerUpdate = $this->db->update('customers', array('subscriptionAmount' => $customerDataWalletAmount, 'subscriptionPoints' => $customerDataWalletPoints), array('id' => $input['customer_id']));
							$msg="Dear ".$customerData['firstName'].' '.$customerData['lastName'].",\nCongratulations!! Your purchase of ".strtoupper($subscriptionDetails['name'])." subscription of the amount ".$subscriptionDetails['basic_amount']." is success.\nYou've also got ".$subscriptionDetails['wallet_points']." subscription points. This can be used while ordering every time on Nalaa Organic Web and Apps.\nThank You.\nNalaa Organic";
							$value = withSuccess('Subscription Amount Updated Successfully');
						}else{
							$value = withSuccess('Subscription Purchased Successfully, Repurchase not updated yet');
						}
						//Create message
					}else{
						$value = withErrors('Error, Payment Details Not Found');
						$msg="Dear ".$customerData['firstName'].' '.$customerData['lastName'].",\nYour purchase of ".strtoupper($subscriptionDetails['name'])." subscription of the amount ".$subscriptionDetails['basic_amount']." is failed. Please follow below link to purchase subscription and get discounts everytime on order.".base_url().'subscription'."\nThank You\nNalaa Organic";
					}
				}else{
					$value = withErrors('Error Updating the subscription');
					$msg="Dear ".$customerData['firstName'].",\nYour purchase of ".strtoupper($subscriptionDetails['name'])." subscription of the amount ".$subscriptionDetails['basic_amount']." is failed. Please follow below link to purchase subscription and get discounts everytime on order.".base_url().'subscription'."\nThank You\nNalaa Organic";
				}
				send_whatsapp($customerData['phone'], $msg);
				log_message('error', $msg);

			}else{
				$value = withErrors('Subscription Details Not Found');
				log_message('error', 'Error, Subscription Details Not Found');
			}
		} else {
			log_message('error', 'Error, Couldn\'t find customers');
			$value = withErrors('Error Updating the subscription, customer Not Found');
		}
		log_message('error', 'printing value');
		log_message('error', print_R($value, true));
		$this->response($value, REST_Controller::HTTP_OK);
	}



	// public function mobile_subscription_payment_update_put($userSubscriptionId)
	// {
    //     $raw_input_stream = file_get_contents('php://input');
    //     $input = json_decode($raw_input_stream, true); 
    //     if (empty($input)) {
    //         $value = json_encode(['status' => 'fail', 'message' => 'Invalid Request, Please try again.']);
    //         http_response_code(400);
    //         echo $value;
    //         return;
    //     }
	// 	$subscriptionDetails = $this->db->get_where('user_subscription', array('id' => $userSubscriptionId))->row_array();
	// 	if (!empty($subscriptionDetails)) {
	// 		//fetch subscription details
	// 		$subscriptionDetails = $this->db->get_where('subscription', ['id'=>$subscriptionId])->row_array();
	// 		if(!empty($subscriptionDetails)){
	// 			$subscriptionInsertId = $this->Mydb->insert_table_data('user_subscription', ['user_id'=>$input['customer_id'], 'subscription_id'=>$subscriptionId, 'status'=>2, 'pay_status'=>($input['pay_status']=='success')?1:0]);
	// 			if($subscriptionInsertId){
	// 				$dbPaymentDetails = [];
	// 				if(!empty($input['paymentDetails'])){
	// 					$decodePaymentDetails = json_decode($input['paymentDetails'], true);
	// 					$dbPaymentDetails['pResponseStatus'] = $decodePaymentDetails['status'];
	// 					$dbPaymentDetails['pCfPaymentId'] = $decodePaymentDetails['cf_payment_id'];
	// 					$dbPaymentDetails['pOrderId'] = $decodePaymentDetails['order_id'];
	// 					$dbPaymentDetails['pEntity'] = $decodePaymentDetails['entity'];
	// 					$dbPaymentDetails['pIsCaptured'] = $decodePaymentDetails['is_captured'];
	// 					$dbPaymentDetails['pOrderAmount'] = $decodePaymentDetails['order_amount'];
	// 					$dbPaymentDetails['paymentGroup'] = $decodePaymentDetails['payment_group'];
	// 					$dbPaymentDetails['paymentCurrency'] = $decodePaymentDetails['payment_currency'];
	// 					$dbPaymentDetails['paymentAmount'] = $decodePaymentDetails['payment_amount'];
	// 					$dbPaymentDetails['paymentTime'] = $decodePaymentDetails['payment_time'];
	// 					$dbPaymentDetails['paymentCompletedTime'] = $decodePaymentDetails['payment_completion_time'];
	// 					$dbPaymentDetails['paymentStatus'] = $decodePaymentDetails['payment_status'];
	// 					$dbPaymentDetails['paymentMessage'] = $decodePaymentDetails['payment_message'];
	// 					$dbPaymentDetails['paymentMethod'] = json_encode($decodePaymentDetails['payment_method']);
	// 					$dbPaymentDetails['reference_table'] = 'user_subscription';
	// 					$dbPaymentDetails['reference_id'] = $subscriptionInsertId;
	// 					$insertId = $this->Mydb->insert_table_data('payment_details', $dbPaymentDetails);
	// 					// subscription bonus
	// 					if($input['pay_status']=='success'){
	// 						if (!empty($customerData['refered_by'])) {
	// 							$amount = $subscriptionDetails['basic_amount'];
	// 							$updateReferenceL1 = $this->update_reference_amount($customerData['refered_by'], 1, $amount, $customerData['id']);
	// 							if ($updateReferenceL1['status'] == 'success' && !empty($updateReferenceL1['referrerId'])) {
	// 								$updateReferenceL2 = $this->update_reference_amount($updateReferenceL1['referrerId'], 2, $amount, $customerData['id']);
	// 								if ($updateReferenceL2['status'] == 'success' && !empty($updateReferenceL2['referrerId'])) {
	// 									$updateReferenceL3 = $this->update_reference_amount($updateReferenceL2['referrerId'], 3, $amount, $customerData['id']);
	// 									if ($updateReferenceL3['status'] == 'success' && !empty($updateReferenceL3['referrerId'])) {
	// 										$updateReferenceL4 = $this->update_reference_amount($updateReferenceL3['referrerId'], 4, $amount, $customerData['id']);
	// 										if ($updateReferenceL4['status'] == 'success' && !empty($updateReferenceL4['referrerId'])) {
	// 											$updateReferenceL5 = $this->update_reference_amount($updateReferenceL4['referrerId'], 5, $amount, $customerData['id']);
	// 											if ($updateReferenceL5['status'] == 'success' && !empty($updateReferenceL5['referrerId'])) {
	// 												$updateReferenceL6 = $this->update_reference_amount($updateReferenceL5['referrerId'], 6, $amount, $customerData['id']);
	// 												if ($updateReferenceL6['status'] == 'success' && !empty($updateReferenceL6['referrerId'])) {
	// 													$updateReferenceL7 = $this->update_reference_amount($updateReferenceL6['referrerId'], 7, $amount, $customerData['id']);
	// 												}
	// 											}
	// 										}
	// 									}
	// 								}
	// 							}
	// 						}
	// 						$customerDataWalletAmount = $customerData['subscriptionAmount'] + $subscriptionDetails['basic_amount'];
	// 						$customerDataWalletPoints = $customerData['subscriptionPoints'] + $subscriptionDetails['wallet_points'];
	// 						$customerUpdate = $this->db->update('customers', array('subscriptionAmount' => $customerDataWalletAmount, 'subscriptionPoints' => $customerDataWalletPoints), array('id' => $input['customer_id']));
	// 						$msg="Dear ".$customerData['firstName'].' '.$customerData['lastName'].",\nCongratulations!! Your purchase of ".strtoupper($subscriptionDetails['name'])." subscription of the amount ".$subscriptionDetails['basic_amount']." is success.\nYou've also got ".$subscriptionDetails['wallet_points']." subscription points. This can be used while ordering every time on Nalaa Organic Web and Apps.\nThank You.\nNalaa Organic";
	// 						$value = withSuccess('Subscription Amount Updated Successfully');
	// 					}else{
	// 						$value = withSuccess('Subscription Purchased Successfully, Repurchase not updated yet');
	// 					}
	// 					//Create message
	// 				}else{
	// 					$value = withErrors('Error, Payment Details Not Found');
	// 					$msg="Dear ".$customerData['firstName'].' '.$customerData['lastName'].",\nYour purchase of ".strtoupper($subscriptionDetails['name'])." subscription of the amount ".$subscriptionDetails['basic_amount']." is failed. Please follow below link to purchase subscription and get discounts everytime on order.".base_url().'subscription'."\nThank You\nNalaa Organic";
	// 				}
	// 			}else{
	// 				$value = withErrors('Error Updating the subscription');
	// 				$msg="Dear ".$customerData['firstName'].",\nYour purchase of ".strtoupper($subscriptionDetails['name'])." subscription of the amount ".$subscriptionDetails['basic_amount']." is failed. Please follow below link to purchase subscription and get discounts everytime on order.".base_url().'subscription'."\nThank You\nNalaa Organic";
	// 			}
	// 			send_whatsapp($customerData['phone'], $msg);
	// 			log_message('error', $msg);

	// 		}else{
	// 			$value = withErrors('Subscription Details Not Found');
	// 		}
	// 	} else {
	// 		$value = withErrors('Error Updating the subscription, customer Not Found');
	// 	}
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }


	public function update_reference_amount($customerId, $level, $subscriptionAmount, $subscriptionCustomer)
	{
		$customerData = $this->db->get_where('customers', array('id' => $customerId))->row_array();
		$refAmount = 0;
		$referrerId = '';
		$status = 'fail';
		$message = 'Something Went Wrong';
		if (!empty($customerData)) {
			switch ($level) {
				case '1':
					$refAmount = ($subscriptionAmount * 10) / 100;
					break;
				case '2':
					$refAmount = ($subscriptionAmount * 3) / 100;
					break;
				case '3':
					$refAmount = ($subscriptionAmount * 1) / 100;
					break;
				case '4':
					$refAmount = ($subscriptionAmount * 0.5) / 100;
					break;
				case '5':
					$refAmount = ($subscriptionAmount * 0.2) / 100;
					break;
				case '6':
					$refAmount = ($subscriptionAmount * 0.1) / 100;
					break;
				case '7':
					$refAmount = ($subscriptionAmount * 0.05) / 100;
					break;
			}
			$creditCustomer = $this->db->insert('customer_reference_amount', array('level' => $level, 'amount' => $refAmount, 'customer_id' => $customerId, 'status' => 1, 'createdDate' => cur_date_time(), 'ref_comission_by' => $subscriptionCustomer, 'subscriptionAmount' => $subscriptionAmount));
			if ($creditCustomer) {
				if (!empty($customerData['refered_by'])) {
					$referrerId = $customerData['refered_by'];
				}
				$status = 'success';
				$message = 'Level ' . $level . ' Reference Updated Successfully';
			}
		}
		return array('status' => $status, 'referrerId' => $referrerId, 'message' => $message);
	}



	public function customer_subscription_get($id = 0)
	{
		$message = "success";
		$data = array();
		if (!empty($id)) { 
			$data = $this->Mydb->get_single_result($id, $this->customer_subscription_table, $this->customer_subscription_model_name);
		} else {
			$data = $this->Mydb->do_search($this->customer_subscription_table, $this->customer_subscription_model_name);
		}
		if (!empty($data)) {
			$value = withSuccess($message, $data);
		} else {
			$value = withSuccess($this->lang->line('no_result_found'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


    function init_post() {
        $raw_input_stream = file_get_contents('php://input');
        $input = json_decode($raw_input_stream, true);
        
    
        if (empty($input)) {
            $value = json_encode(['status' => 'fail', 'message' => 'Invalid Request, Please try again.']);
            http_response_code(400);
            echo $value;
            return;
        }
    
        $userId = $input['customer_id'];
        $subscriptionId = $input['subscription_id'];
        $returnUrl = ['return_url'];
        $customer_data = $this->db->get_where('customers', ['id'=>$userId])->row_array();
        if(!empty($customer_data) && $subscriptionId){
			$subscriptionData = $this->db->get_where('subscription', ['id'=>$subscriptionId])->row_array();
			if(!empty($subscriptionData)){
				$subscriptionAmount = $subscriptionData['basic_amount'];
				// $clientKey = 'cfsk_ma_test_a1cb17f6b142a7f32369e8c28965fddc_2e379846'; // Replace with your actual client secret
				// $clientId = 'TEST102073792924a2850f35797aecba97370201';   // Replace with your actual client ID
				$clientKey = 'cfsk_ma_prod_2daeed7715afcb89811858688b4cd8ed_d280cc62';//LIVE
				$clientId = '69840976689cac79aa7b79fc08904896';//LIVE
				$data = [
					'order_amount' => $subscriptionAmount,
					'order_currency' => 'INR', // Assuming you always use INR
					'customer_details' => [
						'customer_id' => $userId,
						'customer_name' => $customer_data['firstName'] . ' ' . $customer_data['lastName'],
						'customer_email' => $customer_data['email'],
						'customer_phone' => $customer_data['phone'],
					],
				];
			
				$jsonData = json_encode($data);
			
				// $url = 'https://sandbox.cashfree.com/pg/orders';
				$url = 'https://api.cashfree.com/pg/orders';
				$headers = [
					'X-Client-Secret: ' . $clientKey,
					'X-Client-Id: ' . $clientId,
					'x-api-version: 2023-08-01',
					'Content-Type: application/json',
					'Accept: application/json',
				];
			
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
				$response = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				if ($httpCode >= 200 && $httpCode < 300) {
					$responseData = json_decode($response, true);
			
					if (isset($responseData['payment_session_id']) && isset($responseData['order_id'])) {
						$sessionId = $responseData['payment_session_id'];
						$orderId = $responseData['order_id'];
						$result = [
							'paymentDetails' => [
								'sessionId' => $sessionId,
								'orderId' => $orderId,
								'environment'=>'PRODUCTION'
							],
						];
						$value = withSuccess('Order Created Successfully', $result);
					} else {
						$value = withErrors('Order could not be created', ['sessionId'=>'', 'orderId'=>'']);
					}
				} else {
					$value = withErrors('Order could not be created, Network Error', ['sessionId'=>'', 'orderId'=>'']);
				}
			}else{
				$value = withErrors('Subscription Info couldn\'t fetch');
			}
        }else{
            $value = withErrors('Couldn\'t fetch your details', ['sessionId'=>'', 'orderId'=>'']);
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }  



    function check_payment_status_get($orderId) {
		log_message('error', 'Check payment status get request');
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/{$orderId}/payments",
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
            "x-client-secret: cfsk_ma_test_a1cb17f6b142a7f32369e8c28965fddc_2e379846"
          ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $value = withErrors('Payment Status Check Failed, Please contact us if the amount is deducted');
        } else {
            $processedResponse = $this->processPaymentResponse($response);
            if ($processedResponse['status'] === 'success') {
                $value = withSuccess('Payment Status Checked successfully', ['paymentDetails' => $processedResponse]);
            } else {
                $value = withErrors('Failed to process payment details');
            }
        }
        
        $this->response($value, REST_Controller::HTTP_OK);
    }


    
    private function processPaymentResponse($response) {
        $decodedResponse = json_decode($response);
        
        if ($decodedResponse && is_array($decodedResponse) && count($decodedResponse) > 0) {
            $paymentEntity = $decodedResponse[0]; // Assuming there's only one payment entity
            
            // Extract payment method details
            $paymentMethodDetails = $this->extractPaymentMethodDetails($paymentEntity->payment_method);
            $paymentDetails = [
                'status' => 'success',
                'cf_payment_id' => $paymentEntity->cf_payment_id,
                'order_id' => $paymentEntity->order_id,
                'entity' => $paymentEntity->entity,
                'is_captured' => $paymentEntity->is_captured,
                'order_amount' => $paymentEntity->order_amount,
                'payment_group' => $paymentEntity->payment_group,
                'payment_currency' => $paymentEntity->payment_currency,
                'payment_amount' => $paymentEntity->payment_amount,
                'payment_time' => $paymentEntity->payment_time,
                'payment_completion_time' => $paymentEntity->payment_completion_time,
                'payment_status' => $paymentEntity->payment_status,
                'payment_message' => $paymentEntity->payment_message,
                'payment_method' => $paymentMethodDetails,
            ];
            
            return $paymentDetails;
        } else {
            return ['status' => 'fail', 'error' => 'Invalid response structure'];
        }
    }
    
    
    private function extractPaymentMethodDetails($paymentMethod) {
        $details = [];
        
        if (isset($paymentMethod->card)) {
            $details['card'] = $paymentMethod->card;
        }
        
        if (isset($paymentMethod->netbanking)) {
            $details['netbanking'] = $paymentMethod->netbanking;
        }
        
        if (isset($paymentMethod->upi)) {
            $details['upi'] = [
                'channel' => $paymentMethod->upi->channel,
                'upi_id' => $paymentMethod->upi->upi_id,
            ];
        }
        
        if (isset($paymentMethod->app)) {
            $app = $paymentMethod->app;
            $details['app'] = [
                'channel' => $app->channel,
                'provider' => $app->provider,
                'phone' => $app->phone,
            ];
        }
        
        if (isset($paymentMethod->cardless_emi)) {
            $details['cardless_emi'] = $paymentMethod->cardless_emi;
        }
        
        if (isset($paymentMethod->paylater)) {
            $details['paylater'] = $paymentMethod->paylater;
        }
        
        if (isset($paymentMethod->emi)) {
            $details['emi'] = $paymentMethod->emi;
        }
        
        if (isset($paymentMethod->banktransfer)) {
            $details['banktransfer'] = $paymentMethod->banktransfer;
        }
        
        return $details;
    }

	function test_post(){
        $raw_input_stream = file_get_contents('php://input');
        $input = json_decode($raw_input_stream, true);
        
    
        if (empty($input)) {
            $value = json_encode(['status' => 'fail', 'message' => 'Invalid Request, Please try again.']);
            http_response_code(400);
            echo $value;
            return;
        }
		log_message('error', 'Printing test route');
		log_message('error', print_R($input, true));
	}
}