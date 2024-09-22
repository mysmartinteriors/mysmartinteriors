<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

	function __construct(){
		parent::__construct();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("productsmodel","",true);
		// $this->load->model("categorymodel","",true);
		// $this->load->model("usermodel","",true);
	}

	function index() {
	    $data = $this->admin->commonFiles();
		$data['title']="Contact support";
		//if(is_logged()){
	    	$uid=get_userId();
	    	// $data['userQ']=$this->usermodel->get_user_details($uid);
	 	//}
		$this->load->view("contact_view",$data);
    }

    function save_support(){
		$userId=get_userId();
		$data['name']=$this->input->post("name");
		$data['email']=$this->input->post("email");
		$data['phone']=$this->input->post("phone");
		$data['subject']=$this->input->post("subject");
		$data['message']=$this->input->post("message");
		$data['submitDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
		$data['status']=58;

		$cust_data['firstName']=$data['name'];
		$cust_data['email']=$data['email'];
		$cust_data['phone']=$data['phone'];
		$cust_data['status']=14;
		$cust_data['updatedDate'] = get_curentTime();
		$customerId='';

		// print_R($data);echo "<hr>";

		if($userId!=''){
			$get_res = $this->curl->execute("customers/$userId", "GET");
			
			if($get_res['status'] == 'success' && !empty($get_res['data_list'])){
				if($get_res['data_list']['type']=='Guest'){
					$cust_data['type'] = 'Register';
					$update_data = $this->curl->execute("customers/$userId", "PUT", $cust_data);
					if($update_data['status'] == 'success' && !empty($update_data['data_list'])){
						$data['customerId']=$update_data['data_list']['id'];
						$data['user_type']=0;					
					}else{
						$data['customerId']=$get_res['data_list']['id'];
						$data['user_type']=0;	
					}
				}else{
					$data['customerId']=$get_res['data_list']['id'];
					$data['user_type']=1;
				}
				// print_R($data);exit();
			}else{
				// $where = array('email' => $data['email']);
				// $get_res = $this->adminmodel->get_table_data('customers',$where,'*',true);
				// $get_res = $this->curl->execute("customers/$userId", "GET");
				// if($get_res['status'] == 'success' && !empty($get_res[''])){
				// 	$data['customerId']=$get_res->row()->customerId;
				// 	$data['user_type']=1;
				// }else{
					$cust_data['type'] = 'Register';
					$cust_data['createdDate'] = $cust_data['updatedDate'];
					$ins_res = $this->curl->execute("customers", "POST", $cust_data);
					if($ins_res['status'] == 'success' && !empty($ins_res['data_list'])){
						$data['customerId']=$ins_res['data_list']['id'];
						$data['user_type']=0;
					}
				// }
			}
		}else{
			$where = array('customers-email' => $data['email']);
			$get_res = $this->curl->execute("customers", "GET", $where);
			
			if($get_res['status'] == 'success' && !empty($get_res['data_list'])){
				$data['customerId']=$get_res['data_list'][0]['id'];
				$data['user_type']=1;
			}else{
				$cust_data['type'] = 'Register';
				$cust_data['createdDate'] = $cust_data['updatedDate'];
				$ins_res = $this->curl->execute("customers", "POST", $cust_data);
				if($ins_res['status'] == 'success' && !empty($ins_res['data_list'])){
					$data['customerId']=$ins_res['data_list']['id'];
					$data['user_type']=0;
				}
			}
		}

		// $this->usermodel->unset_only();
		// $userdata=array(
		// 	'gt_uId'=>$data['customerId'],
		// 	'gt_uName' => $data['name'],
		// 	'gt_uFullName' => $data['name'],
		// 	'gt_uEmail' => $data['email'],
		// 	'gt_isULogged'=>1
		// );
		// $this->session->set_userdata($userdata);

		$res = $this->curl->execute("support", "POST", $data);
		// print_R($res);exit();
		//print_r($this->db->last_query());
		if($res['status'] == 'success' && !empty($res['data_list'])){
			$result = "success";
			$msg = "We have received your support request...We will get back to you soon!";
		}else{
			$result = "fail";
			$msg = "Unable to submit your request! Try after sometime...";
		}
		$value=array(
			'result'=>$result,
			'msg' =>$msg
		);
		echo json_encode($value);
        exit;
    }


}