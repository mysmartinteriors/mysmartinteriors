	<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Activitylog extends CI_Controller {

	function __construct(){
		parent::__construct();
		check_admin_session();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("logmodel","",true);
		$this->admin->nocache();
		$this->module_name='activity_log';
		$this->view_path = "admin/activitylog"; 
	}

    function index() {
		$data = $this->admin->commonadminFiles();
        $data['title'] = "Activity Log";
		$actionData = $this->curl->execute("activity_log/actions_log", "GET");
		if($actionData['status'] == 'success' && !empty($actionData['data_list'])){
			$data['actionQ']=$actionData['data_list'];
		}
		$moduleData = $this->curl->execute("activity_log/modules_log", "GET");
		if($moduleData['status'] == 'success' && !empty($moduleData['data_list'])){
			$data['moduleQ']=$moduleData['data_list'];
		}
        $this->load->view("admin/activity_log/log_view", $data);
    }

    function getActivity_Logs() {
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
//  print_R($filter_data);exit();
        $apidata=$this->curl->execute($this->module_name,"GET",$filterData,'filter');
        // print_r($apidata);exit();
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
		// print_R($data);exit();
		$str = $this->load->view("admin/activity_log/log_tbl_view", $data, true);		
		$value=array(
            'str'=>$str
        );
		echo json_encode($value);


		// $page = (int) $this->input->post('page');
		// $filter_data=$this->input->post('filter_data');
		// $get_total_rows = $this->logmodel->filterActivityLogs($filter_data,0,0);
		// $item_per_page="10";
		// foreach ($filter_data as $k => $v) {
		// 	if (($v['type'] == 'prd-perpage') && $v['value'] != "") {
		// 		$item_per_page = (int)$v['value'];
		// 	}
		// }
		// $data = $this->adminmodel->getPaginationData($item_per_page,$page,$get_total_rows);
		
		// $data['logdata']=$this->logmodel->filterActivityLogs($filter_data,$data['item_per_page'],$data['page_position']);
		
        // $str = $this->load->view("admin/activity_log/log_tbl_view", $data, true);		
        // $value = array(
        //     'result' => $str
        // );
        // echo json_encode($value);
    }
      
}
