<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_referrals extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		$this->admin->nocache(); 
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Customers";
		$this->load->view("admin/customer_referrals/customer_view",$data);
    }

    function get_customers(){
        $filter_data=$this->input->post('filter_data');
    	$module=$this->input->post('module');
    	$page=$this->input->post('page');
        if(empty($page) && $page<1){
            $page = 1;
        }
    	if(!User::check_permission($module.'/getFilterList', 'check')){
			echo json_encode(array("status"=>"fail", "message"=>"You don't have permission. <br/>Please contact Admininstrator to request access."));
			return;
		}	
    	$filterData[]=array();
    	$filterData['orderby']='ASC';
    	if(!empty($filter_data)){
	    	foreach ($filter_data as $k => $v) {
				if($v['type']=='sortby'){
					$filterData[$v['type']]=$module.'.'.$v['value'];
				}else{					
					$filterData[$v['type']]=$v['value'];
				}
			}
		}
        if(empty($filterData['customers-status'])){
            $filterData['customers-status']=0;
        }
        if(!empty($page)){
            $filterData['page']=$page;
        }
    	$apidata=$this->curl->execute('customers',"GET",$filterData,'filter');
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
    	$str = $this->load->view("admin/customers/customers_tbl_view", $data, true);
    	$value=array('str'=>$str,'status'=>$apidata['status']);
    	echo json_encode($value);
    }

    function add(){
    	$data=array();
        $data = $this->admin->commonadminFiles();
        $data['title']="Add Customer";
        $this->load->view("admin/customers/add_customers_view",$data);
    }

    function edit(){
    	$id=$this->uri->segment(4);
		if($id!=""){
	    	$data=array();
	        $data = $this->admin->commonadminFiles();
	        $data['title']="Edit Customer";
            $customerData = $this->curl->execute("customers/$id", "GET");
            if($customerData['status'] == 'success' && !empty($customerData['data_list'])){
                $data['customerQ']=$customerData['data_list'];
            }
	        $this->load->view("admin/customers/edit_customers_view",$data);
	    }else{
	    	redirect(base_url().'admin/error_404');
	    }
    }

    function view(){
    	$id=$this->uri->segment(4);
		if($id!=""){
	    	$data=array();
	        $data = $this->admin->commonadminFiles();
	        $data['title']="View Customer";
            $customerData = $this->curl->execute("customers/$id", "GET");
            if($customerData['status'] == 'success' && !empty($customerData['data_list'])){
                $data['customerQ']=$customerData['data_list']; 
            } 
            $data['orders'] = $this->curl->execute("orders", "GET", array("orders-customerId" => $id, "perpage" => 10000));
            $data['referals'] = $this->curl->execute("customer_reference_amount", "GET", array("customer_reference_amount-customer_id" => $id, 'perpage'=>1000));
            $data['customer_repurchase_last_month'] = $this->curl->execute('customer_repurchase_amount/last_month_pending/'.$id, 'GET'); 
            $data['customer_reference_last_month'] = $this->curl->execute('customer_reference_amount/last_month_pending/'.$id, 'GET');
            $data['repurchases'] = $this->curl->execute("customer_repurchase_amount", "GET", array("customer_repurchase_amount-customer_id" => $id, 'perpage'=>1000));
            $data['customerId'] = $id;
	        $this->load->view("admin/customers/customer_details_view",$data);
	    }else{
	    	redirect(base_url().'admin/error_404');
	    }
    }

    function save_customer(){
        // $data['customerId']=$this->input->post("customerId");
        $customerId = $this->input->post('customerId');
        $data['firstName']=$this->input->post("firstName");
        $data['lastName']=$this->input->post("lastName");
        $data['email']=$this->input->post("email");
        $data['password']=$this->input->post("password");
        $data['phone']=$this->input->post("phone");
        $data['address']=  $this->input->post("address");
        $data['city']=  $this->input->post("city");
        $data['state']=  $this->input->post("state");
        $data['country']=  $this->input->post("country");
        $data['postalCode']=  $this->input->post("postalCode");
        $data['status']=$this->input->post("status");
        $data['type'] = 'Admin';
        // print_r($this->input->post());exit();
        if(!empty($customerId)){
            // $data['updatedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
            $apidata = $this->curl->execute('customers/'.$customerId, 'PUT', $data);
        }else{
            $apidata = $this->curl->execute('customers', 'POST', $data);
        }
        $result = $apidata['status'];
        $msg = $apidata['message'];
        $value=array(
            'result'=>$result,
            'msg'=>$msg
        );
        echo json_encode($value);
    }


    function deleteCustomer(){
        $id=$this->input->post("id");
        $message = 'Couldn\'t Delete, Something went wrong';
        $status = 'fail';
        if(empty($id)){
            $value = array('result'=>'fail', 'msg'=>'Unknown Customer');
        }else{
            $apidata = $this->curl->execute("customers/$id", "DELETE");
            $value = array('result'=>$apidata['status'], 'msg'=>$apidata['message']);
        }
        echo json_encode($value);
    }

    function export_data(){                
        $this->load->database();        
        $sql = "SELECT email as Email, firstName as First_Name, lastName as Last_Name,
                phone as Phone, address as Address,city as City, state as State, 
                country as Country, postalCode as Postal_Code,
                createdDate as Created_Date, updatedDate as Last_Updated, type as Added_Type
                FROM customers";        
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            $result=1;
            $this->load->helper('csv_helper');            
            $nowdate=$this->admin->getCustomDate("%Y-%m-%d %H-%i-%s",now());            
            $FileName = trim('Customers-'.$nowdate.'.csv');            
            query_to_csv($query, TRUE, $FileName);            
            $Movedpath='downloads/'.$FileName;            
            $fileMoved = rename($FileName, $Movedpath);
            $logData['action']='export';
            $logData['description']='Exported all the customers data and downloaded the excel sheet';
            $logData['dataId']='';
            $logData['module']='customers';
            $logData['table_name']='customers';
            insert_aLog($logData);
        }else{
            $result=0;
            $Movedpath ='';
        }
        
        $value=array("result"=>$result,"filepath"=>$Movedpath);
        
        echo json_encode($value);
    }

    function settleReferralsView(){
        $customerId = $this->input->post('customerId');
        $settleReferralComissions = $this->curl->execute('customers/settleReferrals/'.$customerId, 'GET');
        $value = array();
        if($settleReferralComissions['status']=='success' && !empty($settleReferralComissions['data_list'])){
            $viewData = array();
            $viewData['userId'] = $customerId;
            $viewData['data_list'] = $settleReferralComissions['data_list'];
            $msg = $this->load->view('admin/customers/comissions/referral_comission_settlement_view', $viewData, true); 
            echo json_encode(array('status'=>'success', 'message'=>$msg));
        }else{
            echo json_encode(array('status'=>'fail', 'message'=>'Couldn\'t Settle the Referral Comissions'));
        }
    }

    // function settleRepurchaseView(){
    //     $customerId = $this->input->post('customerId');
    //     $settleReferralComissions = $this->curl->execute('customers/settleRepurchase/'.$customerId, 'GET');
    //     $value = array();
    //     if($settleReferralComissions['status']=='success' && !empty($settleReferralComissions['data_list'])){
    //         $viewData = array();
    //         $viewData['userId'] = $customerId;
    //         $viewData['data_list'] = $settleReferralComissions['data_list'];
    //         $msg = $this->load->view('admin/customers/comissions/repurchase_comission_settlement_view', $viewData, true); 
    //         echo json_encode(array('status'=>'success', 'message'=>$msg));
    //     }else{
    //         echo json_encode(array('status'=>'fail', 'message'=>'Couldn\'t Settle the Referral Comissions'));
    //     }
    // }

    // function settleRepurchaseAmount(){
    //     $customerId = $this->input->post('customerId');
    //     $settleReferralComissions = $this->curl->execute('customers/settleRepurchases/'.$customerId, 'PUT');
    //     echo json_encode(array('status'=>$settleReferralComissions['status']));
    // }

    function getReferralBonus(){
        $status = 'fail';
        $message = 'No Data Found';
        $daterange = $this->input->post('daterange');
        $customerId = $this->input->post('userId');
        $filterData = array(
            'customer_reference_amount-customer_id'=>$customerId, 
            'perpage'=>1000, 
            'daterange'=>$daterange
        );
        $settleReferralComissions = $this->curl->execute('customer_reference_amount', 'GET', $filterData);
        if($settleReferralComissions['status']=='success' && !empty($settleReferralComissions['data_list'])){
            $status = 'success';
            $data = array();
            $data['customerId'] = $customerId;
            $data['data_list'] = $settleReferralComissions['data_list'];
            $message = $this->load->view('admin/customers/comissions/monthlyReferralData', $data, true);
        }
        echo json_encode(array('status'=>$status,'message'=>$message));
    }



    function getRepurchaseBonus(){
        $status = 'fail';
        $message = 'No Data Found';
        $daterange = $this->input->post('daterange');
        $customerId = $this->input->post('userId');
        $filterData = array(
            'customer_repurchase_amount-customer_id'=>$customerId, 
            'perpage'=>1000, 
            'daterange'=>$daterange
        );
        $settleRepurchaseComissions = $this->curl->execute('customer_repurchase_amount', 'GET', $filterData);
        if($settleRepurchaseComissions['status']=='success' && !empty($settleRepurchaseComissions['data_list'])){
            $status = 'success';
            $data = array();
            $data['customerId'] = $customerId;
            $data['data_list'] = $settleRepurchaseComissions['data_list'];
            $message = $this->load->view('admin/customers/comissions/monthlyRepurchaseData', $data, true);
        }
        echo json_encode(array('status'=>$status,'message'=>$message));
    }

    function settleRepurchaseAmount(){
        $customerId = $this->input->post('customerId');
        if(empty($customerId)){
            echo json_encode(array('status'=>'fail', 'message'=>'Invalid Request'));
            return;
        }
        $settleReferralComissions = $this->curl->execute('customer_repurchase_amount/settle/'.$customerId, 'PUT');
        echo json_encode(array('status'=>$settleReferralComissions['status'], 'message'=>$settleReferralComissions['message']));
    }

    function settleReferalAmount(){
        $customerId = $this->input->post('customerId');
        if(empty($customerId)){
            echo json_encode(array('status'=>'fail', 'message'=>'Invalid Request'));
            return;
        }
        $settleReferralComissions = $this->curl->execute('customer_reference_amount/settle/'.$customerId, 'PUT');
        echo json_encode(array('status'=>$settleReferralComissions['status'], 'message'=>$settleReferralComissions['message']));
    }

}