<?php
require APPPATH . 'libraries/REST_Controller.php';

class Orders extends REST_Controller {
   
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table= 'orders';
		$this->orders_details_table = 'order_details';
		$this->products_table = 'products';
        $this->metrics_table = 'product_metrics';
        $this->model_name='orders_model';   
        $this->load->model($this->model_name, "", true);  
        $this->load->library('excelvalidation');
        $this->lang->load('response', 'english');
    }       

    /**

     * Get All Data from this method.

     *

     * @return Response

    */

	// public function index_get($id = 0)
    // {
    //   $message = "success";
    //   $data=array();
    //   if(!empty($id)){
    //      $data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
    //   }else{       
    //      $data = $this->Mydb->do_search($this->table,$this->model_name);
    //   }
    //   if(!empty($data)){
    //      $value  = withSuccess($message,$data);
    //   }else{
    //      $value  = withSuccess($this->lang->line('no_result_found'));
    //   }
      
    //   $this->response($value, REST_Controller::HTTP_OK);
    // }

	// public function order_details_get(){
	// 	$message = "success";
    //   	$data=array();
	// 	$orderId = $this->get("orderId");
	// 	if(!empty($orderId)){
	// 		$data = $this->orders_model->order_details($orderId);
	// 		if(!empty($data)){
	// 			$value  = withSuccess($message,array("details" => $data));
	// 		}else{
	// 			$value  = withSuccess($this->lang->line('no_result_found'));
	// 		}
	// 	}else{
	// 		$value  = withSuccess($this->lang->line('Order Not Found'));
	// 	}
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }


	public function index_get($id = 0)
    {
      $message = "success";
      $data=array();
      if(!empty($id)){
         $data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
         if(!empty($data['details'])){
            $orderDetails = $this->order_details($data['details']['id']);
            $data['details']['order_details'] = $orderDetails;
            $data['details']['total_items'] = count($orderDetails);
         }
      }else{       
         $data = $this->Mydb->do_search($this->table,$this->model_name);
         if(!empty($data['data_list'])){
            foreach($data['data_list'] as $key=>$value){
                $orderDetails = $this->order_details($value->id);
                $data['data_list'][$key]->order_details = $orderDetails;
                $data['data_list'][$key]->total_items = count($orderDetails);
            }
         }
      }
      if(!empty($data)){
         $value  = withSuccess($message,$data);
      }else{
         $value  = withSuccess($this->lang->line('no_result_found'));
      }
      $this->response($value, REST_Controller::HTTP_OK);
    }


    function order_details($id){
        $q = "SELECT $this->orders_details_table.*, $this->products_table.name as product_name, $this->products_table.product_image, $this->metrics_table.unit as product_unit, $this->metrics_table.mrp as product_mrp, $this->metrics_table.quantity as product_quantity, $this->metrics_table.price as product_price, ((product_metrics.CGST + product_metrics.SGST)/100)*product_metrics.price as gst_amount FROM $this->orders_details_table
        INNER JOIN $this->products_table ON $this->orders_details_table.productId = $this->products_table.id
        INNER JOIN $this->metrics_table ON $this->orders_details_table.metricsId = $this->metrics_table.id
        WHERE $this->orders_details_table.orderId = $id
        ";
        // print_R($q);exit();
        $result = $this->db->query($q)->result_array();
        if(!empty($result)){
            return $result;
        }else{
            return [];
        }
    }

	public function product_details_get(){
		$message = "success";
      	$data=array();
		$orderId = $this->get("orderId");
		if(!empty($orderId)){
			$data = $this->orders_model->product_details($orderId);
			if(!empty($data)){
				$value  = withSuccess($message,array("details" => $data));
			}else{
				$value  = withSuccess($this->lang->line('no_result_found'));
			}
		}else{
			$value  = withSuccess($this->lang->line('Order Not Found'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}
   
   
    /**

     * Insert data from this method.

     *

     * @return Response

    */

    // public function index_post()

    // {

    //     $input = $this->input->post();
	// 	// print_R($input);exit();
		
	// 		$rules = [
	// 		'addressId' => ['Address','required']
	// 		];

	// 	Validator::make($rules);
	// 	 if (!Validator::fails()) {
    //          Validator::error();
    //     } else {
			
	// 		$data = array(	
	// 		'shippingAddressId' =>$input['addressId'],
	// 		'customerId'=>$input['userId'],
	// 		'totalAmount'=>$input['orderTotal'],
	// 		'createdDate'=>$input['createdDate'],
	// 		'totalAmount'=>$input['orderTotal'],
	// 		'status'=>4
	// 	);
	// 	$data['orderId'] = generate_order_id();
	// 	$id = $this->Mydb->insert_table_data('orders',$data);
	// 	$result['details'] = $this->get_single_result($id);
	// 	$value  = withSuccess($this->lang->line('role_created_success'),$result);
    //     $this->response($value, REST_Controller::HTTP_OK);
	// 	}	
		

    // } 


	public function index_post()
    {
        $input = $this->input->post();
        $userId = $input['userId'];
		$deliveryCharge = !empty($input['deliveryCharge'])?$input['$deliveryCharge']:0;
        $deliveryAddress = !empty($input['selectedAddress'])?$input['selectedAddress']:'';
        $deliveryAddressLatLong = !empty($input['selectedAddressLatLong'])?$input['selectedAddressLatLong']:'';
        $shippingAddress = '';
        if(!empty($deliveryAddress)){
            $addrRow = $this->db->get_where('shipping_address', array('id'=>$deliveryAddress))->row_array();
            if(!empty($addrRow)){
                $shippingAddress = $addrRow['name'].', '.$addrRow['phone'].', '.$addrRow['apartment'].', '.$addrRow['address'].' - '.$addrRow['postalCode'].', '.$addrRow['city'].', '.$addrRow['state'].', '.$addrRow['country'];
            }else{
                $value  = withErrors("Address Not Found, Please add address to continue");
                $this->response($value, REST_Controller::HTTP_OK);
            }
        }else{
            if(isset($input['userPrimaryAddress'])){
                $name = $input['userPrimaryAddress']['name'];
                $phone = $input['userPrimaryAddress']['phone'];
                $customerId = $input['userPrimaryAddress']['customerId'];
                $apartment = $input['userPrimaryAddress']['apartment'];
                $address = $input['userPrimaryAddress']['address'];
                $city = $input['userPrimaryAddress']['city'];
                $state = $input['userPrimaryAddress']['state'];
                $postalCode = $input['userPrimaryAddress']['postalCode'];
                $country = $input['userPrimaryAddress']['country'];
                //adding the shipping address and making it primary
                $insertShippingAddress = $this->db->insert('shipping_address', array('name'=>$name, 'phone'=>$phone, 'customerId'=>$customerId, 'postalCode'=>$postalCode, 'address'=>$address, 'city'=>$city, 'state'=>$state, 'country'=>$country, 'apartment'=>$apartment));
                $shippingAddress = $name.', '.$phone.', '.$apartment.', '.$address.' - '.$postalCode.', '.$city.', '.$state.', '.$country;
            }else{
                $value  = withErrors("Address Not Found, Please add address to continue");
                $this->response($value, REST_Controller::HTTP_OK);
            }
        }
        $totalCartAmount = $input['cartAmount'];
        
        //checkingUserData
        $userRow = $this->db->get_where('customers', array('id'=>$userId))->row_array();
        if(empty($userRow)){
            $value = withErrors('Unable to Authenticate, Please logout and login again to continue');
            $this->response($value, REST_Controller::HTTP_OK);
            return;
        }
		if(is_array($input['selectedProducts'])){
			$selectedProducts = $input['selectedProducts'];
		}else{
			$selectedProducts = json_decode($input['selectedProducts'], true);
		}
        // print_R($selectedProducts);exit();
        if(empty($selectedProducts)){
            $value  = withSuccess("No Products are selected, Are you sure you've added the products");
            $this->response($value, REST_Controller::HTTP_OK);
            return;
        }
        if(empty($userId)){
            $value  = withSuccess("Unable to Authenticate, Please logout and login again to continue");
            $this->response($value, REST_Controller::HTTP_OK);
            return;
        }
        $orderId = generate_order_id();
        // print_R($shippingAddress);exit();
        $insert = $this->db->insert($this->table, array('orderId'=>$orderId, 'deliveryAddress'=>$shippingAddress, 'latlong'=>$deliveryAddressLatLong, 'customerId'=>$userId, 'createdDate'=>cur_date_time()));
        // print_R($this->db->last_query());exit();
        $taxAmount = $input['orderTax'];
        if($insert){
            // write logic
            $id=$this->db->insert_id();
            foreach($selectedProducts as $product){
				if(!empty($product['quantity'])){
					$count = $product['quantity'];
				}else{
					$count = 1;
				}
				$fetchMetricsTableData = $this->db->get_where('product_metrics', array('id'=>$product['metricsID']))->row_array();
                $productPrice = $fetchMetricsTableData['price'];
                $productAmount = $count*$productPrice;

                $pData = array(
                    'productId'=>$product['product_id'],
                    'count'=>$count,
                    'metricsId'=>$product['metricsID'],
                    'orderId'=>$id,
                    'totalAmount'=>$productAmount,
                    'createdDate'=>cur_date_time()
                );
                $updateOrderDetails = $this->db->insert($this->orders_details_table, $pData);
            }//exit();
            //checking if the customer Has the Subscription Amount
            // $cehckSubscription = $this->db->get_where('')
            $plansId = $userRow['planId'];
            $subscriptionAmount = 0;
            $subscriptionWalletsAmount = 0;
    
            if(!empty($plansId)){
                $subscriptionAmount = $userRow['subscriptionAmount'];
                $subscriptionWalletsAmount = $userRow['subscriptionPoints'];
            }
            $deductedSubscriptionAmount = 0;
            $deductedSubscriptionWalletPointsAmount = 0;

            
            $actualAmountToPay = $totalCartAmount+$deliveryCharge;
            $actualAmountToPay += $taxAmount;
            if($subscriptionAmount>0){
                if($subscriptionAmount>=$totalCartAmount){
                    $actualAmountToPay = 0;
                    $subscriptionAmount -= $totalCartAmount;
                    $deductedSubscriptionAmount = $totalCartAmount;
                }else{ 
                    $actualAmountToPay -= $subscriptionAmount;
                    $subscriptionAmount = 0;
                    $deductedSubscriptionAmount = $totalCartAmount-$actualAmountToPay;
                }
                //updating the subscriptionAmount after subtracting
            }else{
                if($subscriptionWalletsAmount>0 && $subscriptionWalletsAmount>=100){
                    $tenOfCartAmount = $totalCartAmount * 0.1;
                    $subscriptionWalletsAmount -= $tenOfCartAmount;
                    $actualAmountToPay = $totalCartAmount - $tenOfCartAmount;
                    $deductedSubscriptionWalletPointsAmount = $tenOfCartAmount;
                    // $deductedSubscriptionWalletPointsAmount = 100;
                }
            }
            //updating the subscriptionAmount
            $this->db->update('customers', array('subscriptionAmount'=>$subscriptionAmount, 'subscriptionPoints'=>$subscriptionWalletsAmount), array('id'=>$userId));
            //updating the payable amount in the order
            $this->db->update('orders', array('actualAmountToPay'=>$actualAmountToPay, 'status'=>25, 'totalAmount'=>$totalCartAmount, 'deliveryCharge'=>$deliveryCharge, 'deliveryAddress'=>$shippingAddress, 'deductedSubscriptionAmount'=>$deductedSubscriptionAmount, 'deductedSubscriptionWalletPointsAmount'=>$deductedSubscriptionWalletPointsAmount, 'taxAmount'=>$taxAmount), array('id'=>$id));
            //Get Order Data & Return
            $orderRow = $this->db->get_where('orders', array('id'=>$id))->row_array();
            $value = withSuccess('Order Created Successfully', array('data_list'=>$orderRow));
            // print_r($value);exit();
        }else{
            $value = withErrors('Order Couldn\'t be created');
        }
        $this->response($value, REST_Controller::HTTP_OK);

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

		if(!empty($input['delivery_by'])){
			$rules['delivery_by'] = ['Delivery By','required'];
			$data['delivery_by'] = $input['delivery_by'];
		}
		if(!empty($input['payment_status'])){
			$rules['payment_status'] = ['Payment Status','required'];
			$data['paymentStatus'] = $input['payment_status'];
		}
		if(!empty($input['status'])){
			$rules['status'] = ['Status','required'];
			$data['status'] = $input['status'];
		}
		if(!empty($input['total_amount'])){
			$rules['total_amount'] = ['Total Amount','required'];
			$data['total_amount'] = $input['total_amount'];
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
        if(!empty($input['comments'])){
			$data['comments'] = $input['comments'];
		}
        if(!empty($input['deliveredDate'])){
			$data['deliveredDate'] = $input['deliveredDate'];
		}
		$data['updatedDate'] = cur_date_time();
        // print_r($data);exit();
		$is_update = $this->Mydb->update_table_data('orders', array('id'=>$id), $data);
        // print_r($is_update);exit();
		$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if($is_update>0){
			$value  = withSuccess('Orders updated successfully',$result);
		}else{
			$value  = withErrors($this->lang->line('failed_to_update'),$result);
		}       
		$this->response($value, REST_Controller::HTTP_OK);
    }

    

    /**

     * Delete data from this method.

     *

     * @return Response

    */

    public function index_delete($id)
    {
		$data = $this->db->get_where("users", ['roles_id' => $id])->row_array();
		if(empty($data)){
			$data = $this->get_single_result($id);
			$res = $this->Mydb->delete_table_data('roles', array('id'=>$id));	
			if ($res == 1){
				$result = array('details'=>$data);
				$value  = withSuccess($this->lang->line('role_deleted_success'),$result);
			}else if ($res == - 1451){
				$value = withErrors($this->lang->line('failed_to_delete'));
			}else{
				$value = withErrors($this->lang->line('failed_to_delete'));
			}
		}else{
			$value = withErrors('Since role is allocated to user(s), you cannot delete!');
		}
		$this->response($value, REST_Controller::HTTP_OK);

    }
	
	/**
	
	* Search data from this method.
	
	*
	
	* @return Response
	
	*/

	 public function search()

    {
		$param_default = array('search','perpage','page','sortby','orderby');
		$parameters = $this->input->get();
		$diff = array();
		$data = array();
		$data['data_list'] = array();	
		$search = "";
		$perpage = 10;
		$page = 1;
		$sortby = "roles.id";
		$orderby = "DESC";
		$all = false;
		$data['slno'] ='';
		
		if(!empty($parameters)){
				$parem_key = array_keys($parameters);
				$diff = array_diff($parem_key,$param_default);
				$intersect = array_intersect($parem_key,$param_default);
		}
		
		if(array_key_exists('page',$parameters)){
			$all = false;
		}
		
		if(!empty($intersect)){
			foreach($intersect as $inst){	
				$rml =  str_replace('-','.',$parameters[$inst]);
				$$inst = $rml;
			}
		}
	

		$filter_data[0]['type'] = 'search'; $filter_data[0]['value'] = $search;
		
		if(!empty($diff)){
			$i = count($filter_data);
			foreach($diff as $p){
				if(!empty($this->input->get($p))){
					$pa = str_replace('-','.',$p);
					$filter_data[$i]['type'] = $pa;
					$filter_data[$i]['value'] = $this->input->get($p);
				}
				$i++;
			}
		}

		
		$total_rows = $this->rolesmodel->filter($filter_data, 0, 0,$sortby,$orderby,$all);	   
		$udata = $this->Mydb->getPaginationData($perpage, $page, $total_rows);
		$data = array_merge($data,$udata);			
		$getData = $this->rolesmodel->filter($filter_data, $perpage, $data['page_position'],$sortby,$orderby,$all);			
		$data['pagination'] = $this->Mydb->paginate_function($perpage, $page, $total_rows, $data['total_pages']);
		
		
        if ($getData->num_rows() > 0) {            
           foreach ($getData->result() as $row) {
                $data['data_list'][] = $row;
				if ($page == 1) {
	                $data['slno'] = 1;
	            } else {
	                $data['slno'] = (($page - 1) * $perpage) + 1;
	        	}
		   }
			}
		if($all){
			array_splice($data,1);
		}
		//print_r($data);
		return $data;
		
    }
    	
}