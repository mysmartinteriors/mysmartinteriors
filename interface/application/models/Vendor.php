<?php
class Vendor extends CI_Model{    
	private $table_name='users';
	private static $db;

    function __construct(){
        parent::__construct();
        self::$db = &get_instance()->db;
    }
	
	static function check_session()
    {
      	$ci =& get_instance();
        $ci->load->library('session');
        if(Vendor::get_userId()==null || Vendor::is_logged()==null){
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
                $value=array(
                    'session'=>'false',
                    'status'=>'fail',
                    'result'=>'fail',
                    'message'=>'Session timeout, please <a href="'.base_url().'login">login</a> again!'
                );          
                echo json_encode($value);
                // exit();
                return;
            }else{
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
                $domain = get_domain();
                $domain_1 = "localhost";
                $reffer_url='';
                if(strpos($domain,$domain_1)>=0){
                    $reffer_url='?redirect_url='.$actual_link;
                }

                redirect(base_url().'login'.$reffer_url);
            }
        }
        if(Vendor::is_active()!=7){
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
                $value=array(                    
                    'session'=>'false',
                    'status'=>'fail',
                    'result'=>'fail',
                    'message'=>'Your account is suspended, please contact administrator!'
                );          
                echo json_encode($value);
                return;
            }else{
                redirect(base_url().'logout');
            }
        }
        return;        
    }
    // 'nl_vendor_id'=>$row_data['id'],
    // 'nl_vendor_uname'=>$row_data['name'],
    // 'nl_vendor_isLoggedIn'=>1
	static function get_userId()
    {
		$ci =& get_instance();
        return $ci->session->userdata('nl_vendor_id');
    }

	static function get_userName()
    {
		$ci =& get_instance();
        return $ci->session->userdata('nl_vendor_uname');
    }

    static function is_active()
    {
        $ci =& get_instance();
        $id=Vendor::get_userId();
        $apidata=$ci->curl->execute("users/".$id,"GET");
        return $apidata['data_list']['status'];
    }

	static function is_logged()
    {
	    $ci =& get_instance();
	    $logged_in =  $ci->session->userdata('gt_isALogged');
		return $logged_in;        
    }

    static function is_admin()
    {
        $ci =& get_instance();
        $is_admin =  $ci->session->userdata('gt_isadmin');
        return $is_admin;        
    }

    static function get_userRole()
    {
        $ci =& get_instance();
        $data =  $ci->session->userdata('gt_role');
        return $data;        
    }

    // static function get_userDept()
    // {
    //     $ci =& get_instance();
    //     $data =  $ci->session->userdata('userDeptId');
    //     return $data;        
    // }

    // static function get_userClsLevel()
    // {
    //     $ci =& get_instance();
    //     $data =  $ci->session->userdata('userClsLevel');
    //     return $data;        
    // }

    static function get_ReferType()
    {   
        $id=24;
        return $id; 
    }
	

    static function get_serviceExecuteType()
    {   
        $id=54;
        return $id; 
    }

	static function activity($data)
    {
		if(isset($data['reference_id'])){
            $data['reference_id']=$data['reference_id'];
        }else{
            $data['reference_id']=Vendor::get_userId();
        }
        if(isset($data['reference_name'])){
            $data['reference_name']=$data['reference_name'];
        }else{
            $data['reference_name']=Vendor::get_userName();
        }
        $data['action']=strtolower($data['action']);
        $data['module']=strtolower($data['module']);
        $data['ip_address']=get_ipaddress();
		$data['reference_type']=Vendor::get_ReferType();
		$ci =& get_instance();
        $apidata=$ci->curl->execute("activity_log","POST",$data); 
    }


    static function check_permission($ui_url='',$type=''){
        $ci =& get_instance();
        $is_admin=Vendor::is_admin();

        if($is_admin==0 || empty($is_admin)){
            $data = array('roles_id'=>Vendor::get_userRole(),'ui_url'=>$ui_url);
            $apidata = $ci->curl->execute("module_permissions/check_permission","POST",$data);
            $status = $apidata['status'];
            if($status=='success'){
                $result=true;
            }else{
                $result=false;
            }
            if($type=="check"){ 
                return $result;
            }else if($result==false){
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
                    $value=array(
                        'status'=>"fail",
                        'message'=>"You don't have permission. <br/>Please contact Admininstrator to request access."
                    );
                    echo json_encode($value);
                    return;
                }else{            
                    redirect(base_url().'permission');
                }        
            }
        }else{
            return true;
        }
    }

    static function check_service_view($service_dept_id){
        $deptId=Vendor::get_userDept();
        $classLevel=Vendor::get_userClsLevel();
        if(Vendor::is_admin()){
            return true;
        }
        if($deptId==$service_dept_id){
            return true;
        }else{
            return false;
        }
    }
	
    
}