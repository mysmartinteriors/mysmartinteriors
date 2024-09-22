<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_logs extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		$this->admin->nocache(); 
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Whatsapp Logs";
		$this->load->view("admin/whatsapp_logs/whatsapp_logs_view",$data);
    }

    function get_logs(){
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
    	$apidata=$this->curl->execute('whatsapp_logs',"GET",$filterData,'filter');
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
    	$str = $this->load->view("admin/whatsapp_logs/whatsapp_logs_tbl_view", $data, true);
    	$value=array('str'=>$str,'status'=>$apidata['status']);
    	echo json_encode($value);
    }
}