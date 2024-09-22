<?php

require APPPATH . 'libraries/REST_Controller.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;    

class Shipping_address extends REST_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('excelvalidation');
		$this->lang->load('response', 'english');
		$this->table= 'shipping_address';
		$this->model_name='shipping_address_model';
		$this->load->model($this->model_name, "", true);
    }

       

    /**

     * Get All Data from this method.

     *

     * @return Response

    */

	public function index_get($id = 0)
	{
		$message = "success";
        if(!empty($id)){
            $data = $this->db->get_where("shipping_address", ['id' => $id])->row_array();
			if(empty($data)){
				$message = $this->lang->line('no_result_found');
			}
			$data = array('details'=>$data);
        }else{			
			$data = $this->Mydb->do_search( $this->table,$this->model_name);
			if(empty($data['data_list'])){
				$message = $this->lang->line('no_result_found');
			}			
        }
		$value  = withSuccess($message,$data);
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
			'customerId' => ['Customer ID','required|numeric'],
			'name' => ['Name','required|max_length[200]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'phone' => ['Phone','required|numeric|max_length[10]|min_length[10]'],
			'apartment' => ['Apartment','required'],
			'address' => ['Address','required|max_length[200]|min_length[3]'],
			'city' => ['City', 'required|max_length[100]|min_length[3]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'state' => ['State', 'required|max_length[100]|min_length[3]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'country' => ['Country', 'required|max_length[100]|min_length[3]|regex_match[/^[a-zA-Z\'\s\-]+$/]'],
			'postalCode' => ['Postal Code', 'required|numeric|min_length[6]|max_length[6]'],
			'pri_address' => ['Primary Address', 'required|numeric'],
			// 'status' => ['Status', 'required|numeric']
		];
		$message = [
			'is_unique' => 'The %s is already exists',
		];	
		Validator::setMessage($message);
		Validator::make($rules);

		//print_r(Validator::fails());
		if (!Validator::fails()){
            Validator::error();
        }else{
			//find The shipping address
			if($input['pri_address']==1){
				$getShippingAddresses = $this->db->update('shipping_address', array('pri_address'=>2), array('customerId'=>$input['customerId']));
			}
			//Getting the Geocoding
			$get_geocode = get_geocode($input['apartment'].', '.$input['address'].', '.$input['state'].', '.$input['city'].' - '.$input['postalCode']. ', '.$input['country']);
			$data = array(
				'customerId' => $input['customerId'],
				'name' => $input['name'],
				'phone' => $input['phone'],
				'apartment' => $input['apartment'],
				'address' => $input['address'],
				'latitude' => !empty($get_geocode['latitude'])?$get_geocode['latitude']:'',
				'longitude' => !empty($get_geocode['longitude'])?$get_geocode['longitude']:'',
				'state' => $input['state'],
				'city' => $input['city'],
				'country' => $input['country'],
				'postalCode' => $input['postalCode'],
				'pri_address' => $input['pri_address']
			);
			$id = $this->Mydb->insert_table_data('shipping_address',$data);
			$result = $this->db->get_where('shipping_address',array('id'=>$id))->row_array();
			$value  = withSuccess('Shipping Address Created Successfully',array('details'=>$result));
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
		
		if(!empty($input['customerId'])){
			$rules['customerId'] = ['Customer ID','required|numeric'];
			$data['customerId'] = $input['customerId'];
		}
		if(!empty($input['name'])){
			$rules['name'] = ['Name','required|min_length[3]|max_length[200]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['name'] = $input['name'];
		}
		if(!empty($input['phone'])){
			$rules['phone'] = ['Phone','required|min_length[10]|max_length[10]'];
			$data['phone'] = $input['phone'];
		}
		if(!empty($input['apartment'])){
			$rules['apartment'] = ['apartment','required'];
			$data['apartment'] = $input['apartment'];
		}
		if(!empty($input['address'])){
			$rules['address'] = ['Address','required|min_length[3]|max_length[200]'];
			$data['address'] = $input['address'];
		}
		if(!empty($input['state'])){
			$rules['state'] = ['State','required|max_length[100]|min_length[3]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['state'] = $input['state'];
		}
		if(!empty($input['city'])){
			$rules['city'] = ['City','required|max_length[100]|min_length[3]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['city'] = $input['city'];
		}
		if(!empty($input['state'])){
			$rules['state'] = ['State','required|max_length[100]|min_length[3]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['state'] = $input['state'];
		}
		if(!empty($input['country'])){
			$rules['country'] = ['Country','required|max_length[100]|min_length[3]|regex_match[/^[a-zA-Z\'\s\-]+$/]'];
			$data['country'] = $input['country'];
		}
		if(!empty($input['postalCode'])){
			$rules['postalCode'] = ['postalCode','required|numeric|min_length[6]|max_length[6]'];
			$data['postalCode'] = $input['postalCode'];
		}
		if(!empty($input['pri_address'])){
			$rules['pri_address'] = ['Primary Address','required|numeric'];
			$data['pri_address'] = $input['pri_address'];
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
			if($input['pri_address']==1){
				$getShippingAddresses = $this->db->update('shipping_address', array('pri_address'=>2), array('customerId'=>$input['customerId']));
			}
			$get_geocode = get_geocode($input['apartment'].', '.$input['address'].', '.$input['state'].', '.$input['city'].' - '.$input['postalCode']. ', '.$input['country']);
			$data['latitude'] = !empty($get_geocode['latitude'])?$get_geocode['latitude']:'';
			$data['longitude'] = !empty($get_geocode['longitude'])?$get_geocode['longitude']:'';

			$data['updatedDate'] = cur_date_time();
			$is_update = $this->Mydb->update_table_data('shipping_address', array('id'=>$id), $data);
			$q = $this->db->last_query();
			$query = $q;
			$result['details'] = $this->Mydb->get_table_data('shipping_address',array('id'=>$id));
			if($is_update>0){
				$value  = withSuccess('Shipping Address Updated Successfully',$result);
			}else{
				$value  = withErrors('Shipping Address update Failed',$result);
			}       
		}else{
				$value  = withErrors($this->lang->line('failed_to_update'));
		}
		$this->response($value, REST_Controller::HTTP_OK);      

    }

	/**

     * Import data from  file using this method.

     *

     * @return Response

    */


    /**

     * Delete data from this method.

     *

     * @return Response

    */


	public function index_delete($id=0)
    {
		if(!empty($id)){
			$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
			if(!empty($result['details'])){
				$res = $this->Mydb->update_table_data('shipping_address', array('id'=>$id), ['status'=>65]);
				if ($res == 1)
				{	
					$value  = withSuccess('Address Deleted Successfully',$result);
				}else if ($res == - 1451){
					$value = withErrors($this->lang->line('failed_to_delete'));
				}else{
					$value = withErrors($this->lang->line('failed_to_delete'));
				}
			}else{
				$value = withErrors('Shipping Address Not Found');
			}
		}else{
			$value = withErrors('Shipping Address Cannot Delete');
		}
		$this->response($value, REST_Controller::HTTP_OK);
    }

	function primary_address_put($customerId){
		$input = $this->put();
		$updateToZero = $this->Mydb->update_table_data('shipping_address', ['customerId'=>$customerId], ['pri_address'=>2]);
		$updateToPrimary = $this->Mydb->update_table_data('shipping_address', ['id'=>$input['addressId']], ['pri_address'=>1]);
		$value = withSuccess('Shipping Address updated');
		$this->response($value, REST_Controller::HTTP_OK);
	}
}