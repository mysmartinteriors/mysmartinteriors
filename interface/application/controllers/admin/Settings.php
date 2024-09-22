<?php
class settings extends CI_Controller{
    
    function __construct(){
        parent::__construct();
        check_admin_session();
        // $this->load->model("adminmodel","",true);
        $this->admin->nocache(); 
    }
    
    function index(){
        if(is_admin()){
            $data = $this->admin->commonadminFiles();
            $data['title']="General web settings";
            $where = array('megamenu');
            $data['settings_data'] = $this->adminmodel->get_settings_table_data('web_settings',$where);
            $this->load->view("admin/settings/general_settings_view",$data);
        }else{
             redirect(base_url().'admin/denied');       
        }
    }

    function company(){
        if(is_admin()){
            $data = $this->admin->commonadminFiles();
            $data['title']="Company profile settings";
            $id = 1;
            $companyData = $this->curl->execute("organization/$id", "GET");
            if($companyData['status'] == 'success' && !empty($companyData['data_list'])){
                $data['dataQ']=$companyData['data_list'];
            }
            $this->load->view("admin/settings/company_settings_view",$data);
        }else{
             redirect(base_url().'admin/denied');       
        }
    }

    function save_csettings(){
        // print_R($this->input->post());exit();
        $result='fail';
        $id=$this->input->post("id");
        $companyName=$this->input->post("companyName");
        // $logo=$this->input->post("logo");
        $caption=$this->input->post("caption");
        $address=$this->input->post("address");
        $branches=$this->input->post("branches");
        $address=$this->input->post("address");
        $phone=$this->input->post("phone");
        $mobile=$this->input->post("mobile");
        $email=$this->input->post("email");
        $website=$this->input->post("website");
        $GSTNO=$this->input->post("GSTIN");
        $logo_old=$this->input->post("logo_old");

        $settingId=1;

        $data['id']=$settingId;
        $data['name']=$companyName;
        $data['logo']=$logo_old;
        $data['caption']=$caption;
        $data['address']=$address;
        $data['GSTIN']=$GSTNO;
        $data['branches']=$branches;
        $data['phone']=$phone;
        $data['mobile']=$mobile;
        $data['email']=$email;
        $data['website']=$website;        
        $data['updatedBy']=get_adminId();
        $data['updatedDate']=get_curentTime();
        // print_R($data);exit();
        $msg="No changes saved...";
        $dir = 'uploads/site/';
        if (!file_exists($dir )) {
            mkdir($dir , 0777, true);
        }
        $extensions = array("jpeg","jpg","png","PNG","JPEG","JPG");
        if(!empty($_FILES)){
            //foreach($_FILES['attachment']['tmp_name'] as $key => $tmp_name )
            // for($i=0;$i<count($settingId);$i++)
            // {
                $key = 0;
                if(!empty($_FILES['attachment']['tmp_name'][$key])){
                    $file_name = $_FILES['attachment']['name'][$key];
                    $file_size =$_FILES['attachment']['size'][$key];
                    $file_tmp =$_FILES['attachment']['tmp_name'][$key];
                    $file_type=$_FILES['attachment']['type'][$key];        
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
                        if(move_uploaded_file($file_tmp, $dir.$file_name))
                        {
                            $data['logo']=$file_name;
                        }else{
                            $msg = 'Error in uploading file';               
                        }
                    
                    }else{
                        $msg = 'Error in uploading file. <br>File type is not allowed.';
                    }
                }else{
                    $data['logo']=$logo_old;
                }
            // }
        }else{
            $data['logo']=$logo_old;
        }
       $q = $this->curl->execute("organization/$settingId", "PUT", $data);
        if($q['status'] == 'success' && !empty($q['data_list'])){
            $status = $q['status'];
            $message = $q['message'];
        }else{
            $status = $q['status'];
            $message = $q['message'];
        }
    //    print_R($q);exit();
        // $where = array('settingId' => $settingId);
        // $q=$this->adminmodel->update_table_data('company_settings',$where,$data);
        //print_r($this->db->last_query());
        // if($q>0){
        //     $result='success';
        //     $msg="Updated Successfully!";
        //     $logData['action']='Update';
        //     $logData['description']='updated company profile settings';
        //     $logData['dataId']=1;
        //     $logData['module']='Company Settings';
        //     $logData['table_name']='company_settings';
        //     insert_aLog($logData);
        // }
        $value=array(
            'status'=>$status,
            'message'=>$message
        );
        echo json_encode($value);
    }
    
    function email(){
        if(is_admin()){
            $data = $this->admin->commonadminFiles();
            $where = array('settingId' => 1);
            $settings=$this->adminmodel->get_table_data('mail_settings',$where,'*',true);
            $data['title']="Email settings";
            $data['dataQ']=$settings;
            $this->load->view("admin/settings/mail_settings_view",$data);
        }else{
             redirect(base_url().'admin/denied');       
        }
    }

    function save_esettings(){
        $result='fail';
        $data['host']=$this->input->post("host");
        $data['port']=$this->input->post("port");
        $data['hostUsername']=$this->input->post("hostUsername");
        $data['hostPassword']=$this->input->post("hostPassword");
        $data['smtpAuth']=$this->input->post("smtpAuth");
        $data['smtpSecure']=$this->input->post("smtpSecure");
        $data['from']=$this->input->post("from");
        $data['fromText']=$this->input->post("fromText");
        $data['ccEmails']=$this->input->post("ccEmails");


        $data['status']=1;
        $data['updatedBy']=get_adminId();
        $data['updatedDate']=get_curentTime();

        $settingId=1;
        $where = array('settingId' => $settingId);
        $q=$this->adminmodel->update_table_data('mail_settings',$where,$data);
        //print_r($this->db->last_query());
        if($q>0){
            $result='success';
            $logData['action']='Update';
            $logData['description']='updated email settings';
            $logData['dataId']=1;
            $logData['module']='Email Settings';
            $logData['table_name']='mail_settings';
            insert_aLog($logData);
        }
        $value=array(
            'result'=>$result
        );
        echo json_encode($value);
    }
    function myprofile(){
        $uid=get_adminId();
        if(!empty($uid)){
            $q = $this->curl->execute("users/$uid", "GET");
            if($q['status'] == 'success' && !empty($q['data_list'])){
                $data = $this->admin->commonadminFiles();
                $data['title']="My profile settings";
                $data['dataQ']=$q['data_list'];;
                $this->load->view("admin/settings/myprofile_settings_view",$data);
            }
        }
        // $where = array('adminId' => $uid);
        // $q=$this->adminmodel->get_table_data('admin_users',$where,'*',true);
    }

    function savemysettings(){
        $adminId=$this->input->post("id");
        $firstName=$this->input->post("firstName");
        $lastName=$this->input->post("lastName");
        $password=$this->input->post("password");
        $email=$this->input->post("email");
        $logo_old=$this->input->post("logo_old");

        $result='fail';
        $data['first_name']=$firstName;
        $data['last_name']=$lastName;
        $data['password']=$password;
        $data['email']=$email;

        $msg="No changes saved...";
        $dir = 'uploads/site/';
        if (!file_exists($dir )) {
            mkdir($dir , 0777, true);
        }
        $extensions = array("jpeg","jpg","png","PNG","JPEG","JPG");
        if(!empty($_FILES)){
            //foreach($_FILES['attachment']['tmp_name'] as $key => $tmp_name )
            // for($i=0;$i<count($adminId);$i++)
            // {
                $key = 0;
                if(!empty($_FILES['attachment']['tmp_name'][$key])){
                    $file_name = $_FILES['attachment']['name'][$key];
                    $file_size =$_FILES['attachment']['size'][$key];
                    $file_tmp =$_FILES['attachment']['tmp_name'][$key];
                    $file_type=$_FILES['attachment']['type'][$key];        
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
                        if(move_uploaded_file($file_tmp, $dir.$file_name))
                        {
                            $data['picture']=$file_name;
                        }else{
                            $msg = 'Error in uploading file';               
                        }
                    
                    }else{
                        $msg = 'Error in uploading file. <br>File type is not allowed.';
                    }
                }else{
                    $data['picture']=$logo_old;
                }
            // }
        }else{
            $data['picture']=$logo_old;
        }
        $q = $this->curl->execute("users/$adminId", "PUT", $data);
        if($q['status'] == 'success' && !empty($q['data_list'])){
            $status = $q['status'];
            $message = $q['message'];
        }else{
            $status = $q['status'];
            $message = $q['message'];
        }
        // $where=array('adminId' => $adminId );
        // $q=$this->adminmodel->update_table_data('admin_users',$where,$data);
        // if($q>0){
        //     $result='success';
        //     $msg="Updated Successfully";
        //     $logData['action']='Update';
        //     $logData['description']='updated profile settings';
        //     $logData['dataId']=$adminId;
        //     $logData['module']='Profile Settings';
        //     $logData['table_name']='admin_users';
        //     insert_aLog($logData);
        // }
        //print_r($this->db->last_query());
        $value=array(
            'status'=>$status,
            'message'=>$message
        );
        
        echo json_encode($value);
    }



    function update_megamenu() {
        $status = $this->input->post('status');
        $data['columns'] = $this->input->post('columns');
        $data['items'] = $this->input->post('items');
        if($status=='on'){
            $data['status']=1;
        }else{
            $data['status']=0;
        }
                  
        $res = $this->adminmodel->updateSettings($data,'megamenu');
        if($res>0){                
            $value = withSuccess("Megamenu successfully updated...");

            $logData['action']='Update';
            $logData['description']='updated megamenu settings';
            $logData['dataId']='';
            $logData['module']='General Settings';
            $logData['table_name']='web_settings';
            insert_aLog($logData);
        }else{                
            $value = withErrors("Megamenu failed to update...");
        }
        echo json_encode($value);
    }
}