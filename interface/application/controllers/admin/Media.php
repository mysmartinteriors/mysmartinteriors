	<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends CI_Controller {

	function __construct(){
		parent::__construct();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("mediamodel","",true); 
		$this->admin->nocache(); 
	}

	public function index() {
        // check_permission(12,'view','uri');
        $data = $this->admin->commonadminFiles();
		$data['title']="Media Libraries";
		$this->load->view("admin/media/library_view",$data);
    }

    function uploadBox() {
        // check_permission(12,'add','json');
		$data=array();
        $data['media']='media';
        $result='success';
        $str=$this->load->view("admin/media/media_upload_modal_view",$data,true);
        $value=array(
            'status'=>$result,
            'msg'=>$str
        );
         echo json_encode($value);
    }

    function loadLibaries() {

        $filter_data=$this->input->post('filter_data');
    	// $module=$this->input->post('module');
		$module = 'media_library';
    	$page=$this->input->post('page');
        if(empty($page) && $page<1){
            $page = 1;
        }
    	// if(!User::check_permission($module.'/getFilterList', 'check')){
		// 	echo json_encode(array("status"=>"fail", "message"=>"You don't have permission. <br/>Please contact Admininstrator to request access."));
		// 	return;
		// }	
    	$filterData[]=array();
    	$filterData['orderby']='DESC';
    	$filterData['perpage']='1000000';
    	if(!empty($filter_data)){
	    	foreach ($filter_data as $k => $v) {
				if($v['type']=='sortby'){
					$filterData[$v['type']]='createdDate';
				}else{					
					$filterData[$v['type']]=$v['value'];
				}
			}
		}
		// print_r($filterData);exit();
        if(!empty($page)){
            $filterData['page']=$page;
        }
    	$apidata=$this->curl->execute('media',"GET",$filterData,'filter');
		// print_R($apidata);exit();
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
    	$str =  $this->load->view("admin/media/media_tbl_view", $data, true);
    	$value=array('str'=>$str,'status'=>$apidata['status']);
    	echo json_encode($value);

    }
	
    function mediaLibary() { 
        // check_permission(12,'view','json');
		$result=1;
		$data=array();
		$id=$this->input->post('boxId');
		$data['boxId']=$id;
		$str=$this->load->view("admin/media/media_modal_view",$data,true);
		$value=array(
			'boxId'=>$id,
			'str'=>$str,
			'result'=>$result
		);
		
		echo json_encode($value);
    }


	function saveLibrary(){
		$status = 'fail';
		$image = create_slug($_FILES["file"]['name']);
		$folderPath = 'uploads/products/';
		if(!file_exists($folderPath)){
			$createDir = mkdir($folderPath,0777,true);
			if(!$createDir){
				$value=array(
					'str'=>'Failed to create directory',
					'result'=>$status
				);
				echo json_encode($value);
				exit;
			}
		}
		$result=0;
		$config['file_name'] = $image;
        $config['upload_path'] = $folderPath;
		$config['remove_spaces'] = 'false';
		$config['overwrite'] = TRUE;
		$config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|JPG|PNG|GIF';
		//$config['max_size'] = 10240;
        $this->load->library('upload', $config);
        $upload_data = $this->upload->data();
        if ($this->upload->do_upload('file')) {
            $uploadData = $this->upload->data();
            $libraryData = array(
                'folder' => $config['upload_path'],
                'fileName' => $uploadData['file_name'],
				'fileTitle'=>$uploadData['raw_name'],
				'fileType' => strtolower($uploadData['file_ext']),
				'fileSize' => $uploadData['file_size'],
                'createdDate' => $this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now()),
				'updatedDate' => $this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now()),
				'status' => 1,
			);
        	$apidata=$this->curl->execute('media', 'POST', $libraryData);
			// print_r($apidata);exit();
        	$msg=$apidata['message'];
			$status = $apidata['status'];
        }else{
			$msg='Failed to upload';
		}
		$value=array(
				'msg'=>$msg,
				'status'=>$status
		);
		echo json_encode($value);
	}

    function editDetail(){
        // check_permission(12,'edit','json');
    	$result='success';
		$data=array();
		$id=$this->input->post('fileId');
		$data['fileQ'] = $this->curl->execute("media/$id", "GET");
		// $q=$this->mediamodel->get_media_details($fileId);
		// $data['fileQ']=$apidata;
		$str=$this->load->view("admin/media/media_edit_details_view",$data,true);
		$value=array(
			'msg'=>$str,
			'status'=>$result
		);
		
		echo json_encode($value);
    }

    function deleteMedia(){
    	$result=0;
		$fileId=$this->input->post('fileId');
		$status = 'fail';
		$msg='Unable to delete! Try after sometime';
		$apidata = $this->curl->execute("media/$fileId", "DELETE");
		if($apidata['status']=='success' && !empty($apidata['data_list'])){
			$status = 'success';
			$msg = $apidata['message']; 
		}
		if($result>0){
			$status='success';
			$msg='The media has been deleted from database';
		}
		$value=array(
			'msg'=>$msg,
			'status'=>$status
		);
		echo json_encode($value);
    }

    function saveDetails(){
    	$id=$this->input->post('fileId');
    	$data['fileName']=$this->input->post('fileName');
    	$data['oldName']=$this->input->post('oldName');
    	$data['fileTitle']=$this->input->post('fileTitle');
		$oldName=$data['oldName'];
		$newName=$data['fileName'];
		unset($data['oldName']);
		unset($data['fileId']);
		$oldURL = 'uploads/products/'.$oldName;
		$newURL = 'uploads/products/'.$newName;
		$result = "fail";
		if(file_exists(FCPATH.$oldURL)){
			$renamed = rename($oldURL,$newURL);
			if($renamed){
				$apidata = $this->curl->execute("media/$id", "PUT", $data);
				if($apidata['status']=='success' && !empty($apidata['data_list'])){
					$msg = "The file has been successfully renamed";
					$result = 'success';
				}else{
					if(file_exists(FCPATH.$newURL)){
						rename($newURL, $oldURL);
					}
					$msg = "File Could Not Be Renamed";
				}
			}else{
				$msg = "The file has not been successfully renamed";
			}
		}else{
			$msg = "The original file that you want to rename doesn't exist";
		}
        $value=array(
            'result'=>$result,
            'msg'=>$msg
        );
        echo json_encode($value);
    }

	function check_exists(){
		$name=$this->admin->escapespecialchrs(trim($this->input->post('fileName')));
		$edit=$this->admin->escapespecialchrs($this->input->post('edit'));
		if(strtolower($edit)!=strtolower($name)){
			$apidata=$this->curl->execute('media', "GET", array('fileName'=>$name));
			if($apidata['status']=='success' && !empty($apidata['data_list'])){
				$result = "false";
				echo $result;
			}else{
				echo "true";
			}
		}else{
			echo "true";
		}	
	}
  
}
