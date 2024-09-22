<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {

	function __construct(){
		parent::__construct();
		// $this->load->model("usermodel","",true);
		// $this->load->model("cartmodel","",true);
		// $this->load->model("adminmodel","",true);
		
	}

	function index() {
	    $data = $this->admin->commonEmptyFiles();
		$data['title']="Checkout & Shipping";
		$userId=get_userId();
		if(!empty($userId)){
			$orderTotal = $this->curl->execute("cart/cart_total/$userId", "GET");
			if($orderTotal['status'] == 'success' && !empty($orderTotal['data_list'])){
				$data['orderTotal'] = $orderTotal['data_list'];
			}
			$cartData = $this->curl->execute("cart", "GET", array("shopping_cart-customerId" => $userId));
			if($cartData['status'] == 'success' && !empty($cartData['data_list'])){
				$data['cartQ'] = $cartData['data_list'];
			}
			$deliveryAddress = $this->curl->execute("delivery_address", "GET");
			if($deliveryAddress['status'] == 'success' && !empty($deliveryAddress['data_list'])){
				$data['deliveryQ'] = $deliveryAddress['data_list'];
			}
		}
			if(!is_uLogged()){
				$data['tblId']='chktloginBody';
				$data['activeBody']='login';
			}else{
				$data['tblId']='shipaddressTbl';
				$data['activeBody']='shipping';
			}
			if(is_orderAddr()){
				$data['tblId']='chktpaymentBody';
				$data['activeBody']='payment';
			}
			// print_R($data);exit();
			$this->load->view("checkout/checkout_view",$data);
		//}else{
			//redirect(base_url());
		//}
    }

    function get_loginbody(){ 
    	$data=array();
		$str=$this->load->view("checkout/checkout_login_tbl",$data,true);
		$value=array(
			'str'=>$str
		);
	 	echo json_encode($value);
    }

    function get_shipaddrData(){ 
    	$data=array();   	
    	$userId=get_userId();
		$data['addressQ']=$this->curl->execute('shipping_address', 'GET', array('customerId'=>$userId));
		// print_R($data['addressQ']);exit();
		$orderTotal = $this->curl->execute("cart/cart_total/$userId", "GET");
			if($orderTotal['status'] == 'success' && !empty($orderTotal['data_list'])){
				$data['orderTotal'] = $orderTotal['data_list'];
			}
		$str=$this->load->view("checkout/checkout_address_tbl",$data,true);
		$value=array(
			'str'=>$str
		);
	 	echo json_encode($value);
    }

    function get_payoptions(){ 
    	$data=array();
		$str=$this->load->view("checkout/checkout_payment_tbl",$data,true);
		$value=array(
			'str'=>$str
		);
	 	echo json_encode($value);
    }

	function placeorder(){
		$input = $this->input->post();
		$userId=get_userId();
		if(!empty($input) && !empty($userId)){
			$orderTotal = $this->curl->execute("cart/cart_total/$userId", "GET");
			if($orderTotal['status'] == 'success' && !empty($orderTotal['data_list'])){
				$data['orderTotal'] = $orderTotal['data_list'];
			}
			$cartData = $this->curl->execute("cart", "GET", array("shopping_cart-customerId" => $userId));
			if($cartData['status'] == 'success' && !empty($cartData['data_list'])){
				$data['cartQ'] = $cartData['data_list'];
			}
			$deliveryAddress = $this->curl->execute("delivery_address", "GET");
			if($deliveryAddress['status'] == 'success' && !empty($deliveryAddress['data_list'])){
				$data['deliveryQ'] = $deliveryAddress['data_list'];
			}
			$data['deliveryCharge'] = $input['delId'];
			$str = $this->load->view("checkout/total_order_view", $data, true);
			$value=array(
				'str'=>$str
			);
				echo json_encode($value);
		}
	}

    function select_shipaddr(){
		$input = $this->input->post();
    	$addressId=$this->input->post('addressId');
    	if($addressId!=''){
			$this->save_checkout($input);
			$orderdata=array(
				'abvorderaddrId'=>$addressId
			);
			$this->session->set_userdata($orderdata);
			$result='success';
			$msg='Redirect to payment';
		}else{
			$result='fail';
			$msg='Failed to initiate shipping!';
		}
		$value=array(
			'result'=>$result,
			'msg' =>$msg
		);
	 	echo json_encode($value);
    }

    function save_checkout(){
		$input = $this->input->post();
    	$userId=get_userId();
		$orderTotal=$input['orderTotal'];
		$cartAmount=$input['cartAmount'];
		$orderTax=$input['orderTax'];
		$deliveryCharge=$input['deliveryCharge'];
    	if($userId){
			$selectedProducts = '';
			$cartData = $this->curl->execute("cart", "GET", array("customerId"=>$userId));
			if($cartData['status'] == 'success' && !empty($cartData['data_list'])){
				$cart_data = $cartData['data_list'];
				$selectedProducts = json_encode($cart_data);
			}
			$data['selectedAddress']=$this->input->post('selectedAddress');
			$data['selectedAddressLatLong']=$this->input->post('selectedAddressLatLong');
			$data['userId']=$userId;
			$data['cartAmount']=$cartAmount;
			$data['orderTax']=$orderTax;
			$data['deliveryCharge']=$deliveryCharge;
			$data["selectedProducts"] = $selectedProducts;
			$data['createdDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
			$data['updatedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
			$orderdata = $this->curl->execute("orders", "POST", $data);
			if($orderdata['status'] == 'success' && !empty($orderdata['data_list'])){
				$status = $orderdata['status'];
				$message = $orderdata['message'];
			}else{
				$status = $orderdata['status'];
				$message = $orderdata['message'];
			}
			$value=array(
				'status'=>$status,
				'message' =>$message
			);
			echo json_encode($value);
		}else{
			redirect(base_url().'checkout');
			exit;
		}
    }

    function save_checkout_old(){
		$input = $this->input->post();
    	$userId=get_userId();
		$orderTotal=$input['orderTotal'];
		$cartAmount=$input['cartAmount'];
		$orderTax=$input['orderTax'];
		$deliveryCharge=$input['deliveryCharge'];
		// $loggedIn=is_logged();
    	if($userId){
    		// if($orderTotal){
				// $orderTotal = $this->curl->execute("cart/cart_total/$userId", "GET");
				// if($orderTotal['status'] == 'success' && !empty($orderTotal['data_list'])){
				// 	$data['orderTotal'] = $orderTotal['data_list'];
				// }
				$selectedProducts = '';
				// $selectedProduct = [];
				$cartData = $this->curl->execute("cart", "GET", array("customerId"=>$userId));
				if($cartData['status'] == 'success' && !empty($cartData['data_list'])){
					$cart_data = $cartData['data_list'];
				// print_R($cart_data);echo "<hr>";
					$selectedProducts = json_encode($cart_data);
					// $selectedProduct = array_push($selectedProduct, $cart_data);
					// foreach ($cart_data as $cartData) {

					// }//exit();
				}
				// print_R($selectedProducts);exit();
				$data['selectedAddress']=$this->input->post('addressId');
		    	$data['userId']=$userId;
		    	$data['cartAmount']=$cartAmount;
		    	$data['orderTax']=$orderTax;
		    	$data['deliveryCharge']=$deliveryCharge;
				$data["selectedProducts"] = $selectedProducts;
		    	$data['createdDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
				$data['updatedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
				// print_R($data);exit();
				$orderdata = $this->curl->execute("orders", "POST", $data);
				// print_R($orderdata);exit();
				if($orderdata['status'] == 'success' && !empty($orderdata['data_list'])){
					$status = $orderdata['status'];
					$message = $orderdata['message'];
				}else{
					$status = $orderdata['status'];
					$message = $orderdata['message'];
				}
				$value=array(
					'status'=>$status,
					'message' =>$message
				);
				echo json_encode($value);
				// if($orderTotal != ''){
				// 	$result='fail';
				// 	$msg='Minimum shopping price should be greater than 1000Rs.';
				// 	$value=array(
				// 		'result'=>$result,
				// 		'msg' =>$msg
				// 	);
				//  	echo json_encode($value);
		        // 	exit;
				// }else{
				// 	$result='success';
				// 	$msg='Redirecting to the secured payment gateway...';
				// 	$value=array(
				// 		'result'=>$result,
				// 		'msg' =>$msg
				// 	);
				//  	echo json_encode($value);
		        // 	exit;
				// }
			}else{
				redirect(base_url().'checkout');
				exit;
			}
		// }else{
		// 	redirect(base_url().'checkout');
		// 	exit;
		// }
    }

}