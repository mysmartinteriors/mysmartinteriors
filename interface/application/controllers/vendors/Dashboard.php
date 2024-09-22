<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct(){
		parent::__construct();
		check_vendors_session();
		$this->admin->nocache(); 
	}
	
    public function index() {
		$userId = Vendor::get_userId();
        $data = $this->admin->commonvendorFiles();
		$data['title']="Dashboard";
		$this->load->view("vendors/dashboard_view",$data);
    }  

	// function challan_history(){
	// 	$id = $_GET['vI'];
	// 	if(empty($id)){
	// 		redirect(base_url().'vendors/dashboard');
	// 	}
	// 	$apidata = $this->curl->execute("vendors_challan_history/$id", 'GET');
	// 	if($apidata['status']=='success' && !empty($apidata['data_list'])){

	// 	}	
	// }

	function get_challan_history_datas(){
        $filter_data=$this->input->post('filter_data');
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
		$filterData['vendors-id'] = Vendor::get_userId();
    	// $apidata=$this->curl->execute('vendor_challan_history',"GET",$filterData,'filter');
		$apidata=$this->curl->execute('vendors_challan_history',"GET",$filterData,'filter');
		// print_R($apidata);exit();
    	$data=array(
    		'message'=>$apidata['message'],
    		'status'=>$apidata['status'],
    		'data_list'=>$apidata['data_list'],
    	);
    	if(isset($apidata['pagination_data'])){
    		$data['pagination_data']=$apidata['pagination_data'];
    	}
    	$str = $this->load->view("vendors/challan_tbl_view", $data, true);
    	$value=array('str'=>$str,'status'=>$apidata['status']);
    	echo json_encode($value);	
	}



    function challanDetails($id){
		$data = $this->admin->commonvendorFiles();
        $data['title'] = 'Challan History Detail';
        if(empty($id)){
            redirect(base_url());
        }
		$data['challan_history_id'] = $id;
        $apidata=$this->curl->execute('vendors_challan_history/'.$id, "GET");
        $data['apidata'] = $apidata;
        $this->load->view('vendors/challan_detail_view', $data);
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

	function update_challan(){
		$input = $this->input->post('data');
		$encodeData = json_encode($input);
		$challanId = $this->input->post('challanId');
		$updateChallanApi = $this->curl->execute('vendors_challan_history/vendor_challan_tbl_products/'.$challanId, 'PUT', array('data'=>$encodeData));
		echo json_encode($updateChallanApi);
		return;
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
