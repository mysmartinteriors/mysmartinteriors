<?php
require APPPATH . 'libraries/REST_Controller.php';

class Vendors_challan_history extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->table = 'vendors_challan_history';
		$this->model_name = 'vendor_challan_history_model';
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
		$data = array();
		if (!empty($id)) {
			$data = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
			if(!empty($data['details'])){
				$data['details']['products'] = $this->get_products($data['details']['id']); 
			}
		} else {
			$data = $this->Mydb->do_search($this->table, $this->model_name);
			// print_R($this->db->last_query());
			if(!empty($data['data_list'])){
				foreach($data['data_list'] as $index=>$dataList){
					$dataList->products = $this->get_products($dataList->id);
				}
			}
		}
		if (!empty($data)) {
			$value = withSuccess($message, $data);
		} else {
			$value = withSuccess($this->lang->line('no_result_found'));
		}

		$this->response($value, REST_Controller::HTTP_OK);
	}

	function get_products($challanId){
		// $products = $this->db->get_where('vendors_challan_products', array('challan_history_id'=>$challanId))->result_array();
		$q = "SELECT vendors_challan_products.*, product_status.l_value as product_status_name, product_status.color_name as product_status_color_name 
				FROM vendors_challan_products
				LEFT JOIN lookups as product_status ON vendors_challan_products.status = product_status.id
				WHERE vendors_challan_products.challan_history_id = '$challanId'";
		$products = $this->db->query($q)->result_array();
		return $products;
	}


	function check_exist_get()
	{
		$input = $this->input->get();
		if (!empty($input['code'])) {
			$code = $input['code'];
			$sql = "SELECT * from $this->table where $this->table.id >0 && $this->table.code = '$code'";
			$query = $this->db->query($sql)->row_array();
			return $query;
		}
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
			'vendor_id' => ['Vendor ID', 'required|numeric'],
			'created_by' => ['Created By', 'required|numeric']
		];
		Validator::make($rules);
		if (!Validator::fails()) {
			Validator::error();
		} else {

			$data = array(
				'vendor_id' => $input['vendor_id'],
				'created_by' => $input['created_by'],
				'created_at' => cur_date_time(),
				'unique_id' => $this->generate_challan_id(),
				'status' => 59
			);
			$id = $this->Mydb->insert_table_data('vendors_challan_history', $data);
			if ($id) {
				$data = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
				$value = withSuccess('Challan Created Successfully', $data);
			} else {
				$value = withSuccess('Failed to Create Challan', $data);
			}
			$this->response($value, REST_Controller::HTTP_OK);
		}


	}

	function generate_challan_id() {
		$orderData = $this->db->get('vendors_challan_history')->num_rows();
		$orderCode = "NALAA-CHALLAN-";
		$oCount = ($orderData>0)?$orderData:1;
		$orderCode .= $oCount;
		return $orderCode;
	}


	function challan_products_put($id=0){
		$challanProducts = $this->put('challan_products');
		if(!empty($challanProducts)){
			$decoded_json = json_decode($challanProducts, true); 
			if(!empty($decoded_json)){
				$getRows = $this->db->get_where('vendors_challan_products', array('challan_history_id'=>$id))->result_array();
				if(!empty($getRows)){
					$delete =$this->db->delete('vendors_challan_products', array('challan_history_id'=>$id));
				}
				$result = 1;
				foreach($decoded_json as $dec_json){
					$dec_json['challan_history_id'] = $id;
					$insert = $this->db->insert('vendors_challan_products', $dec_json);						
					if($insert){
						$result*=1;
					}else{
						$result*=0;
					}
				}
				if($result){
					$value = withSuccess('Updated Successfully');
				}else{
					$value = withErrors('Couldn\'t Be Updated');
				}
			}else{
				$value = withErrors('No Data Found To Be Updated');
			}
		}else{
			$value = withErrors('No Data Found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


	function vendor_challan_tbl_products_put($challanId){
		$input = $this->put('data');
		$data = json_decode($input, true);
		if(isset($data['in_stock']) && !empty($data['in_stock'])){
			foreach($data['in_stock'] as $stockProduct){
				if($stockProduct['in_stock']=='true'){
					$this->db->update('vendors_challan_products', array('status'=>61), array('id'=>$stockProduct['data-id']));
				}else{
					$this->db->update('vendors_challan_products', array('status'=>62), array('id'=>$stockProduct['data-id']));
				}
			}
		}
		if(isset($data['remarks']) && !empty($data['remarks'])){
			foreach($data['remarks'] as $remarks){
				$this->db->update('vendors_challan_products', array('vendor_remarks'=>$remarks['value']), array('id'=>$remarks['data-id']));
			}
		}
		$this->db->update('vendors_challan_history', array('status'=>60), array('id'=>$challanId));
		$value = withSuccess('Updated Successfully');
		$this->response($value, REST_Controller::HTTP_OK);
	}


	function admin_challan_tbl_products_put(){
		$input = $this->put('data');
		$data = json_decode($input, true);
		if(isset($data['in_stock']) && !empty($data['in_stock'])){
			foreach($data['in_stock'] as $stockProduct){
				if($stockProduct['in_stock']=='true'){
					$this->db->update('vendors_challan_products', array('status'=>61), array('id'=>$stockProduct['data-id']));
				}else{
					$this->db->update('vendors_challan_products', array('status'=>62), array('id'=>$stockProduct['data-id']));
				}
			}
		}
		if(isset($data['remarks']) && !empty($data['remarks'])){
			foreach($data['remarks'] as $remarks){
				$this->db->update('vendors_challan_products', array('vendor_remarks'=>$remarks['value']), array('id'=>$remarks['data-id']));
			}
		}
		$value = withSuccess('Updated Successfully');
		$this->response($value, REST_Controller::HTTP_OK);
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

		if (!empty($input['vendor_id'])) {
			$rules['vendor_id'] = ['Vendor ID', 'required'];
			$data['vendor_id'] = $input['vendor_id'];
		}
		if (!empty($input['status'])) {
			$rules['status'] = ['Status', 'required'];
			$data['status'] = $input['status'];
		}
		if (!empty($input['updated_by'])) {
			$rules['updated_by'] = ['Updated By', 'required'];
			$data['updated_by'] = $input['updated_by'];
		}
		$message = [
			'edit_unique' => 'The %s is already exists',
		];
		Validator::setMessage($message);

		if (array_filter($input)) {
			if (!empty($rules)) {
				Validator::make($rules);
			}
			if (!Validator::fails()) {
				Validator::error();
			}
		}
		$data['updated_at'] = cur_date_time();
		$is_update = $this->Mydb->update_table_data('vendors_challan_history', array('id' => $id), $data);
		$result = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
		if ($is_update > 0) {
			$value = withSuccess('Vendors Challan History updated successfully', $result);
		} else {
			$value = withErrors($this->lang->line('failed_to_update'), $result);
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}


	/**

	 * Delete data from this method.

	 *

	 * @return Response

	*/

	public function index_delete($id = 0)
	{
		if (!empty($id)) {
			$result = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
			$res = $this->Mydb->delete_table_data('vendors_challan_history', array('id' => $id));
			if ($res == 1) {
				$value = withSuccess('Vendor Challan History Deleted Successfully', $result);
			} else if ($res == -1451) {
				$value = withErrors($this->lang->line('failed_to_delete'));
			} else {
				$value = withErrors($this->lang->line('failed_to_delete'));
			}
		} else {
			$value = withErrors('Vendor Challan History cannot be deleted');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function remove_product_delete($id=0){
		if(empty($id)){
			$this->response(withErrors('Product ID is required'));
		}else{
			$deleteProduct = $this->db->delete('vendors_challan_products', array('id'=>$id));
			if($deleteProduct){
				$this->response(withSuccess('Product Deleted successfully'));
			}else{
				$this->response(withErrors('Product Could\'t be deleted'));
			}
		}
	}
}