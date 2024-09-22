<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class Modules extends REST_Controller {

   
	public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table= 'admin_modules';
        $this->model_name='modulesmodel';   
        $this->load->model($this->model_name, "", true);  
        $this->load->library('excelvalidation');
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
      $data=array();
      if(!empty($id)){
         $data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
      }else{       
         $data = $this->Mydb->do_search($this->table,$this->model_name);
      }
      if(!empty($data)){
         $value  = withSuccess($message,$data);
      }else{
         $value  = withSuccess($this->lang->line('no_result_found'));
      }
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
			'name' => ['Module name','required|min_length[3]|max_length[200]|is_unique[modules.table_name]'],
			'table_name' => ['Table name','required|min_length[3]|max_length[200]'],
			];
		$message = [
			'is_unique' => 'The %s is already exists',
		];	
		Validator::setMessage($message);
		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			
			$data = array(	
			'name' =>$input['name'],
			'table_name' =>$input['table_name'],
			'created_at'=>cur_date_time(),
			'created_by'=>$input['created_by'],
			'status'=>14,
		);
		$id = $this->Mydb->insert_table_data('modules',$data);
		$result['details'] = $this->get_single_result($id);
		$value  = withSuccess($this->lang->line('module_created_success'),$result);
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
		
		if(!empty($input['table_name'])){
			$rules['table_name'] = ['Module name','required|max_length[400]'];
			$data['table_name'] = $input['table_name'];
		}
		if(!empty($input['name'])){
			$rules['name'] = ['Name','required|max_length[400]|edit_unique[modules.table_name.id.'.$id.']'];
			$data['name'] = $input['name'];
		}
		if(!empty($input['updated_by'])){
			$data['updated_by'] = $input['updated_by'];
		}
		if(!empty($input['status'])){
			$data['status'] = $input['status'];
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
		
		$is_update = $this->Mydb->update_table_data('modules', array('id'=>$id), $data);
		$result['details'] = $this->get_single_result($id);
		if($is_update>0){
		$value  = withSuccess($this->lang->line('module_updated_success'),$result);
		}else{
			$value  = withErrors($this->lang->line('failed_to_update'),$result);
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
		$p_data = $this->db->get_where("module_permissions", ['modules_id' => $id])->row_array();
		if(empty($p_data)){
			$res = $this->Mydb->delete_table_data('modules', array('id'=>$id));	
			if ($res == 1){
				$result = array('details'=>$data);		
				$value  = withSuccess($this->lang->line('module_deleted_success'),$result);
			}else if ($res == - 1451){
				$value = withErrors($this->lang->line('failed_to_delete'));
			}else{
				$value = withErrors($this->lang->line('failed_to_delete'));
			}
		}else{
			$value = withErrors($this->lang->line('clear_module_permissions'));
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
		$sortby = "modules.id";
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
		
		$total_rows = $this->modulesmodel->filter($filter_data, 0, 0,$sortby,$orderby,$all);	   
		$udata = $this->Mydb->getPaginationData($perpage, $page, $total_rows);
		$data = array_merge($data,$udata);			
		$getData = $this->modulesmodel->filter($filter_data, $perpage, $data['page_position'],$sortby,$orderby,$all);			
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
		return $data;
		
    }
    	
	

}