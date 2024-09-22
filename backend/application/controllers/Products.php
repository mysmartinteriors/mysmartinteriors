<?php

require APPPATH . 'libraries/REST_Controller.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Products extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table= 'products';
        $this->model_name='products_model';   
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
	  $productData = array();
      if(!empty($id)){
         $data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		 if(!empty($data['details'])){
			$data['details']['product_features'] = $this->get_product_feature($data['details']['id']);
			$data['details']['product_metrics'] = $this->get_product_metrics($data['details']['id']);
			$productData = $data['details'];
		 }
      }else{       
         $data = $this->Mydb->do_search($this->table,$this->model_name);
		//  print_R($data);exit();
		 if(!empty($data['data_list'])){
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
	
	function features_get($id){
		$message = "success"; 
		$data = array();
		$data = $this->db->get_where('product_features', array('productId'=>$id))->row_array();
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$value  = withSuccess($message, array('details'=>$data));
		$this->response($value, REST_Controller::HTTP_OK);
	}   
    /**

     * Get the list of Product Feature from this method.

     *

     * @return Response

    */		
	
	function features_post(){
		$data = $this->input->post();
        $productId=$data['productId'];
        $id=$data['id'];
        $this->db->where("id",$productId);
        $get_prd=$this->db->get("products",$data);
        if($get_prd->num_rows()>0){
            if(!empty($id)){
				unset($data['id']);
                $this->db->where("id",$id);
                $this->db->update("product_features",$data);
                $res=$this->db->affected_rows();
                $message="Product details updated successfully";
				$details = $this->db->get_where('product_features', array('id'=>$id))->row_array();
            }else{
				$data['createdDate']=$data['updatedDate'];
                $this->db->insert("product_features",$data);
                $id=$this->db->insert_id();
                $res=$id;
                $message="Product details created successfully";
				$details = $this->db->get_where('product_features', array('id'=>$id))->row_array();
            }
			$value  = withSuccess($message, array('details'=>$details));
        }else{
			$value = withErrors('Product Not Found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}   
   
    /**

     * Get the list of Product Feature from this method.

     *

     * @return Response
 
    */		
	
	function images_get($productId){
		$message = "success";
		$data = array();
		$data = $this->db->get_where('product_images', array('productId'=>$productId))->result_array();
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$data = array('details'=>$data);
		$value  = withSuccess($message,$data);
		$this->response($value, REST_Controller::HTTP_OK);
	}   

	/**

     * Update data from this method.

     *

     * @return Response

    */

	public function images_put($id){
		$rules = array();
		$data = array();
        $input = $this->put();
		if(empty($input['filePath'])){
			$this->response(withErrors('Invalid Input'), REST_Controller::HTTP_OK);
		}else{
			$filePath = json_decode($input['filePath']);
			//check duplicate Data
			$db_data = $this->db->get_where('product_images', array('productId'=>$id))->result_array();
			if(!empty($db_data)){
				$this->db->delete('product_images', array('productId'=>$id));
			}
			$insert = 1;
			foreach($filePath as $file){
				if(!empty($file)){
					$res = $this->db->insert('product_images', array('filePath'=>$file, 'productId'=>$id));
					if(!$res){
						$insert*=0;
					}	
				}	
			}
			if(!$insert){
				$this->db->delete('product_images', array('productId'=>$id));
				$value  = withErrors("Couldn't Update All the Files, Reverted Back");
				$this->response($value, REST_Controller::HTTP_OK);
			}else{
				$value  = withSuccess("Successfully Updated The Files");
				$this->response($value, REST_Controller::HTTP_OK);
			}
		}
	}
	
	
	function images_delete($id){
		$data = $this->db->get_where('product_images', array('id'=>$id))->row_array();
		if(!empty($data)){
			$this->db->delete('product_images', array('id'=>$id));
		}
		$this->response(withSuccess('Deleted Successfully'), REST_Controller::HTTP_OK);

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
			'code' => ['Code','required|min_length[3]|max_length[200]|is_unique[products.code]'],
			'categoryId' => ['Category Id','required|numeric'],
			'name' => ['name','required|min_length[2]|max_length[200]'],
			'description' => ['Description','min_length[4]'],
			'product_url' => ['Product URL','required|min_length[5]'],
			'metaTags' => ['Meta Tags','min_length[5]|max_length[300]'],
			'metaDescription' => ['Meta Description','min_length[5]'],
			'product_image' => ['Product Image','required'],
			'in_stock' => ['IN Stock','required|numeric'],
			'status' => ['Status','required|numeric'],
			'created_by' => ['Created By','required|numeric'],
		];
		$message = [
			'is_unique' => 'The %s is already exists',
		];	
		Validator::setMessage($message);
		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			$categoryData = $this->db->get_where('categories', array('id'=>$input['categoryId']))->row_array();
			$parentId = 0;
			if(!empty($categoryData)){
				$parentId = $categoryData['parent'];
			}
			$data = array(	
				'code' => $input['code'],
				'categoryId' => $input['categoryId'],
				'parentId' => $input['categoryId'],
				'name' => $input['name'],
				'description' => $input['description'],
				'product_url' => $input['product_url'],
				'metaTags' => $input['metaTags'],
				'metaDescription' => $input['metaDescription'],
				'product_image' => $input['product_image'],
				'model_no' => $input['model_no'],
				'color_code' => $input['color_code'],
				'color_name' => $input['color_name'],
				'is_primary' => $input['is_primary'],
				'tag_id' => $input['tag_id'],
				'tag_name' => $input['tag_name'],
				'in_stock' => $input['in_stock'],
				'status' => $input['status'],
				'created_by' => $input['created_by'],
				'badge'=>$input['badge']
			);
			if(isset($input['comission_applicable']) && !empty($input['comission_applicable'])){
				$data['comission_applicable'] = $input['comission_applicable'];
			}
			
			if(!empty($input['CGST'])){
				$data['CGST']=$input['CGST'];
			}
			if(!empty($input['SGST'])){
				$data['SGST']=$input['SGST'];
			}
			$data['createdDate'] = cur_date_time();
			$id = $this->Mydb->insert_table_data('products',$data);
			$data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
			$value  = withSuccess("Product Created Successfully",$data);
			$this->response($value, REST_Controller::HTTP_OK);
		}
    } 


	function metrics_put($productId){
		$metricsData = $this->put('metrics');
		if(!empty($metricsData)){
			$decoded_json = json_decode($metricsData, true); 
			if(!empty($decoded_json)){
				$getRows = $this->db->get_where('product_metrics', array('productId'=>$productId))->result_array();
				if(!empty($getRows)){
					$delete =$this->db->delete('product_metrics', array('productId'=>$productId));
				}
				$result = 1;
				foreach($decoded_json as $dec_json){
					$dec_json['productId'] = $productId;
					$insert = $this->db->insert('product_metrics', $dec_json);						
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


	function metrics_get($productId){
		if(!empty($productId)){
			$getRows = $this->db->get_where('product_metrics', array('productId'=>$productId))->result_array();
			if(!empty($getRows)){
				$value = withSuccess('Success', ['details'=>$getRows]);
			}else{
				$value = withErrors('No Data Found');
			}
		}else{
			$value = withErrors('No Data Found');
		}
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
		
		if(!empty($input['parentId'])){
			$rules['parentId'] = ['Parent Category ID','required|numeric'];
			$data['parentId'] = $input['parentId'];
		} 
		if(!empty($input['categoryId'])){
			$rules['categoryId'] = ['Category ID','required|numeric'];
			$data['categoryId'] = $input['categoryId'];
		}
		if(!empty($input['code'])){
			$rules['code'] = ['Product Code','required|min_length[3]|max_length[200]'];
			$data['code'] = $input['code'];
		}
		if(!empty($input['name'])){
			$rules['name'] = ['Product Name','required|min_length[2]|max_length[200]'];
			$data['name'] = $input['name'];
		}
		if(!empty($input['product_image'])){
			$rules['product_image'] = ['Product Image','required'];
			$data['product_image'] = $input['product_image'];
		}
		if(!empty($input['qty_details'])){
			$rules['qty_details'] = ['Quantity Details','required'];
			$data['qty_details'] = $input['qty_details'];
		}
		if(!empty($input['description'])){
			$rules['description'] = ['Product Description','required|min_length[5]'];
			$data['description'] = $input['description'];
		}
		if(!empty($input['mrp'])){
			$rules['mrp'] = ['Product Price','required|numeric'];
			$data['mrp'] = $input['mrp'];
		}
		if(!empty($input['price'])){
			$rules['price'] = ['Product Price','required|numeric'];
			$data['price'] = $input['price'];
		}
		if(!empty($input['qty'])){
			$rules['qty'] = ['Product Quantity','required|numeric'];
			$data['minQuantity'] = $input['qty'];
		}
		if(!empty($input['unit'])){
			$rules['unit'] = ['Product Unit','required'];
			$data['unit'] = $input['unit'];
		}
		if(!empty($input['comission_applicable'])){
			$rules['comission_applicable'] = ['Comission Applicable','required'];
			$data['comission_applicable'] = $input['comission_applicable'];
		}
		if(!empty($input['CGST'])){
			$rules['CGST'] = ['CGST','required'];
			$data['CGST'] = $input['CGST'];
		}
		if(!empty($input['SGST'])){
			$rules['SGST'] = ['SGST','required'];
			$data['SGST'] = $input['SGST'];
		}
		if(!empty($input['product_url'])){
			$rules['product_url'] = ['Product URL','required'];
			$data['product_url'] = $input['product_url'];
		}
		if(!empty($input['model_no'])){
			$rules['model_no'] = ['Model Number','required'];
			$data['model_no'] = $input['model_no'];
		}
		if(!empty($input['color_code'])){
			$rules['color_code'] = ['Color Code','required'];
			$data['color_code'] = $input['color_code'];
		}
		if(!empty($input['is_primary'])){
			$rules['is_primary'] = ['Primary','required|numeric'];
			$data['is_primary'] = $input['is_primary'];
		}
		if(!empty($input['tag_id'])){
			$rules['tag_id'] = ['TAG ID','min_length[1]'];
			$data['tag_id'] = $input['tag_id'];
		}
		if(!empty($input['tag_name'])){
			$rules['tag_name'] = ['TAG Name','min_length[2]'];
			$data['tag_name'] = $input['tag_name'];
		}
		if(!empty($input['metaTags'])){
			$rules['metaTags'] = ['Meta Tags','min_length[10]'];
			$data['metaTags'] = $input['metaTags'];
		}
		if(!empty($input['metaDescription'])){
			$rules['metaDescription'] = ['Meta Description','min_length[10]'];
			$data['metaDescription'] = $input['metaDescription'];
		}
		if(!empty($input['in_stock'])){
			$rules['in_stock'] = ['In Stock','required|numeric'];
			$data['in_stock'] = $input['in_stock'];
		}
		if(!empty($input['status'])){
			$rules['status'] = ['Status','required|numeric'];
			$data['status'] = $input['status'];
		}		
		if(!empty($input['created_by'])){
			$data['created_by'] = $input['created_by'];
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
		if(isset($input['badge'])){
			$data['badge'] = $input['badge'];
		}


		$is_update = $this->Mydb->update_table_data('products', array('id'=>$id), $data);
		$result = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
		if($is_update>0){
			$value  = withSuccess("Product Updated Successfully",$result);
		}else{
			$value  = withErrors($this->lang->line('failed_to_update'),$result);
		}       
		$this->response($value, REST_Controller::HTTP_OK);
    }


	/**

     * Export data from  file using this method.

     *

     * @return Response

    */

	public function export_get(){
		$rand        = rand(1234, 9898);
        $presentDate = date('d_m_Y_h_i_s_A');
        $file_name    = "report_users_". $presentDate . ".xlsx";
		$data = $this->Mydb->do_search($this->table,$this->model_name,true);
		if(!empty($data['data_list'])){
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Login ID');
			$sheet->setCellValue('B1', 'First Name');
			$sheet->setCellValue('C1', 'Last Name');
			$sheet->setCellValue('D1', 'Email');
			$sheet->setCellValue('E1', 'Mobile');
			$sheet->setCellValue('F1', 'Temporary Address');
			$sheet->setCellValue('G1', 'Permanent Address');
			$sheet->setCellValue('H1', 'Department Name');
			$sheet->setCellValue('I1', 'Role Name');
			$sheet->setCellValue('J1', 'Organization Branch');
			$sheet->setCellValue('K1', 'Class Level');
			$sheet->setCellValue('L1', 'Data Restriction');
			$sheet->setCellValue('M1', 'Status');
			$sheet->setCellValue('N1', 'Created');	
			$sheet->setCellValue('O1', 'Created By');	
			$sheet->setCellValue('P1', 'Updated');	
			$sheet->setCellValue('Q1', 'Updated By');
			$count = 2;
			foreach($data['data_list'] as $row)
			{			
				$sheet->setCellValue('A' . $count, $row->login_id);
				$sheet->setCellValue('B' . $count, $row->first_name);
				$sheet->setCellValue('C' . $count, $row->last_name);
				$sheet->setCellValue('D' . $count, $row->email);
				$sheet->setCellValue('E' . $count, $row->mobile);
				$sheet->setCellValue('F' . $count, $row->temp_address);
				$sheet->setCellValue('G' . $count, $row->perma_address);
				$sheet->setCellValue('H' . $count, $row->departments_name);
				$sheet->setCellValue('I' . $count, $row->roles_name);
				$sheet->setCellValue('J' . $count, $row->organization_branches_name);
				$sheet->setCellValue('K' . $count, $row->class_level);
				$sheet->setCellValue('L' . $count, $row->restriction_name);
				$sheet->setCellValue('M' . $count, $row->status_name);
				$sheet->setCellValue('N' . $count, custom_date('d-m-Y h:i:s A',$row->created_at));
				$sheet->setCellValue('O' . $count, $row->created_username);
				if(!empty($row->updated_at)){
					$updated_at=custom_date('d-m-Y h:i:s A',$row->updated_at);
				}else{
					$updated_at='';
				}
				$sheet->setCellValue('P' . $count, $updated_at);
				$sheet->setCellValue('Q' . $count, $row->updated_username);
				$count++;
			}
			$writer = new Xlsx($spreadsheet);
			$filePath = 'reports/' . $file_name;			
			$writer->save($filePath);			
			$res =  array(
				'filename' => $file_name,
				'url' => base_url().$filePath
			);
			$result=array('details'=>$res);
			$value = withSuccess($this->lang->line('report_generated_successfully'),$result);
		}else{
			$value = withErrors($this->lang->line('no_result_found'));
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
        $res=0;
        $b=1;
        $msg='Unable to delete at this time!';
        $sql="SELECT 1 
            FROM (
                SELECT productId FROM shopping_cart
                UNION ALL
                SELECT productId FROM offers
            ) a
            WHERE productId = $id";
        
        $query1=$this->db->query($sql);

        if($query1->num_rows()>0){
            $b=0;
            $msg='Product is linked with other datas like cart, offers etc...Cannot delete Now';
			$this->response(withErrors($msg), REST_Controller::HTTP_OK);
        }else if($b>0){
            $this->db->where('id',$id);
            $get_res=$this->db->get('products');
    		$this->db->where('id',$id);
    		$this->db->delete('products');
    		$res= $this->db->affected_rows();
			$this->response(withSuccess('Product deleted Successfully'), REST_Controller::HTTP_OK);
        }else{
			$this->response(withSuccess('Undefined Response'), REST_Controller::HTTP_OK);
		}
    }


	public function filter_prices_get(){
		$sql = "SELECT MIN(p.price) as min_price, MAX(p.price) as max_price 
				FROM products p";
		$query=$this->db->query($sql);
		// return $query;
		$value = withSuccess('Success', array('details'=>$query->row_array()));
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function avail_options_get(){
		$tag_id = $this->input->get('tag_id');
		$id = $this->input->get('id');
		if(!empty($tag_id) && !empty($id)){
			$sql = "SELECT * 
					FROM products 
					WHERE tag_id='$tag_id' AND productId!='$id'";
			$query = $this->db->query($sql)->result_array();
			$value = withSuccess("Success", array($query));
		}else{
			$value = withErrors('No Record Found');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	} 	

	function updateMetrics_put($metricsId){
		$input = $this->put();
		$metricsData = $this->db->get_where('product_metrics', array('id'=>$metricsId))->row_array();
		if(!empty($metricsData)){
			$update = $this->db->update('product_metrics', array('unit'=>$input['unit'], 'price'=>$input['price'], 'quantity'=>$input['quantity'], 'mrp'=>$input['mrp']), array('id'=>$metricsId));
			if($update){
				$value = withSuccess('Updated Successfully');
			}else{
				$value = withErrors('Couldn\'t Update');
			}
		}else{
			$value = withErrors('Invalid Metrics Update Request');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function update_metrics_status_put($metricsId){
		$metricsData = $this->db->get_where('product_metrics', array('id'=>$metricsId))->row_array();
		if(!empty($metricsData)){
			$status ='Active';
			if($metricsData['status']=='Active'){
				$status = 'Inactive';
			}
			$update = $this->db->update('product_metrics', array('status'=>$status), array('id'=>$metricsId));
			if($update){
				$value = withSuccess('Updated Successfully');
			}else{
				$value = withErrors('Couldn\'t Update');
			}
		}else{
			$value = withErrors('Invalid Metrics Update Request');
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

}