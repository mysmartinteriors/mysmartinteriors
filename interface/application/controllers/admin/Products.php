<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
        $this->module_name = 'admin_products';
		$this->admin->nocache(); 
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Products";
        $data['categoryQ'] = $this->curl->execute('categories', 'GET');
		$this->load->view("admin/products/products_view",$data);
    }


    function get_products(){
        $filter_data=$this->input->post('filter_data');
    	$module=$this->input->post('module');
    	$page=$this->input->post('page');
        if(empty($page) && $page<1){
            $page = 1;
        }
    	if(!User::check_permission($module.'/getFilterList', 'check')){
			echo json_encode(array("status"=>"fail", "message"=>"You don't have permission. <br/>Please contact Admininstrator to request access."));
			return;
		}	
    	$filterData[]=array();
    	// $filterData['orderby']='DESC';
    	if(!empty($filter_data)){
	    	foreach ($filter_data as $k => $v) {
				if($v['type']=='sortby'){
					$filterData[$v['type']]='products.'.$v['value'];
				}else{					
					$filterData[$v['type']]=$v['value'];
				}
			}
		}
        if(!empty($page)){
            $filterData['page']=$page;
        }
    	$apidata=$this->curl->execute($this->module_name,"GET",$filterData,'filter');
        // print_R($apidata);exit();
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
    	$str =  $this->load->view("admin/products/products_tbl_view", $data, true);
    	$value=array('str'=>$str,'status'=>$apidata['status']);
    	echo json_encode($value);
    }

    function add(){
    	$data=array();
        $data = $this->admin->commonadminFiles();
        $data['title']="Add Product";
        $data['categoryQ'] = $this->curl->execute('categories', 'GET');
        $data['prd_units'] = $this->curl->execute("lookups", "GET", array("l_type"=>"product_unit"));
        $this->load->view("admin/products/add_product_view",$data);
    }

    function save_product(){
        $id = $this->input->post("productId");
        $data['code']=$this->input->post("productCode");
        $data['categoryId']=$this->input->post("categoryId");
        $data['parentId']=$this->input->post("parentId");
        $data['name']=$this->input->post("productName");
        $data['mrp']=  $this->input->post("mrp");
        $data['price']=  $this->input->post("price");
        $data['qty']=  $this->input->post("qty");
        $data['unit']=  $this->input->post("unit");
        $data['CGST']=  $this->input->post("CGST");
        $data['SGST']=  $this->input->post("SGST");
        $data['description']=  $this->input->post("description");
        $data['product_url']=  create_slug($this->input->post("productURL"));
        $data['metaTags']=  $this->input->post("metaTags");
        $data['metaDescription']=  $this->input->post("metaDescription");
        $data['product_image']=$this->input->post("productImage"); 
        $data['model_no']=$this->input->post("model_no");
        $data['color_code']=$this->input->post("color_code");
        $data['color_name']=$this->input->post("color_name");
        $data['is_primary']=$this->input->post("is_primary");
        $data['tag_id']=$this->input->post("tag_id");
        $data['tag_name']=$this->input->post("tag_name");
        $data['in_stock']=  $this->input->post("in_stock");
        $data['status']=  $this->input->post("status");
        $data['comission_applicable']=  $this->input->post("comission_applicable");
        $data['created_by'] = User::get_userId();
        $data['badge'] = $this->input->post('badge');
        // print_R($data);exit();
        // $this->curl->execute('product')

        $json_array = array();
        if (isset($_POST['mrp']) || isset($_POST['price']) || isset($_POST['quantity']) || isset($_POST['SGST']) || isset($_POST['unit']) || isset($_POST['CGST'])) {
            if (count($_POST['mrp']) > 0 || count($_POST['price']) > 0 || count($_POST['quantity']) > 0 || count($_POST['unit']) > 0 || count($_POST['CGST']) > 0 || count($_POST['SGST']) > 0 ) {
                for ($i = 0; $i < count($_POST['mrp']); $i++) {
                        $new_array = array(
                            'mrp' => $_POST['mrp'][$i],
                            'price' => $_POST['price'][$i],
                            'quantity' => $_POST['quantity'][$i],
                            'unit' => $_POST['unit'][$i],
                            'CGST' => $_POST['CGST'][$i],
                            'SGST' => $_POST['SGST'][$i]
                        );
                        array_push($json_array, $new_array);
                }
            }
        }
        unset($data['mrp']);
        unset($data['price']);
        unset($data['quantity']);
        unset($data['unit']);
        unset($data['CGST']);
        unset($data['SGST']);
        if(!empty($id)){ 
            $apidata = $this->curl->execute("products/$id", "PUT", $data);
        }else{
            $apidata = $this->curl->execute("products", "POST", $data);
        } 
        if($apidata['status']=='success'){
            if(!empty($json_array)){
                $productId = $apidata['data_list']['id'];
                $metricsData = json_encode($json_array);
                $metricsUpdateApi = $this->curl->execute('products/metrics/'.$productId, 'PUT', ['metrics'=>$metricsData]);
            }
        }
        echo json_encode(array('status'=>$apidata['status'], 'message'=>$apidata['message']));
        return;
    }

    function check_exists(){
        $name = $this->input->post('productCode');
        $edit = $this->input->post('edit');
        $q=0;
        if(strtolower($edit)!=strtolower($name)){
            $apidata=$this->curl->execute('products',"GET", array('products-code'=> $name));
            if($apidata['status']=='success' && !empty($apidata['data_list'])){
                $result = "false";
            }
        }
        $result="true";
        if($q>0){
            $result="false";
        }       
        echo $result;
    }


    function edit(){
		$id=$_GET['id'];
		$page=$_GET['page'];
		if($id!=""){
	    	$data=array();
	        $data = $this->admin->commonadminFiles();
	        $data['title']="Edit Product";
            $where = array('status'=>1);
            $data['categoryQ'] = $this->curl->execute('categories', 'GET');
            $where = array('productId' => $id );
	        $data['productQ']=$this->curl->execute("products/$id", "GET");
	        $data['metrics']=$this->curl->execute("products/metrics/$id", "GET");
            $data['prd_units'] = $this->curl->execute("lookups", "GET", array("l_type"=>"product_unit"));
	        $this->load->view("admin/products/edit_product_view",$data);
	    }else{
	    	redirect(base_url().'admin/error_404');
	    }
    }

    function deleteProduct(){
        $id=$this->input->post("id");
        if(empty($id)){
            echo json_encode(array('status'=>'fail', 'message'=>'Invalid Request'));
            return;
        }
        $result=1;
        $apidata = $this->curl->execute("products/$id", "DELETE");
        // $value=array(
        //     'result'=>$result['result'],
        //     'msg'=>$result['msg'],
        //     'canDelete'=>$result['canDelete']
        // );
        echo json_encode(array('status'=>$apidata['status'], 'message'=>$apidata['message']));
        return;
    }

    function featured(){
        $data = $this->admin->commonadminFiles();
        $data['title']="Featured Products";
        $where = array('status'=>1);
        $data['categoryQ'] = $this->curl->execute('categories', "GET");
        // $data['categoryQ']=$this->adminmodel->get_table_data('categories',$where,'*',true);
        $this->load->view("admin/products/featured/featured_products_view",$data);
    }

    function get_FeatureProducts(){

        $filter_data=$this->input->post('filter_data');
    	$module=$this->input->post('module');
    	$page=$this->input->post('page');
        if(empty($page) && $page<1){
            $page = 1;
        }
    	if(!User::check_permission($module.'/getFilterList', 'check')){
			echo json_encode(array("status"=>"fail", "message"=>"You don't have permission. <br/>Please contact Admininstrator to request access."));
			return;
		}	
    	$filterData[]=array();
    	$filterData['orderby']='DESC';
    	if(!empty($filter_data)){
	    	foreach ($filter_data as $k => $v) {
				if($v['type']=='sortby'){
					$filterData[$v['type']]=$module.'.'.$v['value'];
				}else{					
					$filterData[$v['type']]=$v['value'];
				}
			}
		}
        if(!empty($page)){
            $filterData['page']=$page;
        }
    	$apidata=$this->curl->execute('products_featured',"GET",$filterData,'filter');
        // print_r($apidata);exit();
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
    	$str =  $this->load->view("admin/products/featured/featured_products_tbl_view", $data, true);
    	$value=array('str'=>$str,'status'=>$apidata['status']);
    	echo json_encode($value);
    }

    function save_Featureproduct(){
        $result='fail';
        $productCode=$this->input->post("productCode"); 
        $data = array();
        $apidata = $this->curl->execute('products', 'GET', array('products-code'=>$productCode));
        // print_r($apidata);exit();
        if($apidata['status']=='success' && !empty($apidata['data_list'])){
            $data_list = $apidata['data_list'][0];
            $data['productId']=$data_list['id'];
            $data['categoryId']=$data_list['categoryId']; 
            $data['status']=1;
            $updateApi = $this->curl->execute("products_featured", "POST", $data);
            $msg = $updateApi['message'];
            $result = $updateApi['status'];
        }else{
            $msg='Product not found!<br>Please enter proper product code...';
        }
        $value=array(
            'result'=>$result,
            'msg'=>$msg
        );
        echo json_encode($value);
    }

    function removeFeaturePrd(){
        $id=$this->input->post("id");
        if(empty($id)){
            echo json_encode(array('result'=>'fail', 'msg'=>'Invalid Request'));
            return;
        }
        $apidata = $this->curl->execute("products_featured/$id", "DELETE");
        // print_R($apidata);exit();
        echo json_encode(array('status'=>$apidata['status'], 'msg'=>$apidata['message']));
        return;
    }

    function statusFeaturePrd(){
        $id=$this->input->post("id");
        $status=$this->input->post("status");
        $result='fail';
        $msg='Cannot update at this time...';
        if($id!=""){    
            $apidata = $this->curl->execute("products_featured/$id", "PUT", array('status'=>$status));
            $msg = $apidata['message'];
            $result = $apidata['status'];
        }else{
            $msg = 'ID Is Required';
        }
        $value=array(
            'result'=>$result,
            'msg'=>$msg
        );
        echo json_encode($value);
    }
    
    function details() {
        $id=$this->uri->segment(4);
        $data = $this->admin->commonadminFiles();
        $data['title']="Products";
        $where = array('productId'=>$id);
        $apidata = $this->curl->execute("products/$id", 'GET');
        // print_r($apidata);exit();
        if($apidata['status']=='success' && !empty($apidata['data_list'])){
            if(!empty($apidata['data_list'])){
                $data['productQ']=$apidata['data_list'];
                $data['prd_listQ']=$this->curl->execute('products', "GET", array('products-status'=>1));
                $data['featureQ']=$this->curl->execute("products/features/$id", "GET");
                // print_R($data['featureQ']);
                // exit();
                $data['imageQ']=$this->curl->execute("products/images/$id", "GET");
                $data['ratingQ']=$this->curl->execute("ratings", "GET", array('productId'=>$id));
                $this->load->view("admin/products/details/features_view",$data);
            }else{
                redirect(base_url().'admin/error_404');
            }
        }
    }

    function save_details_data(){
        $data['productId']=$this->input->post("pId");
        $data['id']=$this->input->post("fId");
        $data['content']=$this->input->post("details");
        $data['content_html']=$this->input->post("details"); 
        $data['updatedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
        $apidata = $this->curl->execute('products/features', 'POST', $data);
        // print_R($apidata);exit();
        $value=array(
            'result'=>$apidata['status'],
            'dataId'=>(isset($apidata['data_list']) && !empty($apidata['data_list']))?$apidata['data_list']['id']:'',
            'msg'=>$apidata['message']
        );
        echo json_encode($value);
    }

    function save_images_data(){
        // $data['id']=$this->input->post("pId");
        $id = $this->input->post('pId');
        // $data['filePath']=$this->input->post("productImage");
        if(!empty($this->input->post('productImage'))){
            $data['filePath'] = json_encode($this->input->post('productImage'));
        }
        $apidata = $this->curl->execute("products/images/$id", 'PUT', $data);
        $value=array(
            'status'=>$apidata['status'],
            'message'=>$apidata['message']
        );
        echo json_encode($value);
        return;
    }

    function rm_prd_image(){
        $id=$this->input->post("id");
        $productId=$this->input->post("pId");
        $apidata = $this->curl->execute("products/images/$id", 'DELETE', array('productId'=>$productId));
        echo json_encode(array('status'=>$apidata['status'], 'message'=>$apidata['message']));
        return;
    }

    function get_prd_json(){
        $id=$this->input->post('pId');
        $where = array('productId' => $id );
        $res=$this->adminmodel->get_table_data('products',$where,'*',true);
        $productId='';
        $productName='';
        $productURL='';
        $color_name='';
        $color_code='';
        $result='fail';
        if($res->num_rows()>0){
            $result='success';
            foreach ($res->result() as $product) {
                $arr = array(
                    $productId = $product->productId,
                    $productName = $product->productName,
                    $productURL = $product->productURL,
                    $color_name = $product->color_name,
                    $color_code = $product->color_code
                );
            }
        }
        $arr = array(
            'productId' => $productId,
            'productName' => $productName,
            'productURL' => $productURL,
            'color_name' => $color_name,
            'color_code' => $color_code
        );

        $value=array(
            'result'=>$result,
            'arr'=>$arr
        );
        echo json_encode($value);
    }

    function add_custom_field()
	{
		$data['count'] = $this->input->post('count');
        $data['prd_units'] = $this->curl->execute("lookups", "GET", array("l_type"=>"product_unit"));
		$str = $this->load->view("admin/products/add_custom_field", $data, true);
		$value = array('result' => 'success', 'msg' => $str);
		echo json_encode($value);
	}


    function changeRepurchase(){
        $id = $this->input->post('id');
        $value = $this->input->post('applicable');
        if($value == 'yes'){
            $value = 'no';
        }else{
            $value = 'yes';
        }
        if(!empty($id)){
            $update = $this->curl->execute('products/'.$id, 'PUT', array('comission_applicable'=>$value));
            if($update['status']== 'success'){
                echo json_encode(array('status'=>'success', 'message'=>'Updated Successfully', 'applicable'=>$value));
            }else{
                echo json_encode(array('status'=>'fail', 'message'=>'Couldn\'t Update'));
            }
            return;
        }else{
            echo json_encode(array('status'=> 'fail','message'=> 'Couldn\t Update'));
            return;
        }
    }


    function updateStatus(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $statusName = '';
        if($status == '32'){
            $status = '33';
            $statusName = 'Inactive';
        }else{
            $status = '32'; 
            $statusName = 'Active';
        }
        if(!empty($id)){
            $update = $this->curl->execute('products/'.$id, 'PUT', array('status'=>$status));
            if($update['status']== 'success' && !empty($update['data_list'])){
                echo json_encode(array('status'=>'success', 'message'=>'Updated Successfully', 'data_list'=>$update['data_list']));
            }else{
                echo json_encode(array('status'=>'fail', 'message'=>'Couldn\'t Update'));
            }
            return;
        }else{
            echo json_encode(array('status'=> 'fail','message'=> 'Couldn\t Update'));
            return;
        }
    }

    function updateMetrics(){
        $id = $this->input->post('id');
        $quantity = $this->input->post('quantity');
        $price = $this->input->post('price');
        $unit = $this->input->post('unit');
        $mrp = $this->input->post('mrp');
        if(!empty($id)){
            $update = $this->curl->execute('products/updateMetrics/'.$id, 'PUT', array('quantity'=>$quantity, 'price'=>$price, 'unit'=>$unit, 'mrp'=>$mrp));
            if($update['status']== 'success'){
                echo json_encode(array('status'=>'success', 'message'=>'Updated Successfully'));
            }else{
                echo json_encode(array('status'=>'fail', 'message'=>'Couldn\'t Update'));
            }
            return;
        }else{
            echo json_encode(array('status'=> 'fail','message'=> 'Couldn\t Update'));
            return;
        }
    }

    function update_metrics_status($id){
        $apidata = $this->curl->execute('products/update_metrics_status/'.$id, 'PUT');
        echo json_encode(['status'=>$apidata['status'], 'message'=>$apidata['message']]);
    }


}
