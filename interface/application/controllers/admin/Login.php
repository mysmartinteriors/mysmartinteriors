<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {

	function __construct(){
		parent::__construct();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("Admin","",true);
		if(is_aLogged()){			
			redirect(base_url().'admin/dashboard');			
		}
		$this->admin->nocache(); 
	}

	public function index()	{
		$data = $this->admin->commonadminFiles();
		unset($data['header_menu']);
		unset($data['header_main']);
		$data['title'] = "Admin Login";
		$this->load->view('admin/login_view',$data);
	}
	
	function authentication() {
		$result='';
		$userdata='';
		$urldirect = base_url().'admin/login';
		$str=0;
		
		$username=$this->input->post("uid");
		$password=$this->input->post("pwd");
		$remember=$this->input->post("remember");
		// $data['username']=$username;
		// $data['password']=$password;
		
		// $q=$this->adminmodel->auth($data);
		$apidata=$this->curl->execute("users/authentication","GET",array('uid'=>$username));
		$row_data = $apidata['data_list'];
		if(is_array($row_data) && !empty($row_data)){
			if($row_data['status'] == "7") {
				$check_pass = $this->Mydb->verifyHash($password, $row_data['password']);
				if($check_pass){
					$userdata=array(
						'gt_aId'=>$row_data['id'],
						'gt_aUname'=>$row_data['first_name'],
						'gt_isadmin'=>$row_data['is_sadmin'],
						'gt_role'=>$row_data['roles_id'],
						'gt_isALogged'=>1
					);
					if($remember){
						setRemember($remember, $username, $password);
					}
    // function setRemember($remember,$uname,$pwd){

					$this->session->set_userdata($userdata);
					$result='success';
					$msg="Login success! Redirecting...";

					$logData=array(
						'action'=>'login',
			        	'description'=>'logged in to the system',
			        	'data_id'=>$row_data['id'],
			        	'module'=>'session'
			        );
			        // Admin::activity($logData);
			        if($this->session->userdata('redirect_url')!=""){
		            	$urldirect =  $this->session->userdata('redirect_url');
					}else{
						$urldirect = base_url().'admin/dashboard';
					}
				}else{
					// $result=0;
					$msg="Wrong Password! Please Check Your Password";
				}
			}else{
				$msg="Your account has been suspended please contact support!";
			}
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
		$data = $this->admin->commonadminFiles();
		unset($data['header_menu']);
		unset($data['header_main']);
		$data['title'] = "Admin Login";
		$this->load->view('admin/login_forgot_view',$data);
	}

	function forgot_pass(){		
		$username=$this->input->post("uid");
		$email=$this->input->post("email");
		$result=0;
		//send email
        $where = array('username' => $username,'email'=>$email );
        $userQ=$this->adminmodel->get_table_data('admin_users',$where,'*',true);
        if($userQ->num_rows()>0){
	        $mailData['name']=ucwords($userQ->row()->username);
	        $mailData['password']=ucfirst($userQ->row()->password);
	        $mailData['mailTo']=$userQ->row()->email;
	        $mailData['mailSubject']='Forgot your admin password?';
	        $mailsend=send_email('admin_login_forgot',$mailData);
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
