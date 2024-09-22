<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	function __construct(){
		parent::__construct();
        // $this->load->model("categorymodel","",true);
		// $this->load->model("adminmodel","",true);
		// $this->load->model("productsmodel","",true);
	}

	function index(){
		$sidebarData['q'] = "";
		$data = $this->admin->commonFiles();
    	$sidebarData['q'] = $this->input->get('search');
        $sidebarData['cat_type'] = $this->input->get('cat_type');
        $sidebarData['sort'] = $this->input->get('sort');
        $sidebarData['scat_type'] = $this->input->get('scat_type');
		$data['title'] = 'Products';
		$sidebarData['featuredQ']=$this->curl->execute('products_featured', 'GET', array('featured_products-status'=>1, 'products-status'=>32));
        // $sidebarData['fpriceQ']=$this->productsmodel->filter_prices();
        $sidebarData['fpriceQ']=$this->curl->execute('products/filter_prices', 'GET');
        if(isset($sidebarData['cat_type']) && $sidebarData['cat_type']!=''){
            $categories = $this->curl->execute('categories', 'GET');
            if($categories['status']=='success' && !empty($categories['data_list'])){
                $data_list = $categories['data_list'];
                $sidebarData['catQ']=$this->filter_category($sidebarData['cat_type'], $data_list);
            }
        } 
		$data['sidebar_filter'] = $this->load->view('products/sidebar_filter_view', $sidebarData, true);
		$this->load->view("products/products_view",$data);
    }


    function filter_category($categoryName, $data_list) {
        $cat_list = '';
        foreach ($data_list as $category) {
            if (strtolower($category['code']) === strtolower($categoryName)) {
                $cat_list .= '<li>';
                $cat_list .= '<label class="">';
                $cat_list .= '<input type="checkbox" class="refine_filter" data-name="category" data-type="cat_type" value="' . $category['code'] . '" data-text="' . $category['text'] . '" disabled>' . ucwords($category['text']) . '</label>';
    
                if (isset($category['children']) && !empty($category['children'])) {
                    $cat_list .= '<ul>';
                    foreach ($category['children'] as $subCategory) {
                        $cat_list .= '<li>';
                        $cat_list .= '<label class="checkbox checkbox-success">';
                        $cat_list .= '<input type="checkbox" class="refine_filter" data-name="subcategory" data-type="scat_type" value="' . $subCategory['code'] . '" data-text="' . $subCategory['text'] . '"><span class="input-span"></span>' . ucwords($subCategory['text']) . '</label>';
                        $cat_list .= '</li>';
                    }
                    $cat_list .= '</ul>';
                }
                $cat_list .= '</li>';
                break; // No need to continue looping once the category is found
            }
        }
    
        return $cat_list;
    }
    

    function get_products(){

        $data['result'] = "success";  
        $filter_data=$this->input->post('filter_data');
        $module=$this->input->post('module');
        $page = (int) $this->input->post('page');
        if(empty($page) && $page<1){
            $page = 1;
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
        $filterData['products-status'] = 32;
        $apidata=$this->curl->execute("products","GET",$filterData,'filter');
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
		// print_R($data);exit();
		$str=$this->load->view("products/products_tbl_view",$data,true);
		$value=array(
            'str'=>$str
        );
		echo json_encode($value);



        // $page = (int) $this->input->post('page');
        // $filter_data=$this->input->post('filter_data');
        // $perpage = 10;
        // // if(isset($filter_data['perpage'])){
        // //     $perpage = $filter_data['perpage'];
        // //     unset($filter_data['perpage']);
        // // }
        // $filterData = array();
    	// if(!empty($filter_data)){
	    // 	foreach ($filter_data as $k => $v) {
		// 		// if($v['type']=='sortby'){
		// 		// 	$filterData[$v['type']]=$this->module_name.'.'.$v['value'];
		// 		// }else{					
        //         $filterData[$v['type']]=$v['value'];
		// 		// }
		// 	}
		// }
        // // print_r($filterData);echo "<hr>";
        // $apidata = $this->curl->execute('products', 'GET', $filterData, 'filter');
        // // print_R($apidata);exit();
        // $data['productQ'] = $apidata;
        // $paginate = '';
        // if($apidata['status']=='success' && !empty($apidata['data_list']) && (isset($apidata['pagination_data']) && !empty($apidata['pagination_data']['pagination_links']))){
        //     $paginate = $apidata['pagination_data']['pagination_links'];
        // }
        // $message=$this->load->view("products/products_tbl_view",$data,true);
        // $value = array('status'=>$data['productQ']['status'], 'message'=>$message, 'paginate'=>$paginate);
        // echo json_encode($value);


        // print_R($apidata);exit();
        // print_R($filter_data);exit();
        // $item_per_page="10";
        // $fun_name='filter_products_all';
        // $search_type='';
        // $parent_cat='';
        // if(!empty($filter_data)){
        //     foreach ($filter_data as $k => $v) {
        //         if (($v['type'] == 'show') && $v['value'] != "") {
        //             $item_per_page = (int)$v['value'];
        //         }
        //         if(($v['type']=='scat_type')&&($v['value']!="")){
        //                 $value = $v['value'];
        //                 $fun_name='filter_products_all';
        //         }
        //         if(($v['type']=='cat_type')&&($v['value']!="")){
        //                 $parent_cat = $v['value'];
        //         }
        //         if(($v['type']=='q')&&($v['value']!="")){
        //                 $search_type = $v['value'];
        //         }
        //     }
        // }
        // if($search_type!='' || $parent_cat!=''){
        //     $get_total_rows = $this->productsmodel->$fun_name($filter_data,0,0,$parent_cat);
        //     $data = $this->adminmodel->getPaginationData($item_per_page,$page,$get_total_rows);

        //     $data['productQ']=$this->productsmodel->$fun_name($filter_data,$data['item_per_page'],$data['page_position'],$parent_cat);

        //     $pagination = $this->adminmodel->paginate_function($item_per_page, $data['page_number'], $get_total_rows, $data['total_pages']);
            
        //     $str=$this->load->view("products/products_tbl_view",$data,true);
            
        //     $counts='Showing all '.$get_total_rows.' product(s)';
        // }else{
        //     $data = array();
        //     $str=$this->load->view("products/products_404_view",$data,true);
        //     $counts='';
        //     $pagination='';
        // }
        
        // $value=array(
        //     'str'=>$str,
        //     'paginate'=>$pagination,
        //     'counts'=>$counts,
        // );
       // print_r($this->db->last_query());
    }

    function prdQuickView() {
		$data=array();
		$result=0;
		$id=$this->input->post('id');
        $data['metricsId'] = !empty($this->input->post('metricsId'))?$this->input->post('metricsId'):'';
		$data['productQ']=$this->curl->execute("products/$id", "GET"); 
        $where = array('productId' => $id );
        $data['imageQ']=$this->curl->execute("products/images/$id", "GET");
		if($data['productQ']['status']=='success' && !empty($data['productQ']['data_list'])){
			$result=1;
		}
        $str=$this->load->view("products/products_quick_view",$data,true);        
        $value=array(
            'result'=>$result,
            'str'=>$str
        );
         echo json_encode($value);
    }

    //ratings
    function get_ratings(){
        $data=array();
        $id=$this->input->post('id');
        $customerId=get_userId();
        $where=array('productId'=>$id,'customerId'=>$customerId);
        $data['userRating']=$this->productsmodel->get_table_data('product_ratings',$where);
        $data['ratingQ']=$this->productsmodel->get_product_ratings($id);
        $str=$this->load->view("products/product_ratings_tbl",$data,true);
        $value=array('str'=>$str);
        echo json_encode($value);
    }

    function save_ratings(){
        if(is_uLogged() && get_userId()){
            $data['productId']=$this->input->post('pid');
            $purl=$this->input->post('purl');
            $data['customerId']=get_userId();
            $data['ratingValue']=$this->input->post('ratingValue');
            $data['reviewSummary']=$this->input->post('reviewSummary');
            $data['reviewContent']=$this->input->post('reviewContent');
            $data['ratedDate']=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
            $data['is_approved']=0;
            $result=$this->productsmodel->insert_table_data('product_ratings',$data);
            if($result>0){
                //insert log data
                $logData['action']='rated';
                $logData['description']=get_uEmail().' submitted ratings for the product '.$purl;
                $logData['dataId']=$data['productId'];
                $logData['module']='Product Ratings';
                $logData['table_name']='product_ratings';
                insert_uLog($logData);
            }
            $value=array('result'=>$result);
            echo json_encode($value);
        }else{
            redirect(base_url().'account');
        }
    }

}