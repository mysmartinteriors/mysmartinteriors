 <?php

class Excelvalidation
{
    
    public function __construct()
    {
        
    }
    
    
    function get_excel_heading($spreadsheet)
    {
		$excelSheet = $spreadsheet->getActiveSheet();
        $spreadSheetAry = $excelSheet->toArray();
		
        return $spreadSheetAry[0];
    }
    function check_excel_heading($heading1, $heading2)
    {
        $result = array_diff($heading1, $heading2);
        return $result;
    }
	function  excelheading_error(){
	$value   = withErrors("Some mismatch of column count or mismatch heading in excel");
	echo json_encode($value);
	exit;	
	}

function display_error($msg){
				$result = "fail";
				$value = array(
                'msg' => $msg,
                'result' => $result
				);            
				echo json_encode($value);
				exit;
 }
	function add_index_error($index_data,$value){
		$imp_data  = implode(',',$index_data);
		$result = preg_replace('/[ ,]+/', ' ', trim($imp_data));
		$msg = " Some <strong>".$value."</strong> -<br/>".$result."<br/>";
		return $msg;
	}
}

?> 