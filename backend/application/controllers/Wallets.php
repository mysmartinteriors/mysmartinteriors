<?php

require APPPATH . 'libraries/REST_Controller.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Wallets extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table= 'wallets';
        $this->model_name='wallets_model';   
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
		// print_R($input);exit();
		$rules = [
			'customerId' => ['Customer','required'],
			'planId' => ['Subscription','required'],
			'balance' => ['Wallet Amount','required'],
			'status' => ['Status','required']
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
				'customerId' =>$input['customerId'],
				'planId' =>$input['planId'],
				'balance' =>$input['balance'],
				'status' =>$input['status'],
			);
		
			$id = $this->Mydb->insert_table_data('wallets',$data);
			if($id){
				$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
				$value  = withSuccess('Subscription Plan Created Successfully',$data);
			}else{
				$value  = withSuccess('Failed to Create Subscription Plan',$data);
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
		$rules = array();
		$data = array();
		
        $input = $this->put();
		// print_R($input);exit();

		if(!empty($input['status'])){
			$rules['status'] = ['Status','required'];
			$data['status'] = $input['status'];
		}
		if(!empty($input['basic_amount'])){
			$rules['basic_amount'] = ['Subscription Amount','required'];
			$data['basic_amount'] = $input['basic_amount'];
		}
		if(!empty($input['wallet_points'])){
			$rules['wallet_points'] = ['Wallet Amount','required'];
			$data['wallet_points'] = $input['wallet_points'];
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
		$data['updatedDate'] = cur_date_time();
// print_R($data);exit();
		$is_update = $this->Mydb->update_table_data('subscription', array('id'=>$id), $data);
		// print_r($is_update);exit();
		$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if($is_update>0){
			$value  = withSuccess($this->lang->line('Subscription Plan Updated Successfully'),$result);
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

    public function index_delete($id=0)
    {
		$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		$res = $this->Mydb->delete_table_data('subscription', array('id'=>$id));
		if ($res == 1)
		{	
			$value  = withSuccess($this->lang->line('Subscription Plan Deleted Successfully'),$result);
		}else if ($res == - 1451){
			$value = withErrors($this->lang->line('failed_to_delete'));
		}else{
			$value = withErrors($this->lang->line('failed_to_delete'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
    }
    	
	function role_services_get($id){
		
		if(empty($id)){
			$value = withErrors('User id is required');
			$this->response($value, REST_Controller::HTTP_OK);
		}
		$result_type='all';
		if(!empty($this->input->get('result_type'))){
			$result_type=$this->input->get('result_type');
		}
		$u_data = $this->db->get_where($this->table, array('id' => $id))->row_array();
		if(!empty($u_data)){
			$result=$this->usersmodel->get_role_services($u_data,$result_type);
			//print_r($result);exit();
			$value=withSuccess('Success',array('data_list'=>$result));
			$this->response($value, REST_Controller::HTTP_OK);
		}else{
			$value = withErrors('User not found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}
	
	
	function test_import_post(){
		$input = $this->input->post();			
		$data = array(	
			'name' =>$input['name'],
			'email' =>$input['email'],
			'phone' =>$input['phone'],
			'password' => '12345',
		);
		$id = $this->Mydb->insert_table_data('user_data',$data);

		$value  = withSuccess('success');
		$this->response($value, REST_Controller::HTTP_OK);
	}

}