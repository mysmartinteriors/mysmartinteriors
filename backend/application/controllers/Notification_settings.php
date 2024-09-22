<?php

require APPPATH . 'libraries/REST_Controller.php';

class Notification_settings extends REST_Controller {
    public function __construct() {
       parent::__construct();
       $this->load->database();
	   $this->table='notification_settings';
	   $this->model_name='notification_settings_model';
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
		// print_r($input);exit();
		$rules = [
			'customers_id' => ['CustomerID','required|numeric'],
			'customer_branches_id' => ['Branch ID','numeric'],
			'customer_branches_persons_id' => ['Contact Person ID','numeric'],
			//'users_id' => ['Users List','required'],
			'created_by' => ['Created By','required']
		];
		Validator::make($rules);
		if (!Validator::fails()){
            Validator::error();
        }else{
			$data = array(
				'customers_id'=>$input['customers_id'],
				'customer_branches_id'=>$input['customer_branches_id'],
				'customer_branches_persons_id' =>$input['customer_branches_persons_id'],
				'created_by' =>$input['created_by'],
				'created_at'=>cur_date_time(),
				'status'=>1
			);
			
			// $sql = "SELECT * FROM $this->table ";
			// $sql.=" WHERE $this->table.customers_id=".$input['customers_id'];
			
			// if(!empty($input['customer_branches_id'])){
				// $data['customer_branches_id']=$input['customer_branches_id'];
				// $sql.=" AND $this->table.customer_branches_id=".$input['customer_branches_id'];
			// }else{
				// $sql.=" AND $this->table.customer_branches_id IS NULL";
			// }
			// if(!empty($input['customer_branches_persons_id'])){
				// $data['customer_branches_persons_id']=$input['customer_branches_persons_id'];
				// $sql.=" AND $this->table.customer_branches_persons_id=".$input['customer_branches_persons_id'];
			// }else{
				// $sql.=" AND $this->table.customer_branches_persons_id IS NULL";
			// }
			
			// $query=$this->db->query($sql);
			// if($query->num_rows()>0){
				// $value  = withErrors('Customer/Branch/SPOC row is already exists, You can edit & save that row.');
				// $this->response($value, REST_Controller::HTTP_OK);
			// }
			
			if(!empty($input['services_id'])){
				$data['services_id']=$input['services_id'];
			}
			if(!empty($input['users_id'])){
				$data['users_id']=$input['users_id'];
			}
			
			$id = $this->Mydb->insert_table_data($this->table,$data);
			//print_r($this->db->last_query());
			if($id>0){
				$get_data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
				$value  = withSuccess('Settings added successfully',$get_data);
			}else{
				$value  = withErrors('Settings failed');
			}
			$this->response($value, REST_Controller::HTTP_OK);
		}
    }
	
	public function test_get()
    {
      $message = "success";
	  $inputs=array(
		'customers_id'=>234,
		'customer_branches_id'=>856,
		'customer_branches_persons_id'=>1101,
		'services_id'=>26
	  );
      $data = $this->notification_settings_model->get_owners($inputs);

      if(!empty($data)){
         $value  = withSuccess($message,$data);
      }else{
         $value  = withSuccess($this->lang->line('no_result_found'));
      }
      
      $this->response($value, REST_Controller::HTTP_OK);
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
		
		
		if(!empty($input['services_id'])){
			$data['services_id'] = json_encode($input['services_id']);
		}
		if(!empty($input['users_id'])){
			$data['users_id'] = json_encode($input['users_id']);
		}
		if(!empty($input['updated_by'])){
			$rules['updated_by'] = ['Updated By','required|numeric'];
			$data['updated_by'] = $input['updated_by'];
		}
			
		// print_r($data);exit();	
		
		if(array_filter($input)) {
			if(!empty($rules)){
				Validator::make($rules);	
			}		
			if (!Validator::fails()) {
				Validator::error();
			}			
		}		
		
		// print_r($data);
		if(!empty($data)){
			$data['updated_at']=cur_date_time();

			$is_update = $this->Mydb->update_table_data($this->table, array('id'=>$id), $data);
			if($is_update>0){
				$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
				$value  = withSuccess('Settings updated successfully',$data);
			}else{
				$value  = withErrors('Settings update failed');
			}
		}else{
			$value  = withErrors('No conditions found');
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
		$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if(!empty($data)){
			$res = $this->Mydb->delete_table_data($this->table, array('id'=>$id));	
			if ($res == 1){	
				$value  = withSuccess('Settings deleted successfully',$data);
			}else if ($res == - 1451){
				$value = withErrors($this->lang->line('failed_to_delete'));
			}else{
				$value = withErrors($this->lang->line('failed_to_delete'));
			}
		}else{
			$value = withErrors($this->lang->line('no_result_found'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
    }	

}	