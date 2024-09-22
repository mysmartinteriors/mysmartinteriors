<?php
require APPPATH . 'libraries/REST_Controller.php';
require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Cashfree\Cashfree;
use Cashfree\Model\CreateOrderRequest;
use Cashfree\Model\CustomerDetails;
use Cashfree\Model\OrderMeta;

class Orders extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = 'orders';
        $this->cart_table = 'shopping_cart';
        $this->orders_details_table = 'order_details';
        $this->orders_table = 'orders';
        $this->lookups_table = 'lookups';
        $this->products_table = 'products';
        $this->metrics_table = 'product_metrics';
        $this->customers_table = 'customers';
        $this->delivery_table = "delivery";
        $this->model_name = 'orders_model';
        $this->load->model($this->model_name, "", true);
        $this->cart_model_name = 'cart_model';
        $this->load->model($this->cart_model_name, "", true);
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
            if (!empty($data['details'])) {
                $orderDetails = $this->order_details($data['details']['id']);
                // print_R($orderDetails);exit();
                $paymentDetails = $this->payment_details($data['details']['id']);
                $data['details']['order_details'] = $orderDetails;
                $data['details']['total_items'] = count($orderDetails);
                $data['details']['payment_details'] = $paymentDetails;
            }
        } else {
            $data = $this->Mydb->do_search($this->table, $this->model_name);
            if (!empty($data['data_list'])) {
                foreach ($data['data_list'] as $key => $value) {
                    $orderDetails = $this->order_details($value->id); 
                    $paymentDetails = $this->payment_details($value->id);
                    $data['data_list'][$key]->order_details = $orderDetails;
                    $data['data_list'][$key]->total_items = count($orderDetails);
                    $data['data_list'][$key]->payment_details = $paymentDetails;
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

    function payment_details($id){
        $row = $this->db->get_where('payment_details', ['reference_table'=>'orders', 'reference_id'=>$id])->row_array();
        return $row;
    }


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

    public function product_details_get()
    {
        $message = "success";
        $data = array();
        $orderId = $this->get("orderId");
        if (!empty($orderId)) {
            $data = $this->orders_model->product_details($orderId);
            if (!empty($data)) {
                $value = withSuccess($message, array("details" => $data));
            } else {
                $value = withSuccess($this->lang->line('no_result_found'));
            }
        } else {
            $value = withSuccess($this->lang->line('Order Not Found'));
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
        $userId = $input['userId'];
        $paymentMethod = $input['payment_method'];
        $dbPaymentDetails = [];
        $pay_status = '';
        if($paymentMethod=='pay_online' && !empty($input['paymentDetails'])){
            $decodePaymentDetails = json_decode($input['paymentDetails'], true);
            $dbPaymentDetails['pResponseStatus'] = $decodePaymentDetails['status'];
            $dbPaymentDetails['pCfPaymentId'] = $decodePaymentDetails['cf_payment_id'];
            $dbPaymentDetails['reference_table'] = 'orders';
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
            $dbPaymentDetails['pOrderId'] = $decodePaymentDetails['order_id'];
            $dbPaymentDetails['paymentMethod'] = json_encode($decodePaymentDetails['payment_method']);

            if($decodePaymentDetails['status']=='success' && $decodePaymentDetails['payment_status']=='SUCCESS'){
                $pay_status = 1;
            }
        }
        $deliveryAddress = !empty($input['selectedAddress']) ? $input['selectedAddress'] : '';
        $deliveryAddressLatLong = !empty($input['selectedAddressLatLong']) ? $input['selectedAddressLatLong'] : '';
        $deductWalletAmount = $input['deduct_wallet_amount'];
        $deductedWalletBonus = $input['deduct_wallet_bonus'];
        $deliveryDate = !empty($input['deliveryDate']) ? $input['deliveryDate'] : '';
        $shippingAddress = '';
        $shippingAddressDetails = '';
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
                $name = $input['userPrimaryAddress']['name'];
                $phone = $input['userPrimaryAddress']['phone'];
                $customerId = $input['userPrimaryAddress']['customerId'];
                $apartment = $input['userPrimaryAddress']['apartment'];
                $address = $input['userPrimaryAddress']['address'];
                $city = $input['userPrimaryAddress']['city'];
                $state = $input['userPrimaryAddress']['state'];
                $postalCode = $input['userPrimaryAddress']['postalCode'];
                $country = $input['userPrimaryAddress']['country'];
                $insertShippingAddress = $this->db->insert('shipping_address', array('name' => $name, 'phone' => $phone, 'customerId' => $customerId, 'postalCode' => $postalCode, 'address' => $address, 'city' => $city, 'state' => $state, 'country' => $country, 'apartment' => $apartment));
                $shippingAddress = $apartment . ', ' . $address . ' - ' . $postalCode . ', ' . $city . ', ' . $state . ', ' . $country;
                $shippingAddressDetails = $name . ', ' . $phone . ', ' . $apartment . ', ' . $address . ' - ' . $postalCode . ', ' . $city . ', ' . $state . ', ' . $country;
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
        $cartDetails = $this->cart_model->get_cart_total($userId);
        // print_R($cartDetails);exit();
        if (empty($userId)) {
            $value = withSuccess("Unable to Authenticate, Please logout and login again to continue");
            $this->response($value, REST_Controller::HTTP_OK);
            return;
        }
        $orderId = $this->generate_order();
        $insert = $this->db->insert($this->table, array('orderId' => $orderId, 'latlong' => $deliveryAddressLatLong, 'deliveryAddress' => $shippingAddress, 'deliveryAddressDetails' => $shippingAddressDetails, 'customerId' => $userId, 'createdDate' => cur_date_time(), 'paymentMethod' => $paymentMethod));
        $id = $this->db->insert_id();
        if ($insert) {
            $sql = "SELECT shopping_cart.*, product_metrics.price, product_metrics.unit, product_metrics.mrp, product_metrics.cgst, product_metrics.sgst
            FROM shopping_cart
            LEFT JOIN product_metrics ON shopping_cart.metricsId = product_metrics.id
            WHERE customerId='$userId'";
            $selectedProducts = $this->db->query($sql)->result_array();
            if (empty($selectedProducts)) {
                $value = withSuccess("No Products are selected, Are you sure you've added the products"); 
                $this->response($value, REST_Controller::HTTP_OK);
                return;
            }

            // $this->metrics_table.unit as product_unit, 
            // $this->metrics_table.mrp as product_mrp, 
            // $this->metrics_table.quantity as product_quantity, 
            // $this->metrics_table.price as product_price, 
            foreach ($selectedProducts as $product) {
                $pData = array(
                    'productId' => $product['productId'],
                    'count' => $product['quantity'],
                    'price' => $product['price'], 
                    'quantity'=>$product['quantity'],
                    'unit'=>$product['unit'],
                    'mrp'=>$product['mrp'],
                    'cgst'=>$product['cgst'],
                    'sgst'=>$product['sgst'],
                    'metricsId' => $product['metricsId'],
                    'orderId' => $id,
                    'totalAmount' => ($product['quantity'] * $product['price']),
                    'createdDate' => cur_date_time()
                );
                $updateOrderDetails = $this->db->insert($this->orders_details_table, $pData);
            }
            if ($deductWalletAmount == 'yes') {
                $actualAmountToPay = $cartDetails['productAmountAfterWalletAmountDeductionWithoutWalletPoints'];
                $deductedWalletAMount = $cartDetails['availableDeductWalletAmountWithoutWalletPointsDeduction'];
                $repurchaseComissionAmount = 0;
                $deductedWalletPoints = 0;
                $this->db->update('customers', array('subscriptionAmount' => abs($userRow['subscriptionAmount']-$deductedWalletAMount)), array('id' => $userId));
            } else if($deductedWalletBonus=='yes') {
                $actualAmountToPay = $cartDetails['totalWithTaxAndDeliveryAndDiscount'];
                $deductedWalletAMount = 0;
                $repurchaseComissionAmount = 0;
                $deductedWalletPoints = $cartDetails['walletPointsDiscounts'];
                $updateWalletPoints = $this->db->update('customers', array('subscriptionPoints' => abs($userRow['subscriptionPoints'] - $deductedWalletPoints)), array('id' => $userId));
            }else{
                $actualAmountToPay = $cartDetails['productTotalWithTaxAndDelivery'];
                $repurchaseComissionAmount = $cartDetails['repurchaseComission'];
                $deductedWalletAMount = 0;
                $deductedWalletPoints = 0;
            }
            if ($actualAmountToPay == 0) {
                $pay_status = 1;
            }
            $this->db->update('orders', array('actualAmountToPay' => $actualAmountToPay, 'status' => 25, 'totalAmount' => $cartDetails['productTotalWithTaxAndDelivery'], 'deliveryCharge' => $cartDetails['deliveryCharge'], 'deliveryAddress' => $shippingAddress, 'deductedSubscriptionAmount' => $deductedWalletAMount, 'deductedSubscriptionWalletPointsAmount' => $deductedWalletPoints, 'taxAmount' => $cartDetails['productTax'], 'deductWallet' => $deductWalletAmount, 'pay_status' => $pay_status, 'repurchaseComissionAmount' => $repurchaseComissionAmount, 'deliveryDate' => $deliveryDate), array('id' => $id));

            if(!empty($decodePaymentDetails) && !empty($decodePaymentDetails['status'])){
                $dbPaymentDetails['reference_id'] = $id;
                $updatePaymentDetails = $this->db->insert('payment_details', $dbPaymentDetails);
            }
            $orderRow = $this->db->get_where('orders', array('id' => $id))->row_array();
            $msg = "Dear ".$userRow['firstName'].",

            Thanks for choosing Nalaa Organic, we have received your order. your order with Order ID ".$orderId." will be delivered to you soon.
            
            Nalaa Organic
            Thank You";
            if($userRow['phone']){
                $send_sms = send_sms($userRow['phone'], $msg);
            }
            $send_sms = "";
            $value = withSuccess('Order Created Successfully', array('data_list' => $orderRow));
        } else {
            $value = withErrors('Order Couldn\'t be created');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }


    function generate_order() {
        $orderData = $this->db->get('orders')->num_rows();
        $orderCode = "NALAA-";
        if($orderData<1){
            $oCount = 1;
        }else{
            $oCount=$orderData+1;
        }
        $orderCode .= $oCount;
        return $orderCode;
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

        if (!empty($input['delivery_by'])) {
            $rules['delivery_by'] = ['Delivery By', 'required'];
            $data['delivery_by'] = $input['delivery_by'];
        }
        if (!empty($input['payment_status'])) {
            $rules['payment_status'] = ['Payment Status', 'required'];
            $data['paymentStatus'] = $input['payment_status'];
        }
        if (!empty($input['status'])) {
            $rules['status'] = ['Status', 'required'];
            $data['status'] = $input['status'];
        }
        if (!empty($input['total_amount'])) {
            $rules['total_amount'] = ['Total Amount', 'required'];
            $data['total_amount'] = $input['total_amount'];
        }
        if (!empty($input['payment_id'])) {
            $rules['payment_id'] = ['Payment ID', 'required'];
            $data['payment_id'] = $input['payment_id'];
        }
        if (!empty($input['pay_order_id'])) {
            $rules['pay_order_id'] = ['Pay Order ID', 'required'];
            $data['pay_order_id'] = $input['pay_order_id'];
        }
        if (!empty($input['pay_signature'])) {
            $rules['pay_signature'] = ['Payment Signature', 'required'];
            $data['pay_signature'] = $input['pay_signature'];
        }
        if (!empty($input['pay_status'])) {
            $rules['pay_status'] = ['Payment Status', 'required'];
            $data['pay_status'] = $input['pay_status'];
        }
        if (!empty($input['payment_gateway_json'])) {
            $rules['payment_gateway_json'] = ['Payment Gateway JSON', 'required'];
            $data['payment_gateway_json'] = $input['payment_gateway_json'];
        }
        if (!empty($input['pay_date'])) {
            $rules['pay_date'] = ['Payment Gateway JSON', 'required'];
            $data['pay_date'] = $input['pay_date'];
        }

        if (!empty($input['paymentMethod'])) {
            $rules['paymentMethod'] = ['Payment Method is required', 'required'];
            $data['paymentMethod'] = $input['paymentMethod'];
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
        
        // log_message('error', print_r($data, true));

        if (!empty($input['comments'])) {
            $data['comments'] = $input['comments'];
        }
        if (!empty($input['deliveredDate'])) {
            $data['deliveredDate'] = $input['deliveredDate'];
        }
        $orderDetails = $this->db->get_where($this->table, array('id' => $id))->row_array();
        if (!empty($orderDetails)) {
            $deliveryData = array();
            $userdata = $this->db->get_where('customers', array('id'=> $orderDetails['customerId']))->row_array();
            if (!empty($input['delivery_by']) && $input['status'] == 27) {
                $deliveryData = $this->db->get_where('delivery', array('id' => $input['delivery_by']))->row_array();
                if (empty($deliveryData)) {
                    $value = withErrors('Unknown Delivery Assignation');
                    $this->response($value, REST_Controller::HTTP_OK);
                }
            }
            $payStatus = $orderDetails['pay_status'];
            
            if(!empty($userdata) && !empty($userdata['phone']) && (isset($input['status']) && $input['status']==27)) {
                $message = "Dear ".$userdata['firstName']. ' '.$userdata['lastName'].", ".PHP_EOL.PHP_EOL."Your order with the order Id ".$orderDetails['orderId']." is dispatched and will be delivered to you by ".$orderDetails['deliveryDate'].".".PHP_EOL.PHP_EOL."Thank You".PHP_EOL."Nalaa Organic";
                $send_sms = send_sms($userdata['phone'], $message);
            }
            if (isset($input['status']) && $input['status'] == 28) {
                $data['pay_status'] = 1;
                $payStatus = 1;
                $message = "Dear ".$userdata['firstName']. ' '.$userdata['lastName'].", ".PHP_EOL.PHP_EOL."Your order with the order Id ".$orderDetails['orderId']." is delivered to you successfully. ".PHP_EOL.PHP_EOL."Thank you for shopping with us.".PHP_EOL.PHP_EOL."Nalaa organic";
                $send_sms = send_sms($userdata['phone'], $message);
            }

            if(isset($input['status']) && $input['status'] == 26){
                $message = "Dear ".$userdata['firstName']. ' '.$userdata['lastName'].", ".PHP_EOL.PHP_EOL."Your order with the order Id ".$orderDetails['orderId']." is cancelled.".PHP_EOL.PHP_EOL."Thank you. ".PHP_EOL.PHP_EOL."Nalaa organic";
                $send_sms = send_sms($userdata['phone'], $message);
            }
            $data['updatedDate'] = cur_date_time();
            $is_update = $this->db->update('orders', $data, array('id' => $id));
            $result = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
           
            if ($is_update > 0) {
                if ($payStatus == 1 && $input['status'] == 28 && $orderDetails['deductWallet'] == 'no' && $orderDetails['repurchaseComissionAmount'] > 0) {
                    $customerData = $this->db->get_where('customers', array('id' => $orderDetails['customerId']))->row_array();
                    if (!empty($customerData) && !empty($customerData['refered_by'])) {
                        $amount = $orderDetails['repurchaseComissionAmount'];
                        $updateReferenceL1 = $this->update_repurchase_amount($customerData['refered_by'], 1, $amount, $customerData['id']);
                        if ($updateReferenceL1['status'] == 'success' && !empty($updateReferenceL1['referrerId'])) {
                            $updateReferenceL2 = $this->update_repurchase_amount($updateReferenceL1['referrerId'], 2, $amount, $customerData['id']);
                            if ($updateReferenceL2['status'] == 'success' && !empty($updateReferenceL2['referrerId'])) {
                                $updateReferenceL3 = $this->update_repurchase_amount($updateReferenceL2['referrerId'], 3, $amount, $customerData['id']);
                                if ($updateReferenceL3['status'] == 'success' && !empty($updateReferenceL3['referrerId'])) {
                                    $updateReferenceL4 = $this->update_repurchase_amount($updateReferenceL3['referrerId'], 4, $amount, $customerData['id']);
                                    if ($updateReferenceL4['status'] == 'success' && !empty($updateReferenceL4['referrerId'])) {
                                        $updateReferenceL5 = $this->update_repurchase_amount($updateReferenceL4['referrerId'], 5, $amount, $customerData['id']);
                                        if ($updateReferenceL5['status'] == 'success' && !empty($updateReferenceL5['referrerId'])) {
                                            $updateReferenceL6 = $this->update_repurchase_amount($updateReferenceL5['referrerId'], 6, $amount, $customerData['id']);
                                            if ($updateReferenceL6['status'] == 'success' && !empty($updateReferenceL5['referrerId'])) {
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if (!empty($deliveryData)) {
                    $mapData = $orderDetails['latlong'];
                    if (!empty($mapData)) {
                        $latLongUrl = "https://www.google.com/maps/search/$mapData";
                    } else {
                        $latLongUrl = str_replace(" ", "%20", "https://www.google.com/maps/search/" . $orderDetails['deliveryAddress']);
                    }
                    $interfaceURL = '';
                    $interfaceDetails = $this->db->get_where('web_settings', array('data_type' => 'interface_url'))->row_array();
                    if (!empty($interfaceDetails)) {
                        $interfaceURL = $interfaceDetails['data_value'];
                    }
                    
$whatsapp_msg = "Dear ".$deliveryData['name'].",

You've got a new delivery !! Find the details below.

Order Details
".$interfaceURL.'delivery/orders'."

Delivery Route
".$latLongUrl."

Delivery Address
".$orderDetails['deliveryAddress']."

Thank You.
Nalaa Organic";
                    $whatsapp = send_whatsapp($deliveryData['phone'], $whatsapp_msg);

                    if($whatsapp['status']=='success'){ 
                        $value = withSuccess('Order assigned and delivery details sent to delivery partner successfully.');
                    }else{
                        $value = withSuccess('Couldn\'t assign order details. Reverting the changes');
                    }
                } else {
                    $value = withSuccess('Orders updated successfully', $result);
                }
            } else {
                $value = withErrors($this->lang->line('failed_to_update'), $result);
            }
        } else {
            $value = withErrors('Invalid Order. Please refresh and try again');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }



    public function update_repurchase_amount($customerId, $level, $amountWithoutTaxAndDelivery, $purchasedBy)
    {
        $customerData = $this->db->get_where('customers', array('id' => $customerId))->row_array();
        $refAmount = 0;
        $referrerId = '';
        $status = 'fail';
        $message = 'Something Went Wrong';
        if (!empty($customerData)) {
            switch ($level) {
                case '1':
                    $refAmount = ($amountWithoutTaxAndDelivery * 3) / 100;
                    break;
                case '2':
                    $refAmount = ($amountWithoutTaxAndDelivery * 1) / 100;
                    break;
                case '3':
                    $refAmount = ($amountWithoutTaxAndDelivery * 0.5) / 100;
                    break;
                case '4':
                    $refAmount = ($amountWithoutTaxAndDelivery * 0.2) / 100;
                    break;
                case '5':
                    $refAmount = ($amountWithoutTaxAndDelivery * 0.1) / 100;
                    break;
                case '6':
                    $refAmount = ($amountWithoutTaxAndDelivery * 0.05) / 100;
                    break;
            }
            $creditCustomer = $this->db->insert('customer_repurchase_amount', array('level' => $level, 'amount' => $refAmount, 'customer_id' => $customerId, 'status' => 1, 'createdDate' => cur_date_time(), 'repurchase_comission_by' => $purchasedBy, 'orderAmount' => $amountWithoutTaxAndDelivery));
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


    /**

     * Delete data from this method.

     *

     * @return Response

    */

    public function index_delete($id)
    {
        $data = $this->db->get_where("users", ['roles_id' => $id])->row_array();
        if (empty($data)) {
            $data = $this->get_single_result($id);
            $res = $this->Mydb->delete_table_data('roles', array('id' => $id));
            if ($res == 1) {
                $result = array('details' => $data);
                $value = withSuccess($this->lang->line('role_deleted_success'), $result);
            } else if ($res == -1451) {
                $value = withErrors($this->lang->line('failed_to_delete'));
            } else {
                $value = withErrors($this->lang->line('failed_to_delete'));
            }
        } else {
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
        $param_default = array('search', 'perpage', 'page', 'sortby', 'orderby');
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
        $data['slno'] = '';

        if (!empty($parameters)) {
            $parem_key = array_keys($parameters);
            $diff = array_diff($parem_key, $param_default);
            $intersect = array_intersect($parem_key, $param_default);
        }

        if (array_key_exists('page', $parameters)) {
            $all = false;
        }

        if (!empty($intersect)) {
            foreach ($intersect as $inst) {
                $rml = str_replace('-', '.', $parameters[$inst]);
                $$inst = $rml;
            }
        }


        $filter_data[0]['type'] = 'search';
        $filter_data[0]['value'] = $search;

        if (!empty($diff)) {
            $i = count($filter_data);
            foreach ($diff as $p) {
                if (!empty($this->input->get($p))) {
                    $pa = str_replace('-', '.', $p);
                    $filter_data[$i]['type'] = $pa;
                    $filter_data[$i]['value'] = $this->input->get($p);
                }
                $i++;
            }
        }


        $total_rows = $this->rolesmodel->filter($filter_data, 0, 0, $sortby, $orderby, $all);
        $udata = $this->Mydb->getPaginationData($perpage, $page, $total_rows);
        $data = array_merge($data, $udata);
        $getData = $this->rolesmodel->filter($filter_data, $perpage, $data['page_position'], $sortby, $orderby, $all);
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
        if ($all) {
            array_splice($data, 1);
        }
        //print_r($data);
        return $data;
    }

    function export_products_get()
    {
        $rand = rand(1234, 9898);
        $presentDate = date('d_m_Y_h_i_s_A');
        $file_name = "report_products_" . $presentDate . ".xlsx";
        $query = "SELECT products.name as product_name, product_metrics.quantity as product_metrics_quantity, product_metrics.unit as product_unit, product_metrics.price as product_price, SUM($this->orders_details_table.count) as product_quantity   
                FROM $this->orders_details_table
                LEFT JOIN product_metrics ON $this->orders_details_table.metricsId = product_metrics.id
                LEFT JOIN orders ON $this->orders_details_table.orderId = orders.id
                LEFT JOIN products ON order_details.productId = products.id
                WHERE orders.status='25' GROUP BY $this->orders_details_table.productId, $this->orders_details_table.metricsId";
        $data = $this->db->query($query)->result_array();
        if (!empty($data)) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->mergeCells('A1:D1');
            $sheet->setCellValue('A1', 'Order Product Information');

            // Style for the header cell
            $styleHeader = [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => 'FFFFFF'], // White text color
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0000FF'], // Blue background color
                ],
            ];

            // Apply style to the header cell
            $sheet->getStyle('A1')->applyFromArray($styleHeader);

            // $sheet->getStyle('A1')->getFont()->setSize(16);
            $sheet->setCellValue('A2', 'Product Name');
            $sheet->setCellValue('B2', 'Metrics');
            $sheet->setCellValue('C2', 'Price');
            $sheet->setCellValue('D2', 'Quantity');
            $count = 3;
            foreach ($data as $row) {
                $sheet->setCellValue('A' . $count, $row['product_name']);
                $sheet->setCellValue('B' . $count, $row['product_metrics_quantity'] . ' ' . $row['product_unit']);
                $sheet->setCellValue('C' . $count, $row['product_price']);
                $sheet->setCellValue('D' . $count, $row['product_quantity']);
                $count++;
            }
            $writer = new Xlsx($spreadsheet);
            create_directory('reports');
            $filePath = 'reports/' . $file_name;
            $writer->save($filePath);
            $res = array(
                'filename' => $file_name,
                'url' => base_url() . $filePath
            );
            $result = array('details' => $res);
            $value = withSuccess($this->lang->line('report_generated_successfully'), $result);
        } else {
            $value = withErrors($this->lang->line('no_result_found'));
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }



    function export_get()
    {
        $rand = rand(1234, 9898);
        $presentDate = date('d_m_Y_h_i_s_A');
        $file_name = "report_products_" . $presentDate . ".xlsx";
        $type = $this->input->get('type');
        $status = '';
        if (!empty($type)) {
            switch ($type) {
                case 'new_orders':
                    $status = 25;
                    break;
                case 'pending_orders':
                    $status = 27;
                    break;
                case 'cancelled_orders':
                    $status = 26;
                    break;
                case 'completed_orders':
                    $status = 28;
                    break;
                default:
                    break;
            }
        }
        $query = "SELECT $this->orders_table.orderId, $this->orders_table.paymentMethod, $this->orders_table.actualAmountToPay, $this->orders_table.createdDate order_created_at, $this->orders_table.orderId, $this->orders_table.deliveryAddress, $this->orders_table.pay_status as pay_status, status_table.l_value as delivery_status,   
			delivery_table.name as delivery_boy_name, delivery_table.email as delivery_boy_email, delivery_table.phone as delivery_boy_phone, customers_table.firstName as firstname, customers_table.lastName as lastname, customers_table.email as email, customers_table.phone as phone
			from $this->orders_table
			left join $this->lookups_table as status_table on $this->orders_table.status = status_table.id 
			left join $this->customers_table as customers_table on $this->orders_table.customerId = customers_table.id 
			left join $this->delivery_table as delivery_table on $this->orders_table.delivery_by = delivery_table.id
            WHERE $this->orders_table.id>0 AND $this->orders_table.status='$status'";
        $data = $this->db->query($query)->result_array();
        if (!empty($data)) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->mergeCells('A1:D1');
            $sheet->setCellValue('A1', 'Orders Information');

            // Style for the header cell
            $styleHeader = [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => 'FFFFFF'], // White text color
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0000FF'], // Blue background color
                ],
            ];

            // Apply style to the header cell
            $sheet->getStyle('A1')->applyFromArray($styleHeader);

            // $sheet->getStyle('A1')->getFont()->setSize(16);
            $sheet->setCellValue('A2', 'Order Date');
            $sheet->setCellValue('B2', 'Order ID');
            $sheet->setCellValue('C2', 'Delivery Address');
            $sheet->setCellValue('D2', 'Delivery Boy');
            $sheet->setCellValue('E2', 'Customer');
            $sheet->setCellValue('F2', 'Order Amount');
            $sheet->setCellValue('G2', 'Payment');
            $sheet->setCellValue('H2', 'Delivery Status');
            $count = 3;
            foreach ($data as $row) {
                // order_created_at, deliveryAddress, payment_status, delivery_status, delivery_boy_name, delivery_boy_email, delivery_boy_phone, firstname, lastname, email, phone
                $paymentMethod = 'Pending';
                if ($row['pay_status'] == 1) {
                    $pStatus = 'Paid';
                    $payMethod = $row['paymentMethod'];
                    if ($payMethod == 'pay_online') {
                        $pStatus .= ' -- Online Payment';
                    } else if ($payMethod == 'cash_on_delivery') {
                        $pStatus .= ' -- Cash On Delivery';
                    } else if ($payMethod == 'pay_with_wallet') {
                        $pStatus .= ' -- Wallet Payment';
                    } else {
                        $pStatus .= ' -- NONE';
                    }
                    $paymentMethod = $pStatus;
                }
                $sheet->setCellValue('A' . $count, $row['order_created_at']);
                $sheet->setCellValue('B' . $count, $row['orderId']);
                $sheet->setCellValue('C' . $count, $row['deliveryAddress']);
                $sheet->setCellValue('D' . $count, $row['delivery_boy_name'] . PHP_EOL . $row['delivery_boy_email'] . PHP_EOL . $row['delivery_boy_phone']);
                $sheet->setCellValue('E' . $count, $row['firstname'] . ' ' . $row['lastname'] . PHP_EOL . $row['email'] . PHP_EOL . $row['phone']);
                $sheet->setCellValue('F' . $count, $row['actualAmountToPay']);
                $sheet->setCellValue('G' . $count, $paymentMethod);
                $sheet->setCellValue('H' . $count, $row['delivery_status']);
                $count++;
            }
            create_directory('reports');
            $writer = new Xlsx($spreadsheet);
            $filePath = 'reports/' . $file_name;
            $writer->save($filePath);
            $res = array(
                'filename' => $file_name,
                'url' => base_url() . $filePath
            );
            $result = array('details' => $res);
            $value = withSuccess($this->lang->line('report_generated_successfully'), $result);
        } else {
            $value = withErrors($this->lang->line('no_result_found'));
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }

    public function initiate_payment_post() {
        $raw_input_stream = $this->input->raw_input_stream;
		$input = array();
		if(!empty($raw_input_stream)){
			$input = json_decode($raw_input_stream, true);
		}
        if(empty($input)){
            $value = withErrors('Invalid Request, Please try again.');
            $this->response($value, REST_Controller::HTTP_OK);
        }
        // print_R($input);exit();
		$userId = $input['customer_id'];
		$order_amount = $input['order_amount'];
		$customer_data = $this->db->get_where('customers', ['id'=>$userId])->row_array();
		if(!empty($customer_data) && $order_amount>0){
			$cashfree = new Cashfree();
			$this->initialize_cashfree();
			$x_api_version = "2022-09-01";
			$create_orders_request = new CreateOrderRequest();
			// if($userId==1 || $userId==2 || $userId==4 || $userId==5 || $userId==6){
			// 	$create_orders_request->setOrderAmount(1);
			// }else{
				$create_orders_request->setOrderAmount($order_amount);
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
			echo json_encode(['status'=>'fail', 'message'=>'Payment Initiation Failed']);
		}
    }


    public function initialize_cashfree() {
        // Set Cashfree credentials
        // Cashfree::$XClientId = '69840976689cac79aa7b79fc08904896';//LIVE
        // Cashfree::$XClientSecret = 'cfsk_ma_prod_2daeed7715afcb89811858688b4cd8ed_d280cc62';//LIVE
		// Cashfree::$XEnvironment = Cashfree::$PRODUCTION;

		Cashfree::$XClientId = 'TEST102073792924a2850f35797aecba97370201';
        Cashfree::$XClientSecret = 'cfsk_ma_test_a1cb17f6b142a7f32369e8c28965fddc_2e379846';
        Cashfree::$XEnvironment = Cashfree::$SANDBOX; // or Cashfree::$PRODUCTION for live environment
    }

    function test_check_payment_status_get($orderId) {
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/{$orderId}/payments",
        //   CURLOPT_URL => "https://api.cashfree.com/pg/orders/{$orderId}/payments",
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
            "x-client-secret: cfsk_ma_test_a1cb17f6b142a7f32369e8c28965fddc_2e379846",
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



    function check_payment_status_get($orderId) {
        $curl = curl_init();
        curl_setopt_array($curl, [
        //   CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/{$orderId}/payments",
          CURLOPT_URL => "https://api.cashfree.com/pg/orders/{$orderId}/payments",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "x-api-version: 2023-08-01",
            "x-client-id: 69840976689cac79aa7b79fc08904896",
            "x-client-secret: cfsk_ma_prod_2daeed7715afcb89811858688b4cd8ed_d280cc62"
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
        Cashfree::$XClientId = 'TEST102073792924a2850f35797aecba97370201';
        Cashfree::$XClientSecret = 'cfsk_ma_test_a1cb17f6b142a7f32369e8c28965fddc_2e379846';
        Cashfree::$XEnvironment = Cashfree::$SANDBOX; // or Cashfree::$PRODUCTION for live environment
        $cashfree = new Cashfree();
        $x_api_version = "2023-09-01";
        $create_orders_request = new CreateOrderRequest();
        $create_orders_request->setOrderAmount(1.0);
        $create_orders_request->setOrderCurrency("INR");
        $customer_details = new CustomerDetails();
        $customer_details->setCustomerId("123");
        $customer_details->setCustomerPhone("7892790889");
        $create_orders_request->setCustomerDetails($customer_details);
        try {
            $result = $cashfree->PGCreateOrder($x_api_version, $create_orders_request);
        } catch (Exception $e) {
            echo 'Exception when calling PGCreateOrder: ', $e->getMessage(), PHP_EOL;
        }
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
        $orderAmount = $input['order_amount'];
        // if($userId==1){
        //     $orderAmount=1;
        // }
        $returnUrl = ['return_url'];
        $customer_data = $this->db->get_where('customers', ['id'=>$userId])->row_array();
        if(!empty($customer_data) && $orderAmount){
            // $clientKey = 'cfsk_ma_test_a1cb17f6b142a7f32369e8c28965fddc_2e379846'; // Replace with your actual client secret
            // $clientId = 'TEST102073792924a2850f35797aecba97370201';   // Replace with your actual client ID
            $clientKey = 'cfsk_ma_prod_2daeed7715afcb89811858688b4cd8ed_d280cc62';//LIVE
            $clientId = '69840976689cac79aa7b79fc08904896';//LIVE
            // Cashfree::$XEnvironment = Cashfree::$PRODUCTION;
            $data = [
                'order_amount' => $orderAmount,
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
                            // 'environment'=>'SANDBOX'
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
            $value = withErrors('Couldn\'t fetch your details', ['sessionId'=>'', 'orderId'=>'']);
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }  



    function test_init_post() {
        $raw_input_stream = file_get_contents('php://input');
        $input = json_decode($raw_input_stream, true);
        if (empty($input)) {
            $value = json_encode(['status' => 'fail', 'message' => 'Invalid Request, Please try again.']);
            http_response_code(400);
            echo $value;
            return;
        }
    
        $userId = $input['customer_id'];
        $orderAmount = $input['order_amount'];
        $returnUrl = ['return_url'];
        $customer_data = $this->db->get_where('customers', ['id'=>$userId])->row_array();
        if(!empty($customer_data) && $orderAmount){
            $clientKey = 'cfsk_ma_test_a1cb17f6b142a7f32369e8c28965fddc_2e379846';
            $clientId = 'TEST102073792924a2850f35797aecba97370201';
            // $clientKey = 'cfsk_ma_prod_2daeed7715afcb89811858688b4cd8ed_d280cc62';//LIVE
            // $clientId = '69840976689cac79aa7b79fc08904896';//LIVE
            $data = [
                'order_amount' => $orderAmount,
                'order_currency' => 'INR', // Assuming you always use INR
                'customer_details' => [
                    'customer_id' => $userId,
                    'customer_name' => $customer_data['firstName'] . ' ' . $customer_data['lastName'],
                    'customer_email' => $customer_data['email'],
                    'customer_phone' => $customer_data['phone'],
                ],
            ];
        
            $jsonData = json_encode($data);
        
            $url = 'https://sandbox.cashfree.com/pg/orders';
            // $url = 'https://api.cashfree.com/pg/orders';

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
            // log_message('error', 'Logging the cashfree log Begining');
            // log_message('error', print_r($response, true));
            // log_message('error', 'Logging the cashfree log End');
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
                            // 'environment'=>'PRODUCTION'
                            'environment'=>'SANDBOX'
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
            $value = withErrors('Couldn\'t fetch your details', ['sessionId'=>'', 'orderId'=>'']);
        }

        $this->response($value, REST_Controller::HTTP_OK);
    }  

    function payment_status_put($id){
        $input = $this->put();
        $getOrder = $this->db->get_where('payment_details', ['id'=>$id])->row_array();
        if(!empty($getOrder)){
            $update = $this->db->update('payment_details', $input, ['id'=>$id]);
            if($update){
                $updateOrder = $this->db->update('orders', ['pay_status'=>1], ['id'=>$getOrder['reference_id']]);
                $value = withSuccess('Payment Details Updated Successfully to '.$input['paymentStatus']. ' Please reload the page');
            }else{
                $value = withErrors('Payment Details couldn\'t be updated');
            }
        }else{
            $value = withErrors('Payment Details Not Found');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }
}