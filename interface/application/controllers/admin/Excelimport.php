<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Excelimport extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model("adminmodel","",true);
        $this->load->library('excel');
		$this->load->library('excelvalidation');
        $this->load->library('iprogress');
        $this->admin->nocache(); 
	}

    function importModal(){
        $module=$this->input->post('module');
        $data=array();
        $msg=$this->load->view("admin/import/".$module,$data,true);
        $value = withSuccess($msg);
        echo json_encode($value);
    }

	function products(){
        $action=$this->input->post('update');
        $progress  = new IProgress('excel', 200);
        $progress->clear(); 
        $excel_validation = new Excelvalidation();
        if (isset($_FILES["file"]["name"])) {
            $ext_array = array(
                'xlsx',
                'xls'
            );
            $ext       = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            if (!in_array($ext, $ext_array)) {
               $value = withErrors("Only xlsx, xls extension file accepted!!!");
               echo json_encode($value);
               exit;
            }
            $path   = $_FILES["file"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            $this->db->trans_begin();
            $data         = array();
            $count        = 0;
            $update_count = 0;
            $insert_count = 0;
            $total_count  = 0;
            $highestRow   = 0;
            $reject_count = 0;
            $time         = time();
            
            $colum_heading = array('ID','Category','Product_Name','Description','Image','HSN','Price','CGST','SGST','In_Stock','Min_Quantity','Meta_Tags','Meta_Description','Model','Color_Code','Color_Name','Is_Primary','Tag_ID','Tag_Name','More_Images');

            $get_excel_heading = $excel_validation->get_excel_heading ($object);
             $check_excel_heading = $excel_validation->check_excel_heading($colum_heading,$get_excel_heading);

            if(!empty($check_excel_heading)){
                $excel_validation->excelheading_error();
    
            }
            $data['updatedDate'] = get_curentTime();
            $data['status']=1;
            
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow    = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                
                for ($row = 2; $row <= $highestRow; $row++) {  
                    $data['productCode']=$worksheet->getCellByColumnAndRow(0,$row)->getValue();
                    $categoryName=$worksheet->getCellByColumnAndRow(1,$row)->getValue();
                    $data['productName']=$worksheet->getCellByColumnAndRow(2,$row)->getValue();
                    $data['description']=$worksheet->getCellByColumnAndRow(3,$row)->getValue();
                    $data['productImage']=$worksheet->getCellByColumnAndRow(4,$row)->getValue(); 
                    $data['hsn']=$worksheet->getCellByColumnAndRow(5,$row)->getValue();
                    $data['price']=$worksheet->getCellByColumnAndRow(6,$row)->getValue();   
                    $data['CGST']=$worksheet->getCellByColumnAndRow(7,$row)->getValue();     
                    $data['SGST']=$worksheet->getCellByColumnAndRow(8,$row)->getValue(); 
                    $in_stock=$worksheet->getCellByColumnAndRow(9,$row)->getValue();
                    $data['minQuantity']=$worksheet->getCellByColumnAndRow(10,$row)->getValue();
                    $data['metaTags']=$worksheet->getCellByColumnAndRow(11,$row)->getValue();
                    $data['metaDescription']=$worksheet->getCellByColumnAndRow(12,$row)->getValue();
                    $data['model_no']=$worksheet->getCellByColumnAndRow(13,$row)->getValue();
                    $data['color_code']=$worksheet->getCellByColumnAndRow(14,$row)->getValue();
                    $data['color_name']=$worksheet->getCellByColumnAndRow(15,$row)->getValue();
                    $is_primary=$worksheet->getCellByColumnAndRow(16,$row)->getValue();
                    $data['tag_id']=$worksheet->getCellByColumnAndRow(17,$row)->getValue();
                    $data['tag_name']=$worksheet->getCellByColumnAndRow(18,$row)->getValue();
                    $imageQ=$worksheet->getCellByColumnAndRow(19,$row)->getValue();


                    if($in_stock=='yes'){
                        $data['in_stock']=1;
                    }else{
                        $data['in_stock']=0;
                    }

                    if($is_primary=='yes'){
                        $data['is_primary']=1;
                    }else{
                        $data['is_primary']=0;
                    }

                    if($data['productCode']!='' && $categoryName!=''){

                    $data['productURL']=create_slug($data['productName'].'-'.$data['color_name'].'-'.$data['productCode']);

                        $where = array('code' =>$categoryName);
                        $get_cats=$this->adminmodel->get_table_data('categories',$where,'*',true);
                        if($get_cats->num_rows()>0){
                            $data['categoryId']=$get_cats->row()->id;
                            $data['parentId']=$get_cats->row()->parent;

                            $where = array('productCode' =>$data['productCode'] );
                            $exist_id=$this->adminmodel->get_table_data('products',$where,'*',true);

                            if($exist_id->num_rows()>0 && $action=='yes'){
                                $res=$this->adminmodel->update_table_data('products',$where,$data);
                                $res_id=$exist_id->row()->productId;
                                ++$update_count;               
                            }else{
                                $data['createdDate'] = $data['updatedDate'];
                                $res_id = $this->adminmodel->insert_table_data('products',$data);
                                if($res_id!=''){                                    
                                    $featureData['productId']=$res_id;
                                    $featureData['content']='';
                                    $featureData['content_html']='';
                                    $featureData['createdDate']=get_curentTime();
                                    $featureData['updatedDate']=$featureData['createdDate'];
                                    $this->db->insert("product_features",$featureData);
                                }
                                ++$insert_count;         
                            }
                            if($imageQ!='' && $res_id!=''){
                                $imageQ = preg_replace('/\.$/', '', $imageQ);
                                $image_arr=$this->split_string_to_arr($imageQ);
                                $iWhere = array('productId' => $res_id);
                                $this->adminmodel->delete_table_data('product_images',$iWhere);
                                
                                //print_r($this->db->last_query());
                                for ($j=0; $j <count($image_arr) ; $j++) {
                                    if($image_arr[$j]!=''){
                                        $image_data['filePath']=$image_arr[$j];
                                        $image_data['productId']=$res_id;
                                        $image_data['createdDate']=get_curentTime();
                                        $this->adminmodel->insert_table_data('product_images',$image_data);
                                    }
                                }
                            }
                        }else{
                            ++$reject_count;
                        }
                    }else{
                        ++$reject_count;
                    }
                    ++$total_count;  
                                        
                    
                    $progress->clear(); 
                    $progress->addMsg("Processing");
                    $progress->addMsg($highestRow-1);
                    $progress->addMsg($total_count);               
          
                    $percent = (($total_count) * 100 / ($highestRow-1));             
                    $progress->setProgress($percent);

                    if($row%30==0){
                        sleep(1);
                    }
                }
            }
            //print_r($this->db->last_query());
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_commit();
                $msg    = "An error occured while importing data ";
                $value = withErrors($msg);
            } else {                
                $this->db->trans_commit();
                $msg    = "Number of records found in file - " .$total_count.
                            "\nNumber of incorrect data(rejected) - " . $reject_count.
                            "\nNumber of products added - " . $insert_count.
                            "\nNumber of products updated - " . $update_count;

                if($insert_count>0){
                    //insert log data
                    $logData['dataId']='';
                    $logData['module']='products';
                    $logData['table_name']='products';
                    $logData['action']='import';
                    $logData['description']='imported '.$insert_count.' product(s) into the system and updated '.$update_count.' products data';
                    insert_aLog($logData);
                }
                $value = withSuccess($msg);
            }           
            echo json_encode($value);
        }
    }

    function split_string_to_arr($text){
        //variable to store the result i.e. an array 
        $arr = [];
        //calculate string length
        $strLength = strlen($text);
        $dl = ','; //delimeter
        $j = 0;
        $tmp = ''; //a temp variable
        //logic - it will check all characters
        //and split the string when comma found
        for ($i = 0; $i < $strLength; $i++) {
            if($dl === $text[$i]) {
                $j++;
                $tmp = '';
                continue;
            }
            $tmp .= $text[$i];
            $arr[$j] = $tmp;
        }
        //return the result
        return $arr;
    }

    function customers(){
        $action=$this->input->post('update');
        $progress  = new IProgress('excel', 200);
        $progress->clear(); 
        $excel_validation = new Excelvalidation();
        if (isset($_FILES["file"]["name"])) {
            $ext_array = array(
                'xlsx',
                'xls'
            );
            $ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            if (!in_array($ext, $ext_array)) {
               $value = withErrors("Only xlsx, xls extension file accepted!!!");
               echo json_encode($value);
               exit;
            }
            $path   = $_FILES["file"]["tmp_name"];
            $objWorksheet = PHPExcel_IOFactory::load($path);
            $this->db->trans_begin();
            $data         = array();
            $count        = 0;
            $update_count = 0;
            $insert_count = 0;
            $total_count  = 0;
            $highestRow   = 0;
            $time         = time();
            
            $colum_heading = array('First_Name','Last_Name','Email','Phone','Address','City','State','Country','Postal_Code','Password','Active');

            $get_excel_heading = $excel_validation->get_excel_heading ($objWorksheet);
             $check_excel_heading = $excel_validation->check_excel_heading($colum_heading,$get_excel_heading);

            if(!empty($check_excel_heading)){
                $excel_validation->excelheading_error();
    
            }
            $data['updatedDate'] = get_curentTime();
            $data['type'] ='Imported';
            $data['status'] = 1;
           
            foreach ($objWorksheet->getWorksheetIterator() as $worksheet) {
                $highestRow    = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                
                for ($row = 2; $row <= $highestRow; $row++) {
                    $data['email']=$worksheet->getCellByColumnAndRow(0,$row)->getValue();
                    $data['firstName']=$worksheet->getCellByColumnAndRow(1,$row)->getValue();
                    $data['lastName']=$worksheet->getCellByColumnAndRow(2,$row)->getValue();
                    $data['phone']=$worksheet->getCellByColumnAndRow(3,$row)->getValue();     
                    $data['address']=$worksheet->getCellByColumnAndRow(4,$row)->getValue();
                    $data['city']=$worksheet->getCellByColumnAndRow(5,$row)->getValue();
                    $data['state']=$worksheet->getCellByColumnAndRow(6,$row)->getValue();
                    $data['country']=$worksheet->getCellByColumnAndRow(7,$row)->getValue();
                    $data['postalCode']=$worksheet->getCellByColumnAndRow(8,$row)->getValue();
                    $data['password']=$worksheet->getCellByColumnAndRow(9,$row)->getValue();
                    $status=$worksheet->getCellByColumnAndRow(10,$row)->getValue();
                    if($status=='yes'){
                    	$data['status']=1;
                    }else{
                    	$data['status']=0;
                    }

                    if($data['email']!=''){
                        $where = array('email' =>$data['email'] );
                        $exist_id=$this->adminmodel->get_table_data('customers',$where,'*',true);

                        if($exist_id->num_rows()>0 && $action=='yes' && $data['email']!=''){
                            $res_id=$this->adminmodel->update_table_data('customers',$where,$data);
                            ++$update_count;               
                        }else{
                            $data['createdDate'] = $data['updatedDate'];
                            $res_id = $this->adminmodel->insert_table_data('customers',$data); 
                            ++$insert_count;         
                        }
                        ++$total_count; 

                        $progress->clear(); 
                        $progress->addMsg("Processing");
                        $progress->addMsg($highestRow-1);
                        $progress->addMsg($total_count);               
              
                        $percent = (($total_count) * 100 / ($highestRow-1));             
                        $progress->setProgress($percent);

                        if($row%30==0){
                            sleep(1);
                        }
                    }
                }
            }
            //print_r($this->db->last_query());

            if ($this->db->trans_status() === FALSE) {
                //$this->db->trans_rollback();
                $this->db->trans_commit();
                $msg    = "An error occured while importing bulk data";
                $value = withErrors($msg);
            } else {                
                $this->db->trans_commit();
                $msg    = "Number of records found in file - " .$total_count.
                            "\nNumber of customers added - " . $insert_count.
                            "\nNumber of customers updated - " . $update_count;
                $value = withSuccess($msg);
                if($insert_count>0){
		            //insert log data
		            $logData['dataId']='';
		            $logData['module']='customers';
		            $logData['table_name']='customers';
		            $logData['action']='import';
		            $logData['description']='imported '.$insert_count.' customer(s) into the system and updated '.$update_count.' customers data';
	            	insert_aLog($logData);
                }
            }           
            echo json_encode($value);
        }
    }

	public function cleardata_unused(){
    	$uid=get_userId();
        $file="lib/data_read/".$uid."-newfile.txt";
        $f=fopen($file,'w');
        fwrite($f,'');
        fclose($f);
    }

    function cleardata(){
        echo  $file = APPPATH."/libraries/excel.iprogress";
         if(file_exists($file)){
             unlink($file);
         }
         
     }
	
}	