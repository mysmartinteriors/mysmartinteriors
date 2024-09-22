<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Vendors extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        check_admin_session();
        $this->admin->nocache();
        $this->module_name = 'vendors';
        $this->view_path = "admin/vendor";
    }

    public function index()
    {
        $data = $this->admin->commonadminFiles();
        $data['title'] = "vendor";
        $this->load->view("admin/vendor/vendor_view", $data);
    }

    function get_datas()
    {
        $data['result'] = "success";
        $filter_data = $this->input->post('filter_data');
        $module = $this->input->post('module');
        $page = (int) $this->input->post('page');
        if (empty($page) && $page < 1) {
            $page = 1;
        }
        $filterData[] = array();
        $filterData['orderby'] = 'DESC';
        if (!empty($filter_data)) {
            foreach ($filter_data as $k => $v) {
                if ($v['type'] == 'sortby') {
                    $filterData[$v['type']] = $module . '.' . $v['value'];
                } else {
                    $filterData[$v['type']] = $v['value'];
                }
            }
        }
        if (!empty($page)) {
            $filterData['page'] = $page;
        }
        $apidata = $this->curl->execute($this->module_name, "GET", $filterData, 'filter');
        $data = array(
            'message' => $apidata['message'],
            'status' => $apidata['status'],
            'data_list' => $apidata['data_list'],
        );
        if (isset($apidata['pagination_data'])) {
            $data['pagination_data'] = $apidata['pagination_data'];
        }

        $str = $this->load->view("admin/vendor/vendor_tbl_view", $data, true);
        $value = array('str' => $str, 'status' => $apidata['status']);
        echo json_encode($value);
    }

    function add()
    {
        $id = $this->input->post('id');
        $data = array();
        if ($id != '') {
            $where = array('id' => $id);
            $apidata = $this->curl->execute("vendors/$id", "GET");
            if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
                $data['dataQ'] = $apidata['data_list'];
            }
        }
        $str = $this->load->view("admin/vendor/add_vendor_view", $data, true);
        $value = array(
            'result' => 1,
            'str' => $str
        );
        echo json_encode($value);
    }

    function check_exists()
    {
        $name = $this->admin->escapespecialchrs(trim($this->input->post('code')));
        $edit = $this->admin->escapespecialchrs($this->input->post('edit'));
        if (strtolower($edit) != strtolower($name)) {
            $where = array('code' => $name);
            $apidata = $this->curl->execute('vendors/check_exist', "GET", $where);
            if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
                $result = "false";
            } else {
                $result = "true";
            }
        } else {
            $result = 'true';
        }
        echo $result;
    }

    function save_vendor()
    {
        $id = $this->input->post("id");
        $data['name'] = $this->input->post("name");
        $data['code'] = $this->input->post("code");
        $data['email'] = $this->input->post("email");
        $data['phone'] = $this->input->post("phone");
        $data['address'] = $this->input->post("address");
        $data['pincode'] = $this->input->post("pincode");
        $data['status'] = $this->input->post("status");
        $data['password'] = $this->input->post("password");
        if (!empty($id)) {
            $apidata = $this->curl->execute("vendors/$id", "PUT", $data);
        } else {
            $apidata = $this->curl->execute("vendors", "POST", $data);
        }
        if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
            $status = $apidata['status'];
            $message = $apidata['message'];
        } else {
            $status = $apidata['status'];
            $message = $apidata['message'];
        }
        $value = array(
            'status' => $status,
            'message' => $message
        );

        echo json_encode($value);
    }

    function update_ticket()
    {
        $id = $this->input->post("id");
        $data['customerId'] = $this->input->post("cid");
        $data['status'] = $this->input->post("status");
        $res = $this->curl->execute($this->module_name . "/" . $id, "PUT", $data);
        if ($res['status'] == 'success' && !empty($res['data_list'])) {
            $msg = $res['status'];
        } else {
            $msg = $res['status'];
        }
        $value = array(
            'result' => $res,
            'msg' => $msg
        );
        echo json_encode($value);
    }

    function delete()
    {
        $id = $this->input->post("id");
        $status = '';
        $msg = 'Cannot delete at this time...';
        if ($id != "") {
            $apidata = $this->curl->execute("vendors/$id", "DELETE");
            if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
                $status = $apidata['status'];
                $message = $apidata['message'];
            } else {
                $status = $apidata['status'];
                $message = $apidata['message'];
            }
        }
        $value = array(
            'status' => $status,
            'message' => $message
        );
        echo json_encode($value);
    }

    function challan($id){
        $data = $this->admin->commonadminFiles();
        $data['title'] = "Vendors Challan History";
        if(!$id){
            $data['error_msg'] = 'Vendor Not Found, Invalid Request';
        }
        $vendorAPI = $this->curl->execute("vendors/$id", "GET");
        if($vendorAPI['status']=='success' && !empty($vendorAPI['data_list'])){
            unset($vendorAPI['data_list']['password']);
            $this->session->set_userdata(['curVendor'=>$id]);
            $data['datas'] = $vendorAPI['data_list'];
        }else{
            $data['error_msg'] = 'Vendor Not Found, Invalid Request';
        }
        $this->load->view("admin/vendor/challan_history", $data);
    }

	function get_challan_history_datas(){
        $filter_data=$this->input->post('filter_data');
        $vendorId = $this->session->userdata('curVendor');
        if(!$vendorId){
            $data=array(
                'message'=>'Internal Server Error Detected',
                'status'=>'fail',
                'data_list'=>[],
            );
            $str = $this->load->view("vendors/challan_tbl_view", $data, true);
            $value=array('str'=>$str,'status'=>'fail');
            echo json_encode($value);
        }else{
            $module=$this->input->post('module');
            $page=$this->input->post('page');
            if(empty($page) && $page<1){
                $page = 1;
            }
            $filterData[]=array();
            $filterData['orderby']='ASC';
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
            $filterData['vendors-id'] = $vendorId;
            $apidata=$this->curl->execute('vendors_challan_history',"GET",$filterData,'filter');
            $data=array(
                'message'=>$apidata['message'],
                'status'=>$apidata['status'],
                'data_list'=>$apidata['data_list'],
            );
            if(isset($apidata['pagination_data'])){
                $data['pagination_data']=$apidata['pagination_data'];
            }
            $str = $this->load->view("admin/vendor/challan_tbl_view", $data, true);
            $value=array('str'=>$str,'status'=>$apidata['status']);
            echo json_encode($value);	
        }
	}


    function challanDetails($id){
        $data = [];
        $data = $this->admin->commonadminFiles();
        $data['title'] = 'Challan History Detail';
        if(empty($id)){
            redirect(base_url());
        }
        $apidata=$this->curl->execute('vendors_challan_history/'.$id, "GET");
        $data['apidata'] = $apidata;
        $this->load->view('admin/vendor/challan_detail_view', $data);
    }

    function delete_challan_history_product(){
        $id = $this->input->post('productId');
        $apidata = $this->curl->execute('vendors_challan_history/remove_product/'.$id, 'DELETE');
        if($apidata['status']=='success'){
            echo json_encode(array('status'=>'success'));
        }else{
            echo json_encode(array('status'=>'fail'));
        }
    }

    function add_challan(){
        $id = $this->input->post('id');
        $data = array();
        if(!empty($id)){
            $data['id'] = $id;
        }
        $data['dataQ'] = array();
        $vendorsApi = $this->curl->execute('vendors', 'GET', array('perpage'=>1000, 'vendors-status'=>42));
        $data['vendors'] = $vendorsApi;
        if ($id != '') {
            $where = array('id' => $id);
            $apidata = $this->curl->execute("vendors_challan_history/$id", "GET");
            if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
                $data['dataQ'] = $apidata['data_list'];
            }
        }
        $str = $this->load->view("admin/vendor/add_challan_view", $data, true);
        $value = array(
            'result' => 1,
            'str' => $str
        );
        echo json_encode($value);   
    }


    function add_custom_field()
	{
		$data['count'] = $this->input->post('count');
        $data['prd_units'] = $this->curl->execute("lookups", "GET", array("l_type"=>"product_unit"));
		$str = $this->load->view("admin/vendor/add_custom_field_view", $data, true);
		$value = array('result' => 'success', 'msg' => $str);
		echo json_encode($value);
	}


    function save_challan(){
        $data = [];
        $id = $this->input->post("id");
        $data['vendor_id'] = $this->input->post("vendor_id");
        $data['created_by'] = User::get_userId();
        $json_array = array();
        if (isset($_POST['name']) || isset($_POST['quantity']) || isset($_POST['unit'])) {
            if (count($_POST['quantity']) > 0 || count($_POST['unit']) > 0 || count($_POST['name']) > 0) {
                for ($i = 0; $i < count($_POST['quantity']); $i++) {
                        $new_array = array(
                            'product_quantity' => $_POST['quantity'][$i],
                            'product_unit' => $_POST['unit'][$i],
                            'product_name' => $_POST['name'][$i],
                        );
                        array_push($json_array, $new_array);
                }
            }
        }
        unset($data['quantity']);
        unset($data['unit']);
        unset($data['name']);
        if (!empty($id)) {
            $apidata = $this->curl->execute("vendors_challan_history/$id", "PUT", $data);
        } else {
            $apidata = $this->curl->execute("vendors_challan_history", "POST", $data);
        }
        if ($apidata['status'] == 'success' && !empty($apidata['data_list'])) {
            $challanId = $apidata['data_list']['id'];
            $challanProducts = json_encode($json_array);
            $metricsUpdateApi = $this->curl->execute("vendors_challan_history/challan_products/$challanId", 'PUT', array('challan_products'=>$challanProducts));
            $status = $apidata['status'];
            $message = $apidata['message'];
        } else {
            $status = $apidata['status'];
            $message = $apidata['message'];
        }
        $value = array(
            'status' => $status,
            'message' => $message
        );
        echo json_encode($value);

    }
 
	function pdf()
	{
		$id = $this->uri->segment(4);
		if ($id) {
			$vendor_challan_history = $this->curl->execute("vendors_challan_history/$id", "GET");
			if ($vendor_challan_history['status'] == 'success' && !empty($vendor_challan_history['data_list'])) {
				$data['vendor_challan_history'] = $vendor_challan_history['data_list'];
			}
			require FCPATH . '/vendor/autoload.php';
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$code = $vendor_challan_history['data_list']['unique_id'];
			$pdf->setTitle('Order_' . $code);
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
				require_once (dirname(__FILE__) . '/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			$pdf->setFont('times', 'BI', 20);
			$pdf->AddPage();
			$str = $this->load->view("admin/vendor/pdf_view", $data, true);
			ob_end_clean();
			$pdf->writeHTML($str, false, false, true, false, '');
			$pdf->Output('Order_' . $code . '.pdf', 'I');
		} else {
			redirect(base_url() . 'admin/dashboard');
		}
	}
}