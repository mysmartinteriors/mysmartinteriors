<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class Categories extends REST_Controller {	 

    public function __construct() {

       	parent::__construct();
       	$this->load->database();

        $this->table = 'categories';
      	$this->model_name='categories_model';      

	   $this->load->model($this->model_name, "", true);
	   $this->lang->load('response', 'english');
    }

    function index_get($id=0){
		if(!empty($id)){
			$data = $this->db->get_where('categories', array('id'=>$id))->row_array();
		}else{
			$data = $this->categories_model->get_categories_json();
		}
		$value  = withSuccess('Data Fetched Successfully',array('details'=>$data));
		$this->response($value, REST_Controller::HTTP_OK);
	}

    function parent_get($id=0){
		$data = $this->db->get_where('categories', array('parent'=>'0'))->result_array();
		$value  = withSuccess('Data Fetched Successfully',array('details'=>$data));
		$this->response($value, REST_Controller::HTTP_OK);
	}
	
	function reorder_post(){
		$input = $this->input->post();
		if(!empty($input['category_data'])){
			if(!empty(json_validate($input['category_data']))){
				$value = withErrors('Invalid JSON Received to the Server');
			}else{
				$data = json_decode($input['category_data'], true);
				if(!empty($data) && is_array($data)){
					$is_update = 1;
					foreach($data as $d){
						$where = $d['where'];
						$up_data = $d['data'];
						$update = $this->db->update('categories', $up_data, $where);
						log_message('debug', $this->db->last_query());
						log_message('debug', PHP_EOL);
						if(!$update){	
							$is_update *= 0;
						}
					}
					if($is_update){
						$value = withSuccess('Update Successful');
					}
				}else{
					$value = withErrors('No Valid Data');
				}
			}
		}else{
			$value = withErrors('No Data found to be updated');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function index_delete($id=0){
		if(!empty($id)){
			$cat_data = $this->db->get_where('categories', array('id'=>$id))->row_array();
			if(!empty($cat_data)){
				$prd_data = $this->db->get_where('products',array('categoryId'=>$id))->result_array();
				$pwhere = array('parent'=>$id);
				$parent_data = $this->db->get_where('categories',$pwhere)->row_array();
				if(!empty($parent_data)){
					$value = withErrors('Since the category having sub categories, cannot delete!!!');
					$this->response($value, REST_Controller::HTTP_OK);
				}else if(!empty($prd_data)){
					$value = withErrors('Since the category having products, cannot delete!!!');
					$this->response($value, REST_Controller::HTTP_OK);
				}else{
					$cwhere = array('id'=>$id);
					// $cat_data = $this->Mydb->get_table_data('categories',$cwhere);
					$res = $this->Mydb->delete_table_data('categories',$cwhere);        
					if ($res){
						$value = withSuccess('Category has been deleted successfully!', array('details'=>$cat_data));
					}else{
						$value = withErrors('Category failed to delete!!!');
					}
					$this->response($value, REST_Controller::HTTP_OK);
				}
			}else{
				$value = withErrors('No Data Found');
				$this->response($value, REST_Controller::HTTP_BAD_REQUEST);
			}
		}else{
			$value = withErrors('Invalid Category Selected');
			$this->response($value, REST_Controller::HTTP_BAD_REQUEST);
		}
	}

    public function index_post()
    {
        $input = $this->input->post();		
		$rules = [
			'text' => ['Category Name','required|min_length[3]|max_length[200]']
		];
		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			$data = array(	
				'text' =>$input['text'],
				'status'=>$input['status'],
				'createdDate'=>cur_date_time(),
				'image'=>!empty($input['image'])?$input['image']:''
			);
			$this->db->insert('categories',$data);
			$id=$this->db->insert_id();
			$res=$id;
			if($res>0){
				$code=create_slug($data['text']);
				$up_data['code']=$code.'.'.$id;
				$where = array('id' => $id);
				$this->Mydb->update_table_data('categories',$where,$up_data);
				$cat_data = $this->db->get_where('categories', array('id'=>$id))->row_array();
				$value = withSuccess('Saved Category Successfully', array('details'=>$cat_data));
			}else{
				$value = withErrors('Couldn\'t create the category.');
			}
			$this->response($value, REST_Controller::HTTP_OK);
		}
    } 

    /**

     * Update data from this method.

     *

     * @return Response

    */

    public function index_put($id)
    {
        $input = $this->put();
			$rules = [
				'text' => ['Category name','required|min_length[3]|max_length[200]'],
				'status' => ['Status','required|numeric']			
			];
		
		Validator::make($rules);		
		
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			
			$data = array(	
			'text' =>$input['text'],
			'status'=>$input['status'],
			'updatedDate'=>cur_date_time(),
		);
		if(!empty($input['image'])){
			$data['image']=$input['image'];
		}
		$is_update = $this->Mydb->update_table_data('categories', array('id'=>$id), $data);
		if($is_update>0){
		$value  = withSuccess('Category Updated Successfully');
		}else{
			$value  = withErrors($this->lang->line('failed_to_update'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
		}
    }


	function edit_cat() {
		$id=$this->input->post('id');
        $data = array();
        $where = array('id' => $id );
        $data['dataQ']=$this->adminmodel->get_table_data('categories',$where,'*',true);
		$str=$this->load->view("admin/category/edit_category_view",$data,true);		
		$res = array(
			'str' => $str,
		);
		$value = withSuccess("Categories", $res);
		echo json_encode($value);
    }

	function check_exists(){
		$name=$this->admin->escapespecialchrs(trim($this->input->post('text')));
		$edit=$this->admin->escapespecialchrs($this->input->post('edit'));
		$q=0;
		if(strtolower($edit)!=strtolower($name)){
        	$where = array('text' => $name );
			$query=$this->adminmodel->get_table_data('categories',$where,'*',true);
			$q=$query->num_rows();
		}
		$result="true";
		if($q>0){
			$result="false";
		}		
		echo $result;
	}

	function check_add_exists(){
		$name=$this->admin->escapespecialchrs(trim($this->input->post('cat_name')));
		$edit=$this->admin->escapespecialchrs($this->input->post('edit'));
		$q=0;
		if(strtolower($edit)!=strtolower($name)){
        	$where = array('text' => $name );
			$query=$this->adminmodel->get_table_data('categories',$where,'*',true);
			$q=$query->num_rows();
		}
		$result="true";
		if($q>0){
			$result="false";
		}		
		echo $result;
	}

	function save_update(){
		$data['id']=$this->input->post("id");
		$text=$this->input->post("text");
		$cat_name=$this->input->post("cat_name");
        $image_old=$this->input->post("image_old");
        $status=$this->input->post("status");
		$data['createdDate']=get_curentTime();
		$data['updatedDate']=get_curentTime();
		
		$result=0;
		
		if($data['id']!='' && $text!==''){
			$data['text']=$text;
			$data['status']=$status;
		}else{
			$data['text']=$cat_name;
			$data['status']=1;
		}

		$msg="No changes saved...";
        $dir = 'uploads/categories/'.$data['id'].'/menu/';
        if (!file_exists($dir )) {
            mkdir($dir , 0777, true);
        }
        $extensions = array("jpeg","jpg","png","PNG","JPEG","JPG");
        if(!empty($_FILES)){
            for($i=0;$i<count($data['id']);$i++)
            {
                $key = $i;
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
                            $data['image']=$file_name;
                        }else{
                            $msg = 'Error in uploading file';               
                        }
                    
                    }else{
                        $msg = 'Error in uploading file. <br>File type is not allowed.';
                    }
                }else{
                    $data['image']=$image_old;
                }
            }
        }else{
            $data['image']=$image_old;
        }
		$q=$this->categorymodel->savecategory($data);
		$value=array(
			'result'=>$q['res'],
			'msg'=>$q['msg']
		);
		
		echo json_encode($value);
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
		$res=0;
		$prwhere = array('categoryId'=>$id);
		$prd_data = $this->adminmodel->get_table_data('products',$prwhere,'*',true);
		$pwhere = array('parent'=>$id);
		$parent_data = $this->adminmodel->get_table_data('categories',$pwhere,'*',true);
		if($parent_data->num_rows()>0){
			$value = withErrors('Since the category having sub categories, cannot delete!!!');
		}else if($prd_data->num_rows()>0){
			$value = withErrors('Since the category having products, cannot delete!!!');
		}else{
			$cwhere = array('id'=>$id);
			$cat_data = $this->adminmodel->get_table_data('categories',$cwhere,'*',true);
			$catName=$cat_data->row()->text;
			$res = $this->adminmodel->delete_table_data('categories',$cwhere);
			$logData['action']='delete';
	        $logData['description']='deleted the category '.$catName;
			$logData['dataId']=$id;
	        $logData['module']='categories';
	        $logData['table_name']='categories';	        
		}
		
		if ($res == 1){
			insert_aLog($logData);
			$value = withSuccess('Category has been deleted successfully!');
		}else if ($res == - 1451){
			$value = withErrors('Category failed to delete!!!');
		}
		echo json_encode($value);
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
