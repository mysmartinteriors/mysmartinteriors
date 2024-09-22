<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

function print_array($array){
	echo '<pre>';
   print_r($array);
   echo '<pre>';
}

function create_slug($string){
  $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
  return strtolower($slug);
}

if(!function_exists('insert_aLog')){
    function insert_aLog($data){
        $ci =& get_instance();
        $ci->load->library('adminmodel',true);
        $data['ip_address']=get_ipaddress();
        $data['createdDate']=get_curentTime();
        $data['adminId']=get_adminId();
        $data['type']='admin';
        $result = $ci->adminmodel->insert_table_data("activity_log", $data);
        return $result;        
    }
}

if(!function_exists('insert_uLog')){
    function insert_uLog($data){
        $ci =& get_instance();
        $ci->load->library('adminmodel',true);
        $data['ip_address']=get_ipaddress();
        $data['createdDate']=get_curentTime();
        $data['customerId']=get_userId();
        $data['type']='customer';
        $result = $ci->adminmodel->insert_table_data("activity_log", $data);
        return $result;        
    }
}

if(!function_exists('user_notify')){
    function user_notify($data){
        $ci =& get_instance();
        $ci->load->library('adminmodel',true);
        $data['created_date']=get_curentTime();
        $data['is_read']=0;
        $result = $ci->adminmodel->insert_table_data("customer_notifications", $data);
        return $result;        
    }
}

/* admin data */
if (!function_exists('check_admin_session')) {
    function check_admin_session(){
        $ci =& get_instance();
        $ci->load->library('session');
        if($ci->session->userdata('gt_aId')==null && $ci->session->userdata('gt_isALogged')==null){
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest')
            {
                $value=array(
                    'session'=>'false'
                );          
                echo json_encode($value);
                exit();
            }else{
                redirect(base_url().'admin/login');
            }
        }
    }
}

/* delivery data */
if (!function_exists('check_delivery_session')) {
    function check_delivery_session(){
        $ci =& get_instance();
        $ci->load->library('session');
        if($ci->session->userdata('gt_dId')==null && $ci->session->userdata('gt_isdLogged')==null){
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest')
            {
                $value=array(
                    'session'=>'false'
                );          
                echo json_encode($value);
                exit();
            }else{
                redirect(base_url().'delivery/login');
            }
        }
    }
}

if (!function_exists('check_vendors_session')) {
    function check_vendors_session(){
        $ci =& get_instance();
        $ci->load->library('session');
        if($ci->session->userdata('nl_vendor_id')==null && $ci->session->userdata('nl_vendor_isLoggedIn')==null){
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest')
            {
                $value=array(
                    'session'=>'false'
                );          
                echo json_encode($value);
                exit();
            }else{
                redirect(base_url().'vendors/login');
            }
        }
    }
}

if(!function_exists('check_permission')){
    function check_permission($moduleId,$perType,$type){
        $mode = true;
        $ci =& get_instance();
        $ci->load->library('adminmodel',true);
        $where = array('roleId'=>get_admnRole(),'moduleId'=>$moduleId,$perType=>1);
        $result = $ci->adminmodel->get_table_data("admin_permissions", $where,'',$mode);
        $num_rows = $result->num_rows();
        if($num_rows == 0){
            if($type=="json"){
                $value=array(
                    'status' =>"fail",
                    'msg'=>"You don't have permission.\nPlease contact your admininstrator to request access."
                );
                echo json_encode($value);
                exit;
            }else{                
                redirect(base_url().'admin/permission');
            }
        }else{
            return true;
            exit;
        }
    }
}


if (!function_exists('is_admin')) {    
    function is_admin()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_isadmin');            
    }    
}

if (!function_exists('get_adminId')) {    
    function get_adminId()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_aId');            
    }    
}

if (!function_exists('get_aUname')) {    
    function get_aUname()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_aUname');            
    }    
}

if (!function_exists('is_aLogged')) {    
    function is_aLogged()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_isALogged');            
    }    
}

if (!function_exists('is_dLogged')) {    
    function is_dLogged()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_isdLogged');            
    }    
}

