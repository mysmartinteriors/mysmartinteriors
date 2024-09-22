<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class product extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('cookie');
	}

	function index($productCode){
		$productCode = str_replace('%20', ' ', $productCode);
		$data=array();
	    $data = $this->admin->commonFiles();
		$data['title']="Products";
		$apidata=$this->curl->execute('products', 'GET', array('products-code'=>$productCode));
		if($apidata['status']=='success' && !empty($apidata['data_list'])){ 
			$product=$apidata['data_list'][0];
			$data['productQ']=$apidata['data_list'][0];
            $data['categoryQ'] = $this->curl->execute('categories', 'GET');
			$data['featuredQ']=$this->curl->execute('feature_products', 'GET');
			$data['suggestQ']=$this->curl->execute('products', 'GET', array('categoryId'=>$product['categoryId']));
	        $data['imageQ']=$this->curl->execute('products/images',"GET", array('perpage'=>100000));
	        $data['availableQ']=$this->curl->execute('products/avail_options', 'GET', array('id'=>$product['tag_id'], 'id'=>$product['id']));
			$this->load->view('products/product_detailed_view', $data);
		}else{
			$this->load->view('error/html/error_404', $data);
		}
    }

}