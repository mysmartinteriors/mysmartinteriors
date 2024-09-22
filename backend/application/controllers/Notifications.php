<?php

require APPPATH . 'libraries/REST_Controller.php';

class Notifications extends REST_Controller {
    public function __construct() {
       parent::__construct();
       $this->load->database();
	   $this->table='notifications';
	   $this->model_name='notifications_model';
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
			'dataId' => ['Data ID','required|numeric'],
			'reference_id' => ['Reference ID','required|numeric'],
			'reference_type' => ['Reference Type','required|numeric'],
			'title' => ['Title','required'],
			'description' => ['Description','required'],
			'reference_url' => ['URL','required']
		];
		Validator::make($rules);
		if (!Validator::fails()){
            Validator::error();
        }else{
			$data = array(
				'dataId'=>$input['dataId'],
				'reference_id'=>$input['reference_id'],
				'reference_type' =>$input['reference_type'],
				'title' =>$input['title'],
				'description' =>$input['description'],
				'reference_url' =>$input['reference_url'],
				'is_seen'=>1,
				'created_at'=>cur_date_time()
			);
			$id = $this->Mydb->insert_table_data($this->table,$data);
			//print_r($this->db->last_query());
			if($id>0){
				$value  = withSuccess('Notification created successfully');
			}else{
				$value  = withErrors('Notification create failed');
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
		$rules = array();
		$data = array();		
        $input = $this->put();
		
		if(isset($input['is_seen'])){
			$rules['is_seen'] = ['Seen','required'];
			$data['is_seen'] = $input['is_seen'];
			$data['seen_at'] = cur_date_time();
		}
				
		if(array_filter($input)) {
			if(!empty($rules)){
				Validator::make($rules);	
			}		
			if (!Validator::fails()) {
				Validator::error();
			}			
		}		
		
		$where=array();
		if(!empty($input['dataId'])){
			$where=array_merge($where,array('dataId'=>$input['dataId']));
		}
		if(!empty($input['reference_id'])){
			$where=array_merge($where,array('reference_id'=>$input['reference_id']));
		}
		if(!empty($input['reference_type'])){
			$where=array_merge($where,array('reference_type'=>$input['reference_type']));
		}
		// print_r($data);
		if(!empty($where)){
			$is_update = $this->Mydb->update_table_data($this->table, $where, $data);
			if($is_update>0){
				$value  = withSuccess('Notification updated successfully');
			}else{
				$value  = withErrors('Notification update failed');
			}
		}else{
			$value  = withErrors('No conditions found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
		
    }

}	