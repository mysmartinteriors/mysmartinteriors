<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		$this->load->model("adminmodel","",true);
        $this->load->model("ordersmodel","",true);
		$this->admin->nocache(); 
	}
	
    public function index() {
        $data = $this->admin->commonadminFiles();
		$data['title']="Invoices";
		$this->load->view("admin/invoices/invoices_view",$data);
    }

    function get_bookings(){
        $data['result'] = "success";  
        $page = (int) $this->input->post('page');
        $filter_data=$this->input->post('filter_data');
        $get_total_rows = $this->ordersmodel->filter_bookings($filter_data,0,0);
        $item_per_page="10";
        foreach ($filter_data as $k => $v) {
            if (($v['type'] == 'perpage') && $v['value'] != "") {
                $item_per_page = (int)$v['value'];
            }
        }
        $data = $this->adminmodel->getPaginationData($item_per_page,$page,$get_total_rows);
        
        $data['dataQ']=$this->ordersmodel->filter_bookings($filter_data,$data['item_per_page'],$data['page_position']);

        $data['pagination'] = $this->adminmodel->paginate_function($item_per_page, $data['page_number'], $get_total_rows, $data['total_pages']);
        
        $str=$this->load->view("admin/orders/bookings/orders_tbl_view",$data,true);
        
        $value=array(
            'str'=>$str
        );
         echo json_encode($value);
    }

}