if (!function_exists('is_vLogged')) {    
    function is_vLogged()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('nl_vendor_isLoggedIn');            
    }    
}

if (!function_exists('get_admnRole')) {    
    function get_admnRole()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_role');            
    }    
}


/* user data */

if (!function_exists('check_user_session')) {
    function check_user_session(){
        $ci =& get_instance();
        $ci->load->library('session');
        if($ci->session->userdata('gt_uId')==null && $ci->session->userdata('gt_isULogged')==null){
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest')
            {
                $value=array(
                    'session'=>'false'
                );          
                echo json_encode($value);
                exit();
            }else{
                redirect(base_url().'admin/login');
            }
        }
    }
}


if (!function_exists('is_uLogged')) {    
    function is_uLogged()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_isULogged');            
    }    
}

if (!function_exists('get_userId')) {    
    function get_userId()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_uId');            
    }    
}

if (!function_exists('get_uMobile')) {    
    function get_uMobile()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_uMobile');            
    }    
}

if (!function_exists('get_userName')) {    
    function get_userName()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_uName');            
    }    
}

if (!function_exists('get_uFullName')) {    
    function get_uFullName()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_uFullName');            
    }    
}

if (!function_exists('get_uEmail')) {    
    function get_uEmail()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_uEmail');            
    }    
}


/* cart order data */

if (!function_exists('is_orderTotal')) {    
    function is_orderTotal()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_orderTotal');            
    }    
}

if (!function_exists('is_orderAddr')) {    
    function is_orderAddr()
    {
        $ci =& get_instance();
        $ci->load->library('session');
        return $ci->session->userdata('gt_orderaddrId');            
    }    
}

 if (!function_exists('setRemember')) {
    function setRemember($remember,$uname,$pwd){
        if(!empty($remember)){
            if(trim($remember)=='on') {
            $year = time() + 3600;  
            }
            else if($remember=="") {            
            $year = time() - 3600;
            }
        }
        setcookie('user_remember_me', $uname, $year);
        setcookie('user_password', $pwd, $year);
    }
}

// if (!function_exists('get_ipaddress')) {    
//     function get_ipaddress()
//     {
//         return isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];  
//     }    
// }



if (!function_exists('get_ipaddress')) {
    function get_ipaddress()
    {
        return isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
    }
}


if (!function_exists('get_curentTime')) {    
    function get_curentTime()
    {   
        date_default_timezone_set('Asia/Kolkata');
        $date=date('Y-m-d H:i:s');
        //$dateFormat="%Y-%m-%d %H:%i:%s";
        //return mdate($dateFormat,strtotime(trim($date)));
        return $date;
    }    
}


if (!function_exists('getMyDbDate')) {   
    function getMyDbDate($dateFormat,$date){        
        return mdate($dateFormat,strtotime(trim($date)));
    }
}

if (!function_exists('withErrors')) {
    function withErrors($msg,$result=""){
         $value=array(
                'status'=>"fail",
                'result' =>$result,
                'msg'=>$msg,
                );
        return $value;
    }
}
if (!function_exists('withSuccess')) {
    function withSuccess($msg,$result=""){
         $value=array(
            'status'=>"success",
            'result' => $result,
            'msg'=>$msg,
            );
        return $value;
    }
}

if (!function_exists('display_error')) {
    function display_error($msg){
        $result = "fail";
        $value = array(
            'status'=>"fail",
            'msg' => $msg,
            'result' => $result
        );            
        echo json_encode($value);
        exit;
    }
}

if (!function_exists('main_url')) {
	function main_url(){
    	$main_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
        $main_url .= '://'. $_SERVER['HTTP_HOST']."/";
    	return $main_url;
    }

}
if(!function_exists('write_excel_data')){   
    function write_excel_data($txt){
        $ci =& get_instance();
        $filename = $ci->session->userdata('adminId')."-newfile.txt";
        $myfile = fopen( getcwd()."/ui/data_read/$filename", "w") or die("Unable to open file!");

        //$this->excel_data = $txt;
        fwrite($myfile, $txt);
    }   
}
/************************************************CCAvenue******************************************************************************/

