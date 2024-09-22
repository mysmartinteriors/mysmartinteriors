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
        $this->lang->load('response', 'english');
		$this->load->model("user_analytics_model", "", true);	
    }
	

	function dashboard_counts_get(){
		$message='success'; 
		$inputs=$this->input->get();
		// print_R($inputs);exit();
		$result=$this->user_analytics_model->get_dashboard_counts($inputs);
		$value  = withSuccess($message,array('details'=>$result));
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function log_data_get(){
		$message='success'; 
		$inputs=$this->input->get();
		$result=$this->user_analytics_model->get_logs($inputs);
		$value  = withSuccess($message,array('details'=>$result));
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function enquiry_data_get(){
		$message='success'; 
		$inputs=$this->input->get();
		$result=$this->user_analytics_model->get_enquiries($inputs);
		$value  = withSuccess($message,array('details'=>$result));
		$this->response($value, REST_Controller::HTTP_OK);
	}

	function order_data_get(){
		$message='success'; 
		$inputs=$this->input->get();
		$result=$this->user_analytics_model->get_orders($inputs);
		$value  = withSuccess($message,array('details'=>$result));
		$this->response($value, REST_Controller::HTTP_OK);
	}
	


	// function summary_get(){
	// 	$message='success';
	// 	$inputs=$this->input->get();
	// 	$result=$this->user_analytics_model->get_statistics_summary($inputs);
	// 	$value  = withSuccess($message,array('details'=>$result));
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }
	
	// function management_summary_get(){
	// 	$message='success';
	// 	$inputs=$this->input->get();
	// 	$result=$this->user_analytics_model->get_clients_summary($inputs);
	// 	$value  = withSuccess($message,array('data_list'=>$result));
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }
	
	// function dashboard_counts_get(){
	// 	$message='success'; 
	// 	$inputs=$this->input->get();
	// 	$result=$this->user_analytics_model->get_dashboard_counts($inputs);
	// 	$value  = withSuccess($message,array('details'=>$result));
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }
	
	// function dashboard_checks_get(){
	// 	$message = 'success';
	// 	$inputs = $this->input->get();
	// 	$result = $this->user_analytics_model->get_checks_counts($inputs);
	// 	$value = withSuccess($message, array('details'=>$result));
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }
	
	// function bar_charts_counts_get(){
	// 	$message = 'success';
	// 	$inputs = $this->input->get();
	// 	$result = $this->user_analytics_model->get_barChart_checks_counts($inputs);
	// 	$value = withSuccess($message, array('details'=>$result));
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }
	
	// function linechart_checks_counts_get(){
	// 	$message = 'success';
	// 	$inputs = $this->input->get();
	// 	$result = $this->user_analytics_model->get_linechart_checks_counts($inputs);
	// 	$value = withSuccess($message, array('details'=>$result));
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }

	// function management_summary_export_get(){
	// 	$inputs=$this->input->get();
	// 	$data_list=$this->user_analytics_model->get_clients_summary($inputs);
	// 	if(!empty($data_list)){
	// 		$spreadsheet = new Spreadsheet();
	// 		$sheet = $spreadsheet->getActiveSheet();
	// 		$file_name    = "management_analytics_".date('d_m_Y_h_i_s_A'). ".xlsx";
			
	// 		$i=1;
			
	// 		$serviceNameStyle= array(
	// 			'font'  => array(
	// 				'bold'  => true,
	// 				'size'  => 11,
	// 				'color' => array('argb' => 'FFFFFFFF'),
	// 			),
	// 			'fill' => array(
	// 				'fillType' => Fill::FILL_SOLID,
	// 				'startColor' => array('argb' => 'FF4F81BD')
	// 			)
	// 		);
			
	// 		$columnNameStyle= array(
	// 			'font'  => array(
	// 				'bold'  => true,
	// 				'size'  => 11
	// 			),
	// 			'borders' => array(
	// 		        'outline' => array(
	// 		            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
	// 		            'color' => array('argb' => '000'),
	// 		        )
	// 		    )
	// 		);
			
	// 		$valueStyle=array(
	// 			'borders' => array(
	// 		        'outline' => array(
	// 		            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
	// 		            'color' => array('argb' => '000'),
	// 		        )
	// 		    )
	// 		);
			
			
			
	// 		$grandTotStyle= array(
	// 			'font'  => array(
	// 				'bold'  => true,
	// 				'size'  => 11
	// 			),
	// 			'borders' => array(
	// 		        'outline' => array(
	// 		            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
	// 		            'color' => array('argb' => 'ffcbcb50'),
	// 		        )
	// 		    ),
	// 			'fill' => array(
	// 				'fillType' => Fill::FILL_SOLID,
	// 				'startColor' => array('argb' => 'ffcbcb50')
	// 			)
	// 		);
			
	// 		$g_total_count=0;
	// 		$g_customer_price=0;
	// 		$g_vendor_price=0;

			
	// 		foreach($data_list as $key=>$value){
	// 			if(is_array($value)){
	// 				//print_r('A'.$i);
	// 				$sheet->setCellValue('A'.$i, $key)->getStyle('A'. $i)->applyFromArray($serviceNameStyle);
	// 				$range1='A'.$i;
	// 				$range2='D'.$i;
					
	// 				$sheet->mergeCells("$range1:$range2");
				
	// 				$total_count=0;
	// 				$customer_price=0;
	// 				$vendor_price=0;
					
	// 				$j=$i+1;
	// 				$sheet->setCellValue('A'.$j, 'Customer')->getStyle('A'.$j)->applyFromArray($columnNameStyle);
	// 				$sheet->setCellValue('B'.$j, 'Count')->getStyle('B'.$j)->applyFromArray($columnNameStyle);
	// 				$sheet->setCellValue('C'.$j, 'Total Price')->getStyle('C'.$j)->applyFromArray($columnNameStyle);
	// 				$sheet->setCellValue('D'.$j, 'Execution Count')->getStyle('D'.$j)->applyFromArray($columnNameStyle);
					
	// 				$j=$j+1;
	// 				foreach($value as $row){
	// 					$sheet->setCellValue('A'.$j, $row['customers_name'])->getStyle('A'.$j)->applyFromArray($valueStyle);
	// 					$sheet->setCellValue('B'.$j, $row['countt'])->getStyle('B'.$j)->applyFromArray($valueStyle);
	// 					$sheet->setCellValue('C'.$j, $row['customer_price_total'])->getStyle('C'.$j)->applyFromArray($valueStyle);
	// 					$sheet->setCellValue('D'.$j, $row['vendor_price_total'])->getStyle('D'.$j)->applyFromArray($valueStyle);
						
	// 					$total_count+=$row['countt']; 
	// 					$customer_price+=$row['customer_price_total']; 
	// 					$vendor_price+=$row['vendor_price_total'];
				
	// 					$j++;
	// 				}
	// 				$sheet->setCellValue('A'.$j, 'Total')->getStyle('A'.$j)->applyFromArray($columnNameStyle);
	// 				$sheet->setCellValue('B'.$j, $total_count)->getStyle('B'.$j)->applyFromArray($columnNameStyle);
	// 				$sheet->setCellValue('C'.$j, $customer_price)->getStyle('C'.$j)->applyFromArray($columnNameStyle);
	// 				$sheet->setCellValue('D'.$j, $vendor_price)->getStyle('D'.$j)->applyFromArray($columnNameStyle);
					
	// 				$g_total_count+=$total_count; 
	// 				$g_customer_price+=$customer_price; 
	// 				$g_vendor_price+=$vendor_price;
	// 			}
	// 			$i=$j+2;				
	// 		}
			
			
	// 		if(!empty($g_total_count)){
	// 			$sheet->setCellValue('A'.$i+1, 'Grand Total')->getStyle('A'.$i+1)->applyFromArray($grandTotStyle);
	// 			$sheet->setCellValue('B'.$i+1, $g_total_count)->getStyle('B'.$i+1)->applyFromArray($grandTotStyle);
	// 			$sheet->setCellValue('C'.$i+1, $g_customer_price)->getStyle('C'.$i+1)->applyFromArray($grandTotStyle);
	// 			$sheet->setCellValue('D'.$i+1, $g_vendor_price)->getStyle('D'.$i+1)->applyFromArray($grandTotStyle);
	// 		}
			
	// 		foreach ($sheet->getColumnIterator() as $column) {
	// 		   $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
	// 		}
	// 		//exit();
			
	// 		$writer = new Xlsx($spreadsheet);
	// 		$filePath = 'reports/' . $file_name;			
	// 		$writer->save($filePath);			
	// 		$res =  array(
	// 			'filename' => $file_name,
	// 			'url' => base_url().$filePath
	// 		);
	// 		$result=array('details'=>$res);
	// 		$value = withSuccess('Report is ready for download',$result);
	// 	}else{
	// 		$value = withErrors($this->lang->line('no_result_found'));
	// 	}
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }
	
	// function execution_summary_get(){
	// 	$message='success';
	// 	$inputs=$this->input->get();
	// 	$result=$this->user_analytics_model->get_execution_summary($inputs);
	// 	$value  = withSuccess($message,array('data_list'=>$result));
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }
	
	
	// function execution_summary_export_get(){
	// 	$inputs=$this->input->get();
	// 	$data_list=$this->user_analytics_model->get_execution_summary($inputs);
	// 	if(!empty($data_list)){
	// 		$spreadsheet = new Spreadsheet();
	// 		$sheet = $spreadsheet->getActiveSheet();
	// 		$file_name    = "execution_summary_".date('d_m_Y_h_i_s_A'). ".xlsx";
			
	// 		$i=1;
			
	// 		$serviceNameStyle= array(
	// 			'font'  => array(
	// 				'bold'  => true,
	// 				'size'  => 11,
	// 				'color' => array('argb' => 'FFFFFFFF'),
	// 			),
	// 			'fill' => array(
	// 				'fillType' => Fill::FILL_SOLID,
	// 				'startColor' => array('argb' => 'FF4F81BD')
	// 			)
	// 		);
			
	// 		$columnNameStyle= array(
	// 			'font'  => array(
	// 				'bold'  => true,
	// 				'size'  => 11
	// 			),
	// 			'borders' => array(
	// 		        'outline' => array(
	// 		            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
	// 		            'color' => array('argb' => '000'),
	// 		        )
	// 		    )
	// 		);
			
	// 		$valueStyle=array(
	// 			'borders' => array(
	// 		        'outline' => array(
	// 		            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
	// 		            'color' => array('argb' => '000'),
	// 		        )
	// 		    )
	// 		);
			
			
			
	// 		$grandTotStyle= array(
	// 			'font'  => array(
	// 				'bold'  => true,
	// 				'size'  => 11
	// 			),
	// 			'borders' => array(
	// 		        'outline' => array(
	// 		            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
	// 		            'color' => array('argb' => 'ffcbcb50'),
	// 		        )
	// 		    ),
	// 			'fill' => array(
	// 				'fillType' => Fill::FILL_SOLID,
	// 				'startColor' => array('argb' => 'ffcbcb50')
	// 			)
	// 		);
			
	// 		$total=0;
    //         $execution_total=0;
    //         $total_cost=0;
    //         $vendor_price_total=0;
			
	// 		$j=2;
						
	// 		$sheet->setCellValue('A1', 'Service Name');
	// 		$sheet->setCellValue('B1', 'No. of Checks');
	// 		$sheet->setCellValue('C1', 'No. of Execution(s)');
	// 		$sheet->setCellValue('D1', 'Execution Cost');		
	// 		$sheet->setCellValue('E1', 'Total Cost');
			
	// 		foreach($data_list as $row){
	// 			$sheet->setCellValue('A'.$j, $row['services_name'])->getStyle('A'.$j)->applyFromArray($valueStyle);
	// 			$sheet->setCellValue('B'.$j, $row['countt'])->getStyle('B'.$j)->applyFromArray($valueStyle);
	// 			$sheet->setCellValue('C'.$j, $row['execution_count_total'])->getStyle('C'.$j)->applyFromArray($valueStyle);
	// 			$sheet->setCellValue('D'.$j, $row['vendor_price_total'])->getStyle('D'.$j)->applyFromArray($valueStyle);
	// 			$sheet->setCellValue('E'.$j, $row['execution_count_total']*$row['vendor_price_total'])->getStyle('E'.$j)->applyFromArray($valueStyle);
				
	// 			$total+=$row['countt']; 
	// 			$execution_total+=$row['execution_count_total']; 
	// 			$vendor_price_total+=$row['vendor_price_total']; 
	// 			$total_cost+=$row['execution_count_total']*$row['vendor_price_total']; 
		
	// 			$j++;
	// 		}	
			
			
	// 		if(!empty($total)){
	// 			$sheet->setCellValue('A'.$j+1, 'Total')->getStyle('A'.$j+1)->applyFromArray($grandTotStyle);
	// 			$sheet->setCellValue('B'.$j+1, $total)->getStyle('B'.$j+1)->applyFromArray($grandTotStyle);
	// 			$sheet->setCellValue('C'.$j+1, $execution_total)->getStyle('C'.$j+1)->applyFromArray($grandTotStyle);
	// 			$sheet->setCellValue('D'.$j+1, $vendor_price_total)->getStyle('D'.$j+1)->applyFromArray($grandTotStyle);
	// 			$sheet->setCellValue('E'.$j+1, $total_cost)->getStyle('E'.$j+1)->applyFromArray($grandTotStyle);
	// 		}
			
	// 		foreach ($sheet->getColumnIterator() as $column) {
	// 		   $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
	// 		}
	// 		//exit();
			
	// 		$writer = new Xlsx($spreadsheet);
	// 		$filePath = 'reports/' . $file_name;			
	// 		$writer->save($filePath);			
	// 		$res =  array(
	// 			'filename' => $file_name,
	// 			'url' => base_url().$filePath
	// 		);
	// 		$result=array('details'=>$res);
	// 		$value = withSuccess('Report is ready for download',$result);
	// 	}else{
	// 		$value = withErrors($this->lang->line('no_result_found'));
	// 	}
	// 	$this->response($value, REST_Controller::HTTP_OK);
	// }
	
	
}