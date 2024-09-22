<?php
class Adminusers extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		check_admin_session();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("permissionmodel","",true);
		$this->admin->nocache();
		$this->module_name='users';
		$this->view_path = "admin/users";
	}

	function index(){
        // check_permission(1,'view','uri');
        $data = $this->admin->commonadminFiles();
        $data['title'] = "Admin Users";
        $data['rolesQ'] = $this->curl->execute('roles','GET');
        $this->load->view("admin/users/users_view", $data);
    }

	function get_datas(){		
		// print_R("HERE");exit();
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
		$str=$this->load->view("admin/users/users_tbl_view",$data,true);
		$value=array(
            'str'=>$str
        );
		echo json_encode($value);
	}

	function add(){
        // check_permission(1,'add','uri');
		$data=array();
		$data = $this->admin->commonadminFiles();
		$data['title']="Add user";
		$apidata = $this->curl->execute("roles", "GET");
		// print_R($apidata);exit();
		if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
			$data['roles_data'] = $apidata['data_list'];
		}
		$this->load->view("admin/users/add_users_view",$data);
	}
	
	function edit(){
        // check_permission(1,'edit','uri');
		$id=$this->uri->segment(4);
		if($id!=""){
			$data=array();
			$data = $this->admin->commonadminFiles();
			$data['title']="Edit user details";
			$apidata = $this->curl->execute("users/$id", "GET");
			if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
				$roles_data = $this->curl->execute("roles", "GET");
				if($roles_data['status'] == 'success' && !empty($roles_data['data_list'])){
					$data['roles_data'] = $roles_data['data_list'];
				}
				$data['dataQ'] = $apidata['data_list'];
				$this->load->view("admin/users/edit_users_view",$data);
			// }
			// $where = array('adminId' => $id);

			// $q=$this->adminmodel->get_table_data('admin_users',$where,'*',true);
			// $data['dataQ']=$q;
			// if($q->num_rows()>0){
			// 	$this->load->view("admin/users/edit_users_view",$data);
			}else{
				redirect(base_url().'admin/error_404');
			}
		}else{
			redirect(base_url().'admin/dashboard');
		}
	}
	
	function saveuser(){
		$adminId=$this->input->post("id");
		$fisrtName=$this->input->post("firstName");
		$lastName=$this->input->post("lastName");
		$username=$this->input->post("login_id");
		$password=$this->input->post("password");
		$email=$this->input->post("email");
		$mobile=$this->input->post("mobile");
		$perma_address=$this->input->post("perma_address");
		$temp_address=$this->input->post("temp_address");
		$roleId=$this->input->post("roles_id");
		$status=$this->input->post("status");
		$is_sadmin=$this->input->post("is_sadmin");
		$is_restricted=$this->input->post("is_restricted");
		$data['adminId']=$adminId;
		$data['firstName']=$fisrtName;
		$data['lastName']=$lastName;
		$data['login_id']=$username;
		$data['password']=$password;
		$data['email']=$email;
		$data['mobile']=$mobile;
		$data['perma_address']=$perma_address;
		$data['temp_address']=$temp_address;
		$data['roles_id']=$roleId==""?0:$roleId;
		$data['is_sadmin']=$roleId==""?0:$is_sadmin;
		$data['is_restricted']=$roleId==""?1:$is_restricted;
		$data['status']=$status==""?7:$status;
		$result=0;
		if(!empty($adminId)){
			$apidata = $this->curl->execute("users/$adminId", "PUT", $data);
		}else{
			$apidata = $this->curl->execute("users", "POST", $data);
		}
		
		if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
			$status = $apidata['status'];
			$message = $apidata['message'];
		}else{
			$status = $apidata['status'];
			$message = $apidata['message'];
		}
		$value=array(
			'status'=>$status,
			'message'=>$message
		);
		echo json_encode($value);
	}
	
	function deluser(){
        // check_permission(1,'delete','json');
		$id=$this->input->post("id");
		if(!empty($id)){
			$apidata = $this->curl->execute("users/$id", "DELETE");
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
			'message' =>  $message
		);
		echo json_encode($value);
	}	
	
	function checkusername(){
		$username=$this->admin->escapespecialchrs(trim($this->input->post('login_id')));
		$edit=$this->admin->escapespecialchrs($this->input->post('edit'));
		$q=0;
		if(strtolower($edit)!=strtolower($username)){
			$apidata = $this->curl->execute("users", "GET", array("users.login_id" => $username));
			if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
				$result = 'false';
			}else{
				$result = 'true';
			}
			echo $result;
		}	
	}
	
	function checkemail(){
		$email=$this->admin->escapespecialchrs(trim($this->input->post('email')));
		$edit=$this->admin->escapespecialchrs($this->input->post('edit'));
		$q=0;
		if(strtolower($edit)!=strtolower($email)){
			$apidata = $this->curl->execute("users", "GET", array("users.email" => $email));
			if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
				$result = 'false';
			}else{
				$result = 'true';
			}
			echo $result;
		}	
	}

}