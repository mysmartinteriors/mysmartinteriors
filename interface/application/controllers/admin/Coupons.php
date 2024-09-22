<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Coupons extends CI_Controller {

	function __construct(){
		parent::__construct();
        check_admin_session();
		$this->load->model("adminmodel","",true);
        $this->load->model("couponsmodel","",true);
		$this->admin->nocache(); 
	}
	
    public function index() {
        check_permission(9,'view','uri');
        $data = $this->admin->commonadminFiles();
		$data['title']="Product coupons";
		$this->load->view("admin/coupons/coupons_view",$data);
    }

    function get_datas(){
        $data['result'] = "success";
        $page = (int) $this->input->post('page');
        $filter_data=$this->input->post('filter_data');
        $get_total_rows = $this->couponsmodel->filter_datas($filter_data,0,0);
        $item_per_page="10";
        foreach ($filter_data as $k => $v) {
            if (($v['type'] == 'perpage') && $v['value'] != "") {
                $item_per_page = (int)$v['value'];
            }
        }
        $data = $this->adminmodel->getPaginationData($item_per_page,$page,$get_total_rows);
        $data['dataQ']=$this->couponsmodel->filter_datas($filter_data,$data['item_per_page'],$data['page_position']);
        // /print_r($this->db->last_query());
        $data['pagination'] = $this->adminmodel->paginate_function($item_per_page, $data['page_number'], $get_total_rows, $data['total_pages']);
        $str=$this->load->view("admin/coupons/coupons_tbl_view",$data,true);
        $value=array(
            'str'=>$str
        );
         echo json_encode($value);
    }


    function add_new(){
        check_permission(9,'add','json');   
        $id=$this->input->post('id');
        $data = array();
        if($id!=''){
            $where = array('id' => $id );
            $data['dataQ']=$this->adminmodel->get_table_data('product_coupons',$where,'*',true);
        }
        $str=$this->load->view("admin/coupons/add_coupon_view",$data,true);
        $value=array(
            'status'=>'success',
            'msg'=>$str
        );
        echo json_encode($value);
    }

    function check_add_exists(){
        $name=$this->admin->escapespecialchrs(trim($this->input->post('coupon_code')));
        $edit=$this->admin->escapespecialchrs($this->input->post('edit'));
        $q=0;
        if(strtolower($edit)!=strtolower($name)){
            $where = array('coupon_code' => $name );
            $query=$this->adminmodel->get_table_data('product_coupons',$where,'*',true);
            $q=$query->num_rows();
        }
        $result="true";
        if($q>0){
            $result="false";
        }       
        echo $result;
    }

    function save_add_data(){
        $data['id']=$this->input->post("id");
        $data['coupon_code']=$this->input->post("coupon_code");
        $data['price_type']=$this->input->post("price_type");
        $data['coupon_value']=$this->input->post("coupon_value");
        $data['min_purchase']=$this->input->post("min_purchase");
        $data['valid_from']=getMyDbDate('%Y-%m-%d %H:%i:%s',$this->input->post("valid_from"));
        $data['valid_to']=getMyDbDate('%Y-%m-%d %H:%i:%s',$this->input->post("valid_to"));
        $data['applicable_to']=$this->input->post("applicable_to");
        $data['status']=$this->input->post("status");
        $data['updatedDate']=get_curentTime();

        $res=$this->couponsmodel->save_coupon($data);

        $value = array('result' =>$res['result'] ,'msg'=>$res['msg']);
        echo json_encode($value);
    }

    function update_status(){
        check_permission(9,'edit','json');
        $id=$this->input->post("id");
        $data['status']=$this->input->post("status");
        $data['updatedDate']=get_curentTime();
        $result='fail';
        $msg='Unable to process your request';    
        
        $where = array('id'=>$id);
        $get_res=$this->adminmodel->get_table_data('product_coupons',$where,'*',true);
        if($get_res->num_rows()>0){
            $res=$this->adminmodel->update_table_data('product_coupons',$where,$data);
            if($res>0){
                $result='success';
                $msg='Status updated successfully';

                $logData['dataId']=$id;
                $logData['module']='product coupons';
                $logData['table_name']='product_coupons';
                $logData['action']='update';
                $logData['description']='updated the status of a coupon '.$get_res->row()->coupon_code;
                insert_aLog($logData);
            }
        }
        //print_r($this->db->last_query());
        $value=array(
            'status'=>$result,
            'msg'=>$msg
        );
        echo json_encode($value);
    }

    function delete_coupon(){
        check_permission(9,'delete','json');
        $id=$this->input->post("id");
        $result=1;
        $msg='Cannot delete at this time...';
        if($id!=""){    
            $result=$this->couponsmodel->delete_coupon($id);
        }
        $value=array(
            'status'=>$result['result'],
            'msg'=>$result['msg'],
            'canDelete'=>$result['canDelete']
        );

        echo json_encode($value);
    }
}