<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sliders extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		// $this->load->model("adminmodel","",true);
        // $this->load->model("slidermodel","",true);
		$this->admin->nocache(); 
        $this->module_name='sliders';
		$this->view_path = "admin/sliders";
	}
	
    public function index() {
        // check_permission(4,'view','uri');
        $data = $this->admin->commonadminFiles();
		$data['title']="Homepage Sliders";
		$this->load->view("admin/sliders/sliders_view",$data);
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



    	// $data['result'] = "success";  
        // $page = (int) $this->input->post('page');
        // $filter_data=$this->input->post('filter_data');
        // $get_total_rows = $this->slidermodel->filter_home_sliders($filter_data,0,0);
        // $item_per_page="10";
        // foreach ($filter_data as $k => $v) {
        //     if (($v['type'] == 'perpage') && $v['value'] != "") {
        //         $item_per_page = (int)$v['value'];
        //     }
        // }
        // $data = $this->adminmodel->getPaginationData($item_per_page,$page,$get_total_rows);
        
        // $data['dataQ']=$this->slidermodel->filter_home_sliders($filter_data,$data['item_per_page'],$data['page_position']);

        // $data['pagination'] = $this->adminmodel->paginate_function($item_per_page, $data['page_number'], $get_total_rows, $data['total_pages']);
        
        $str=$this->load->view("admin/sliders/sliders_tbl_view",$data,true);
        
        $value=array(
            'str'=>$str
        );
        echo json_encode($value);
    }

    function add_new(){
        // check_permission(4,'add','json');
    	$id=$this->input->post('id');
        
        $data = array();
        if($id!=''){
            $where = array('id' => $id );
            $apidata = $this->curl->execute("sliders/$id", "GET");
            if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
                $data['dataQ'] = $apidata['data_list'];
            }
            // $data['dataQ']=$this->adminmodel->get_table_data('sliders',$where,'*',true);
        }
        $str=$this->load->view("admin/sliders/add_slider_view",$data,true);
        $value=array(
            'result'=>1,
            'str'=>$str
        );
        echo json_encode($value);
    }

    function check_add_exists(){
        // print_R($this->input->post());exit();
        $name=$this->admin->escapespecialchrs(trim($this->input->post('slider_name')));
        $edit=$this->admin->escapespecialchrs($this->input->post('edit'));
        // $q=0;
        if(strtolower($edit)!=strtolower($name)){
            $where = array('slider_name' => $name );
            $apidata=$this->curl->execute('sliders/check_add_exist', "GET", $where);
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


    function save_add_data(){
        // print_R($this->input->post());exit();
        $id = $this->input->post("id");
        $data['slider_name']=$this->input->post("slider_name");
        $data['main_text']=$this->input->post("main_text");
        $data['sub_text']=$this->input->post("sub_text");
        $data['shop_url']=$this->input->post("shop_url");
        $image_old=$this->input->post("image_old");
        $data['status']=$this->input->post("status");
        $data['order']=$this->input->post("order");
        $data['is_ads']=$this->input->post("is_ads");
        $data['updatedDate']=get_curentTime();
        // $result=0;

        $msg="No changes saved...";
        $dir = 'uploads/sliders/';
        // create_directory($dir);
        if (!file_exists($dir )) {
            mkdir($dir , 0777, true);
        }
        $extensions = array("jpeg","jpg","png","PNG","JPEG","JPG");
        if(!empty($_FILES)){
           
            
            // $file_res = upload_files('report',$file_dir);

            //foreach($_FILES['attachment']['tmp_name'] as $key => $tmp_name )
            // for($i=0;$i<count($data['id']);$i++)
            // {
                // $key = $i;
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
                        if(move_uploaded_file($file_tmp, $target))
                        {
                            $data['slide_image']=$file_name;
                        }else{
                            $msg = 'Error in uploading file';               
                        }
                    
                    }else{
                        $msg = 'Error in uploading file. <br>File type is not allowed.';
                    }
                    $key++;
                }else{
                    $data['slide_image']=$image_old;
                }
            // }
        }else{
            $data['slide_image']=$image_old;
        }
        if(!empty($id)){
            $apidata = $this->curl->execute("sliders/$id", "PUT", $data);
        }else{
            $apidata = $this->curl->execute("sliders", "POST", $data);
        }
        if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
            // $result = $apidata['data_list'];
            $status = $apidata['status'];
            $message = $apidata['message'];
        }else{
            // $result = '';
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


    function update_status(){
        // check_permission(4,'edit','json');
        $id=$this->input->post("id");
        $data['status']=$this->input->post("status");
        // print_R($data['status']);exit();
        // $data['updatedDate']=get_curentTime();
        $status='fail';
        $message='Unable to process your request';    
        $where = array('id'=>$id);
        $apidata = $this->curl->execute("sliders/$id", "PUT", $data);
        if($apidata['status'] == 'success' && !empty($apidata['data_list'])){
            $status = $apidata['status'];
            $message = $apidata['message'];

            // $logData['dataId']=$id;
            // $logData['module']='sliders';
            // $logData['table_name']='sliders';
            // $logData['action']='update';
            // $logData['description']='updated the status of a slider';
            // insert_aLog($logData);
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

    function delete_slider(){
        // check_permission(4,'delete','json');
        $id=$this->input->post("id");
        $status='';
        $msg='Cannot delete at this time...';
        if($id!=""){ 
            $apidata = $this->curl->execute("sliders/$id", "DELETE");
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
}