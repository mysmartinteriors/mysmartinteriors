<?php

require APPPATH . 'libraries/REST_Controller.php'; 

class Bulk_requests extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();        
        $this->table= 'workorder_bulk_requests';
        $this->model_name='workorder_bulk_request_model';   

        $this->load->model($this->model_name, "", true);
		$this->load->model("workorders_model", "", true);
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
            'workorders_id' => ['Workorder','required|numeric'],
            'excel_path' => ['Excel Path','required'],
            //'zip_path' => ['Zip Path','required'],
            'customers_id' => ['Customer','required|numeric'],
            'customer_branches_id' => ['Customer Branch','required|numeric'],
            'customer_branches_persons_id' => ['Branch Contact  Person','required|numeric'],
            'description' => ['Description','max_length[500]'],
            'created_by' => ['Created By','required|numeric']
        ];
        Validator::make($rules);
        if (!Validator::fails()){
            Validator::error();
        }else{
			
			$workorder=$this->db->get_where('workorders',array('id'=>$input['workorders_id']))->row_array();
			if(empty($workorder)){
				 $value  = withErrors('Workorder not found');
				 $this->response($value, REST_Controller::HTTP_OK);
			}			

            $data = array(  
                'customers_id' =>$input['customers_id'],
                'customer_branches_id' =>$input['customer_branches_id'],
                'customer_branches_persons_id'=>$input['customer_branches_persons_id'],
                'workorders_id' =>$workorder['id'],
                'excel_path'=>$input['excel_path'],
                'created_at'=>cur_date_time(),
                'created_by'=>$input['created_by'],
                'status' =>52
            );
			if(!empty($input['zip_path'])){
				$data['zip_path']=$input['zip_path']; 
			}
			
			if(!empty($input['description'])){
				$data['description']=$input['description'];
			}
            $id = $this->Mydb->insert_table_data($this->table,$data);
			if(!empty($id)){				
				// call function to create log				
				$logData=array(
					'workorders_id'=>$id,
					'created_by'=>$input['created_by'],
					'description'=>'Created a bulk request'
				);
				// $this->save_check_log($logData);
				
                $result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
                $value  = withSuccess($this->lang->line($this->table.'_created_success'),$result);
            }else{
                $value  = withErrors($this->lang->line($this->table.'_create_fail'));
            }
            $this->response($value, REST_Controller::HTTP_OK);
		}
	}
	
	function save_check_log($data){
		$data['created_at']=cur_date_time();
		$data['ip_address']=getRealIpAddr();
		$data['created_by_type']=60;
		$this->Mydb->insert_table_data('workorders_log',$data);
	}	
	
}