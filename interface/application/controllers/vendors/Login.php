<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {

	function __construct(){
		parent::__construct();
		if(is_vLogged()){			
			redirect(base_url().'vendors/dashboard');			
		}
		$this->admin->nocache(); 
	}

	public function index()	{
		$data = $this->admin->commondeliveryFiles();
		unset($data['header_menu']);
		unset($data['header_main']);
		$data['title'] = "Delivery Login";
		$this->load->view('vendors/login_view',$data);
	}
	
	function authentication() {
		$result='';
		$userdata='';
		$urldirect = base_url().'vendors/login';
		$str=0;
		$username=$this->input->post("uid");
		$password=$this->input->post("pwd");
		$remember=$this->input->post("remember");
		$apidata=$this->curl->execute("vendors/authentication","GET",array('phone'=>$username));
		$row_data = $apidata['data_list'];
		// print_r($row_data);exit();
		if(is_array($row_data) && !empty($row_data)){
			// if($row_data['status'] == "40") {
				$check_pass = $this->Mydb->verifyHash($password, $row_data['password']);

				if($check_pass){
					$userdata=array(
						'nl_vendor_id'=>$row_data['id'],
						'nl_vendor_uname'=>$row_data['name'],
						'nl_vendor_isLoggedIn'=>1
					);
					if($remember){
						setRemember($remember, $username, $password);
					}
					$this->session->set_userdata($userdata);
					$result='success';
					$msg="Login success! Redirecting...";

					$logData=array(
						'action'=>'login',
			        	'description'=>'logged in to the system',
			        	'data_id'=>$row_data['id'],
			        	'module'=>'session'
			        );
			        if($this->session->userdata('redirect_url')!=""){
		            	$urldirect =  $this->session->userdata('redirect_url');
					}else{
						$urldirect = base_url().'vendors/dashboard';
					}
				}else{
					$msg="Wrong Password! Please Check Your Password";
				}
			// }else{
			// 	$msg="Your account has been suspended please contact support!";
			// }
		}else{
			$msg="Your account is not found";
		}
		$value=array(
			'result'=>$result,
			'msg'=>$msg,
			'urldirect'=>$urldirect
		);
		echo json_encode($value);
	}

	public function forgot()	{
		$data = $this->delivery->commondeliveryFiles();
		unset($data['header_menu']);
		unset($data['header_main']);
		$data['title'] = "delivery Login";
		$this->load->view('delivery/login_forgot_view',$data);
	}

	function forgot_pass(){		
		$username=$this->input->post("uid");
		$email=$this->input->post("email");
		$result=0;
		//send email
        $where = array('username' => $username,'email'=>$email );
        $userQ=$this->deliverymodel->get_table_data('delivery_users',$where,'*',true);
        if($userQ->num_rows()>0){
	        $mailData['name']=ucwords($userQ->row()->username);
	        $mailData['password']=ucfirst($userQ->row()->password);
	        $mailData['mailTo']=$userQ->row()->email;
	        $mailData['mailSubject']='Forgot your delivery password?';
	        $mailsend=send_email('delivery_login_forgot',$mailData);
	        if($mailsend){
				$result=1;
	        	$msg='Please check your email and login again';
	        }else{
	        	$msg='Unable to send email at this time!';
	        }
        }else{
        	$msg='Invalid username or email address!';
        }

		$value=array(
			'result'=>$result,
			'msg'=>$msg
		);
		echo json_encode($value);
	}
	
}
