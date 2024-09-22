<?php
require APPPATH . 'libraries/REST_Controller.php';

class Delivery_address extends REST_Controller {
   
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table= 'delivery_address';
        $this->model_name='delivery_address_model';   
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
			'address' => ['Address','required'],
			'delivery_charge' => ['Delivery Charges','required'],
			'status' => ['Status','required']
			];

		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			
			$data = array(	
				'address'=>$input['address'],
				'pincode'=>$input['pincode'],
				'delivery_charge'=>$input['delivery_charge'],
				'status'=>$input['status'],
				'createdDate'=>cur_date_time(),
				'status'=>40
			);
			$id = $this->Mydb->insert_table_data('delivery_address',$data);
			if($id){
				$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
				$value  = withSuccess('Delivery Address Created Successfully',$data);
			}else{
				$value  = withSuccess('Failed to Create Delivery Address',$data);
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

		if(!empty($input['address'])){
			$rules['address'] = ['Address','required'];
			$data['address'] = $input['address'];
		}
		if(!empty($input['pincode'])){
			$rules['pincode'] = ['Pincode','required'];
			$data['pincode'] = $input['pincode'];
		}
		if(!empty($input['delivery_charge'])){
			$rules['delivery_charge'] = ['delivery_charge','required'];
			$data['delivery_charge'] = $input['delivery_charge'];
		}
		if(!empty($input['status'])){
			$rules['status'] = ['Status','required'];
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
		$data['updatedDate'] = cur_date_time();
		$is_update = $this->Mydb->update_table_data('delivery_address', array('id'=>$id), $data);
		$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if($is_update>0){
			$value  = withSuccess('Delivery address updated successfully',$result);
		}else{
			$value  = withErrors('Failed to Update',$result);
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
		if(!empty($id)){
			$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
			$res = $this->Mydb->delete_table_data('delivery', array('id'=>$id));
			if ($res == 1)
			{	
				$value  = withSuccess('Delivery Boy Deleted Successfully',$result);
			}else if ($res == - 1451){
				$value = withErrors($this->lang->line('failed_to_delete'));
			}else{
				$value = withErrors($this->lang->line('failed_to_delete'));
			}
		}else{
			$value = withErrors('Delivery Boy cannot delete');
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