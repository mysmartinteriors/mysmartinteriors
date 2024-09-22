 <?php

class Excelvalidation
{
    
    public function __construct()
    {
        
    }
    
    
    function get_excel_heading($object)
    {
        $crows = array();
        foreach ($object->getWorksheetIterator() as $worksheet) {
            for ($row = 0; $row <= 20; $row++) {
                $crows[] = $worksheet->getCellByColumnAndRow($row, 1)->getValue();
            }
        }
        return $crows;
    }

    function check_excel_heading($heading1, $heading2)
    {
        $result = array_diff($heading1, $heading2);
        return $result;
    }

	function  excelheading_error(){
		$msg    = "Some mismatch of column count or mismatch heading in excel";
		return display_error($msg);
	}

	function add_index_error($index_data){
		$imp_data  = implode(',',$index_data);
		$msg = " Some <b>('".$imp_data."')</b> are not found. Check the spelling of the data and upload back </br></br>";
		return $msg;
	}
}

?> 