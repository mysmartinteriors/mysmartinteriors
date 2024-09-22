<?php
require APPPATH . 'libraries/REST_Controller.php';

class Cart extends REST_Controller {
   
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table= 'shopping_cart';
        $this->model_name='cart_model';   
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


	public function cart_total_get($id = 0)
    {
      $message = "success";
      $data=array();
      if(!empty($id)){
         $data = $this->cart_model->get_cart_total($id);
        //  print_r($data);exit();
		 if(!empty($data)){
			$value  = withSuccess($message,array("details" => $data));
		 }else{
			$value  = withSuccess($this->lang->line('no_result_found'));
		 }
      }else{
		$value  = withSuccess($this->lang->line('no_result_found'));
	  }
      $this->response($value, REST_Controller::HTTP_OK);
    }

	public function showcartquantity_get($id = 0)
    {
      $message = "success";
      $data=array();
      if(!empty($id)){
         $data = $this->cart_model->showcartquantity($id);
		 if(!empty($data)){
			$value  = withSuccess($message,array("details" => $data));
		 }else{
			$value  = withSuccess($this->lang->line('no_result_found'));
		 }
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
			'productId' => ['Product','required'],
			'customerId' => ['Customer','required'],
			'metricsId' => ['Quantity Details','required'],
			'quantity' => ['Quantity','required']
			];

		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {
            $checkExistingData = $this->db->get_where('shopping_cart', array('customerId'=>$input['customerId'], 'productId'=>$input['productId'], 'metricsId'=>$input['metricsId']))->row_array();
            // log_message('debug', print_r($this->db->last_query(), true));
            if(empty($checkExistingData)){
                $data = array(	
                    'productId' =>$input['productId'],
                    'customerId'=>$input['customerId'],
                    'metricsId'=>$input['metricsId'],
                    'quantity'=>$input['quantity'],
                    'updatedDate'=>cur_date_time(),
                    'status'=>1
                );
                $id = $this->Mydb->insert_table_data('shopping_cart',$data);
            }else{
                $count = $checkExistingData['quantity'];
                $id = $this->db->update('shopping_cart', array('quantity'=>($count+$input['quantity'])), array('id'=>$checkExistingData['id']));
            }
			if($id){
				$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
				$value  = withSuccess('Cart Updated Successfully',$data);
			}else{
				$value  = withSuccess('Failed to Update Cart',$data);
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

		if(!empty($input['quantity'])){
			$rules['quantity'] = ['Quantity','required'];
			$data['quantity'] = $input['quantity'];
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
		$is_update = $this->Mydb->update_table_data('shopping_cart', array('id'=>$id), $data);
		$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if($is_update>0){
			$value  = withSuccess('Cart id updated successfully',$result);
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
			$res = $this->Mydb->delete_table_data('shopping_cart', array('id'=>$id));
			if ($res == 1)
			{	
				$value  = withSuccess('Item Removed Successfully',$result);
			}else if ($res == - 1451){
				$value = withErrors($this->lang->line('failed_to_delete'));
			}else{
				$value = withErrors($this->lang->line('failed_to_delete'));
			}
		}else{
			$value = withErrors('Item cannot be removed');
		}
		$this->response($value, REST_Controller::HTTP_OK);
    }

    public function clear_cart_delete($userId){
        $result = $this->Mydb->get_table_data('shopping_cart', array('customerId'=>$userId));
        if(!empty($result)){
            foreach($result as $res){
                $this->db->delete('shopping_cart', array('id'=>$res['id']));
            }
            $value = withSuccess('Cart Items Deleted Successfully');
        }else{
            $value = withErrors('No Cart Items Found');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }
    
    public function update_cart_post(){
        $input = $this->input->post();
        if(empty($input)){
            $value = withErrors('No Data found to be updated');
            $this->response($value, REST_Controller::HTTP_OK);
        }
        // $is_updated = 1;
        // print_R($input);exit();
        // foreach(json_decode($input, true) as $cart){
        //     $id = $cart['cartId'];
        //     unset($cart['cartId']);
        //     $update = $this->db->update('shopping_cart', $cart, array('id'=>$id));
        //     if(!$update){
        //         $is_updated*=0;
        //     }
        // }
        // $data['cartId']=$this->input->post('cartId');
    	// $data['productId']=$this->input->post('productId');
    	// $data['quantity']=$this->input->post('quantity');
        // print_r($input);exit();
        $is_updated = $this->db->update('shopping_cart', array('quantity'=>$input['quantity']), array('id'=>$input['cartId']));
        // print_R($this->db->last_query());exit();
        if($is_updated){
            $value = withSuccess('Updated Successfully');
        }else{
            $value = withErrors('Unable to Update, Please try again');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }
	


}