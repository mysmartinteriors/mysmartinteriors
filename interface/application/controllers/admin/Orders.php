<?php
// require_once('tcpdf_include.php');
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';

use Cashfree\Cashfree;
use Cashfree\Model\CreateOrderRequest;
use Cashfree\Model\CustomerDetails;
use Cashfree\Model\OrderMeta;

class Orders extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		check_admin_session();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("ordersmodel","",true);

		$this->admin->nocache();
		$this->module_name = 'orders';
		$this->view_path = "admin/orders/bookings";
	}

	public function index()
	{
		$data = $this->admin->commonadminFiles();
		$data['title'] = "Customer booking orders";
		$customer_data = $this->curl->execute("customers", "GET", array("perpage" => 10000));
		if ($customer_data['status'] == 'success' && !empty($customer_data['data_list'])) {
			$data['customerData'] = $customer_data['data_list'];
		}
		$this->load->view("admin/orders/bookings/orders_view", $data); 
	} 

	function get_bookings()
	{
		$data['result'] = "success";

		$filter_data = $this->input->post('filter_data');
		$module = $this->input->post('module');

		$page = (int) $this->input->post('page');
		if (empty($page) && $page < 1) {
			$page = 1;
		}
		$filterData[] = array();
		$filterData['orderby'] = 'DESC';
		if (!empty($filter_data)) {
			foreach ($filter_data as $k => $v) {
				if ($v['type'] == 'sortby') {
					$filterData[$v['type']] = $module . '.' . $v['value'];
				} else {
					$filterData[$v['type']] = $v['value'];
				}
			}
		}
		if (!empty($page)) {
			$filterData['page'] = $page;
		}
		$apidata = $this->curl->execute($this->module_name, "GET", $filterData, 'filter');
		// print_R($apidata);exit();
		$data = array(
			'message' => $apidata['message'],
			'status' => $apidata['status'],
			'data_list' => $apidata['data_list'],
		);
		if (isset($apidata['pagination_data'])) {
			$data['pagination_data'] = $apidata['pagination_data'];
		}

		$str = $this->load->view("admin/orders/bookings/orders_tbl_view", $data, true);

		$value = array(
			'str' => $str
		);
		echo json_encode($value);
	} 

	public function pending_orders()
	{
		$data = $this->admin->commonadminFiles();
		$data['title'] = "Customer booking orders";
		$this->load->view("admin/orders/bookings/orders_pending_view", $data);
	}

	function get_pending_bookings()
	{
		$data['result'] = "success";

		$filter_data = $this->input->post('filter_data');
		$module = $this->input->post('module');

		$page = (int) $this->input->post('page');
		if (empty($page) && $page < 1) {
			$page = 1;
		}
		$filterData[] = array();
		$filterData['orderby'] = 'DESC';
		if (!empty($filter_data)) {
			foreach ($filter_data as $k => $v) {
				if ($v['type'] == 'sortby') {
					$filterData[$v['type']] = $module . '.' . $v['value'];
				} else {
					$filterData[$v['type']] = $v['value'];
				}
			}
		}
		if (!empty($page)) {
			$filterData['page'] = $page;
		}
		$filterData['orders-status'] = 27;
		$filterData['perpage'] = 100000;
		$apidata = $this->curl->execute($this->module_name, "GET", $filterData, 'filter');
		$data = array(
			'message' => $apidata['message'],
			'status' => $apidata['status'],
			'data_list' => $apidata['data_list'],
		);
		if (isset($apidata['pagination_data'])) {
			$data['pagination_data'] = $apidata['pagination_data'];
		}
		log_message('error', print_r($data, true));

		$str = $this->load->view("admin/orders/bookings/orders_pending_tbl_view", $data, true);

		$value = array(
			'str' => $str
		);
		echo json_encode($value);
	}

	public function completed_orders()
	{
		$data = $this->admin->commonadminFiles();
		$data['title'] = "Customer booking orders";
		$this->load->view("admin/orders/bookings/orders_completed_view", $data);
	}

	function get_completed_bookings()
	{
		$data['result'] = "success";

		$filter_data = $this->input->post('filter_data');
		$module = $this->input->post('module');

		$page = (int) $this->input->post('page');
		if (empty($page) && $page < 1) {
			$page = 1;
		}
		$filterData[] = array();
		$filterData['orderby'] = 'DESC';
		if (!empty($filter_data)) {
			foreach ($filter_data as $k => $v) {
				if ($v['type'] == 'sortby') {
					$filterData[$v['type']] = $module . '.' . $v['value'];
				} else {
					$filterData[$v['type']] = $v['value'];
				}
			}
		}
		if (!empty($page)) {
			$filterData['page'] = $page;
		}
		$filterData['orders-status'] = 28;
		$filterData['perpage'] = 100000;
		$apidata = $this->curl->execute($this->module_name, "GET", $filterData, 'filter');
		$data = array(
			'message' => $apidata['message'],
			'status' => $apidata['status'],
			'data_list' => $apidata['data_list'],
		);
		if (isset($apidata['pagination_data'])) {
			$data['pagination_data'] = $apidata['pagination_data'];
		}

		$str = $this->load->view("admin/orders/bookings/orders_completed_tbl_view", $data, true);

		$value = array(
			'str' => $str
		);
		echo json_encode($value);
	}

	public function cancelled_orders()
	{
		$data = $this->admin->commonadminFiles();
		$data['title'] = "Customer booking orders";
		$this->load->view("admin/orders/bookings/orders_cancelled_view", $data);
	}

	function get_cancelled_bookings()
	{
		$data['result'] = "success";

		$filter_data = $this->input->post('filter_data');
		$module = $this->input->post('module');

		$page = (int) $this->input->post('page');
		if (empty($page) && $page < 1) {
			$page = 1;
		}
		$filterData[] = array();
		$filterData['orderby'] = 'DESC';
		if (!empty($filter_data)) {
			foreach ($filter_data as $k => $v) {
				if ($v['type'] == 'sortby') {
					$filterData[$v['type']] = $module . '.' . $v['value'];
				} else {
					$filterData[$v['type']] = $v['value'];
				}
			}
		}
		if (!empty($page)) {
			$filterData['page'] = $page;
		}
		$filterData['orders-status'] = 26;
		$filterData['perpage'] = 100000;
		//  print_R($filter_data);exit();
		$apidata = $this->curl->execute($this->module_name, "GET", $filterData, 'filter');
		// print_r($apidata);exit();
		$data = array(
			'message' => $apidata['message'],
			'status' => $apidata['status'],
			'data_list' => $apidata['data_list'],
		);
		if (isset($apidata['pagination_data'])) {
			$data['pagination_data'] = $apidata['pagination_data'];
		}

		$str = $this->load->view("admin/orders/bookings/orders_cancelled_tbl_view", $data, true);

		$value = array(
			'str' => $str
		);
		echo json_encode($value);
	}

	function edit()
	{
		$id = $this->uri->segment(4);
		if ($id != '') {
			$data = $this->admin->commonadminFiles();
			$data['title'] = "Edit customer order";
			$order_data = $this->curl->execute($this->module_name . "/" . $id, "GET");
			if ($order_data['status'] == 'success' && !empty($order_data['data_list'])) {
				$data['order_data'] = $order_data['data_list'];
				$order_details_data = $this->curl->execute("orders/order_details/", "GET", array("orderId" => $id));
				if ($order_details_data['status'] == 'success' && !empty($order_details_data['data_list'])) {
					$data['order_details'] = $order_details_data['data_list'];
					$ordered_prd_data = $this->curl->execute("orders/product_details/", "GET", array("orderId" => $id));
					if ($ordered_prd_data['status'] == 'success' && !empty($ordered_prd_data['data_list'])) {
						$data['product_details'] = $ordered_prd_data['data_list'];
						$str = $this->load->view("admin/orders/bookings/edit_order_view", $data);
					} else {
						$str = 'Ordered products not found...';
					}
				} else {
					$str = 'Order Details not found...';
				}
				// if($data['dataQ']->row()->status>=0 && $data['dataQ']->row()->status<3){
				// 	$data['productQ']=$this->ordersmodel->get_user_booking_prd($id);
				// 	$this->load->view("admin/orders/bookings/edit_order_view",$data);
				// }else{
				// 	redirect(base_url().'admin/error_404');
				// }
			} else {
				redirect(base_url() . 'admin/error_404');
			}
		} else {
			redirect(base_url() . 'admin/dashboard');
		}
		echo json_encode($str);
	}

	function update_booking()
	{
		$orderId = $this->input->post('id');
		$data['delivery_by'] = $this->input->post('delivery_by');
		$data['payment_status'] = $this->input->post('payment_status');
		$data['status'] = $this->input->post('status');
		$data['total_amount'] = $this->input->post('total_amount');

		if (!empty($orderId)) {
			$apidata = $this->curl->execute("orders/$orderId", "PUT", $data);
			if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
				$status = $apidata['status'];
				$message = $apidata['message'];
			} else {
				$status = $apidata['status'];
				$message = $apidata['message'];
			}
		} else {
			$status = "fail";
			$message = "Order data not found";
		}
		$value = array(
			'status' => $status,
			'message' => $message
		);
		echo json_encode($value);
	}

	function view()
	{
		$id = $this->uri->segment(4);
		if ($id != '') {
			$data = $this->admin->commonadminFiles();
			$data['title'] = "View customer order";
			$order_data = $this->curl->execute("orders/$id", "GET");
			if ($order_data['status'] == 'success' && !empty($order_data['data_list'])) {
				$data['order_data'] = $order_data['data_list'];
				$order_details_data = $this->curl->execute("orders/order_details/", "GET", array("orderId" => $id));
				if ($order_details_data['status'] == 'success' && !empty($order_details_data['data_list'])) {
					$data['order_details'] = $order_details_data['data_list'];
					$ordered_prd_data = $this->curl->execute("orders/product_details/", "GET", array("orderId" => $id));
					if ($ordered_prd_data['status'] == 'success' && !empty($ordered_prd_data['data_list'])) {
						$data['product_details'] = $ordered_prd_data['data_list'];
					}
				}
				$this->load->view("admin/orders/bookings/order_details_view", $data);
			} else {
				redirect(base_url() . 'admin/orders');
			}
		} else {
			redirect(base_url() . 'admin/dashboard');
		}
	}

 
	function pdf()
	{
		$id = $this->uri->segment(4);
		if ($id) {
			$order_data = $this->curl->execute("orders/$id", "GET"); 
			if ($order_data['status'] == 'success' && !empty($order_data['data_list'])) {
				$data['order_data'] = $order_data['data_list'];
			}
			require FCPATH . '/vendor/autoload.php';
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$code = $order_data['data_list']['orderId'];
			$pdf->setTitle('Order_' . $code);
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
				require_once (dirname(__FILE__) . '/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			$pdf->setFont('times', 'BI', 20);
			$pdf->AddPage();
			$str = $this->load->view("admin/orders/bookings/pdf_view", $data, true);
			ob_end_clean();
			$pdf->writeHTML($str, false, false, true, false, '');
			$pdf->Output('Order_' . $code . '.pdf', 'I');

		} else {
			redirect(base_url() . 'admin/dashboard');
		}
	}

	function assign_order()
	{
		$data = array();
		$id = $this->input->post('id');
		$code = $this->input->post('code');
		$apidata = $this->curl->execute("orders/$id", 'GET');
		if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
			$data['orderData'] = $apidata['data_list'];
			$delivery = $this->curl->execute("delivery", "GET", array("delivery-status" => 40));
			// print_R($delivery);exit();
			if ($delivery['status'] == 'success' && !empty($delivery['data_list'])) {
				$data['deliveryBoys'] = $delivery['data_list'];
				$msg = $this->load->view("admin/orders/bookings/assign_order_view", $data, true);
				$status = 'success';
			} else {
				$msg = 'Unable to assign the order!';
				$status = 'fail';
			}
		} else {
			$msg = 'Unable to load the order!';
			$status = 'fail';
		}
		//print_r($this->db->last_query());

		$value = array('msg' => $msg, 'status' => $status);
		echo json_encode($value);
	}

	function update_assign_order()
	{
		$id = $this->input->post("id");
		$data['delivery_by'] = $this->input->post("delivery_boy");
		$data['paymentMethod'] = $this->input->post('paymentMethod');
		$data['status'] = 27;
		$res = $this->curl->execute($this->module_name . "/" . $id, "PUT", $data);
		if ($res['status'] == 'success') {
			$status = 'success';
			$message = "Order Assigned Successfully";
		} else {
			$status = 'failed';
			$message = "Failed to Assign the Order";
		}
		echo json_encode(array('status' => $status, 'message' => $message));
	}

	function order_products_total()
	{
		$data = $this->admin->commonadminFiles();
		$data['title'] = "Customer booking orders";
		$count_by_cat = array();
		$orders = $this->curl->execute("orders", "GET", array("orders-status" => 25));
		if ($orders['status'] == 'success' && !empty($orders['data_list'])) {
			foreach ($orders['data_list'] as $new_orders) {
				if (!empty($new_orders['order_details'])) {
					foreach ($new_orders['order_details'] as $products_data) {
						$cat_name = $products_data['product_name'];
						if (array_key_exists($cat_name, $count_by_cat)) {
							$count_by_cat[$cat_name] += $products_data['product_quantity'] * $products_data['count'];
						} else {
							$count_by_cat[$cat_name] = $products_data['product_quantity'] * $products_data['count'];
						}
					}
				}
			}
			$data['total_order'] = $count_by_cat;
			$this->load->view("admin/orders/bookings/total_orders_view", $data);
		}
	}


	function productDetails(){
		$orderId = $this->input->post('orderId');
		$status = 'fail';
		$message = 'No products found for the given order';
		if($orderId){
			$data = array();
			$orderDetailsApi = $this->curl->execute('orders/'.$orderId, 'GET');
			if($orderDetailsApi['status']=='success' && !empty($orderDetailsApi['data_list'])){
				$data['orderDetails'] = $orderDetailsApi['data_list'];
				$status = 'success';
				$message = $this->load->view('admin/orders/bookings/ordered_product_details', $data, true);
			}
		}
		echo json_encode(['status'=>$status, 'message'=>$message]);
	}


	function export_data(){
		$type = $this->input->post('type');
		$message = 'Unable to get the data';
		$getexportdata = $this->curl->execute('orders/export_products', 'GET');
		$message = $getexportdata['message'];
		if($getexportdata['status']=='success' && !empty($getexportdata['data_list']['url'])){
			$value = array('status'=>'success', 'message'=>$message, 'download_url'=>$getexportdata['data_list']['url']);
		}else{
			$value = array('status'=>'failed', 'messag'=>$message);
		}
		echo json_encode($value);
}


	function export_orders(){
		$type = $this->input->post('type');
		$message = 'Unable to export the orders';
		if(!empty($type)){
			$getexportdata = $this->curl->execute('orders/export', 'GET', array('type'=>$type));
			$message = $getexportdata['message'];
			if($getexportdata['status']=='success' && !empty($getexportdata['data_list']['url'])){
				$value = array('status'=>'success', 'message'=>$message, 'download_url'=>$getexportdata['data_list']['url']);
			}else{
				$value = array('status'=>'failed', 'messag'=>$message);
			}
			echo json_encode($value);
		}else{
			$value = array('status'=>'failed', 'message'=>$message);
			echo json_encode($value);
		}
	}

	function checkPaymentStatus(){
		$orderId = $this->input->post('orderId');
		$apidata = $this->curl->execute('orders/payment_details/'.$orderId, 'GET');
		if($apidata['status']=='success' && !empty($apidata['data_list'])){
			$paymentDetails = $apidata['data_list'];

		}else{
			echo json_encode(['status'=>'fail', 'message'=>'Payment Status Not Found']);
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

    function check_payment_status() {
		$orderId = $this->input->post('orderId');
		$paymentDetailsId = $this->input->post('paymentDetailsId');
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
            $value =['status'=>'fail', 'message'=>'Payment Status Check Failed, Please contact us if the amount is deducted'];
			$status = 'fail';
        } else {
            $processedResponse = $this->processPaymentResponse($response);
			if($processedResponse['payment_status']=='SUCCESS'){
				// update db
				$apidata = $this->curl->execute("orders/payment_status/$paymentDetailsId", 'PUT', ['paymentStatus'=>$processedResponse['payment_status'], 'paymentCompletedTime'=>$processedResponse['payment_completion_time'], 'paymentMessage'=>$processedResponse['payment_message']]);
				$value = ['status'=>$apidata['status'], 'message'=>$apidata['message']];
			}else{
				$value = ['status'=>$processedResponse['payment_status'], 'message'=>$processedResponse['payment_message']];
			}
			$status = 'success';
        }
		$message = $this->load->view('admin/orders/bookings/booking_payment_status_view', $value, true);
		echo json_encode(['status'=>$status, 'message'=>$message]);
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
    
    
}