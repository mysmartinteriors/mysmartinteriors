<?php

require APPPATH . 'libraries/REST_Controller.php';   

class Media_library extends REST_Controller {
    public function __construct() {
       parent::__construct();
       $this->load->database();
	   $this->load->model("medialibrary_model", "", true);
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
            $data = $this->db->get_where("uploads", ['id' => $id])->row_array();
			if(empty($data)){
				$message = $this->lang->line('no_result_found');
			}
			$data = array('details'=>$data);
        }else{			
			$data = $this->search();
			if(empty($data['data_list'])){
				$message = $this->lang->line('no_result_found');
			}			
        }
		$value  = withSuccess($message,$data);
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
			'orig_name' => ['File Original Name','required'],
			'file_name' => ['File Encrypted Name','required'],
			'file_ext' => ['File Extension','required'],
			'file_size' => ['File Size','required'],
			'file_path' => ['File Relative Path','required'],
			'base_path' => ['File Base Path','required'],
			'created_by' => ['Created By','required|numeric'],
		];
		Validator::make($rules);
		if (!Validator::fails()){
            Validator::error();
        }else{
			$data = array(
				'orig_name'=>$input['orig_name'],
				'file_name' =>$input['file_name'],
				'file_ext' =>$input['file_ext'],
				'file_size' =>$input['file_size'],
				'base_path'=>$input['base_path'],
				'file_path'=>$input['file_path'],
				'upload_for'=>$input['upload_for'],
				'created_at'=>cur_date_time(),
				'created_by'=>$input['created_by']
			);
			if(isset($input['reference_id']) && !empty($input['reference_id'])){
				$data['reference_id'] = $input['reference_id'];
			}
			$id = $this->Mydb->insert_table_data('uploads',$data);
			//print_r($this->db->last_query());
			$result['details'] = $this->Mydb->get_table_data('uploads',array('id'=>$id));
			$value  = withSuccess($this->lang->line('uploads_success'),$result);
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
		if(!empty($input['email'])){
			$rules['email'] = ['Email','required|valid_email|min_length[3]|max_length[200]'];
			$data['email'] = $input['email'];
		}
		if(!empty($input['phone'])){
			$rules['phone'] = ['Phone','required|min_length[10]|max_length[15]'];
			$data['phone'] = $input['phone'];
		}
		if(!empty($input['address'])){
			$rules['address'] = ['Address','required|min_length[5]|max_length[300]'];
			$data['address'] = $input['address'];
		}
		if(!empty($input['updated_by'])){
			$data['updated_by'] = $input['updated_by'];
		}
		if(!empty($input['status'])){
			$data['status'] = $input['status'];
		}
		
		if(array_filter($input)) {
			if(!empty($rules)){
				Validator::make($rules);	
			}		
			if (!Validator::fails()) {
				Validator::error();
			}			
		}		
		
		$data['updated_at'] = cur_date_time();
		
		$is_update = $this->Mydb->update_table_data('uploads', array('id'=>$id), $data);
		$result['details'] = $this->Mydb->get_table_data('uploads',array('id'=>$id));
		if($is_update>0){
			$value  = withSuccess($this->lang->line('uploads_success'),$result);
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
	
		$data = $this->db->get_where("uploads", ['id' => $id])->row_array();
       $res = $this->Mydb->delete_table_data('uploads', array('id'=>$id));	
	
		if ($res == 1)
			{
			$result = array('details'=>$data);		
			 $value  = withSuccess($this->lang->line('uploads_deleted_success'),$result);
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
		$sortby = "uploads.id";
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
		
		$total_rows = $this->medialibrary_model->filter($filter_data, 0, 0,$sortby,$orderby,$all);	   
		$udata = $this->Mydb->getPaginationData($perpage, $page, $total_rows);
		$data = array_merge($data,$udata);			
		$getData = $this->medialibrary_model->filter($filter_data, $perpage, $data['page_position'],$sortby,$orderby,$all);			
       $data['pagination'] = $this->Mydb->paginate_function($perpage, $data['page_number'], $total_rows, $data['total_pages']);
		
		
        if ($getData->num_rows() > 0) {            
           foreach ($getData->result() as $row) {
                $data['data_list'][] = $row;
		   }
		   if ($page == 1) {
				$data['slno'] = 1;
			} else {
				$data['slno'] = (($page - 1) * $perpage) + 1;
			}
		}
		if($all){
			array_splice($data,1);
		}
		return $data;
		
    }
    	
	

}