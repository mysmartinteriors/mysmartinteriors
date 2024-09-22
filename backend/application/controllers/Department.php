<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class Department extends REST_Controller {

   
	 

    public function __construct() {

       parent::__construct();

       $this->load->database();
	  $this->load->model("departmentmodel", "", true);
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
        }else{
			$parameters = $this->input->get();
			if(!empty($parameters)){	
				if(array_key_exists('branch_id',$parameters)){
					$data = $this->search($parameters['branch_id']);	
					$error  = false;
				}
			}
		}
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}

		$result = array('details'=>$data);
		if($error){
			$message = "Branch id required";
			$value  = withErrors($message,$result);
			$this->response($value, REST_Controller::HTTP_BAD_REQUEST);
		}else{
			$value  = withSuccess($message,$result);
			$this->response($value, REST_Controller::HTTP_OK);
		}

	}

    public function get_single_result($id){		
		$getData = $this->departmentmodel->get_department($id);	
		return $getData->row_array();
	}
	
	public function list_all_get(){
		$message = "success";
		$input = $this->input->get();		
		$data = $this->db->get_where("departments", ['organization_branches_id' => $input['branch_id']])->result();
		//print_r($this->db->last_query());
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$result = array('data_list'=>$data);
		$value  = withSuccess($message,$result);
		$this->response($value, REST_Controller::HTTP_OK);
	}
      

    /**

     * Insert data from this method.

     *

     * @return Response

    */

    public function index_post()
    {
        $input = $this->input->post();		
		$rules = [
			'name' => ['Department name','required|min_length[3]|max_length[200]'],
			'parent' => ['Parent','required'],
			'branch' => ['Branch','required'],	
		];
		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			
			$data = array(	
			'name' =>$input['name'],
			'parent'=>$input['parent'],
			'organization_branches_id'=>$input['branch'],			
			'created_at'=>cur_date_time(),
			'created_by'=>$input['created_by'],
		);
		$id = $this->Mydb->insert_table_data('departments',$data);
		$result['details'] = $this->get_single_result($id);
		$value  = withSuccess($this->lang->line('dept_created_success'),$result);
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
			'name' => ['Department name','required|min_length[3]|max_length[200]'],
			'parent' => ['Parent','required'],
			'branch' => ['Branch','required'],
			];
		
		Validator::make($rules);		
		
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			
			$data = array(	
			'name' =>$input['name'],
			'parent'=>$input['parent'],
			'organization_branches_id'=>$input['branch'],	
			'updated_at'=>cur_date_time(),
			'updated_by'=>$input['updated_by'],
		);
		$is_update = $this->Mydb->update_table_data('departments', array('id'=>$id), $data);
		$result['details'] = $this->get_single_result($id);
		if($is_update>0){
		$value  = withSuccess($this->lang->line('dept_updated_success'),$result);
		}else{
			$value  = withErrors($this->lang->line('failed_to_update'),$result);
		}
		$this->response($value, REST_Controller::HTTP_OK);
		}
    }

    

    /**

     * Delete data from this method.

     *

     * @return Response

    */

    public function index_delete($id)
    {
		$p_data = $this->db->get_where("users", ['departments_id' => $id])->num_rows();
		if($p_data==0){
			$p_data = $this->db->get_where("roles", ['departments_id' => $id])->num_rows();
			if($p_data==0){
				$p_data = $this->db->get_where("departments", ['parent' => $id])->num_rows();
				if($p_data==0){
					$data = $this->get_single_result($id);
					$res = $this->Mydb->delete_table_data('departments', array('id'=>$id));	
					
					if ($res == 1)
					{
					$result = array('details'=>$data);
					 $value  = withSuccess($this->lang->line('dept_deleted_success'),$result);
					}
				  else
					if ($res == - 1451)
					{
					$value = withErrors($this->lang->line('failed_to_delete'));
					}
				  else
					{
					$value = withErrors($this->lang->line('failed_to_delete'));
					}
				}else{
					$value = withErrors($this->lang->line('clear_children'));
				}
			}else{
				$value = withErrors($this->lang->line('dept_has_roles'));
			}
		}else{
			$value = withErrors($this->lang->line('dept_has_users'));
		}

		$this->response($value, REST_Controller::HTTP_OK);


    }
	
	/**
	
	* Search data from this method.
	
	*
	
	* @return Response
	
	*/

	public function search($branch_id)
    {					
        $list_data = $this->departmentmodel->departmentTreeList($branch_id);
		$build_tree = $this->departmentmodel->buildTree($list_data);	
		return $build_tree;	
	}
    	
	
	
}