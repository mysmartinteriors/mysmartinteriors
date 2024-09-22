<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		// $this->load->model("adminmodel","",true);
        // $this->load->model("supportmodel","",true);
		$this->admin->nocache(); 
        $this->module_name='delivery';
		$this->view_path = "admin/delivery";
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Delivery";
		$this->load->view("admin/delivery/delivery_view",$data);
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
        
        $str=$this->load->view("admin/delivery/delivery_tbl_view",$data,true);
        $value=array('str'=>$str,'status'=>$apidata['status']);
         echo json_encode($value);
    }

    function add(){
        // check_permission(4,'add','json');
    	$id=$this->input->post('id');
    	$data=array();
        if($id!=''){
            $where = array('id' => $id );
            $apidata = $this->curl->execute("delivery/$id", "GET");
            if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
                $data['dataQ'] = $apidata['data_list'];
            }
            $str=$this->load->view("admin/delivery/edit_delivery_view",$data,true);
        }else{
            $str=$this->load->view("admin/delivery/add_delivery_view",$data,true);
        }
        $value=array(
            'result'=>1,
            'str'=>$str
        );
        echo json_encode($value);
        // $data = $this->admin->commonadminFiles();
        // $data['title']="Add Delivery Boy";
        // $this->load->view("admin/delivery/add_delivery_view",$data);
    }

    function check_exists(){
        // print_R($this->input->post());exit();
        $name=$this->admin->escapespecialchrs($this->input->post('phone'));
        $edit=$this->admin->escapespecialchrs($this->input->post('edit'));
        // $q=0;
        if(strtolower($edit)!=strtolower($name)){
            $where = array('phone' => $name );
            $apidata=$this->curl->execute('delivery/check_exist', "GET", $where);
            // print_R($query);exit();
            // $q=$query->num_rows();
            if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
                $result="false";
            }else{
                $result="true";
            }
        }else{
            $result = 'true';
        }
        echo $result;
    }

    function save_delivery(){
        // print_R($this->input->post());
        // exit();
        $id = $this->input->post("id");
        $data['name']=$this->input->post("name");
        $data['code']=$this->input->post("code");
        $data['email']=$this->input->post("email");
        $data['phone']=$this->input->post("phone");
        $data['password']=$this->input->post("password");
        $data['address']=  $this->input->post("address");
        $data['pincode']=  $this->input->post("pincode");
        $data['availability']=  $this->input->post("availability");
        $data['profile_picture']=  $this->input->post("profile_picture");
        $data['aadhaar_card']=  $this->input->post("aadhaar_card");
        $data['pan_card']=  $this->input->post("pan_card");
        $data['driving_licence']=  $this->input->post("driving_licence");
        $data['vehicle_rc']=  $this->input->post("vehicle_rc");
        $data['status']=  $this->input->post("status");
        $dir = 'uploads/delivery/';
        // create_directory($dir);
        if (!file_exists($dir )) {
            mkdir($dir , 0777, true);
        }
        $image_old = '';
        $extensions = array("jpeg","jpg","png","PNG","JPEG","JPG");
        // print_R($_FILES);exit();
        if(!empty($_FILES)){
            $key = 0;
            if(!empty($_FILES['profile_picture']['tmp_name'][$key])){
                $file_name = $_FILES['profile_picture']['name'][$key];
                $file_size =$_FILES['profile_picture']['size'][$key];
                $file_tmp =$_FILES['profile_picture']['tmp_name'][$key];
                $file_type=$_FILES['profile_picture']['type'][$key];        
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                if($file_size > 2048) {
                    $msg='File size should not exceed 2 MB';
                }
                if(in_array($ext,$extensions ) === true)
                {  

                    $target = $dir.$file_name;
                    if(file_exists($target)){
                        $file_name = rand(01,1000).$file_name;
                    }
                    $target = $dir.$file_name;
                    if(move_uploaded_file($file_tmp, $target))
                    {
                        $data['profile_picture']=$file_name;
                    }else{
                        $msg = 'Error in uploading file';               
                    }
                
                }else{
                    $msg = 'Error in uploading file. <br>File type is not allowed.';
                }
                $key++;
            }else{
                $data['profile_picture']=$image_old;
            }
                $key = 0;
            if(!empty($_FILES['aadhaar_card']['tmp_name'][$key])){
                $file_name = $_FILES['aadhaar_card']['name'][$key];
                $file_size =$_FILES['aadhaar_card']['size'][$key];
                $file_tmp =$_FILES['aadhaar_card']['tmp_name'][$key];
                $file_type=$_FILES['aadhaar_card']['type'][$key];        
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                if($file_size > 2048) {
                    $msg='File size should not exceed 2 MB';
                }
                if(in_array($ext,$extensions ) === true)
                {  

                    $target = $dir.$file_name;
                    if(file_exists($target)){
                        $file_name = rand(01,1000).$file_name;
                    }
                    $target = $dir.$file_name;
                    if(move_uploaded_file($file_tmp, $target))
                    {
                        $data['aadhaar_card']=$file_name;
                    }else{
                        $msg = 'Error in uploading file';               
                    }
                
                }else{
                    $msg = 'Error in uploading file. <br>File type is not allowed.';
                }
                $key++;
            }else{
                $data['aadhaar_card']=$image_old;
            }
            $key = 0;
            if(!empty($_FILES['pan_card']['tmp_name'][$key])){
                $file_name = $_FILES['pan_card']['name'][$key];
                $file_size =$_FILES['pan_card']['size'][$key];
                $file_tmp =$_FILES['pan_card']['tmp_name'][$key];
                $file_type=$_FILES['pan_card']['type'][$key];        
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                if($file_size > 2048) {
                    $msg='File size should not exceed 2 MB';
                }
                if(in_array($ext,$extensions ) === true)
                {  

                    $target = $dir.$file_name;
                    if(file_exists($target)){
                        $file_name = rand(01,1000).$file_name;
                    }
                    $target = $dir.$file_name;
                    if(move_uploaded_file($file_tmp, $target))
                    {
                        $data['pan_card']=$file_name;
                    }else{
                        $msg = 'Error in uploading file';               
                    }
                
                }else{
                    $msg = 'Error in uploading file. <br>File type is not allowed.';
                }
                $key++;
            }else{
                $data['pan_card']=$image_old;
            }
            $key = 0;
            if(!empty($_FILES['driving_licence']['tmp_name'][$key])){
                $file_name = $_FILES['driving_licence']['name'][$key];
                $file_size =$_FILES['driving_licence']['size'][$key];
                $file_tmp =$_FILES['driving_licence']['tmp_name'][$key];
                $file_type=$_FILES['driving_licence']['type'][$key];        
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                if($file_size > 2048) {
                    $msg='File size should not exceed 2 MB';
                }
                if(in_array($ext,$extensions ) === true)
                {  

                    $target = $dir.$file_name;
                    if(file_exists($target)){
                        $file_name = rand(01,1000).$file_name;
                    }
                    $target = $dir.$file_name;
                    if(move_uploaded_file($file_tmp, $target))
                    {
                        $data['driving_licence']=$file_name;
                    }else{
                        $msg = 'Error in uploading file';               
                    }
                
                }else{
                    $msg = 'Error in uploading file. <br>File type is not allowed.';
                }
                $key++;
            }else{
                $data['driving_licence']=$image_old;
            }

            $key = 0;
            if(!empty($_FILES['driving_licence']['tmp_name'][$key])){
                $file_name = $_FILES['driving_licence']['name'][$key];
                $file_size =$_FILES['driving_licence']['size'][$key];
                $file_tmp =$_FILES['driving_licence']['tmp_name'][$key];
                $file_type=$_FILES['driving_licence']['type'][$key];        
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                if($file_size > 2048) {
                    $msg='File size should not exceed 2 MB';
                }
                if(in_array($ext,$extensions ) === true)
                {  

                    $target = $dir.$file_name;
                    if(file_exists($target)){
                        $file_name = rand(01,1000).$file_name;
                    }
                    $target = $dir.$file_name;
                    if(move_uploaded_file($file_tmp, $target))
                    {
                        $data['driving_licence']=$file_name;
                    }else{
                        $msg = 'Error in uploading file';               
                    }
                
                }else{
                    $msg = 'Error in uploading file. <br>File type is not allowed.';
                }
                $key++;
            }else{
                $data['driving_licence']=$image_old;
            }
            $key = 0;
            if(!empty($_FILES['vehicle_rc']['tmp_name'][$key])){
                $file_name = $_FILES['vehicle_rc']['name'][$key];
                $file_size =$_FILES['vehicle_rc']['size'][$key];
                $file_tmp =$_FILES['vehicle_rc']['tmp_name'][$key];
                $file_type=$_FILES['vehicle_rc']['type'][$key];        
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                if($file_size > 2048) {
                    $msg='File size should not exceed 2 MB';
                }
                if(in_array($ext,$extensions ) === true)
                {  

                    $target = $dir.$file_name;
                    if(file_exists($target)){
                        $file_name = rand(01,1000).$file_name;
                    }
                    $target = $dir.$file_name;
                    if(move_uploaded_file($file_tmp, $target))
                    {
                        $data['vehicle_rc']=$file_name;
                    }else{
                        $msg = 'Error in uploading file';               
                    }
                
                }else{
                    $msg = 'Error in uploading file. <br>File type is not allowed.';
                }
                $key++;
            }else{
                $data['vehicle_rc']=$image_old;
            }

        }
    //    print_R($data);exit();
        if(!empty($id)){
            $apidata = $this->curl->execute("delivery/$id", "PUT", $data);
        }else{
            $apidata = $this->curl->execute("delivery", "POST", $data);
        }
        // print_R($apidata);exit();
        if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
            $status = $apidata['status'];
            $message = $apidata['message'];
        }else{
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

    function update_ticket(){
        // print_R($this->input->post());exit();
        $id=$this->input->post("id");
        $data['customerId']=$this->input->post("cid");
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

     function delete(){
        // check_permission(4,'delete','json');
        $id=$this->input->post("id");
        $status='';
        $msg='Cannot delete at this time...';
        if($id!=""){ 
            $apidata = $this->curl->execute("delivery/$id", "DELETE");
            if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
                $status = $apidata['status'];
                $message = $apidata['message'];
            }else{
                $status = $apidata['status'];
                $message = $apidata['message'];
            }
            // $result=$this->slidermodel->delete_slider($id);
        }
        $value=array(
            'status'=>$status,
            'message'=>$message 
        );
        echo json_encode($value);
    }

    public function delivery_address() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Delivery Address";
		$this->load->view("admin/delivery/delivery_address_view",$data);
    }

    function get_addresss_datas(){
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
        $apidata=$this->curl->execute("delivery_address","GET",$filterData,'filter');
        // print_r($apidata);exit();
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
        
        $str=$this->load->view("admin/delivery/delivery_address_tbl_view",$data,true);
        $value=array('str'=>$str,'status'=>$apidata['status']);
         echo json_encode($value);
    }

    function add_address(){
        // check_permission(4,'add','json');
    	$id=$this->input->post('id');
    	$data=array();
        if($id!=''){
            $where = array('id' => $id );
            $apidata = $this->curl->execute("delivery_address/$id", "GET");
            if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
                $data['dataQ'] = $apidata['data_list'];
            }
        }
        $str=$this->load->view("admin/delivery/add_delivery_address_view",$data,true);
        $value=array(
            'result'=>1,
            'str'=>$str
        );
        echo json_encode($value);
    }

    function save_delivery_address(){
        $id = $this->input->post("id");
        $data['address']=  $this->input->post("address");
        $data['pincode']=  $this->input->post("pincode");
        $data['delivery_charge']=  $this->input->post("delivery_charge");
        $data['status']=  $this->input->post("status");
        
       
        if(!empty($id)){
            $apidata = $this->curl->execute("delivery_address/$id", "PUT", $data);
        }else{
            $apidata = $this->curl->execute("delivery_address", "POST", $data);
        }
        // print_R($apidata);exit();
        if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
            $status = $apidata['status'];
            $message = $apidata['message'];
        }else{
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
}