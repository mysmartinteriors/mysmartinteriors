<?php
require APPPATH . 'libraries/REST_Controller.php';

class Delivery extends REST_Controller {
   
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table= 'delivery';
        $this->model_name='delivery_model';   
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

	//check add exist

	function check_exist_get(){
		$input = $this->input->get();
		if(!empty($input['code'])){
			$code = $input['code'];
			$sql = "SELECT * from $this->table where $this->table.id >0 && $this->table.code = '$code'";
			// print_R($sql);exit();
			$query = $this->db->query($sql)->row_array();
			return $query;
		}
	}

	public function order_details_get(){
		$message = "success";
      	$data=array();
		$orderId = $this->get("orderId");
		if(!empty($orderId)){
			$data = $this->orders_model->order_details($orderId);
			if(!empty($data)){
				$value  = withSuccess($message,array("details" => $data));
			}else{
				$value  = withSuccess($this->lang->line('no_result_found'));
			}
		}else{
			$value  = withSuccess($this->lang->line('Order Not Found'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


	public function product_details_get(){
		$message = "success";
      	$data=array();
		$orderId = $this->get("orderId");
		if(!empty($orderId)){
			$data = $this->orders_model->product_details($orderId);
			if(!empty($data)){
				$value  = withSuccess($message,array("details" => $data));
			}else{
				$value  = withSuccess($this->lang->line('no_result_found'));
			}
		}else{
			$value  = withSuccess($this->lang->line('Order Not Found'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	/**

     * Get user by email or username for login authentication.

     *

     * @return Response

    */

    public function authentication_get()
    {
		$input = $this->get();
		$uid =$input['phone'];
		$sql="SELECT * FROM delivery WHERE phone='$uid' OR email='$uid'";
		$query=$this->db->query($sql);
		$result['details'] =$query->row_array();
		$value  = withSuccess('success',$result);
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
			'name' => ['Name','required|min_length[3]|max_length[200]'],
			'code' => ['Code','required'],
			'email' => ['Email','required'],
			'phone' => ['Phone','required'],
			'address' => ['Address','required'],
			'password' => ['Password','required'],
			'availability' => ['Availability','required'],
			'profile_picture' => ['Profile Picture','required'],
			'aadhaar_card' => ['Aadhaar Card','required'],
			'pan_card' => ['PAN Card','required'],
			'driving_licence' => ['Driving Licence','required'],
			'vehicle_rc' => ['Vehicle RC','required'],
			'pincode' => ['Pincode','required'],
			'status' => ['Status','required']
			];

		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			$password = $this->Mydb->hash($input['password']);
			$data = array(	
			'name' =>$input['name'],
			'code'=>$input['code'],
			'email'=>$input['email'],
			'phone'=>$input['phone'],
			'address'=>$input['address'],
			'pincode'=>$input['pincode'],
			'password'=>$password,
			'availability'=>$input['availability'],
			'profile_picture'=>$input['profile_picture'],
			'aadhaar_card'=>$input['aadhaar_card'],
			'pan_card'=>$input['pan_card'],
			'driving_licence'=>$input['driving_licence'],
			'vehicle_rc'=>$input['vehicle_rc'],
			'status'=>$input['status'],
			'createdDate'=>cur_date_time(),
			'status'=>40
		);
		$id = $this->Mydb->insert_table_data('delivery',$data);
			if($id){
				$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
				$value  = withSuccess('Delivery Boy Created Successfully',$data);
			}else{
				$value  = withSuccess('Failed to Create Delivery Boy',$data);
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

		if(!empty($input['name'])){
			$rules['name'] = ['Name','required'];
			$data['name'] = $input['name'];
		}
		if(!empty($input['code'])){
			$rules['code'] = ['Code','required'];
			$data['code'] = $input['code'];
		}
		if(!empty($input['email'])){
			$rules['email'] = ['Email','required'];
			$data['email'] = $input['email'];
		}
		if(!empty($input['phone'])){
			$rules['phone'] = ['Phone','required'];
			$data['phone'] = $input['phone'];
		}
		if(!empty($input['address'])){
			$rules['address'] = ['Address','required'];
			$data['address'] = $input['address'];
		}
		if(!empty($input['password'])){
			$rules['password'] = ['password','required'];
			$data['password'] = $this->Mydb->hash($input['password']);
		}
		if(!empty($input['availability'])){
			$rules['availability'] = ['availability','required'];
			$data['availability'] = $input['availability'];
		}
		if(!empty($input['pincode'])){
			$rules['pincode'] = ['Pincode','required'];
			$data['pincode'] = $input['pincode'];
		}
		if(!empty($input['profile_picture'])){
			$rules['profile_picture'] = ['profile_picture','required'];
			$data['profile_picture'] = $input['profile_picture'];
		}
		if(!empty($input['aadhaar_card'])){
			$rules['aadhaar_card'] = ['aadhaar_card','required'];
			$data['aadhaar_card'] = $input['aadhaar_card'];
		}
		if(!empty($input['pan_card'])){
			$rules['pan_card'] = ['pan_card','required'];
			$data['pan_card'] = $input['pan_card'];
		}
		if(!empty($input['driving_licence'])){
			$rules['driving_licence'] = ['driving_licence','required'];
			$data['driving_licence'] = $input['driving_licence'];
		}
		if(!empty($input['vehicle_rc'])){
			$rules['vehicle_rc'] = ['vehicle_rc','required'];
			$data['vehicle_rc'] = $input['vehicle_rc'];
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

		// if(!empty($input['password'])){
		// 	$data['password'] = $this->Mydb->hash($input['password']);
		// }	
		
		$data['updatedDate'] = cur_date_time();
		$is_update = $this->Mydb->update_table_data('delivery', array('id'=>$id), $data);
		$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if($is_update>0){
			$value  = withSuccess('Delivery Boy updated successfully',$result);
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