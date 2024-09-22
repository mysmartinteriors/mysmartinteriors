<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		// $this->load->model("adminmodel","",true);
        // $this->load->model("reportsmodel","",true);
		$this->admin->nocache();
        $this->module_name='reports';
		$this->view_path = "admin/reports";
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Reports";
		$this->load->view("admin/reports/reports_view",$data);
    }


    function getreports() {
        $module=  $this->input->post("module");
        $status=   $this->input->post("status");
        $dateType=   $this->input->post("dateType");
        $daterange=   $this->input->post("daterange");
        $fromTime=substr($daterange,0,10);
        $toTime=substr($daterange,13,10);
        $str="";
        $fromTime=$fromTime!=""?  $this->admin->getCustomDate("%Y-%d-%m",strtotime($fromTime)):"";
        $toTime=$toTime!=""?  $this->admin->getCustomDate("%Y-%d-%m",strtotime($toTime)):"";

        $from=$fromTime.' 00:00:00';
        $to= $toTime.' 23:59:59';

        if($module=="customers"){
            $customer_data = $this->curl->execute("reports/customer_report", "GET", array("status" => $status, "dateType" => $dateType, "from" => $from, "to" => $to));
            // print_R($customer_data);exit();
            if($customer_data['status'] == 'success' && !empty($customer_data['data_list'])){
                $data['dataQ'] = $customer_data['data_list'];
                $str = $this->load->view("admin/reports/customers_tbl", $data, true);
            }
            // $data['dataQ'] = $this->reportsmodel->get_customer_reports($status,$dateType,$from,$to);
        }        
        if($module=="products"){
            // $data['dataQ'] = $this->reportsmodel->get_product_reports($status,$dateType,$from,$to);
            $product_data = $this->curl->execute("reports/product_report", "GET", array("status" => $status, "dateType" => $dateType, "from" => $from, "to" => $to));
            // print_R($product_data);exit();
            if($product_data['status'] == 'success' && !empty($product_data['data_list'])){
                $data['dataQ'] = $product_data['data_list'];
                $str = $this->load->view("admin/reports/products_tbl", $data, true);      
            }
        }
        if($module=="orders"){
            // $data['dataQ'] = $this->reportsmodel->get_order_reports($status,$dateType,$from,$to);
            $order_data = $this->curl->execute("reports/order_report", "GET", array("status" => $status, "dateType" => $dateType, "from" => $from, "to" => $to));
            // print_R($order_data);exit();
             if($order_data['status'] == 'success' && !empty($order_data['data_list'])){
                $data['dataQ'] = $order_data['data_list'];
                $str = $this->load->view("admin/reports/orders_tbl", $data, true);
             }
        }
        if($module=="prd_orders"){
            // $data['dataQ'] = $this->reportsmodel->get_prd_order_reports($status,$dateType,$from,$to);
            $prd_order_data = $this->curl->execute("reports/prd_order_report", "GET", array("status" => $status, "dateType" => $dateType, "from" => $from, "to" => $to));
            // print_R($prd_order_data);exit();
            if($prd_order_data['status'] == 'success' && !empty($prd_order_data['data_list'])){
                $data['dataQ'] = $prd_order_data['data_list'];
                $str = $this->load->view("admin/reports/product_orders_tbl", $data, true);
            }
        }
        if(!empty($data['dataQ'])){
            $result='success';
        }else{
            $result='fail';
        }
        $value = array(
            'str' => $str,"result"=>$result
        );
        //print_r($this->db->last_query());
        echo json_encode($value);

    }


    function download(){
        $module=  $this->input->post("module");
        $status=   $this->input->post("status");
        $ordstatus=   $this->input->post("ordstatus");
        $dateType=   $this->input->post("dateType");
        $daterange=   $this->input->post("daterange");
        $fromTime=substr($daterange,0,10);
        $toTime=substr($daterange,13,10);

        $str="";
        $fromTime=$fromTime!=""?  $this->admin->getCustomDate("%Y-%d-%m",strtotime($fromTime)):"";
        $toTime=$toTime!=""?  $this->admin->getCustomDate("%Y-%d-%m",strtotime($toTime)):"";
        
        $from=$fromTime.' 00:00:00';
        $to= $toTime.' 23:59:59';
        $statusType="All";

        if($module=="customers"){
            $customer_data = $this->curl->execute("reports/customer_report_download", "GET", array("status" => $status, "dateType" => $dateType, "from" => $from, "to" => $to));
            // print_R($customer_data);exit();
            if($customer_data['status'] == 'success' && !empty($customer_data['data_list'])){
                $query = $customer_data['data_list'];
                $fileTitle="Customers";
                if($status!=""){
                    if($status==1){ $statusType="Active";}
                    else if($status==2){ $statusType="InActive";};
                }
            }
        }        
        // if($module=="products"){
        //     $query = $this->reportsmodel->product_download($status,$dateType,$from,$to);
        //     $fileTitle="Products";
        //     if($status!=""){
        //         if($status==1){ $statusType="Active";}
        //         else if($status==2){ $statusType="InActive";};
        //     }
        // }
        // if($module=="orders"){
        //     $query = $this->reportsmodel->orders_download($status,$dateType,$from,$to);
        //     $fileTitle="Orders";
        //     if($ordstatus!=""){
        //         if($ordstatus==0){ $statusType="Online";}
        //         else if($ordstatus==1){ $statusType="COD";};
        //     }
        // }
        // if($module=="prd_orders"){
        //     $query = $this->reportsmodel->prd_sales_download($status,$dateType,$from,$to);
        //     $fileTitle="Product_Sales";
        //     $statusType="All";
        // }
        // if($module=="support"){
        //     $query = $this->reportsmodel->support_download($status,$dateType,$from,$to);
        //     $fileTitle="Support_Enquiries";
        //     if($status!=""){
        //         if($status==0){ $statusType="Pending";}
        //         else if($status==1){ $statusType="Resolved";};
        //     }
        // }
        // if($module=="newsletter"){
        //     $query = $this->reportsmodel->newsletter_download($status,$dateType,$from,$to);
        //     $fileTitle="Newsletter";
        //     if($status!=""){
        //         if($status==0){ $statusType="Unsubscribed";}
        //         else if($status==1){ $statusType="Active";};
        //     }
        // }
        // print_R("HERE");exit();
        // exit();
        if(!empty($query)){
            $result='success';
        }else{
            $result='fail';
        }
        $Movedpath='';
        if($result=='success'){
            $this->load->helper('csv_helper');            
            $nowdate=$this->admin->getCustomDate("%Y-%m-%d %H-%i-%s",now());            
            $FileName = trim($statusType.'-'.$fileTitle.'-'.$nowdate.'.csv'); 
            
            // query_to_csv($query, TRUE, $FileName);            
            array_to_csv($query, TRUE, $FileName);            
            $Movedpath='downloads/'.$FileName;            
            $fileMoved = rename($FileName, $Movedpath);            

            // $logData['action']='Download';
            // $logData['description']='downloaded '.$module.' report from '.$fromTime.' to '.$toTime;
            // $logData['dataId']='';
            // $logData['module']='Reports';
            // $logData['table_name']=$module;
            // insert_aLog($logData);
        }
        $value=array("result"=>$result,"filepath"=>$Movedpath);
            
        echo json_encode($value);
    }


    
}