function decrypt($encryptedText, $key)
{
    $key           = hextobin(md5($key));
    $initVector    = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
    $encryptedText = hextobin($encryptedText);
    $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
    return $decryptedText;
}
/**
 * Function to encrypt
 * @param $plainText string
 * @param $key string
 * @return string
 */
function encrypt($plainText, $key)
{
    $key           = hextobin(md5($key));
    $initVector    = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
    $openMode      = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
    $encryptedText = bin2hex($openMode);
    return $encryptedText;
}

//*********** Padding Function *********************

function pkcs5_pad($plainText, $blockSize)
{
    $pad = $blockSize - (strlen($plainText) % $blockSize);
    return $plainText . str_repeat(chr($pad), $pad);
}

//********** Hexadecimal to Binary function for php 4.0 version ********

function hextobin($hexString)
{
    $length    = strlen($hexString);
    $binString = "";
    $count     = 0;
    while ($count < $length) {
        $subString    = substr($hexString, $count, 2);
        $packedString = pack("H*", $subString);
        if ($count == 0) {
            $binString = $packedString;
        } else {
            $binString .= $packedString;
        }
        
        $count += 2;
    }
    return $binString;
}


// sentence teaser
function word_teaser($string, $count){
  $original_string = $string;
  $words = explode(' ', $original_string);
 
  if (count($words) > $count){
   $words = array_slice($words, 0, $count);
   $string = implode(' ', $words);
  }
  return $string;
}

function string_teaser($string,$count){
    $string = strip_tags($string); $string = trim($string);
    if(strlen($string)<$count+1){
        $res = $string. " ".str_repeat("&nbsp;",$count-strlen($string));
    }else{

        $res = substr($string, 0, $count)."...";
    }
    return $res;
}

function filename_teaser($string,$count,$ext){
    $string = strip_tags($string); $string = trim($string);
    if(strlen($string)<$count+1){
        $res = $string.$ext;
    }else{

        $res = substr($string, 0, $count)."...".$ext;
    }
    return $res;
}

function moneyFormat($num) {
    $explrestunits = "" ;
    if(strlen($num)>3) {
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if($i==0) {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}


if(!function_exists('currencyWords')){

function currencyWords($number) {
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;    
    $digits_length = strlen($no);    
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }    
    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? "And " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])  : '';
    return ($Rupees ? 'Rupees ' . $Rupees : '') . ($paise ? $paise . ' Paise ' : '') . " Only";
}

}

function file_download($filepath){
	  if(file_exists($filepath)) {

            header('Content-Description: File Transfer');

            header('Content-Type: application/octet-stream');

            header('Content-Disposition: attachment; filename="'.basename($filepath).'"');

            header('Expires: 0');

            header('Cache-Control: must-revalidate');

            header('Pragma: public');

            header('Content-Length: ' . filesize($filepath));

            flush(); // Flush system output buffer

            readfile($filepath);

            exit;

        }
	
}

 function check_imageformat($str) {
        $allowed_mime_type_arr = array('application/octet-stream','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.ms-excel', 'application/msword', 'application/x-zip');
        $ci =& get_instance();
        $mime = get_mime_by_extension($_FILES['file']['name']);
        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
            if (in_array($mime, $allowed_mime_type_arr)) {
                return true;
            } else {
                $ci->form_validation->set_message('check_imageformat', 'Please select only /gif/jpg/png file.');
                return false;
            }
        } else {
            $ci->form_validation->set_message('check_imageformat', 'Please choose a file to upload.');
            return false;
        }
    }


	 function curl_handler($url,$fields_string="",$POST=array()){
		  // $key_id = RAZOR_KEY_ID;
        //$key_secret = RAZOR_KEY_SECRET;
		   //cURL Request
        $ch = curl_init();
        //set the url, number of POST vars, POST data
       if($POST==0){
		 curl_setopt($ch, CURLOPT_URL, $url.'?'.$fields_string ); //Url together with parameters
		 
	   }else{
		 curl_setopt($ch, CURLOPT_URL, $url ); //Url together with parameters   
		     curl_setopt($ch, CURLOPT_POST, $POST);
			 if(!empty($fields_string)){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		}
	   }
     //   curl_setopt($ch, CURLOPT_USERPWD, $key_id.':'.$key_secret);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    
		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).'/ca-bundle.crt');
		
		  $result = curl_exec($ch);
		 $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		 if($result==false){
			 $response = 'Curl error: '.curl_error($ch);
		 }else{
			  $response= json_decode($result, true);
		 }
		
		 return $response;
		
	}

	function humanTiming ($time)
    {
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }

	
function email_content_replace($template,$data){	
	if (preg_match_all("/{{(.*?)}}/", $template, $m)) {
        foreach ($m[1] as $i => $varname) {
            $template = str_replace($m[0][$i], sprintf('%s', $data[$varname]), $template);
        }
    }
	return $template;
}

