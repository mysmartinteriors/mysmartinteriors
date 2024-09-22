<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class Module_permissions extends REST_Controller {

    public function __construct() {
       parent::__construct();
       $this->load->database();
	   $this->load->model("module_permissionsmodel", "", true);
	   $this->load->model("rolesmodel", "", true);
	   $this->lang->load('response', 'english');
    }
       

    /**

     * Get All Data from this method.

     *

     * @return Response

    */

	public function index_get($id = 0)
	{
		$data = array();
		$message = "success";
		$error = true;
        if(!empty($id)){
			$error = false;
            $data = $this->get_single_result($id);
			$result = array('details'=>$data);
        }else{				
			$data = $this->search();
			$result = array('data_list'=>$data);				
		}
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$value  = withSuccess($message,$result);
		$this->response($value, REST_Controller::HTTP_OK);
	}
	
	public function get_single_result($id){		
		$getData = $this->module_permissionsmodel->get_single_details($id);
		return $getData;
	}
	
	public function get_role_result($id){
		
		$filter_data[0]['type'] = 'roles.id'; $filter_data[0]['value'] = $id;
		
		$getData = $this->rolesmodel->filter($filter_data);	

		return $getData->row_array();
	}

   
   
   /**

     * Get All Data from this method.

     *

     * @return Response

    */
   
	public function set_permission_get($id = 0)
	{		
		$data = array();
		$message = "success";
		$error = true;
        if(!empty($id)){
			$error = false;
			$data = $this->module_permissionsmodel->roles_permission_list($id);			
        }else{
			$error = true;
		}
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$result = array('details'=>$data);
		if($error){
			$message = "Role id required";
			$value  = withErrors($message,$result);
			$this->response($value, REST_Controller::HTTP_BAD_REQUEST);
		}else{
			$value  = withSuccess($message,$result);
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}
	
   
    /**

     * Insert data from this method.

     *

     * @return Response

    */

    public function index_post()
    {
        $input = $this->input->post();
		// print_R($input);exit();
		$rules = [
			'roleId' => ['Roles','required'],
			'moduleId' => ['Modules','required']
		];
		
		Validator::make($rules);
		if (!Validator::fails()) {
             Validator::error();
        } else {			
			$data = array(	
				'roleId' =>$input['roleId'],
				'moduleId' =>$input['moduleId'],
				'add' =>$input['add'],
				'view' =>$input['view'],
				'edit' =>$input['edit'],
				'delete' =>$input['delete'],
				'status'=>1,
			);
			// print_R($data);exit();
			$id = $this->Mydb->insert_table_data('admin_permissions',$data);
			$result['details'] = $this->get_single_result($id);
			$value  = withSuccess($this->lang->line('module_permission_created_success'),$result);
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
		$rules = array();
		$data = array();
		
        $input = $this->put();
		
		if(!empty($input['name'])){
			$rules['name'] = ['Name','required|min_length[3]|max_length[200]'];
			$data['name'] = $input['name'];
		}
		if(!empty($input['parent'])){
			$rules['parent'] = ['Parent','required'];
			$data['parent'] = $input['parent'];
		}
		if(!empty($input['modules_id'])){
			$rules['modules_id'] = ['Module ID','required'];
			$data['modules_id'] = $input['modules_id'];
		}
		if(!empty($input['api_url'])){
			$rules['api_url'] = ['API URL','required'];
			$data['api_url'] = $input['api_url'];
		}
		if(!empty($input['api_method'])){
			//$rules['api_method'] = ['API Method','required'];
			$data['api_method'] = strtoupper($input['api_method']);
		}
		if(!empty($input['ui_url'])){	
			$rules['ui_url'] = ['Interface URL','required'];	
			$data['ui_url'] = $input['ui_url'];
		}
		
		$message = [
			'edit_unique' => 'The %s is already exists',
		];	
		
		Validator::setMessage($message);
		
		if(array_filter($input)) {
			if(!empty($rules)){
				Validator::make($rules);	
			}		
			if (!Validator::fails()) {
				Validator::error();
			}			
		}
		$data['updated_at'] = cur_date_time();
		$data['updated_by']=$input['updated_by'];
		
		
		//print_r($data);
		$is_update = $this->Mydb->update_table_data('module_permissions', array('id'=>$id), $data);
		$result['details'] = $this->get_single_result($id);
		if($is_update>0){
			$value  = withSuccess($this->lang->line('module_permission_updated_success'),$result);
		}else{
			$value  = withErrors($this->lang->line('failed_to_update'),$result);
		}
		$this->response($value, REST_Controller::HTTP_OK);        
    }


	/**

     * Update data from this method.

     *

     * @return Response

    */


	public function save_permission_put($id=0){
		$input = $this->put();
		//print_r($input);
		if(!empty($id)){
			if(empty($input['module_permissions_id'])){
				$value  = withErrors($this->lang->line('select_atleast_one_option'));
			}else{			
				foreach($input['module_permissions_id'] as $mp){
					$data[] = array(	
						'roles_id' =>$id,
						'module_permissions_id' => $mp,
						'created_at'=>cur_date_time(),
						'created_by'=>$input['created_by'],
					);				 
				}		
				// print_r($data);exit();
				$this->Mydb->delete_table_data('roles_permissions_map',array('roles_id'=>$id));
				$is_update = $this->db->insert_batch('roles_permissions_map',$data);
				if($is_update>0){
					$result['details']=$this->get_role_result($id);
					$value  = withSuccess($this->lang->line('permission_set_role_success'),$result);
				}else{
					$value  = withErrors($this->lang->line('failed_to_update'));
				}
			}
		}else{
			$value  = withErrors('Role id is required');
		}
		$this->response($value, REST_Controller::HTTP_OK);		
	}
    

    /**

     * Delete data from this method.

     *

     * @return Response

    */

    public function index_delete($id)
    {
		$data = $this->get_single_result($id);
		if(empty($data)){
			$value = withErrors($this->lang->line('data_not_exists'));
		}else{
			$p_data = $this->db->get_where("module_permissions", ['parent' => $data['id']])->row_array();
			if(empty($p_data)){
				$res = $this->Mydb->delete_table_data('module_permissions', array('id'=>$id));	
				if ($res == 1){
					$result = array('details'=>$data);		
					$value  = withSuccess($this->lang->line('module_permission_deleted_success'),$result);
				}else if ($res == - 1451)
				{
					$value = withErrors($this->lang->line('failed_to_delete'));
				}else{
					$value = withErrors($this->lang->line('failed_to_delete'));
				}
			}else{			
				$value = withErrors($this->lang->line('clear_children'));
			}
		}
		$this->response($value, REST_Controller::HTTP_OK);
    }
	
	/**
	
	* Search data from this method.
	
	*
	
	* @return Response
	
	*/
/**
	
	* Search data from this method.
	
	*
	
	* @return Response
	
	*/

	public function search()
    {
		$input = $this->input->get();
		if(!empty($input)){
			$rid = $input['roleId'];
			$list_data = $this->module_permissionsmodel->getrolepermission($rid);
			return $list_data;		
		}
        // $list_data = $this->module_permissionsmodel->TreeList();
		// $build_tree = $this->module_permissionsmodel->buildTree($list_data);	
		// print_R($list_data);exit();
	}
	
	
	/**

     * Check single permission from this method.

     *

     * @return Response

    */
   
	public function check_permission_post()
	{
		$input = $this->input->post(); 
		$rules = [
			'roles_id' => ['Role ID','required'],
			'ui_url' => ['Interface Url','required']
		];
		
		Validator::make($rules);
		if (!Validator::fails()) {
             Validator::error();
        } else {
			$result = $this->module_permissionsmodel->check_permission($input);
			if(!empty($result)){
				$value  = withSuccess('User is allowed for the action',$result);
				$this->response($value, REST_Controller::HTTP_OK);
			}else{
				$value  = withErrors('User is not allowed for the action');
				$this->response($value, REST_Controller::HTTP_OK);
			}
		}
	}
	
	
	/**

     * Get all the permissions of user from this method.

     *

     * @return Response

    */
   
	public function user_permissions_get($id=0)
	{
		if (empty($id) || $id<=0) {
            $value  = withErrors('User role id is required');
			$this->response($value, REST_Controller::HTTP_OK);
        } else {
			$data = $this->module_permissionsmodel->get_user_permissions($id);
			if(!empty($data)){
				$result=array('data_list'=>$data);
				$value  = withSuccess(count($data).' permissions found',$result);
			}else{				
				$value  = withSuccess('User role does not have any permissions');
			}
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}	

}