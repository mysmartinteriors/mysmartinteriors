<?php
class roles extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		check_admin_session();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("permissionmodel","",true);
		$this->admin->nocache();
		$this->module_name='roles';
		$this->view_path = "admin/roles";
	}
	
	function index(){
        // check_permission(2,'view','uri');
		$data = $this->admin->commonadminFiles();
		$data['title']="Roles";
		$this->load->view("admin/roles/roles_view",$data);
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
		$str=$this->load->view("admin/roles/roles_tbl_view",$data,true);		
		$value=array('str'=>$str);
         echo json_encode($value);
	}
	
	function add(){
        // check_permission(2,'add','uri');
		$data=array();
		$data = $this->admin->commonadminFiles();
		$data['title']="Create user role";	
		$modules = $this->curl->execute("modules", "GET", ["status" => 1, "perpage" => 10000]);
		if($modules['status'] == 'success' && !empty($modules['data_list'])){
			$data['modules'] = $modules['data_list'];
		}
		$this->load->view("admin/roles/add_roles_view",$data);
	}

	function edit(){
        // check_permission(2,'edit','uri');
		$id=$this->uri->segment(4);
		if($id!=""){
			$apidata = $this->curl->execute("roles/$id", "GET");
			if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
				$data=array();
				$data = $this->admin->commonadminFiles();
				$data['title']="Edit user roles";
				$data['roleQ']=$apidata['data_list'];
				$permission = $this->curl->execute("module_permissions", "GET", ['roleId' => $id, "status" => 1]);
				// print_R($permission);exit();
				if($permission['status'] == 'success' && !empty($permission['data_list'])){
					$data['permitQ']=$permission['data_list'];
				}
				$this->load->view("admin/roles/edit_roles_view",$data);
			// }
			// $q=$this->adminmodel->get_table_data('admin_roles',$where,'*',true);
			// if($q->num_rows()>0){
			// 	$data=array();
			// 	$data = $this->admin->commonadminFiles();
			// 	$data['title']="Edit user roles";
			// 	$data['permitQ']=$this->permissionmodel->getrolepermission($id);
			// 	$data['roleQ']=$q;
			// 	$this->load->view("admin/roles/edit_roles_view",$data);
			}else{
				redirect(base_url().'admin/error_404');
			}
		}else{			
			redirect(base_url().'admin/dashboard');
		}
	}

	function checkrolename(){
		$roleName=$this->admin->escapespecialchrs(trim($this->input->post('name')));
		$edit=$this->admin->escapespecialchrs($this->input->post('edit'));
		$q=0;
		$result="true";
		if(strtolower($edit)!=strtolower($roleName)){
			$apidata = $this->curl->execute("roles", "GET", array("name" => $roleName));
			if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
				$result="false";
			}else{
				$result="true";
			}
			echo $result;
		}	
	}
		
	function saverole(){
		$a=$this->input->post();
		$roleId=$a['id'];
		$roleName=$a['name'];
		$description=$a['description'];
		$permissionId="";
		$data1=array(
			'roleId'=>$roleId,
			'name'=>$roleName,
			'description'=>$roleName
		);
		$result='fail';
		$msg='Unable to update at this time.';
		$apidata = $this->curl->execute("roles", "POST", $data1);
		if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
			$roleId = $apidata['data_list']['id'];
			$moduleIdarr=array();
			foreach ($a as $k=>$v){
				$arr=explode("_",$k);
				$moduleId=isset($arr[1])?$arr[1]:"";
				if(!in_array($moduleId, $moduleIdarr)){
					$moduleIdarr[]=$moduleId;
				}
			}
			$data=array(
				'roleId'=>$roleId,
				'view'=>0,
				'add'=>0,
				'edit'=>0,
				'delete'=>0,
				'status'=>1
			);
			
			foreach ($moduleIdarr as $v){
				if($v!=""){
					$data2['roleId']=$roleId;
					$data2['moduleId']=$v;
					$data2['view']=isset($a['view_'.$v])?1:0;
					$data2['add']=isset($a['add_'.$v])?1:0;
					$data2['edit']=isset($a['edit_'.$v])?1:0;
					$data2['delete']=isset($a['delete_'.$v])?1:0;
					$data2['status']=1;
					$apidata = $this->curl->execute('module_permissions',"POST",$data2);
					// print_r($apidata);
					// $q=$this->permissionmodel->savepermission($data2);
				}
			}//exit();
			$result='success';
			$msg='Role and permissions has been saved successfully';
		}
		$value=array(
			'result'=>$result,
			'msg'=>$msg
		);
		echo json_encode($value);		
	}
	
    function del_role(){
        // check_permission(2,'delete','json');
		$id=$this->input->post("id");
		if(!empty($id)){
			$apidata = $this->curl->execute("roles/$id", "DELETE");
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
}