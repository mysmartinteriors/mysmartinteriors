<?php

require APPPATH . 'libraries/REST_Controller.php';   

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Activity_log extends REST_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->database();
        $this->table= 'activity_log';
        $this->model_name='activitylog_model';   
        $this->load->model($this->model_name, "", true);   
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
      if(!empty($id)){
         $data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
      }else{       
         $data = $this->Mydb->do_search($this->table,$this->model_name);
      }
      if(!empty($data)){
         $value  = withSuccess($message,$data);
      }else{
         $value  = withSuccess($this->lang->line('no_result_found'));
      }
      
      $this->response($value, REST_Controller::HTTP_OK);
    }


	public function actions_log_get($input = ""){
		$message = "success";
		$data = $this->activitylog_model->get_actions_log();
		// print_R($data);exit();
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$data = array('data_list'=>$data);
		$value  = withSuccess($message,$data);
		$this->response($value, REST_Controller::HTTP_OK);
	}

	public function modules($input = ""){
		$message = "success";
		$data = $this->activitylog_model->get_modules_log();
		// print_R($data);exit();
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}
		$data = array('data_list'=>$data);
		$value  = withSuccess($message,$data);
		$this->response($value, REST_Controller::HTTP_OK);
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
			'reference_id' => ['Reference ID','required|numeric'],
			'reference_name' => ['Reference Name','required'],
			'reference_type' => ['Reference Type','required|numeric'],
			'data_id' => ['Data ID','required|numeric'],
			'action' => ['Action Name','required'],
			'module' => ['Module Name','required'],
			'description' => ['Description','required'],
			'ip_address' => ['IP Address','required']
		];
		Validator::make($rules);
		if (!Validator::fails()){
            Validator::error();
        }else{
			$data = array(
				'reference_id'=>$input['reference_id'],
				'reference_type'=>$input['reference_type'],
				'reference_name' =>$input['reference_name'],
				'data_id' =>$input['data_id'],
				'action' =>$input['action'],
				'module' =>$input['module'],
				'description' =>$input['description'],
				'ip_address'=>$input['ip_address'],
				'created_at'=>cur_date_time()
			);
			$id = $this->Mydb->insert_table_data('activity_log',$data);
			//print_r($this->db->last_query());
			$value  = withSuccess($this->lang->line('uploads_success'));
			$this->response($value, REST_Controller::HTTP_OK);
		}
    }
	
	
	/**

     * Export data from  file using this method.

     *

     * @return Response

    */

	public function export_get(){
		$rand        = rand(1234, 9898);
        $presentDate = date('d_m_Y_h_i_s_A');
        $file_name    = "report_activity_log_". $presentDate . ".xlsx";
		$data = $this->Mydb->do_search($this->table,$this->model_name,true);
		
		if(!empty($data['data_list'])){
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			$sheet->setCellValue('A1', 'IP Address');
			$sheet->setCellValue('B1', 'Type');
			$sheet->setCellValue('C1', 'Username');
			$sheet->setCellValue('D1', 'Action');		
			$sheet->setCellValue('E1', 'Module');	
			$sheet->setCellValue('F1', 'Description');	
			$sheet->setCellValue('G1', 'Time');

			$count = 2;

			foreach($data['data_list'] as $row)
			{			
				$sheet->setCellValue('A' . $count, $row->ip_address);
				$sheet->setCellValue('B' . $count, $row->refer_type);
				$sheet->setCellValue('C' . $count, $row->reference_name);
				$sheet->setCellValue('D' . $count, $row->action);
				$sheet->setCellValue('E' . $count, $row->module);
				$sheet->setCellValue('F' . $count, $row->description);
				$sheet->setCellValue('G' . $count, custom_date('d-m-Y h:i:s A',$row->created_at));
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

}	