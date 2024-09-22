<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class Roles extends REST_Controller {

   
	 

    public function __construct() {

       parent::__construct();

       $this->load->database();
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
		
		$message = "success";
        if(!empty($id)){

            $data = $this->get_single_result($id);
			if(empty($data)){
				$message = $this->lang->line('no_result_found');
			}
			$data=array('details'=>$data);

        }else{
			
			$data = $this->search();
			if(empty($data['data_list'])){
				$message = $this->lang->line('no_result_found');
			}
			
        }
		//$result = array('details'=>$data);
		$value  = withSuccess($message,$data);

		$this->response($value, REST_Controller::HTTP_OK);

	}

	public function get_single_result($id){
		
		$filter_data[0]['type'] = 'roles.id'; $filter_data[0]['value'] = $id;
		
		$getData = $this->rolesmodel->filter($filter_data);	

		return $getData->row_array();
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
			'name' => ['Name','required']
			];

		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			
			$data = array(	
			'name' =>$input['name'],
			'description'=>$input['description'],
			'created_at'=>cur_date_time(),
			'status'=>4
		);
		$id = $this->Mydb->insert_table_data('roles',$data);
		$result['details'] = $this->get_single_result($id);
		$value  = withSuccess($this->lang->line('role_created_success'),$result);
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
			'name' => ['Name','required|min_length[3]|max_length[200]'],
			'departments_id' => ['Department','required'],
			];
		
		Validator::make($rules);		
	
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			
			$data = array(	
			'name' =>$input['name'],
			'departments_id'=>$input['departments_id'],
			'updated_at'=>cur_date_time(),
			'updated_by'=>$input['updated_by'],
			'status'=>$input['status']
		);
		
		$is_update = $this->Mydb->update_table_data('roles', array('id'=>$id), $data);
		$result['details'] = $this->get_single_result($id);
		if($is_update>0){
		$value  = withSuccess($this->lang->line('role_updated_success'),$result);
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
		$data = $this->db->get_where("users", ['roles_id' => $id])->row_array();
		if(empty($data)){
			$data = $this->get_single_result($id);
			$res = $this->Mydb->delete_table_data('roles', array('id'=>$id));	
			if ($res == 1){
				$result = array('details'=>$data);
				$value  = withSuccess($this->lang->line('role_deleted_success'),$result);
			}else if ($res == - 1451){
				$value = withErrors($this->lang->line('failed_to_delete'));
			}else{
				$value = withErrors($this->lang->line('failed_to_delete'));
			}
		}else{
			$value = withErrors('Since role is allocated to user(s), you cannot delete!');
		}
		$this->response($value, REST_Controller::HTTP_OK);

    }
	
	/**
	
	* Search data from this method.
	
	*
	
	* @return Response
	
	*/

	 public function search()

    {
		$param_default = array('search','perpage','page','sortby','orderby');
		$parameters = $this->input->get();
		$diff = array();
		$data = array();
		$data['data_list'] = array();	
		$search = "";
		$perpage = 10;
		$page = 1;
		$sortby = "roles.id";
		$orderby = "DESC";
		$all = false;
		$data['slno'] ='';
		
		if(!empty($parameters)){
				$parem_key = array_keys($parameters);
				$diff = array_diff($parem_key,$param_default);
				$intersect = array_intersect($parem_key,$param_default);
		}
		
		if(array_key_exists('page',$parameters)){
			$all = false;
		}
		
		if(!empty($intersect)){
			foreach($intersect as $inst){	
				$rml =  str_replace('-','.',$parameters[$inst]);
				$$inst = $rml;
			}
		}
	

		$filter_data[0]['type'] = 'search'; $filter_data[0]['value'] = $search;
		
		if(!empty($diff)){
			$i = count($filter_data);
			foreach($diff as $p){
				if(!empty($this->input->get($p))){
					$pa = str_replace('-','.',$p);
					$filter_data[$i]['type'] = $pa;
					$filter_data[$i]['value'] = $this->input->get($p);
				}
				$i++;
			}
		}

		
		$total_rows = $this->rolesmodel->filter($filter_data, 0, 0,$sortby,$orderby,$all);	   
		$udata = $this->Mydb->getPaginationData($perpage, $page, $total_rows);
		$data = array_merge($data,$udata);			
		$getData = $this->rolesmodel->filter($filter_data, $perpage, $data['page_position'],$sortby,$orderby,$all);			
		$data['pagination'] = $this->Mydb->paginate_function($perpage, $page, $total_rows, $data['total_pages']);
		
		
        if ($getData->num_rows() > 0) {            
           foreach ($getData->result() as $row) {
                $data['data_list'][] = $row;
				if ($page == 1) {
	                $data['slno'] = 1;
	            } else {
	                $data['slno'] = (($page - 1) * $perpage) + 1;
	        	}
		   }
			}
		if($all){
			array_splice($data,1);
		}
		//print_r($data);
		return $data;
		
    }
    	
	

}