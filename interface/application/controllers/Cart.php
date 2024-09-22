<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

	function __construct(){
		parent::__construct();
        // $this->load->model("adminmodel","",true);
		// $this->load->model("usermodel","",true);
		// $this->load->model("cartmodel","",true);
	}

	function index() {
	    $data = $this->admin->commonFiles();
		$data['title']="Shopping Cart";
		$this->load->view("cart/cart_view",$data);
    }

    function get_data(){
		$data = array();
		$data['orderTotal'] = array();
		$data['cartQ'] = array();
		$customerId = get_userId();
		if(!empty($customerId)){
			$order_data = $this->curl->execute("cart/cart_total/$customerId", "GET", array('perpage'=>1000));
			// print_r($order_data);
			// echo "<hr>";
			// exit();
			if($order_data['status'] == 'success' && !empty($order_data['data_list'])){
				$data['orderTotal'] = $order_data['data_list'];
			}
			$cart_data = $this->curl->execute("cart", "GET", array("customerId" => $customerId, 'perpage'=>1000));
			if($cart_data['status'] == 'success' && !empty($cart_data['data_list'])){
				$data['cartQ'] = $cart_data['data_list'];
			}
		}
        $str=$this->load->view("cart/cart_tbl_view",$data,true);
        $value=array(
            'str'=>$str
        );
        echo json_encode($value);
    }

    function savecart() {
		$userId = get_userId();
		if(!empty($userId)){	
			$result=0;
			$msg='Failed to add!';
			$productId=$this->input->post('productId');
			$metricsId=$this->input->post('metricsId');
			$quantity=$this->input->post('quantity');
			$quantity=$quantity==""?1:$quantity;
		
		// if(!is_uLogged()){
		// 	if(!$userId){
		// 		$guestdata['type']= 'Guest';
		// 		$guestdata['status']=2;
		// 		// $ins_id=$this->usermodel->addguestuser($guestdata);
		// 		$apidata = $this->curl->execute('customers/register_guest', 'POST', $guestdata);
		// 		if($apidata['status']=='success' && !empty($apidata['data_list'])){
		// 			$this->session->set_userdata('gt_uId',$apidata['data_list']['id']);
		// 		}else{
		// 			$this->session->set_userdata('gt_uId',1);
		// 		}
		// 	}
		// }
		// $userId = get_userId();
		$data['customerId']=$userId;
		$data['productId']=$productId;
		$data['metricsId']=$metricsId;
		$data['quantity']=$quantity;
		// Not checking Now - Begining
		// $minQuantity = $this->cartmodel->getMinQuantity($data['productId']);
		// if(($minQuantity>1)&&($quantity<$minQuantity)){
		// 	$data['quantity']=$minQuantity;
		// }else{
		// 	$data['quantity']=$quantity;
		// }
		// $data['updatedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
		$data['status']=1;
		// print_r($data);exit();
		$cartApi = $this->curl->execute('cart', 'POST', $data);	
		// print_r($cartApi);exit();
		if($cartApi['status'] == "success" && !empty($cartApi['data_list'])){
			$status=$cartApi['status'];
			$message=$cartApi['message'];
		}else{
			$status=$cartApi['status'];
			$message=$cartApi['message'];
		}
		
		// print_r($cartApi);exit();
	}else{
		$status="fail";
		$message="Please Login";
	}
	echo json_encode(array('status'=>$status, 'message'=>$message));
	return;
    }

    function showcartqty(){
    	$uid=get_userId();
		$q = 0;
		if(!empty($uid)){
			$apidata = $this->curl->execute('cart', 'GET', array('customerId'=>$uid, 'perpage'=>1000));
			// print_r($apidata);exit();
			if($apidata['status']=='success' && !empty($apidata['data_list'])){
				$q = count($apidata['data_list']);
			}
		}
		$data['quantity']=$q;
		$str=$this->load->view("cart/cart_qty_view",$data,true);
		
		$value=array(
			'result'=>$str
		);
		echo json_encode($value);
    }

    function update(){
    	$data['cartId']=$this->input->post('cartId');
    	$data['productId']=$this->input->post('productId');
    	$data['quantity']=$this->input->post('quantity');
		// print_r($data);exit();
    	$customerId=get_userId();
		// print_R($data);exit();
		$cartData = array();
		if(!empty($customerId)){
			// for($i=0;$i<count($data['cartId']);$i++){
			// 	$cartData[]=array(
			// 		'cartId'=>$data['cartId'][$i],
			// 		'customerId'=>$customerId,
			// 		'productId'=>$data['productId'][$i],
			// 		'quantity'=>$data['quantity'][$i],
			// 		);
			// }
			// if(!empty($cartData)){
				// $apidata = $this->curl->execute('cart/update_cart', 'POST', array('cart_data'=>json_encode($cartData)));
				$apidata = $this->curl->execute('cart/update_cart', 'POST', $data);
				// print_R($apidata);exit();
				$value = array('status'=>$apidata['status'], 'message'=>$apidata['message']);
			// }else{
			// 	$value = array('status'=>'fail', 'message'=>'Invalid Cart Data');
			// }
		}else{
    		$value=array(
                'status'=>'success',
                'message'=>'Please login to continue...'
            );
		}
        echo json_encode($value);
		return;

    	// if($data['customerId']){
    	// 	$res=$this->cartmodel->update_cart($data);
    	// 	if($res>0){
    	// 		$result='success';
    	// 		$msg='Your cart has been updated';
    	// 	}else{
    	// 		$result='fail';
    	// 		$msg='Failed to update your cart!';
    	// 	}
    	// 	//print_r($this->db->last_query());
    	// 	$value=array(
	    //         'result'=>$result,
	    //         'msg'=>$msg
	    //     );
    	// }else{
    	// 	$value=array(
        //         'result'=>0,
        //         'msg'=>'Please login to continue...'
        //     );
    	// }
    }

    function remove(){
    	$cartId=$this->input->post("id");
    	$data['customerId']=get_userId();
    	if(empty($cartId)){
			echo json_encode(array('status'=>'fail', 'message'=>'Unknown Request'));
			return;
    	}else{
			$apidata = $this->curl->execute("cart/$cartId", "DELETE");
    		$value=array(
	            'status'=>$apidata['status'],
	            'message'=>$apidata['message']
	        );
	         echo json_encode($value);
    	}
    }

    function clear(){
    	$userId=get_userId();
    	if($userId){
    		$apidata=$this->curl->execute("cart/clear_cart/$userId", 'DELETE'); 
    		$value=array(
	            'status'=>$apidata['status'],
	            'message'=>$apidata['message']
	        );
	         echo json_encode($value);
    	}else{
    		redirect(base_url().'cart');
    	}
    }
    

    function save_booking(){
        $customerId=get_userId();
        if(is_uLogged() && $customerId){
			
			// $res = $this->curl->execute("cart/book")
            // $res=$this->cartmodel->book_user_order($customerId);
			print_R($customerId);echo "<hr>";
			$res = $this->curl->execute("cart", "GET", array("customerId => $customerId"));
			
			if($res['status'] == 'success' && !empty($res['data_list'])){
				$data['customerId'] = $customerId;
				// foreach ($res['data_list'] as $key => $value) {
				// 	# code...
				// }
				// $data['productsData'] = $res['data_list'];
				$apidata = $this->curl->execute("orders", "POST", $data);
				// print_R($apidata);exit();
			}
            //print_r($this->db->last_query());
            $value=array(
                'result'=>$res['result'],
                'msg'=>$res['msg'],
                'dataId'=>$res['dataId'],
                'urlredirect' =>$res['urlredirect']
            );
        }else{
            $value=array(
                'result'=>'fail',
                'msg'=>'Please login to continue...',
                'dataId'=>0,
                'urlredirect' => 'login'
            );
        }
        echo json_encode($value);
    }
}