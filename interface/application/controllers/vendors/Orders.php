<?php
// require_once('tcpdf_include.php');
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_delivery_session();
		$this->admin->nocache(); 
		$this->module_name='orders';
		$this->view_path = "delivery/orders";
	}
	
    public function index() {
        $data = $this->admin->commondeliveryFiles();
		$data['title']="Customer booking orders";
		$this->load->view("delivery/orders/pending_orders_view",$data);
    }

    function get_bookings(){
		$userId = User::get_deliveryId();
        $data['result'] = "success";  

		$filter_data=$this->input->post('filter_data');
        $module=$this->input->post('module');
       
        $page = (int) $this->input->post('page');
        if(empty($page) && $page<1){
            $page = 1;
        }
        $filterData[]=array();
    	$filterData['orderby']='DESC';
    	if(!empty($filter_data)){
	    	foreach ($filter_data as $k => $v) {
				if($v['type']=='sortby'){
					$filterData[$v['type']]=$module.'.'.$v['value'];
				}else{					
					$filterData[$v['type']]=$v['value'];
				}
			}
		}
        if(!empty($page)){
            $filterData['page']=$page;
        }

		$filterData[''] = 27;
		$filterData['orders-delivery_by'] = $userId;
        $apidata=$this->curl->execute($this->module_name,"GET",$filterData,'filter');
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}

		$apidata=$this->curl->execute($this->module_name,"GET",$filterData,'filter');
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
        $str=$this->load->view("delivery/orders/pending_orders_tbl_view",$data,true);
        
        $value=array(
            'str'=>$str
        );
         echo json_encode($value);
    }

	function update_order(){
        $data=array();
        $id=$this->input->post('id');
        $code=$this->input->post('code');
        $apidata=$this->curl->execute("orders/$id", 'GET');
        if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
            $data['orderData'] = $apidata['data_list'];
			$msg=$this->load->view("delivery/orders/update_order_view",$data,true);
			$status='success';
        }else{
            $msg='Unable to load the order!';
            $status='fail';
        }
        $value=array('msg'=>$msg,'status'=>$status); 
        echo json_encode($value);
    }

	function update_delivered_order(){
        $id=$this->input->post("id");
        $data['payment_status']=$this->input->post("payment_status");
        $data['status']=$this->input->post("status");
		$data['comments']=$this->input->post("comments");
        $data['deliveredDate']=get_curentTime();
        $res = $this->curl->execute( $this->module_name."/".$id, "PUT", $data);
        if($res['status'] == 'success' && !empty($res['data_list'])){
            $status = $res['status'];
            $message = $res['message'];
        }else{
             $status = $res['status'];
			 $message = $res['message'];
        }
        $value=array(
            'message'=>$message,
            'status'=>$status
        );
        echo json_encode($value);
    }

	public function completed_orders() {
        $data = $this->admin->commondeliveryFiles();
		$data['title']="Customer booking orders";
		$this->load->view("delivery/orders/completed_orders_view",$data);
    }

    function get_completed_orders(){
		$userId = User::get_deliveryId();
        $data['result'] = "success";  

		$filter_data=$this->input->post('filter_data');
        $module=$this->input->post('module');
       
        $page = (int) $this->input->post('page');
        if(empty($page) && $page<1){
            $page = 1;
        }
        $filterData[]=array();
    	$filterData['orderby']='DESC';
    	if(!empty($filter_data)){
	    	foreach ($filter_data as $k => $v) {
				if($v['type']=='sortby'){
					$filterData[$v['type']]=$module.'.'.$v['value'];
				}else{					
					$filterData[$v['type']]=$v['value'];
				}
			}
		}
        if(!empty($page)){
            $filterData['page']=$page;
        }
		$filterData['deliverystatus'] = "28";
		$filterData['orders-delivery_by'] = $userId;
        $apidata=$this->curl->execute($this->module_name,"GET",$filterData,'filter');
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
        $str=$this->load->view("delivery/orders/completed_orders_tbl_view",$data,true);
        
        $value=array(
            'str'=>$str
        );
         echo json_encode($value);
    }
}