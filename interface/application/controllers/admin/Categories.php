	<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

	function __construct(){
		parent::__construct();
		check_admin_session();
		// $this->load->model("adminmodel","",true);
		// $this->load->model("categorymodel","",true);
		$this->admin->nocache(); 
		$this->module_name='categories';
		$this->view_path = "admin/category";
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Categories";
		$this->load->view("admin/category/category_view",$data);
    }

	function get_datas(){
		$data = $this->curl->execute('categories', 'GET');
		if($data['status']=='success' && !empty($data['data_list'])){
			echo json_encode(array('status'=>'success', 'message'=>$data['message'], 'menus'=>$data['data_list']));
			return;
		}else{
			echo json_encode(array('status'=>'success', 'message'=>"No Data Found"));
			return;
		}
	}

	function edit_cat() {
		$message = 'Category Not Found';
		$status = 'fail';
		$str = '';
		$id=$this->input->post('id');
        $data = array();
        $where = array('id' => $id );
		$apidata = $this->curl->execute("categories/$id", 'GET');
		if($apidata['status']=='success' && !empty($apidata['data_list'])){
			$status = 'success';
			$data['dataQ'] = $apidata['data_list'];
			$str=$this->load->view("$this->view_path/edit_category_view",$data,true);		
		}
		echo json_encode(array('status'=>$status, 'message'=>$message, 'str'=>$str));
    }

	function check_exists(){
		$name = trim($this->input->post('text'));
		$edit = trim($this->input->post('edit'));
		$q=0;
		$res = "true";
		if(strtolower($edit)!=strtolower($name)){
			$apidata=$this->curl->execute('categories',"GET");
			if($apidata['status']=='success' && !empty($apidata['data_list'])){
				$data = $apidata['data_list'];
				foreach($data as $value){
					if($value['text']==$name){
						$res = 'false';
						break;
					}
					if(isset($value['children'])){
						foreach($value['children'] as $child){
							if($child['text']==$name){
								$res = "false";
								break;
							}
						}
					}
				}
			}
		}
		echo $res;
	}

	function check_add_exists(){
		$name=$this->admin->escapespecialchrs(trim($this->input->post('cat_name')));
		$edit=$this->admin->escapespecialchrs($this->input->post('edit'));
		$q=0;
		if(strtolower($edit)!=strtolower($name)){
        	// $where = array('text' => $name );
			$apidata=$this->curl->execute('categories', "GET");
			if($apidata['status']=='success' && !empty($apidata['data_list'])){
				$data = $apidata['data_list'];
				foreach($data as $value){
					if($value['text']==$name){
						$res = 'false';
						break;
					}
					if(isset($value['children'])){
						foreach($value['children'] as $child){
							if($child['text']==$name){
								$res = "false";
								break;
							}
						}
					}
				}
			}
		}
		$result="true";
		if($q>0){
			$result="false";
		}		
		echo $result;
	}

	function save_update(){
		$id=$this->input->post("id");
		$text=$this->input->post("text");
		$cat_name=$this->input->post("cat_name");
        $image_old=$this->input->post("image_old");
        $status=$this->input->post("status");
		$data['created_at']=cur_datetime();
		$data['udpated_at']=cur_datetime();
		$result=0;
		$data['image'] = '';
		// print_R($_FILES);exit();
		if(isset($_FILES) && !empty($_FILES['attachment']['name'][0])){
			$save_categories_update = $this->save_categories_update();
			if($save_categories_update['status']!='success'){
				echo json_encode(array('result'=>'error', 'msg'=>'Couldn\'t Uplaod the file'));
				return;
			}else{
				$data['image'] = $save_categories_update['file_name'];
			}
		}
		if(!empty($id) && $text!==''){
			$data['text']=$text;
			$data['status']=$status;
			$apidata = $this->curl->execute("categories/$id", 'PUT', $data);
			if($apidata['status']=='success' && !empty($apidata['data_list'])){
				$activity_name = $this->module_name.'_update';
				Activity::module_log($activity_name,'user',$apidata['data_list']);
			}
		}else{
			$data['text']=$cat_name;
			$data['status']=1;
			$apidata = $this->curl->execute("categories", 'POST', $data);
			if($apidata['status']=='success'){
				$activity_name = $this->module_name.'_create';
				Activity::module_log($activity_name,'user',$apidata['data_list']);
			}
		}
		$value = array('result'=>$apidata['status'], 'msg'=>$apidata['message']);
		echo json_encode($value);
		return;
	}

	function save_categories_update(){
		$dir = 'uploads/categories/';
        if (!file_exists($dir )) {
            mkdir($dir , 0777, true);
        }
		if(!empty($_FILES['attachment']['tmp_name'][0])){
			$file_name = $_FILES['attachment']['name'][0];
			$file_size =$_FILES['attachment']['size'][0];
			$file_tmp =$_FILES['attachment']['tmp_name'][0];
			$file_type=$_FILES['attachment']['type'][0];        
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			if($file_size > 2048) {
				$msg='File size should not exceed 2 MB';
			}
			$extensions = array("jpeg","jpg","png","PNG","JPEG","JPG");
			if(in_array($ext,$extensions ) === true)
			{  
				$target = $dir.$file_name;
				if(file_exists($target)){
					$file_name = rand(01,1000).$file_name;
				}
				$target = $dir.$file_name;
				if(move_uploaded_file($file_tmp, $dir.$file_name)){
					$data['image']=$file_name;
					$value = array('status'=>'success', 'message'=>'File Uploaded Successfully', 'file_name'=>$dir.$file_name);
				}else{
					$msg = 'Error in uploading file';               
					$value = array('status'=>'error', 'message'=>$msg, 'file_name'=>'');
				}
			}else{
				$msg = 'Error in uploading file. <br>File type is not allowed.';
				$value = array('status'=>'error', 'message'=>$msg, 'file_name'=>'');
			}
		}else{
			$value = array('status'=>'error', 'message'=>'Couldn\'t Update File', 'file_name'=>'');
		}
		return $value;
	}


	function save_categories(){
		// print_R($this->input->post());exit();
		$datas = json_decode($this->input->post('str'));
		
		$readbleArray = $this->parseJsonArray($datas);

		$i=0;
		$data = array();
		foreach($readbleArray as $row){
			$i++;
			$mdata = array();
			$where = array('id'=>$row['id']);
			$mdata['where'] = $where;
			$mdata['data'] = array(
				'parent'=>$row['parentID'],
				'orders'=>$i
			);
			array_push($data, $mdata);
		}		
		// if(!empty($data)){
		// }
		$apidata = $this->curl->execute('categories/reorder', 'POST', array('category_data'=>json_encode($data)));
		echo json_encode(array('status'=>$apidata['status'], 'message'=>$apidata['message']));
		return;
	}

	function parseJsonArray($jsonArray, $parentID = 0) {
		$return = array();
		foreach ($jsonArray as $subArray) {
		$returnSubSubArray = array();
		if (isset($subArray->children)) {
			$returnSubSubArray = $this->parseJsonArray($subArray->children, $subArray->id);
		}

		$return[] = array('id' => $subArray->id, 'parentID' => $parentID);
		$return = array_merge($return, $returnSubSubArray);
		}
		return $return;
	}
	
	function delete_cat() {
		$id = $this->input->post("id");
		if(!empty($id)){
			$apidata = $this->curl->execute("categories/$id", "DELETE");
			// if($apidata['status']=='success' && !empty($apidata['data_list'])){
			// 	// $activity_name=$this->module_name.'_delete';
			// 	// $message=$apidata['data_list']['text']."-".$apidata['message'];
			// 	// Activity::module_log($activity_name,'user',$apidata['data_list']);
			// }
			echo json_encode(array('status'=>$apidata['status'], 'message'=>$apidata['message']));
			return;
		}else{
			echo json_encode(array('status'=>'fail', 'message'=>'Ivalid Category'));
			return;
		}
	}

	function cat_settings() {
		$id=$this->input->post('id');
        $data = array();
        $where = array('id' => $id );
        $data['dataQ']=$this->adminmodel->get_table_data('categories',$where,'*',true);
		$str=$this->load->view("admin/category/category_settings_view",$data,true);		
		$res = array(
			'str' => $str,
		);
		$value = withSuccess("Settings", $res);
		echo json_encode($value);
    }    
	function settings(){
        $data = $this->admin->commonadminFiles();
		$data['title']="Category menu settings";
        $where = array('type' => 'megamenu','name'=>'status');
        $status=$this->adminmodel->get_table_data('web_settings',$where,'*',true);
        $data['status']=$status->row()->value;
		$this->load->view("admin/category/menu_settings_view",$data);
    }

	function mega_menu_action(){
		$action = $this->input->post('action');
		switch($action)
		{
			case 'mm_get_mega_menu_column': 	
				$data = $this->get_saved_grid_data();
				echo json_encode($data);
				break;
			case 'mm_get_empty_grid_column': 
				$data = $this->get_empty_column();
				echo json_encode($data);
				break;
			case 'mm_get_empty_grid_row': 
				$data = $this->get_empty_row();
				echo json_encode($data);
				break;
			case 'mm_save_grid_data': 
				$grid = $this->input->post('grid');
				$parent = $this->input->post('parent_menu_item');
				$data = $this->saved_grid_data2($parent,$grid);
				break;
			case 'mega_menu_status':
				$this->mega_menu_status();
				break;
		}
	}

	function mega_menu_status(){
		if($this->input->post('mega_menu_status')==0){
			$mega_menu_status = 1;
			$actstatus = "enabled";
		}else{
			$actstatus = "disabled";
			$mega_menu_status = 0;
		}
		$up_data = array(
			'mega_menu_status'=>$mega_menu_status
		);		
		$res = $this->adminmodel->update_table_data('categories','',$up_data);

        //insert log data
        $logData['action']='update';
        $logData['description']=$actstatus.' megamenu for the categories';
        $logData['dataId']='';
        $logData['module']='categories';
        $logData['table_name']='categories';
        insert_aLog($logData);		
			
		if($res){
			$result = array('mega_val'=>$mega_menu_status);
			$value = withSuccess("Mega menu successfully updated",$result);
		}else{
			$value = withErrors("Mega menu failed to update");
		}
		echo json_encode($value);
	}

    function get_saved_grid_data(){
		$data['mega_menu_rows'] = $this->categorymodel->get_mega_menu_rows();
		//$data['datas'] = $this->megamenumodel->get_mega_menu_column($parent);
		$mega_content =  $this->load->view("admin/category/mega_menu_format", $data, true);
		$value = array(
			'success'=>true,
			'data'=> $mega_content,
		);
		return $value;
	}
	
	function get_empty_column(){		
		$val = array(
			'success'=>true,
			'data'=>"<div class='mega-col' data-span='3'>
			   <div class='mega-col-wrap'>
			      <div class='mega-col-header'>
			         <div class='mega-col-description'>                
			         	<span class='dashicons dashicons-move'></span>  
			         	<span class='dashicons dashicons-trash'></span>            
			         </div>
			         <div class='mega-col-actions'>                
				         <a class='mega-col-option mega-col-contract' title='Contract'>
				         	<span class='dashicons dashicons-arrow-left-alt2'></span>
				         </a>                
				         <span class='mega-col-cols'>
				         <span class='mega-num-cols'>3</span>
				         <span class='mega-of'>/</span>
				         <span class='mega-num-total-cols'>12</span>
				         </span>                
				         <a class='mega-col-options mega-col-expand' title='Expand'>
				         	<span class='dashicons dashicons-arrow-right-alt2'></span>
				         </a>            
			         </div>
			      </div>
			      <div class='mega-col-settings'>            
				      <input name='mega-hide-on-mobile' type='hidden' value='false' />            
				      <input name='mega-hide-on-desktop' type='hidden' value='false'/>            
				      <label>Column class</label>            
				      <input class='mega-column-class' type='text' value='' />            
				      <button class='button button-primary mega-save-column-settings' type='submit'>Save</button>        
			      </div>
			      <div class='mega-col-widgets'>        
			      </div>
			   </div>
		</div>"
		);
		 return $val;
	}

	function get_empty_row(){		
		$val = array(
		'success'=>true,
		'data'=>'<div class="mega-row ui-sortable" data-available-cols="12" data-used-cols="3">    <div class="mega-row-header">        <div class="mega-row-actions">            <span class="dashicons dashicons-sort"></span>            <span class="dashicons dashicons-trash"></span>        </div>        <div class="mega-row-settings">            <input name="mega-hide-on-mobile" type="hidden" value="false">            <input name="mega-hide-on-desktop" type="hidden" value="false">            <div class="mega-settings-row">                <label>Row class</label>                <input class="mega-row-class" type="text" value="">            </div>            <div class="mega-settings-row">                <label>Row columns</label>                <select class="mega-row-columns">                    <option value="1">1 column</option>                    <option value="2">2 columns</option>                    <option value="3">3 columns</option>                    <option value="4">4 columns</option>                    <option value="5">5 columns</option>                    <option value="6">6 columns</option>                    <option value="7">7 columns</option>                    <option value="8">8 columns</option>                    <option value="9">9 columns</option>                    <option value="10">10 columns</option>                    <option value="11">11 columns</option>                    <option value="12" selected="selected">12 columns</option>                </select>            </div>            <button class="button button-primary mega-save-row-settings" type="submit">Save</button>        </div>        <button class="btn btn-success btn-sm mega-add-column"><span class="dashicons dashicons-plus"></span>Column</button>    </div>    <div class="error notice is-dismissible mega-too-many-cols">        <p>You should rearrange the content of this row so that all columns fit onto a single line.</p>    </div>    <div class="error notice is-dismissible mega-row-is-full">        <p>There is not enough space on this row to add a new column.</p>    </div><div class="mega-col" data-span="3" data-total-blocks="0">    <div class="mega-col-wrap">        <div class="mega-col-header">            <div class="mega-col-description">                <span class="dashicons dashicons-move ui-sortable-handle"></span>                <span class="dashicons dashicons-admin-generic"></span>                <span class="mega-tooltip mega-enabled" data-tooltip-enabled="Column: Visible on desktop" data-tooltip-disabled="Column: Hidden on desktop"><span class="dashicons dashicons-desktop"></span></span>                <span class="mega-tooltip mega-enabled" data-tooltip-enabled="Column: Visible on mobile" data-tooltip-disabled="Column: Hidden on mobile"><span class="dashicons dashicons-smartphone"></span></span>                <span class="dashicons dashicons-trash"></span>            </div>            <div class="mega-col-actions">                <a class="mega-col-option mega-col-contract" title="Contract"><span class="dashicons dashicons-arrow-left-alt2"></span></a>                <span class="mega-col-cols"><span class="mega-num-cols">3</span><span class="mega-of">/</span><span class="mega-num-total-cols">12</span></span>                <a class="mega-col-options mega-col-expand" title="Expand"><span class="dashicons dashicons-arrow-right-alt2"></span></a>            </div>        </div>        <div class="mega-col-settings">            <input name="mega-hide-on-mobile" type="hidden" value="false">            <input name="mega-hide-on-desktop" type="hidden" value="false">            <label>Column class</label>            <input class="mega-column-class" type="text" value="">            <button class="button button-primary mega-save-column-settings" type="submit">Save</button>        </div>        <div class="mega-col-widgets ui-sortable">        </div>    </div></div></div>'
		);
		 return $val;
	}
}