function send_email($templateName,$mailData){
    $ci =& get_instance();
    $ci->load->model("adminmodel", "", true);
    $where = array('settingId' => 1 );
    $settings=$ci->adminmodel->get_table_data('company_settings',$where,'*',true);
    $mailData['companyAddress']=$settings->row()->address;
    $mailData['companyPhone']=$settings->row()->phone;
    $mailData['companyMobile']=$settings->row()->mobile;
    $mailData['companyEmail']=$settings->row()->email;
    $mailData['companyWebsite']=$settings->row()->website;
    $content  = $ci->load->view('email-template/'.$templateName, $mailData, TRUE); 
    $from='';
    $fromText='';
    $user_cc='';
    if(isset($mailData['attachment'])){
        $attachment=$mailData['attachment'];
    }else{
        $attachment='';
    }
    if(isset($mailData['attachmentName'])){
        $attachmentName=$mailData['attachmentName'];
    }else{
        $attachmentName='';
    }

    $mailsend = $ci->admin->sendmail($from, $fromText, $mailData['mailTo'], $user_cc, $mailData['mailSubject'], $content,$attachment,$attachmentName);             
    return $mailsend;   
}


if (!function_exists('cur_datetime')) {    
    function cur_datetime(){
        $date=date('Y-m-d H:i:s');
        return $date;
    }    
}



if(!function_exists('custom_date')){
    function custom_date($format,$originalDate){
      $newDate = date($format, strtotime($originalDate));
      return $newDate;
    }
  }
  


  function lookupsFetcher($id){
    $lookups = array();
    $lookupsdata = $this->curl->execute("lookups", "GET", array('perpage'=>1000)); 
    if($lookupsdata['status']=='success' && !empty($lookupsdata['data_list'])){
       $lookups = $lookupsdata['data_list'];
    }
       if(!empty($lookups)){
          foreach($lookups as $lookup){
             if($lookup['id']==$id){
                return $lookup;
             }
          }
       }else{
          print_r("LOOKUPS NOT FOUND");
       }
  }



  function create_underscore_slug($string)
{
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '_', $string);
    return strtolower($slug);
}



function pay_gateway_chars_slug($string, $limit = 0)
{
    $slug = preg_replace('/[^\da-z ]/i', '', $string);
    if ($limit > 0) {
        $slug = substr($slug, 0, $limit);
    }
    return $slug;
}

function checkValidity($inputDatetime) {
    $inputTimestamp = strtotime($inputDatetime);
    $thirtyDaysAgo = strtotime('-30 days');
    if ($inputTimestamp <= $thirtyDaysAgo) {
        return "expired";
    } else {
        return "active";
    }
}



if (!function_exists('customErrors')) {
    function customErrors($msg,$result=""){
         $value=array(
                'status'=>"fail",
                'result' =>$result,
                'message'=>$msg,
                );
        return $value;
    }
}
if (!function_exists('customSuccess')) {
    function customSuccess($msg,$result=""){
         $value=array(
            'status'=>"success",
            'result' => $result,
            'message'=>$msg,
            );
        return $value;
    }
}


function generateRandom() {
    $min = 1000000; // Smallest 10-digit number
    $max = 9999999; // Largest 10-digit number

    return rand($min, $max);
}
