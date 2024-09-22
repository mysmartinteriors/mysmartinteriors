<?php

require APPPATH . 'libraries/REST_Controller.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Products_featured extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table= 'featured_products'; 
        $this->model_name='featured_products_model';   
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
    //   if(!empty($id)){
    //      $data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
    //   }else{       
    //      $data = $this->Mydb->do_search($this->table,$this->model_name);
    //   }
    //   if(!empty($data)){
    //      $value  = withSuccess($message,$data);
    //   }else{
    //      $value  = withSuccess($this->lang->line('no_result_found'));
    //   }
	if(!empty($id)){
		$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if(!empty($data['details'])){
		   $data['details']['product_features'] = $this->get_product_feature($data['details']['id']);
		   $data['details']['product_metrics'] = $this->get_product_metrics($data['details']['id']);
		   $productData = $data['details'];
		}
	 }else{       
		$data = $this->Mydb->do_search($this->table,$this->model_name);
		if($data['data_list']){
		   foreach($data['data_list'] as $index=>$dataList){
			   $dataList->product_features = $this->get_product_feature($dataList->id);
			   $dataList->product_metrics = $this->get_product_metrics($dataList->id);
			   $productData[] = $dataList;
		   }
		}
	 }
	 if(!empty($data)){
		$value  = withSuccess($message,$data);
	 }else{
		$value  = withSuccess($this->lang->line('no_result_found'));
	 }
	 $this->response($value, REST_Controller::HTTP_OK);
      $this->response($value, REST_Controller::HTTP_OK);
    }



	function get_product_feature($id){
		$message = "success"; 
		$data = array();
		$data = $this->db->get_where('product_features', array('productId'=>$id))->row_array();
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$imagesData = $this->db->get_where('product_images', array('productId'=>$id))->result_array();
		if(!empty($imagesData)){
			$data['product_images'] = $imagesData;
		}
		return $data;
	} 
	
	function get_product_metrics($id){
		$metrics = $this->db->get_where('product_metrics', array('productId'=>$id, 'status'=>'Active'))->result_array();
		return $metrics;
	}

   
    /**

     * Get the list of users from this method.

     *

     * @return Response

    */		
	
	function list_get(){
		$message = "success";
		$data = $this->usersmodel->get_users_list();
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$data = array('data_list'=>$data);
		$value  = withSuccess($message,$data);
		$this->response($value, REST_Controller::HTTP_OK);
	}   
   
    /**

     * Get the list of Product Feature from this method.

     *

     * @return Response

    */		
	
   
   
    /**

     * Insert data from this method.

     *

     * @return Response

    */

	// $data['productId']=$data_list['id'];
	// $data['categoryId']=$data_list['categoryId']; 
	// $data['status']=1;
	// $data['createdDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
	// $data['updatedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
    public function index_post()
    {
        $input = $this->input->post();		
		$rules = [
			'productId' => ['Product ID','required|numeric'],
			'categoryId' => ['Category ID','required|numeric'],
		];
		$message = [
			'is_unique' => 'The %s is already exists',
		];	
		Validator::setMessage($message);
		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			// print_r($input);exit();
			$data = array(	
				'productID' =>$input['productId'],
				'categoryId' =>$input['categoryId'],
				'status'=>(isset($input['status']) && !empty($input['status']))?$input['status']:0,
				'createdDate'=>cur_date_time(),
				'updatedDate'=>cur_date_time(),
			);
			$check_db = $this->db->get_where('featured_products', array('productId'=>$input['productId']))->row_array();
			// print_r($check_db);exit();
			if(empty($check_db)){
				$id = $this->Mydb->insert_table_data('featured_products',$data);
				$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
				$value = withSuccess("Featured Product Added Successfully",$data);
			}else{
				$value = withErrors("Featured Product Already Exists");
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
		// print_r($input);exit();
		
		if(isset($input['status'])){
			$rules['status'] = ['Status','required|numeric'];
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
		$is_update = $this->Mydb->update_table_data($this->table, array('id'=>$id), $data);
		$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if($is_update>0){
			$value  = withSuccess("Featured Product Updated Successfully",$result);
		}else{
			$value  = withErrors("Featured Product Updated Failed",$result);
		}       
		$this->response($value, REST_Controller::HTTP_OK);
    }

	function index_delete($id){
		$getData = $this->db->get_where('products', array('id'=>$id))->row_array();
		// print_r($getData);exit();
		if(!empty($getData)){
			$delete = $this->db->delete('featured_products', array('productId'=>$id));
			if($delete>0){
				$value = withSuccess('Featured Product Deleted Successfully', array('details'=>$getData));
			}else{
				$value = withErrors('Could Not Delete, Something went wrong');
			}
		}else{
			$value = withErrors('No Product Found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}
  
}