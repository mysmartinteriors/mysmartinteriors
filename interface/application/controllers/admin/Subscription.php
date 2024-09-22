<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		// $this->load->model("adminmodel","",true);
        // $this->load->model("supportmodel","",true);
		$this->admin->nocache(); 
        $this->module_name='subscription'; 
		$this->view_path = "admin/subscription";
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="subscription Plans";
		$this->load->view("admin/subscription/subscription_view",$data);
    }

    function get_datas(){
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
        $apidata=$this->curl->execute($this->module_name,"GET",$filterData,'filter');
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
        
        $str=$this->load->view("admin/subscription/subscription_tbl_view",$data,true);
        $value=array('str'=>$str,'status'=>$apidata['status']);
         echo json_encode($value);
    }

    function add_new(){
        // check_permission(4,'add','json');
    	$id=$this->input->post('id');
        
        $data = array();
        if($id!=''){
            $apidata = $this->curl->execute("subscription/$id", "GET");
            if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
                $data['dataQ'] = $apidata['data_list'];
            }
        }
        $str=$this->load->view("admin/subscription/add_subscription_view",$data,true);
        $value=array(
            'status'=>'success',
            'message'=>$str
        );
        echo json_encode($value);
    }

    function save_add_data(){
        // print_R($this->input->post());exit();
        $id = $this->input->post("id");
        $data['basic_amount']=$this->input->post("basic_amount");
        $data['name']=$this->input->post("name");
        $data['wallet_points']=$this->input->post("wallet_points");
        $data['status']=$this->input->post("status");

        $msg="No changes saved...";
        
        if(!empty($id)){
            $apidata = $this->curl->execute("subscription/$id", "PUT", $data);
        }else{
            $apidata = $this->curl->execute("subscription", "POST", $data);
        }
        if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
            // $result = $apidata['data_list'];
            $status = $apidata['status'];
            $message = $apidata['message'];
        }else{
            // $result = '';
            $status = $apidata['status'];
            $message = $apidata['message'];
        }

        // $q=$this->slidermodel->save_slider($data);
        $value=array(
            'status'=>$status,
            'message'=>$message
        );
        
        echo json_encode($value);
    }

     function delete_subscription(){
        // check_permission(4,'delete','json');
        $id=$this->input->post("id");
        $status='';
        $msg='Cannot delete at this time...';
        if($id!=""){ 
            $apidata = $this->curl->execute("subscription/$id", "DELETE");
            if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
                $status = $apidata['status'];
                $message = $apidata['message'];
            }else{
                $status = $apidata['status'];
                $message = $apidata['message'];
            }
        }
        $value=array(
            'status'=>$status,
            'message'=>$message
        );
        echo json_encode($value);
    }
}