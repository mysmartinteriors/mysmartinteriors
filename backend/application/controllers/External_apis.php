<?php

require APPPATH . 'libraries/REST_Controller.php';

class External_apis extends REST_Controller {   

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->table = 'external_apis';
		$this->model_name='external_apis_model';    

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
      //print_r($this->db->last_query());
      $this->response($value, REST_Controller::HTTP_OK);
	}
   
 

   /**

     * Insert data from this method.

     *

     * @return Response

   */

   public function index_post(){
      $input = $this->input->post();
      $rules = [
         'name' => ['Name','required|max_length[200]|min_length[3]'],
         'description' => ['Description','max_length[500]'],
         'api_url' => ['API URL','required|valid_url'],
         'api_method' => ['API Method','required|max_length[50]'],
         //'api_result' => ['API Result','required'],
         //'api_auth' => ['API Authentication Value','required'],
         //'price' => ['Price','required'],
         'created_by' => ['Created By','required|numeric']
      ];
      
      Validator::make($rules);

      if(!Validator::fails()){
         Validator::error();
      }else{
			$description=!empty($input['description']) ? $input['description'] : '';
			$api_result=!empty($input['api_result']) ? $input['api_result'] : '';
			$api_auth=!empty($input['api_auth']) ? $input['api_auth'] : '';
            $data = array(
               'name' =>$input['name'],
               'description' =>$description,
               'api_url' =>$input['api_url'],
               'api_method' =>$input['api_method'],
               'api_result'=>$api_result,
               'api_auth'=>$api_auth,
               'created_at'=>cur_date_time(),
               'created_by'=>$input['created_by']
            );
			if(!empty($input['price'])){
				$data['price'] = $input['price'];
			}
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

   public function index_put($id=0){

      if(empty($id)){
         $value=withErrors('ID is required');
         $this->response($value, REST_Controller::HTTP_OK);
      }

      $input = $this->put();
      $rules = array();
      $data = array();
      
      if(!empty($input['name'])){
         $rules['name'] = ['Name','required|max_length[200]|min_length[3]'];
         $data['name'] = $input['name'];
      }
      if(!empty($input['description'])){
         $rules['description'] = ['Description','max_length[500]'];
         $data['description'] = $input['description'];
      }
      if(!empty($input['api_url'])){
         $rules['api_url'] = ['API URL','required|valid_url'];
         $data['api_url'] = $input['api_url'];
      }
      if(!empty($input['api_method'])){
         $rules['api_method'] = ['API Method','required|max_length[50]'];
         $data['api_method'] = $input['api_method'];
      }
      if(!empty($input['api_result'])){
         $rules['api_result'] = ['API Result','required'];
         $data['api_result'] = $input['api_result'];
      }
      if(!empty($input['api_auth'])){
         $rules['api_auth'] = ['API Authentication Value','required'];
         $data['api_auth'] = $input['api_auth'];
      }
	  if(!empty($input['price'])){
         $data['price'] = $input['price'];
      }
      if(!empty($input['updated_by'])){
         $rules['updated_by'] = ['Updated By','required|numeric'];
         $data['updated_by'] = $input['updated_by'];
      }
      
      if(array_filter($input)) {
         if(!empty($rules)){
            Validator::make($rules);   
         }     
         if (!Validator::fails()) {
            Validator::error();
         }        
      }

      if(!empty($data)){
         $get_data=$this->db->get_where($this->table,['id'=>$id])->row_array();
         if(!empty($get_data)){
            $data['updated_at']=cur_date_time();
            $is_update = $this->Mydb->update_table_data($this->table,array('id'=>$id),$data);
            if($is_update>0){
               $result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
               $value  = withSuccess($this->lang->line($this->table.'_updated_success'),$result);
            }else{
               $value  = withErrors($this->lang->line($this->table.'_update_fail'));
            }
         }else{
            $value  = withErrors($this->lang->line($this->table.'_not_found'));
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

	public function index_delete($id=0){
		if(empty($id)){
			$value=withErrors('API ID is required');
			$this->response($value, REST_Controller::HTTP_OK);
		}

		$get_data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if(!empty($get_data)){
			 $res = $this->Mydb->delete_table_data($this->table, array('id'=>$id));   
			 if ($res == 1){    
				$value  = withSuccess($this->lang->line($this->table.'_deleted_success'),$get_data);
			 }else if ($res == - 1451){
				$value = withErrors($this->lang->line('failed_to_delete'));
			 }else{
				$value = withErrors($this->lang->line('failed_to_delete'));
			 }
		}else{
			$value = withErrors($this->lang->line($this->table.'_not_found'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

   

}