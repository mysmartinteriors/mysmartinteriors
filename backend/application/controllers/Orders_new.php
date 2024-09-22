<?php
require APPPATH . 'libraries/REST_Controller.php';

class Orders_new extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = 'orders';
        $this->orders_details_table = 'order_details';
        $this->products_table = 'products';
        $this->metrics_table = 'product_metrics';
        $this->model_name = 'orders_model_new';
        $this->load->model($this->model_name, "", true);
        $this->load->library('excelvalidation');
        $this->lang->load('response', 'english');
        $this->lookups_table = 'lookups';
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
                $orderDetails = $this->order_details($data['details']['id']);
                $data['details']['order_details'] = $orderDetails;
                $data['details']['total_items'] = count($orderDetails);
            }
        } else {
            $data = $this->Mydb->do_search($this->table, $this->model_name);
            if (!empty($data['data_list'])) {
                foreach ($data['data_list'] as $key => $value) {
                    $orderDetails = $this->order_details($value->id);
                    $data['data_list'][$key]->order_details = $orderDetails;
                    $data['data_list'][$key]->total_items = count($orderDetails);
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
    // INNER JOIN $this->lookups_table ON $this->order

    // function order_details($id){ 
    //     $q = "SELECT $this->orders_details_table.*, $this->products_table.name as product_name, $this->products_table.product_image, $this->metrics_table.unit as product_unit, $this->metrics_table.mrp as product_mrp, $this->metrics_table.quantity as product_quantity, $this->metrics_table.price as product_price FROM $this->orders_details_table
    //     INNER JOIN $this->products_table ON $this->orders_details_table.productId = $this->products_table.id
    //     INNER JOIN $this->metrics_table ON $this->orders_details_table.metricsId = $this->metrics_table.id
    //     WHERE $this->orders_details_table.orderId = $id
    //     ";
    //     $result = $this->db->query($q)->result_array();
    //     if(!empty($result)){
    //         return $result;
    //     }else{
    //         return [];
    //     }
    // }

    function order_details($id)
    {
        // $q = "SELECT $this->orders_details_table.*, 
        // $this->products_table.name as product_name, 
        // $this->products_table.product_image, 
        // (($this->order_details_table.cgst + $this->order_details_table.sgst)/100)*product_metrics.price as gst_amount 
        // FROM $this->orders_details_table
        // INNER JOIN $this->products_table ON $this->orders_details_table.productId = $this->products_table.id
        // WHERE $this->orders_details_table.orderId = $id
        // ";
        $q = "SELECT $this->orders_details_table.*, 
        $this->products_table.name as product_name, 
        $this->products_table.product_image, 
        (($this->orders_details_table.cgst + $this->orders_details_table.sgst)/100) * $this->orders_details_table.price as gst_amount 
      FROM $this->orders_details_table
      INNER JOIN $this->products_table 
        ON $this->orders_details_table.productId = $this->products_table.id
      WHERE $this->orders_details_table.orderId = $id";
        // -- INNER JOIN $this->metrics_table ON $this->orders_details_table.metricsId = $this->metrics_table.id
        // -- $this->metrics_table.unit as product_unit, 
        // -- $this->metrics_table.mrp as product_mrp, 
        // -- $this->metrics_table.quantity as product_quantity, 
        // -- $this->metrics_table.price as product_price, 
        // print_R($q);exit();
        $result = $this->db->query($q)->result_array();
        if (!empty($result)) {
            return $result;
        } else {
            return [];
        }
    }

    /**

     * Insert Data from this method.

     *

     * @return Response

    */

    public function index_post()
    {
        $raw_input_stream = $this->input->raw_input_stream;
        $input = array();
        if (!empty($raw_input_stream)) {
            $input = json_decode($raw_input_stream, true);
        }
        if (empty($input)) {
            $value = withErrors('Invalid Request, Please try again.');
            $this->response($value, REST_Controller::HTTP_OK);
        }
        $pay_status = '';
        $dbPaymentDetails = [];
        $userId = $input['userId'];
        $decodePaymentDetails = $input['paymentDetails'];
        $paymentMethod = $input['payment_method'];
        if ($paymentMethod == 'pay_online' && !empty($input['paymentDetails'])) {
            $dbPaymentDetails['pResponseStatus'] = $decodePaymentDetails['status'];
            $dbPaymentDetails['pCfPaymentId'] = $decodePaymentDetails['cf_payment_id'];
            $dbPaymentDetails['reference_table'] = 'orders';
            $dbPaymentDetails['pEntity'] = $decodePaymentDetails['entity'];
            $dbPaymentDetails['pIsCaptured'] = $decodePaymentDetails['is_captured'];
            $dbPaymentDetails['pOrderId'] = $decodePaymentDetails['order_id'];
            $dbPaymentDetails['pOrderAmount'] = $decodePaymentDetails['order_amount'];
            $dbPaymentDetails['paymentGroup'] = $decodePaymentDetails['payment_group'];
            $dbPaymentDetails['paymentCurrency'] = $decodePaymentDetails['payment_currency'];
            $dbPaymentDetails['paymentAmount'] = $decodePaymentDetails['payment_amount'];
            $dbPaymentDetails['paymentTime'] = $decodePaymentDetails['payment_time'];
            $dbPaymentDetails['paymentCompletedTime'] = $decodePaymentDetails['payment_completion_time'];
            $dbPaymentDetails['paymentStatus'] = $decodePaymentDetails['payment_status'];
            $dbPaymentDetails['paymentMessage'] = $decodePaymentDetails['payment_message'];
            $dbPaymentDetails['paymentMethod'] = json_encode($decodePaymentDetails['payment_method']);
            if ($decodePaymentDetails['status'] == 'success' && $decodePaymentDetails['payment_status'] == 'SUCCESS') {
                $pay_status = 1;
            }
        }

        $deliveryAddress = !empty($input['selectedAddress']) ? $input['selectedAddress'] : '';
        $deliveryAddressLatLong = !empty($input['selectedAddressLatLong']) ? $input['selectedAddressLatLong'] : '';
        $deductWalletAmount = $input['deduct_wallet_amount'];
        $deductWalletBonus = $input['deduct_wallet_bonus'];
        $deliveryDate = !empty($input['deliveryDate']) ? $input['deliveryDate'] : '';
        $shippingAddress = '';
        $shippingAddressDetails = '';
        $selectedProducts = $input['selectedProducts'];
        // print_R($selectedProducts);exit();
        if (empty($selectedProducts)) {
            $value = withErrors('No products found for the order');
            $this->response($value, REST_Controller::HTTP_OK);
        }
        if (!empty($deliveryAddress)) {
            $addrRow = $this->db->get_where('shipping_address', array('id' => $deliveryAddress))->row_array();
            if (!empty($addrRow)) {
                $shippingAddressDetails = $addrRow['name'] . ', ' . $addrRow['phone'] . ', ' . $addrRow['apartment'] . ', ' . $addrRow['address'] . ' - ' . $addrRow['postalCode'] . ', ' . $addrRow['city'] . ', ' . $addrRow['state'] . ', ' . $addrRow['country'];
                $shippingAddress = $addrRow['apartment'] . ', ' . $addrRow['address'] . ' - ' . $addrRow['postalCode'] . ', ' . $addrRow['city'] . ', ' . $addrRow['state'] . ', ' . $addrRow['country'];
            } else {
                $value = withErrors("Address Not Found, Please add address to continue");
                $this->response($value, REST_Controller::HTTP_OK);
            }
        } else {
            if (isset($input['userPrimaryAddress'])) {
                // print_R($input['userPrimaryAddress']);exit();
                $name = $input['userPrimaryAddress']['name'];
                $phone = $input['userPrimaryAddress']['phone'];
                $customerId = $input['userPrimaryAddress']['customerId'];
                $apartment = isset($input['userPrimaryAddress']['apartment']) ? $input['userPrimaryAddress']['apartment'] : '';
                $address = $input['userPrimaryAddress']['address'];
                $city = $input['userPrimaryAddress']['city'];
                $state = $input['userPrimaryAddress']['state'];
                $postalCode = $input['userPrimaryAddress']['postalCode'];
                $country = $input['userPrimaryAddress']['country'];
                $insertShippingAddress = $this->db->insert('shipping_address', array('name' => $name, 'phone' => $phone, 'customerId' => $customerId, 'postalCode' => $postalCode, 'address' => $address, 'city' => $city, 'state' => $state, 'country' => $country, 'apartment' => $apartment));
                $shippingAddress = '';
                if (!empty($apartment)) {
                    $shippingAddress .= $apartment;
                }
                if (!empty($address)) {
                    if (!empty($shippingAddress)) {
                        $shippingAddress .= ', ' . $address;
                    } else {
                        $shippingAddress .= $address;
                    }
                }
                if (!empty($postalCode)) {
                    $shippingAddress .= ' - ' . $postalCode;
                }
                if (!empty($city)) {
                    $shippingAddress .= ', ' . $city;
                }
                if (!empty($state)) {
                    $shippingAddress .= ', ' . $state;
                }
                if (!empty($country)) {
                    $shippingAddress .= ', ' . $country;
                }

                $shippingAddressDetails = '';
                if (!empty($phone)) {
                    $shippingAddressDetails = $phone . ', ' . $shippingAddress;
                }
                if (!empty($name)) {
                    $shippingAddressDetails = $name . ', ' . $shippingAddressDetails;
                }
                // $shippingAddress = $apartment . ', ' . $address . ' - ' . $postalCode . ', ' . $city . ', ' . $state . ', ' . $country;
                // $shippingAddressDetails = $name . ', ' . $phone . ', ' . $apartment . ', ' . $address . ' - ' . $postalCode . ', ' . $city . ', ' . $state . ', ' . $country;
            } else {
                $value = withErrors("Address Not Found, Please add address to continue");
                $this->response($value, REST_Controller::HTTP_OK);
            }
        }
        //checkingUserData
        $userRow = $this->db->get_where('customers', array('id' => $userId))->row_array();
        if (empty($userRow)) {
            $value = withErrors('Unable to Authenticate, Please logout and login again to continue');
            $this->response($value, REST_Controller::HTTP_OK);
            return;
        }
        // $cartDetails = 
        $cartDetails = $input['cartDetails'];
        // $cartDetails = $this->cart_model->get_cart_total($userId);
        if (empty($userId)) {
            $value = withSuccess("Unable to Authenticate, Please logout and login again to continue");
            $this->response($value, REST_Controller::HTTP_OK);
            return;
        }
        $orderId = $this->generate_order();
        $insert = $this->db->insert($this->table, array('orderId' => $orderId, 'latlong' => $deliveryAddressLatLong, 'deliveryAddress' => $shippingAddress, 'deliveryAddressDetails' => $shippingAddressDetails, 'customerId' => $userId, 'createdDate' => cur_date_time(), 'paymentMethod' => $paymentMethod));
        $id = $this->db->insert_id();
        log_message('error', 'Printing Selected Products');
        log_message('error', print_R($selectedProducts, true));
        if ($insert) {
            foreach ($selectedProducts as $product) {
                $findProductMetricsData = $this->db->get_where('product_metrics', array('productId' => $product['product_id'], 'id'=>$product['selectedMetricsId']))->row_array();
                // print_R($findProductMetricsData);exit();
                $productPrice = 0; 
                if (!empty($findProductMetricsData)) {
                    $productPrice = $findProductMetricsData['price'];
                    $pData = array(
                        'productId' => $product['product_id'],
                        'count' => $product['count'],
                        'price' => $findProductMetricsData['price'], 
                        'quantity'=>$findProductMetricsData['quantity'],
                        'unit'=>$findProductMetricsData['unit'],
                        'mrp'=>$findProductMetricsData['mrp'],
                        'cgst'=>$findProductMetricsData['CGST'],
                        'sgst'=>$findProductMetricsData['SGST'],
                        'metricsId' => $product['selectedMetricsId'],
                        'orderId' => $id,
                        'totalAmount' => ($product['count'] * $productPrice),
                        'createdDate' => cur_date_time() 
                    );
                    log_message('error', 'Printing OrderedProduct Data');
                    log_message('error', print_R($pData, true));
                    $updateOrderDetails = $this->db->insert($this->orders_details_table, $pData);
                }
            }
            $updateWalletPoints = $this->db->update('customers', array('subscriptionPoints' => $cartDetails['remainingWalletPoints']), array('id' => $userId));
            if ($deductWalletAmount == 'yes') {
                $this->db->update('customers', array('subscriptionAmount' => $cartDetails['remainingWalletAmount']), array('id' => $userId));
                $actualAmountToPay = $cartDetails['totalWithWalletAmountDeduction'];
                $deductedWalletAMount = $cartDetails['availableWalletAmountDeduction'];
                $repurchaseComissionAmount = 0;
                $deductedWalletPoints = 0;
            } else if ($deductWalletBonus == 'yes') {
                $actualAmountToPay = $cartDetails['totalWithTaxAndDeliveryAndDiscount'];
                $deductedWalletAMount = 0;
                $deductedWalletPoints = $cartDetails['walletPointsDiscounts'];
                $repurchaseComissionAmount = 0;
            } else {
                $actualAmountToPay = $cartDetails['productTotalWithTaxAndDelivery'];
                $deductedWalletAMount = 0;
                $deductedWalletPoints = 0;
                $repurchaseComissionAmount = $cartDetails['repurchaseComission'];
            }
            if ($actualAmountToPay == 0) {
                $pay_status = 1;
            }
            // log_message('error', 'Printing cart details');
            // log_message('error', print_R($cartDetails, true));

            //updating the payable amount in the order
            $this->db->update('orders', array('actualAmountToPay' => $actualAmountToPay, 'status' => 25, 'totalAmount' => $cartDetails['productTotalWithTaxAndDelivery'], 'deliveryCharge' => $cartDetails['deliveryCharge'], 'deliveryAddress' => $shippingAddress, 'deductedSubscriptionAmount' => $deductedWalletAMount, 'deductedSubscriptionWalletPointsAmount' => $deductedWalletPoints, 'taxAmount' => $cartDetails['productTax'], 'deductWallet' => $deductWalletAmount, 'pay_status' => $pay_status, 'repurchaseComissionAmount' => $repurchaseComissionAmount, 'deliveryDate' => $deliveryDate), array('id' => $id));
            //Get Order Data & Return
            if (!empty($decodePaymentDetails) && !empty($decodePaymentDetails['status'])) {
                $dbPaymentDetails['reference_id'] = $id;
                $updatePaymentDetails = $this->db->insert('payment_details', $dbPaymentDetails);
            }
            $orderRow = $this->db->get_where('orders', array('id' => $id))->row_array();
            // $msg = "Dear ".$userRow['firstName'].",\n\nThanks for choosing Nalaa Organic, we have received your order. your order ".$orderId." will be delivered to you soon.\n\nNalaa Organic";
            $msg = "Dear " . $userRow['firstName'] . ",

Thanks for choosing Nalaa Organic, we have received your order. your order with Order ID " . $orderId . " will be delivered to you soon.

Nalaa Organic
Thank You";
            $send_sms = send_sms($userRow['phone'], $msg);
            // if($userRow['phone']){
            //     $send_whatsapp = send_whatsapp($userRow['phone'], $msg);
            // }
            $value = withSuccess('Order Created Successfully', array('data_list' => $orderRow));
        } else {
            $value = withErrors('Order Couldn\'t be created');
        }
        // log_message('error', 'printing order DEtails');
        // log_message('error', print_R($value, true));
        $this->response($value, REST_Controller::HTTP_OK);
    }



    function generate_order()
    {
        $orderData = $this->db->get('orders')->num_rows();
        $orderCode = "NALAA-";
        if ($orderData < 1) {
            $oCount = 1;
        } else {
            $oCount = $orderData + 1;
        }
        $orderCode .= $oCount;
        return $orderCode;
    }

    public function index_old_post()
    {
        $raw_input_stream = $this->input->raw_input_stream;
        $input = array();
        if (!empty($raw_input_stream)) {
            $input = json_decode($raw_input_stream, true);
        }
        if (empty($input)) {
            $value = withErrors('Invalid Request, Please try again.');
            $this->response($value, REST_Controller::HTTP_OK);
        }
        $userId = $input['userId'];
        $deliveryCharge = $input['deliveryCharge'];
        $taxAmount = !empty($input['taxAmount']) ? $input['taxAmount'] : 0;
        $deliveryAddress = !empty($input['selectedAddressId']) ? $input['selectedAddressId'] : '';
        $deliveryAddressLatLong = !empty($input['latlong']) ? $input['latlong'] : '';
        $shippingAddress = '';
        if (!empty($deliveryAddress)) {
            $addrRow = $this->db->get_where('shipping_address', array('id' => $deliveryAddress))->row_array();
            if (!empty($addrRow)) {
                $shippingAddress = $addrRow['name'] . ', ' . $addrRow['phone'] . ', ' . $addrRow['address'] . ' - ' . $addrRow['postalCode'] . ', ' . $addrRow['city'] . ', ' . $addrRow['state'] . ', ' . $addrRow['country'];
            } else {
                $value = withErrors("Address Not Found, Please add address to continue");
                $this->response($value, REST_Controller::HTTP_OK);
            }
        } else {
            if (isset($input['userPrimaryAddress'])) {
                $name = $input['userPrimaryAddress']['name'];
                $phone = $input['userPrimaryAddress']['phone'];
                $customerId = $input['userPrimaryAddress']['customerId'];
                $address = $input['userPrimaryAddress']['address'];
                $city = $input['userPrimaryAddress']['city'];
                $state = $input['userPrimaryAddress']['state'];
                $postalCode = $input['userPrimaryAddress']['postalCode'];
                $country = $input['userPrimaryAddress']['country'];
                //adding the shipping address and making it primary
                $checkSavedAddr = $this->db->get_where('shipping_address', array('customerId' => $userId))->result_array();
                $savedPrimaryAddr = 1;
                if (!empty($checkSavedAddr)) {
                    foreach ($checkSavedAddr as $savedAddr) {
                        if ($savedAddr['pri_address'] == 1) {
                            $savedPrimaryAddr = 0;
                            break;
                        }
                    }
                }
                $insertShippingAddress = $this->db->insert('shipping_address', array('name' => $name, 'phone' => $phone, 'customerId' => $customerId, 'postalCode' => $postalCode, 'address' => $address, 'city' => $city, 'state' => $state, 'country' => $country, 'pri_address' => $savedPrimaryAddr));
                $shippingAddress = $name . ', ' . $phone . ', ' . $address . ' - ' . $postalCode . ', ' . $city . ', ' . $state . ', ' . $country;
            } else {
                $value = withErrors('Address Not Found, Please add address to continue');
                $this->response($value, REST_Controller::HTTP_OK);
            }
        }
        $totalCartAmount = $input['cartAmount'];
        //checkingUserData
        $userRow = $this->db->get_where('customers', array('id' => $userId))->row_array();
        if (empty($userRow)) {
            $value = withErrors('Unable to Authenticate, Please logout and login again to continue');
            $this->response($value, REST_Controller::HTTP_OK);
            return;
        }
        $selectedProducts = $input['selectedProducts'];
        if (empty($selectedProducts)) {
            $value = withSuccess("No Products are selected, Are you sure you've added the products");
            $this->response($value, REST_Controller::HTTP_OK);
            return;
        }
        if (empty($userId)) {
            $value = withSuccess("Unable to Authenticate, Please logout and login again to continue");
            $this->response($value, REST_Controller::HTTP_OK);
            return;
        }
        $orderId = generate_order_id();
        $insert = $this->db->insert($this->table, array('orderId' => $orderId, 'deliveryAddress' => $shippingAddress, 'customerId' => $userId, 'createdDate' => cur_date_time()));
        if ($insert) {
            // write logic
            $id = $this->db->insert_id();
            foreach ($selectedProducts as $product) {
                $fetchMetricsTableData = $this->db->get_where('product_metrics', array('id' => $product['selectedMetricsId']))->row_array();
                $productPrice = $fetchMetricsTableData['price'];
                $productAmount = $product['count'] * $productPrice;
                $pData = [
                    'productId' => $product['product_id'],
                    'count' => $product['count'],
                    'metricsId' => $product['selectedMetricsId'],
                    'orderId' => $id,
                    'totalAmount' => $productAmount,
                    'createdDate' => cur_date_time()
                ];
                $updateOrderDetails = $this->db->insert($this->orders_details_table, $pData);
            }
            //checking if the customer Has the Subscription Amount
            $plansId = $userRow['planId'];

            $subscriptionAmount = $userRow['subscriptionAmount'];
            $subscriptionWalletsAmount = $userRow['subscriptionPoints'];

            $deductedSubscriptionAmount = 0;
            $deductedSubscriptionWalletPointsAmount = 0;


            $actualAmountToPay = $totalCartAmount + $deliveryCharge;
            $actualAmountToPay += $taxAmount;

            // $actualAmountToPay += $taxAmount;
            if ($subscriptionAmount > 0) {
                if ($subscriptionAmount >= $actualAmountToPay) {
                    $actualAmountToPay = 0;
                    $subscriptionAmount = $subscriptionAmount - $actualAmountToPay;
                    $deductedSubscriptionAmount = $actualAmountToPay;
                } else {
                    $actualAmountToPay = $actualAmountToPay - $subscriptionAmount;
                    $deductedSubscriptionAmount = $subscriptionAmount;
                    $subscriptionAmount = 0;
                }
                //updating the subscriptionAmount after subtracting
            } else {
                if ($subscriptionWalletsAmount >= 100) {
                    $tenOfCartAmount = $totalCartAmount * 0.1;
                    $subscriptionWalletsAmount -= $tenOfCartAmount;
                    $actualAmountToPay = $totalCartAmount - $tenOfCartAmount;
                    $deductedSubscriptionWalletPointsAmount = $tenOfCartAmount;
                    // $deductedSubscriptionWalletPointsAmount = 100;
                }
            }
            $paymentUrl = '';
            $paymentCode = '';
            // if($input['paymentMethod']=='payNow' && $actualAmountToPay>0){
            //     //generate the Interface URL and respond back the the client App
            //     $interfaceUrl = $this->db->get_where('web_settings', array('data_type'=>'interface_url'))->row_array();
            //     $paymentCode = generate_order_token();
            //     if(!empty($interfaceUrl)){
            //         $paymentUrl = $interfaceUrl.'checkout/payorderfee?oId='.$id.'&paymentCode='.$paymentCode;
            //     }
            // }
            //updating the subscriptionAmount
            $this->db->update('customers', array('subscriptionAmount' => $subscriptionAmount, 'subscriptionPoints' => $subscriptionWalletsAmount), array('id' => $userId));
            //updating the payable amount in the order
            $this->db->update('orders', array('actualAmountToPay' => $actualAmountToPay, 'status' => 25, 'latlong' => $deliveryAddressLatLong, 'totalAmount' => $totalCartAmount, 'deliveryCharge' => $deliveryCharge, 'deductedSubscriptionAmount' => $deductedSubscriptionAmount, 'deductedSubscriptionWalletPointsAmount' => $deductedSubscriptionWalletPointsAmount, 'taxAmount' => $taxAmount, 'payment_code' => $paymentCode, 'paymentMethod' => $input['paymentMethod']), array('id' => $id));

            //Get Order Data & Return
            $orderRow = $this->db->get_where('orders', array('id' => $id))->row_array();
            $value = withSuccess('Order Created Successfully', array('data_list' => $orderRow, 'payment_url' => $paymentUrl));
        } else {
            $value = withErrors('Order Couldn\'t be created');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }
    /**

     * Insert Data from this method.

     *

     * @return Response

    */
    public function index_put($id)
    {
        $raw_input_stream = $this->input->raw_input_stream;
        $input = array();
        if (!empty($raw_input_stream)) {
            $input = json_decode($raw_input_stream, true);
        }
        if (!empty($input)) {
            $orderData = $this->db->get_where('orders', array('id' => $id))->row_array();
            if (empty($orderData)) {
                $value = withErrors('No Data Found');
                $this->response($value, REST_Controller::HTTP_OK);
            }
            $update = $this->db->update('orders', $input, array('id' => $id));
            if ($update) {
                $updatedData = $this->db->get_where('orders', array('id' => $id))->row_array();
                $value = withSuccess('Update Success', $updatedData);
                $this->response($value, REST_Controller::HTTP_OK);
            } else {
                $value = withErrors('Update Failed');
                $this->response($value, REST_Controller::HTTP_OK);
            }
        } else {
            $value = withErrors('Invalid Data');
            $this->response($value, REST_Controller::HTTP_OK);
        }
    }
}