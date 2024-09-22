<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

require APPPATH . 'libraries/REST_Controller.php'; 

class Analytics extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();       
        $this->workorders_table= 'workorders';
        $this->profiles_table= 'workorder_profiles';
        $this->checks_table= 'workorder_profiles_checks';
        $this->lang->load('response', 'english');
		$this->load->model("vendor_analytics_model", "", true);	
    }
	
	function summary_get(){
		$message='success';
		$inputs=$this->input->get();
		if(empty($inputs['uid'])){
			$value  = withErrors('Vendor id is required');
			$this->response($value, REST_Controller::HTTP_OK);
		}else{
			$result=$this->vendor_analytics_model->get_statistics_summary($inputs);
			
			$value  = withSuccess($message,array('details'=>$result));
			$this->response($value, REST_Controller::HTTP_OK);
		}
	}
	
	
}