<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		// $this->load->model("adminmodel","",true);
        // $this->load->model("supportmodel","",true);
		$this->admin->nocache(); 
        $this->module_name='support';
		$this->view_path = "admin/support";
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Support Enquiries";
		$this->load->view("admin/support/support_view",$data);
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
        
        $str=$this->load->view("admin/support/support_tbl_view",$data,true);
        $value=array('str'=>$str,'status'=>$apidata['status']);
         echo json_encode($value);
    }

    function view(){
        $data=array();
        $id=$this->input->post('id');
        $code=$this->input->post('code');
        $where = array('support_enquiries-id'=>$id,'support_enquiries-code'=>$code);
        $apidata=$this->curl->execute('support', 'GET', $where);
        // print_R($data['mainQ']);exit();
        if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
            // $data['tblQ']=$this->supportmodel->get_ticket_comments($id);
            $data['support_enquiry'] = $apidata['data_list'];
            $msg=$this->load->view("admin/support/modal_details_view",$data,true);
            $status='success';
        }else{
            $msg='Unable to fecth your ticket details!';
            $status='fail';
        }
        //print_r($this->db->last_query());
        
        $value=array('msg'=>$msg,'status'=>$status);
        echo json_encode($value);
    }

    function update_ticket(){
        // print_R($this->input->post());exit();
        $id=$this->input->post("id");
        $data['customerId']=$this->input->post("cid");
        $data['comments']=$this->input->post("comments");
        $data['status']=$this->input->post("status");

        // $data['from_type']=0;
        // $data['is_read']=1;

        // $data['reply_date']=get_curentTime();
        $res = $this->curl->execute( $this->module_name."/".$id, "PUT", $data);
        if($res['status'] == 'success' && !empty($res['data_list'])){
            $msg = $res['status'];
        }else{
             $msg = $res['status'];
        }
        // $res=$this->adminmodel->insert_table_data('support_enquiries_details',$data);
        // if($status==0){
        //     $where = array('id' => $data['support_id']);
        //     $up_data['closedDate']=get_curentTime();
        //     $up_data['status']=$status;
        //     $res = $this->adminmodel->update_table_data('support_enquiries',$where,$up_data);

        //     $get_res=$this->adminmodel->get_table_data('support_enquiries',$where,'*',true);

        //     $logData['action']='reply';
        //     $logData['description']='resoved the support enquiry of '.$get_res->row()->email.' assigned to '.$get_res->row()->code;
        //     $logData['dataId']=$get_res->row()->id;
        //     $logData['module']='support enquiries';
        //     $logData['table_name']='support_enquiries';
        //     insert_aLog($logData);
        // }

        // if($res>0){
        //     $msg='Your reply has been sent...';
        //     $logData['action']='reply';
        //     $logData['description']='replied to the support enquiry of '.$get_res->row()->email.' assigned to '.$get_res->row()->code;
        //     $logData['dataId']=$get_res->row()->id;
        //     $logData['module']='support enquiries';
        //     $logData['table_name']='support_enquiries';
        //     insert_aLog($logData);
        // }else{
        //     $msg="Error in updating ticket!";
        // }
        //print_r($this->db->last_query());
        $value=array(
            'result'=>$res,
            'msg'=>$msg
        );
        echo json_encode($value);
    }

}