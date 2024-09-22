<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class Subcategories extends REST_Controller {	 

    public function __construct() {

       	parent::__construct();
       	$this->load->database();

        $this->table = 'subcategories';
      	$this->model_name='subcategories_model';      

	   $this->load->model($this->model_name, "", true);
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
			'name' => ['Category Name','required|min_length[3]|max_length[20]|is_unique[categories.name]'],
			'description' => ['Category Description','min_length[3]|max_length[100]'],
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
				'code' =>strtolower(underscore_slug($input['name'])),
				'name' =>$input['name'],
				'description' =>!empty($input['description'])?$input['description']:'',
				'created_at'=>cur_date_time(),
				'created_by'=>$input['created_by'],
				'status'=>23,
			);
			$id = $this->Mydb->insert_table_data($this->table,$data);
			if(!empty($id)){
               $result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
               $value  = withSuccess($this->lang->line($this->table.'_created_success'),$result);
            }else{
               $value  = withErrors($this->lang->line($this->table.'_create_fail'));
            }
			$this->response($value, REST_Controller::HTTP_OK);
		}		

    }     

    /**

     * Update data from this method.

     *

     * @return Response

    */

    public function index_put($id=0)
    {
    	if(empty($id)){
         $value=withErrors('Id is required');
         $this->response($value, REST_Controller::HTTP_OK);
      	}

		$rules = array();
		$data = array();		
        $input = $this->put();		
		if(!empty($input['description'])){
			$data['description'] = $input['description'];
		}
		if(!empty($input['name'])){
			$rules['name'] = ['Category Name','required|min_length[3]|max_length[100]|edit_unique[categories.name.id.'.$id.']'];
			$data['name'] = $input['name'];
		}
		if(!empty($input['updated_by'])){
			$data['updated_by'] = $input['updated_by'];
		}
		if(!empty($input['status'])){
			$data['status'] = $input['status'];
		}
		if(!empty($input['image_id'])){
			$data['image_id'] = $input['image_id'];
			$rules['image_id'] = ['Image Id', 'numeric'];
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

		if(!empty($data)){
			$data['updated_at'] = cur_date_time();			
			$is_update = $this->Mydb->update_table_data($this->table, array('id'=>$id), $data);
			$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
			if($is_update>0){
				$value  = withSuccess($this->lang->line($this->table.'_updated_success'),$result);
			}else{
				$value  = withErrors($this->lang->line('failed_to_update'),$result);
			}    
		}else{
	        $value  = withErrors($this->lang->line('no_data_for_update'));
	    }   
		$this->response($value, REST_Controller::HTTP_OK);
    }

    /**

     * Delete data from this method.

     *

     * @return Response

    */

    public function index_delete($id=0)
    {
    	if(empty($id)){
         	$value=withErrors('Id is required');
        	$this->response($value, REST_Controller::HTTP_OK);
      	}
		$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		$res = $this->Mydb->delete_table_data($this->table, array('id'=>$id));	
		if ($res == 1){
			//$result = array('details'=>$data);		
			$value  = withSuccess($this->lang->line($this->table.'_deleted_success'),$data);
		}else if ($res == - 1451){
			$value = withErrors($this->lang->line('failed_to_delete'));
		}else{
			$value = withErrors($this->lang->line('failed_to_delete'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
    }

}