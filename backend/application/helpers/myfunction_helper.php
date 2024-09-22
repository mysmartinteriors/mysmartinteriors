<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


function in_array_any($needles, $haystack)
{
	return (bool) array_intersect($needles, $haystack);
}


function generate_base64($Url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $Url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13');
	$output = curl_exec($ch);
	curl_close($ch);
	return ('data:image/png;base64, ' . base64_encode($output));
}

function image_base64($Url)
{
	$str = 'data:image/png;base64, ' . base64_encode(file_get_contents($Url));
	return $str;
}

function randomCodenum($num)
{
	$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < $num; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}

function replace_carriage_return($string)
{

	//$text=ltrim($string,"\r\n");
	//$text = preg_replace('~[\r\n]+~', ' ', $string);
	//$text= str_replace("\r\n\t", 'a', $string);

	//$text="<pre>"; 
	//$text=json_encode(json_decode($string), JSON_PRETTY_PRINT); 

	$info = str_replace(array("\r\n", "\r\n\t", "\r\n\t\/", "\n", "\r"), "", $string);
	$text = json_decode($info);
	return $text;
}


function format_json($json)
{
	$indents = 0;
	$output = '';
	$inside = false;
	for ($i = 0, $j = strlen($json); $i < $j; $i++) {
		$char = $json[$i];
		if ($char == '{' || $char == '[') {
			if (!$inside) {
				$indents += 3;
				$output .= $char . "\n" . space($indents);
			} else {
				$output .= $char;
			}
		} elseif ($char == ',') {
			if (!$inside) {
				$output .= $char . "\n" . space($indents);
			} else {
				$output .= $char;
			}
		} elseif ($char == ':') {
			if (!$inside) {
				$output .= $char . " ";
			} else {
				$output .= $char;
			}
		} elseif ($char == '}' || $char == ']') {
			if (!$inside) {
				$indents -= 3;
				$output .= "\n" . space($indents) . $char;
			} else {
				$output .= $char;
			}
		} elseif ($char == '"') {
			if ($inside) {
				$inside = false;
			} else {
				$inside = true;
			}
			$output .= $char;
		} else {
			$output .= $char;
		}
	}
	$output = str_replace('\/', '/', $output);
	return $output;
}
/**
 * Returns a string containing a given number of spaces. Used by the format_json function.
 *
 * @param integer $x The number of spaces to return
 * @return string A given number of spaces.
 *
 * @author Edmund Gentle (https://github.com/edmundgentle)
 */
function space($x)
{
	$output = '';
	for ($y = 1; $y <= $x; $y++) {
		$output .= ' ';
	}
	return $output;
}

function json_entities($data = null)
{
	//stripslashes
	return str_replace(
		"\r",
		"\n",
		"\\" . "\\n",
		htmlentities(
			utf8_encode(json_encode($data)),
			ENT_QUOTES | ENT_IGNORE,
			'UTF-8'
		)
	);
}

function print_array($array)
{
	echo '<pre>';
	print_r($array);
	echo '<pre>';
}
function test_print($item, $key)
{
	echo "$key holds $item\n";
	echo '<br/>';
}
function get_domain_from_url($url)
{
	$pieces = parse_url($url);
	$domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
	if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
		return $regs['domain'];
	}
	return false;
}

function array_flatten($array)
{
	if (!is_array($array)) {
		return FALSE;
	}
	$result = array();
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$result = array_merge($result, array_flatten($value));
		} else {
			$result[$key] = $value;
		}
	}
	//print_r($result);
	if (!empty($result['status']) && strtolower($result['status']) == 'filed') {
		$result['status'] = 'success';
	}
	return $result;
}


if (!function_exists('FontAwesomeIcon')) {

	function FontAwesomeIcon($mime_type)
	{
		// List of official MIME Types: http://www.iana.org/assignments/media-types/media-types.xhtml
		static $font_awesome_file_icon_classes = [
		// Images
		'image' => 'fa-file-image',
		// Audio
		'audio' => 'fa-file-audio',
		// Video
		'video' => 'fa-file-video',
		// Documents
		'application/pdf' => 'fa-file-pdf',
		'application/msword' => 'fa-file-word',
		'application/vnd.ms-word' => 'fa-file-word',
		'application/vnd.oasis.opendocument.text' => 'fa-file-word',
		'application/vnd.openxmlformats-officedocument.wordprocessingml' => 'fa-file-word',
		'application/vnd.ms-excel' => 'fa-file-excel',
		'application/vnd.openxmlformats-officedocument.spreadsheetml' => 'fa-file-excel',
		'application/vnd.oasis.opendocument.spreadsheet' => 'fa-file-excel',
		'application/vnd.ms-powerpoint' => 'fa-file-powerpoint',
		'application/vnd.openxmlformats-officedocument.presentationml' => 'ffa-file-powerpoint',
		'application/vnd.oasis.opendocument.presentation' => 'fa-file-powerpoint',
		'text/plain' => 'fa-file-alt',
		'text/html' => 'fa-file-code',
		'application/json' => 'fa-file-code',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fa-file-word',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa-file-excel',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'fa-file-powerpoint',
		// Archives
		'application/gzip' => 'fa-file-archive',
		'application/zip' => 'fa-file-archive',
		'application/x-zip-compressed' => 'fa-file-archive',
		// Misc
		'application/octet-stream' => 'fa-file-archive',
		];

		if (isset($font_awesome_file_icon_classes[$mime_type]))
			return $font_awesome_file_icon_classes[$mime_type];

		$mime_group = explode('/', $mime_type, 2)[0];
		return (isset($font_awesome_file_icon_classes[$mime_group])) ? $font_awesome_file_icon_classes[$mime_group] : 'fa-file';
	}

}


function my_icon($file_mime)
{

	return '<i class="fa ' . FontAwesomeIcon($file_mime) . '"></i>';
}

function get_domain()
{
	$pos = strpos($_SERVER['SERVER_NAME'], 'www');
	if ($pos === 0) {
		$domain = str_replace("www.", "", $_SERVER['SERVER_NAME']);
	} else {
		$domain = $_SERVER['SERVER_NAME'];
	}
	return $domain;
}
function wordlimit($string, $length = 40, $ellipsis = "...")
{
	$string = strip_tags($string, '<div>');
	$string = strip_tags($string, '<p>');
	$words = explode(' ', $string);
	if (count($words) > $length)
		return implode(' ', array_slice($words, 0, $length)) . $ellipsis;
	else
		return $string;
}



function create_slug($string)
{
	$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
	return strtolower($slug);
}

function underscore_slug($str, $delimiter = '_')
{
	$slug = trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter);
	return $slug;
}


function createSlug($str, $delimiter = '-')
{
	$slug = trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter);
	return $slug;
}

if (!function_exists('makeDirectory')) {
	function makeDirectory($new_path, $mode = 777)
	{
		if (!file_exists($new_path)) {
			mkdir($new_path, $mode, true);
		}
		return true;
	}
}

if (!function_exists('delete_file')) {
	function delete_file($new_path)
	{
		if (file_exists($new_path)) {
			unlink($new_path);
		}
		return true;
	}
}

if (!function_exists('cur_date')) {
	function cur_date()
	{
		return date('Y-m-d');
	}
}
if (!function_exists('cur_date_time')) {
	function cur_date_time()
	{
		return date('Y-m-d H:i:s');
	}
}

if (!function_exists('custom_date')) {
	function custom_date($format, $originalDate)
	{
		$newDate = date($format, strtotime($originalDate));
		return $newDate;
	}

}


function formatSizeUnits($bytes)
{
	if ($bytes >= 1073741824) {
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	} elseif ($bytes >= 1048576) {
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	} elseif ($bytes >= 1024) {
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	} elseif ($bytes > 1) {
		$bytes = $bytes . ' bytes';
	} elseif ($bytes == 1) {
		$bytes = $bytes . ' byte';
	} else {
		$bytes = '0 bytes';
	}

	return $bytes;
}

if (!function_exists('get_extension_by_type')) {
	function get_extension_by_type($type)
	{
		if ($type == "image") {
			$extension = unserialize(image_extension);
		} else if ($type == "video") {
			$extension = unserialize(video_extension);
		} else {
			$extension = array();
		}

		return $extension;
	}
}
if (!function_exists('withErrors')) {
	function withErrors($msg, $result = "")
	{
		$value = array(
			'status' => "fail",
			'result' => $result,
			'message' => $msg,
		);
		return $value;
	}
}
if (!function_exists('withSuccess')) {
	function withSuccess($msg, $result = "")
	{
		$value = array(
			'status' => "success",
			'result' => $result,
			'message' => $msg,
		);
		return $value;
	}

}


if (!function_exists('getRealIpAddr')) {
	function getRealIpAddr()
	{
		$ip = '';
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if (!empty($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = "";
		}
		return $ip;
	}
}


if (!function_exists('form_validation_error')) {

	function form_validation_error()
	{
		$ci =& get_instance();
		$post = $ci->input->post();
		$errors = $ci->functions->ciError($post);
		$value = array(
			'result' => $errors,
			'status' => 'error'
		);

		echo json_encode($value);
		exit;
	}
}



if (!function_exists('main_url')) {
	function main_url()
	{
		$main_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
		$main_url .= '://' . $_SERVER['HTTP_HOST'] . "/";
		return $main_url;
	}

}

/************************************************CCAvenue******************************************************************************/

function decrypt($encryptedText, $key)
{
	$key = hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
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
	$key = hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	$encryptedText = bin2hex($openMode);
	return $encryptedText;
}

/*
function encrypt($plainText, $key) {
$secretKey = hextobin(md5($key));
$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
$plainPad = pkcs5_pad($plainText, $blockSize);
if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1) {
$encryptedText = mcrypt_generic($openMode, $plainPad);
mcrypt_generic_deinit($openMode);
}
return bin2hex($encryptedText);
}

function decrypt($encryptedText, $key) {
$secretKey = hextobin(md5($key));
$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
$encryptedText = hextobin($encryptedText);
$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
mcrypt_generic_init($openMode, $secretKey, $initVector);
$decryptedText = mdecrypt_generic($openMode, $encryptedText);
$decryptedText = rtrim($decryptedText, "\0");
mcrypt_generic_deinit($openMode);
return $decryptedText;
}*/

//*********** Padding Function *********************

function pkcs5_pad($plainText, $blockSize)
{
	$pad = $blockSize - (strlen($plainText) % $blockSize);
	return $plainText . str_repeat(chr($pad), $pad);
}

//********** Hexadecimal to Binary function for php 4.0 version ********

function hextobin($hexString)
{
	$length = strlen($hexString);
	$binString = "";
	$count = 0;
	while ($count < $length) {
		$subString = substr($hexString, $count, 2);
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
// this function will cut the string by how many words you want
function word_teaser($string, $count)
{
	$original_string = $string;
	$words = explode(' ', $original_string);

	if (count($words) > $count) {
		$words = array_slice($words, 0, $count);
		$string = implode(' ', $words);
	}

	return $string;
}


function file_download($filepath)
{
	if (file_exists($filepath)) {

		header('Content-Description: File Transfer');

		header('Content-Type: application/octet-stream');

		header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');

		header('Expires: 0');

		header('Cache-Control: must-revalidate');

		header('Pragma: public');

		header('Content-Length: ' . filesize($filepath));

		flush(); // Flush system output buffer

		readfile($filepath);

		exit;

	}

}


function create_directory($dir_name)
{
	if (empty($dir_name)) {
		$dir_name = 'uploads/' . date('m-Y') . '/';
	}
	$dir = $dir_name;
	// print_r($dir_name);exit();
	if (!file_exists($dir)) {
		mkdir($dir, 0777, true);
	} else {
		$msg = "Directory already exists!";
	}
	$newFileName = $dir . "index.html";
	if (!file_exists($newFileName)) {
		$newFileContent = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
		if (file_put_contents($newFileName, $newFileContent) !== false) {
			$msg = "File created (" . basename($newFileName) . ")";
		} else {
			$msg = "Cannot create file (" . basename($newFileName) . ")";
		}
	}
	$data = array('msg' => $msg, 'dir' => $dir);
	return $dir;
}


function download_file_url($url, $save_path, $file_name = '')
{
	$uploads_url = '';
	$result = false;
	$file_url = '';
	if (!empty($uploads_url)) {
		create_directory($uploads_url);
	}
	$newfname = $save_path . $file_name;

	$file = fopen($url, "rb");
	if ($file) {
		$newf = fopen($newfname, "wb");

		if ($newf)
			while (!feof($file)) {
				fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
			}
	}

	if ($file) {
		fclose($file);
	}

	if ($newf) {
		fclose($newf);
		$result = true;
		$file_url = $newfname;
	}
	$value = array('result' => $result, 'file_url' => $file_url);
	return $value;
}



function upload_report_file($url, $save_path, $file_name, $file_ext, $download_type)
{

	$status = 'fail';
	$file_url = '';

	$ci =& get_instance();
	$uploads_data = $ci->db->get_where('web_settings', array('data_type' => 'interface_upload_url'))->row_array();
	$uploads_url = $uploads_data['data_value'];

	$post_fields = array('file_url' => $url, 'file_folder' => $save_path, 'file_name' => $file_name, 'file_ext' => $file_ext, 'download_type' => $download_type);
	// print_r($post_fields);exit();

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
	curl_setopt($ch, CURLOPT_URL, $uploads_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	$returnGroup['info'] = curl_getinfo($ch);
	$returnGroup['errno'] = curl_errno($ch);
	$returnGroup['error'] = curl_error($ch);
	$returnGroup['http_code'] = $returnGroup['info']['http_code'];
	curl_close($ch);
	$response = json_decode($response, true);
	// print_r($response);exit();
	//print_r($returnGroup);

	// $log_file_data=array(
	// 	'username'=>'Sanchar',
	// 	'inputs'=>json_encode($post_fields),
	// 	'outputs'=>json_encode($response),
	// 	'message'=>'Helper function log for uploading file'
	// );				
	// write_log_file('Sanchar',$log_file_data);

	if (!empty($response['status']) && $response['status'] == 'success') {
		$status = 'success';
		$file_url = $response['file_url'];
	}
	$value = array('status' => $status, 'file_url' => $file_url);
	if (!empty($response['message'])) {
		$value['message'] = $response['message'];
	}
	return $value;
}

function delete_report_file($file_path)
{

	$status = 'fail';
	$file_url = '';

	$ci =& get_instance();
	$uploads_data = $ci->db->get_where('web_settings', array('data_type' => 'interface_url'))->row_array();
	$interface_url = $uploads_data['data_value'];
	$interface_url = $interface_url . 'upload_reports/delete_report_file';

	$post_fields = array('file_url' => $file_path);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
	curl_setopt($ch, CURLOPT_URL, $interface_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	$returnGroup['info'] = curl_getinfo($ch);
	$returnGroup['errno'] = curl_errno($ch);
	$returnGroup['error'] = curl_error($ch);
	$returnGroup['http_code'] = $returnGroup['info']['http_code'];
	curl_close($ch);
	$response = json_decode($response, true);

	if (!empty($response['status']) && $response['status'] == 'success') {
		$status = 'success';
		$file_url = $file_path;
	}
	$value = array('status' => $status, 'file_url' => $file_url);
	if (!empty($response['message'])) {
		$value['message'] = $response['message'];
	}
	return $value;
}

function check_imageformat($str)
{
	$allowed_mime_type_arr = array('application/octet-stream', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.ms-excel', 'application/msword', 'application/x-zip');
	$mime = get_mime_by_extension($_FILES['file']['name']);
	if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
		if (in_array($mime, $allowed_mime_type_arr)) {
			return true;
		} else {
			$this->form_validation->set_message('check_imageformat', 'Please select only /gif/jpg/png file.');
			return false;
		}
	} else {
		$this->form_validation->set_message('check_imageformat', 'Please choose a file to upload.');
		return false;
	}
}

if (!function_exists('custom_sort_by_annexure')) {
	function custom_sort_by_annexure($a, $b)
	{
		return $a['annexure'] - $b['annexure'];
	}
}



if (!function_exists('randomPassword')) {

	function randomPassword($len = 8)
	{

		//enforce min length 8
		if ($len < 8)
			$len = 8;

		//define character libraries - remove ambiguous characters like iIl|1 0oO
		$sets = array();
		$sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		$sets[] = '23456789';
		$sets[] = '~!@#$%^&*(){}[],./?';

		$password = '';

		//append a character from each set - gets first 4 characters
		foreach ($sets as $set) {
			$password .= $set[array_rand(str_split($set))];
		}

		//use all characters to fill up to $len
		while (strlen($password) < $len) {
			//get a random set
			$randomSet = $sets[array_rand($sets)];

			//add a random char from the random set
			$password .= $randomSet[array_rand(str_split($randomSet))];
		}

		//shuffle the password string before returning!
		return str_shuffle($password);
	}


}


function humanTiming($time)
{

	$time = time() - $time; // to get the time since that moment
	$time = ($time < 1) ? 1 : $time;
	$tokens = array(
		31536000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
	);

	foreach ($tokens as $unit => $text) {
		if ($time < $unit)
			continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
	}

}
function get_timing_tooltip($date)
{
	$result = humanTiming(strtotime($date)) . ' ago <span data-toggle="tooltip" data-placement="top" title="' . date("M jS, Y h:i:s: A", strtotime($date)) . '"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
	return $result;
}


function add_message_array($index_data, $message)
{
	$imp_data = implode(',<br/>', $index_data);
	$result = preg_replace('/[ ,]+/', ' ', trim($imp_data));
	$msg = $message . '<br/>' . $result;
	return $msg;
}

if(!function_exists("json_validate")){
function json_validate($string, $do_exit = '')
{
	// decode the JSON data
	$result = json_decode($string);

	// switch and check possible JSON errors
	switch (json_last_error()) {
		case JSON_ERROR_NONE:
			$error = ''; // JSON is valid // No error has occurred
			break;
		case JSON_ERROR_DEPTH:
			$error = 'The maximum stack depth has been exceeded.';
			break;
		case JSON_ERROR_STATE_MISMATCH:
			$error = 'Invalid or malformed JSON.';
			break;
		case JSON_ERROR_CTRL_CHAR:
			$error = 'Control character error, possibly incorrectly encoded.';
			break;
		case JSON_ERROR_SYNTAX:
			$error = 'Syntax error, malformed JSON.';
			break;
		// PHP >= 5.3.3
		case JSON_ERROR_UTF8:
			$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
			break;
		// PHP >= 5.5.0
		case JSON_ERROR_RECURSION:
			$error = 'One or more recursive references in the value to be encoded.';
			break;
		// PHP >= 5.5.0
		case JSON_ERROR_INF_OR_NAN:
			$error = 'One or more NAN or INF values in the value to be encoded.';
			break;
		case JSON_ERROR_UNSUPPORTED_TYPE:
			$error = 'A value of a type that cannot be encoded was given.';
			break;
		default:
			$error = 'Unknown JSON error occured.';
			break;
	}
	return $error;
}
}

function is_base64($s)
{
	// Check if there are valid base64 characters
	// if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;
	// Decode the string in strict mode and check the results
	$decoded = base64_decode($s, true);
	if (false === $decoded)
		return false;
	// Encode the string again
	if (base64_encode($decoded) != $s)
		return false;
	return true;
}

function du_uploads($path, $base64string)
{
	if (is_base64($base64string) == true) {
		$base64string = "data:image/jpeg;base64," . $base64string;
		$this &= get_instance();
		$this->check_size($base64string);
		$this->check_dir($path);
		$this->check_file_type($base64string);

		/*=================uploads=================*/
		list($type, $base64string) = explode(';', $base64string);
		list(, $extension) = explode('/', $type);
		list(, $base64string) = explode(',', $base64string);
		$fileName = uniqid() . date('d_m_Y_H_i_s') . '.' . $extension;
		$base64string = base64_decode($base64string);
		file_put_contents($path . $fileName, $base64string);
		return array('status' => true, 'message' => 'successfully upload !', 'file_name' => $fileName, 'with_path' => $path . $fileName);
	} else {
		print_r(json_encode(array('status' => false, 'message' => 'This Base64 String not allowed !')));
		exit;
	}
}


function replace_html_json($raw_json, $data_row)
{

	$oldjson = json_decode($raw_json, true);
	$accept = 0;
	$reject = 0;

	$validation = array();
	if (!empty($data_row)) {
		foreach ($data_row as $key => $val) {
			foreach ($oldjson as $i => $oVal) {
				//print_r($oldjson[$i]);
				if (!empty($oldjson[$i]['name']) && $key === $oldjson[$i]['name']) {
					// if(!empty($oldjson[$i]['dataEditable']) && $oldjson[$i]['dataEditable']!='yes'){
					// 	$reject++;
					// }else{
					if (!empty($oldjson[$i]['required']) && $oldjson[$i]['required'] == 'true' && empty($val)) {
						$reject++;
					} else {
						if (!empty($oldjson[$i]['validateExp'])) {
							if (preg_match("/^(?:'" . $oldjson[$i]['validateExp'] . "')$/", $val)) {
								$reject++;
							} else {
								$oldjson[$i]['userData'][0] = $val;
								$accept++;
							}
						} else {
							$oldjson[$i]['userData'][0] = $val;
							$accept++;
						}
					}
					// }
				}
			}
		}
	}
	$value = array('accept' => $accept, 'reject' => $reject, 'json_data' => json_encode($oldjson));
	return $value;
}


function get_json_key_value($json_data)
{
	if (!empty($json_data)) {
		$data = json_decode($json_data, true);
		$flat_data = array_flatten($data);

		$final_data = array();
		if (array_key_exists('type', $flat_data) && array_key_exists('label', $flat_data) && array_key_exists('name', $flat_data)) {
			foreach ($data as $row) {
				if (isset($row['userData'])) {
					if (isset($row['dateFormat'])) {
						$final_data[$row['name']] = custom_date($row['dateFormat'], implode($row['userData']));
					} else {
						$final_data[$row['name']] = implode($row['userData']);
					}
				}
			}
		} else {
			foreach ($data as $k => $v) {
				if (is_array($v)) {
					$final_data += array_flatten($v);
				} else {
					$final_data[$k] = $v;
				}
			}
		}
		return $final_data;
	}
}

function get_custom_json_key_value($json_data)
{
	$final_data = array();
	if (!empty($json_data)) {
		$data = json_decode($json_data, true);
		foreach ($data as $row) {
			if (isset($row['userData']) && isset($row['label_type']) && $row['label_type'] == 'custom') {
				$final_data[$row['name']] = $row['userData'][0];
			}
		}
	}
	return $final_data;
}


function get_json_key_name_value($json_data)
{
	if (!empty($json_data)) {
		$data = json_decode($json_data, true);
		$flat_data = array_flatten($data);

		$exclude_arr = array('client_id', 'status', 'http_code', 'date_of_verification');

		$final_data = array();
		if (array_key_exists('type', $flat_data) && array_key_exists('label', $flat_data) && array_key_exists('name', $flat_data)) {
			foreach ($data as $row) {
				if (isset($row['userData'])) {
					if (isset($row['dateFormat'])) {
						$final_data[] = array(
							'label' => ucwords($row['label']),
							'name' => $row['name'],
							'value' => custom_date($row['dateFormat'], implode($row['userData']))
						);
						$final_data[] = array($row['name'] => v);
					} else {
						$final_data[] = array(
							'label' => ucwords($row['label']),
							'name' => $row['name'],
							'value' => implode($row['userData'])
						);
					}
				}
			}
		} else {
			foreach ($data as $k => $v) {
				if (is_array($v)) {
					$final_data[] += array_flatten($v);
				} else {
					if (!in_array($k, $exclude_arr)) {
						$final_data[] = array(
							'label' => ucwords(str_replace('_', ' ', $k)),
							'name' => $k,
							'value' => $v
						);
					}
				}
			}
		}
		return $final_data;
	}
}

function htmlToPlainText($str)
{
	$str = str_replace('&nbsp;', ' ', $str);
	$str = html_entity_decode($str, ENT_QUOTES | ENT_COMPAT, 'UTF-8');
	$str = html_entity_decode($str, ENT_HTML5, 'UTF-8');
	$str = html_entity_decode($str);
	$str = htmlspecialchars_decode($str);
	$str = strip_tags($str);

	return $str;
}


function write_log_file($service_name, $data)
{
	$file_path = 'logs/' . create_slug($service_name) . '/';
	create_directory($file_path);
	$file_url = $file_path . 'log-' . date('d-m-Y') . '.txt';
	$contents = 'Time => ' . date('d-M-Y h:i:s A', strtotime(cur_date_time()));
	$contents .= ' | IP =>' . getRealIpAddr();


	if (!empty($data['message'])) {
		$contents .= ' | Title => ' . $data['message'];
	}

	// print_r($data);
	if (!empty($data['username'])) {
		$contents .= ' | Username => ' . $data['username'];
	}
	if (!empty($data['batch_id'])) {
		$contents .= ' | Batch id => ' . $data['batch_id'];
	}
	if (!empty($data['check_id'])) {
		if (is_array($data['check_id'])) {
			$contents .= ' | ' . count($data['check_id']) . ' Check ids=' . implode(',', $data['check_id']);
		} else {
			$contents .= ' | Check id => ' . $data['check_id'];
		}
	}
	if (!empty($data['check_code'])) {
		$contents .= ' | Check code => ' . $data['check_code'];
	}
	if (!empty($data['profile_id'])) {
		$contents .= ' | Profile id => ' . $data['profile_id'];
	}
	if (!empty($data['profile_code'])) {
		$contents .= ' | Profile code => ' . $data['profile_code'];
	}
	if (!empty($data['profile_name'])) {
		$contents .= ' | Profile name => ' . $data['profile_name'];
	}
	if (!empty($data['inputs'])) {
		// if(is_array($data['inputs'])){
		$contents .= ' | Input => ' . htmlToPlainText($data['inputs']);
		// }else{
		// 	$contents.=' | Input => '.$data['inputs'];
		// }
	}
	if (!empty($data['outputs'])) {
		// if(is_array($data['outputs'])){
		$contents .= ' | Output => ' . htmlToPlainText($data['outputs']);
		// }else{
		// 	$contents.=' | Outputs =>'.$data['outputs'];
		// }
	}
	if (!empty($data['status'])) {
		$contents .= ' | Status => ' . $data['status'];
	}
	$contents .= PHP_EOL;
	$myfile = file_put_contents($file_url, $contents . PHP_EOL, FILE_APPEND | LOCK_EX);
}


function download_base64_file($base64_code, $path, $file_name, $check_file_type = true)
{
	//if(is_base64($base64string) == true){  
	//$base64string = "data:image/jpeg;base64,".$base64string;
	//$this->check_size($base64string);

	if (($pos = strpos($base64_code, ",")) !== FALSE) {
		$base64_code = substr($base64_code, $pos + 1);
	}
	$base64string = base64_decode($base64_code);
	create_directory($path);

	if ($check_file_type) {
		check_file_type($base64string);
	}

	if (file_put_contents($path . $file_name, $base64string) !== false) {
		return array('status' => 'success', 'message' => 'Successfully uploaded!', 'file_name' => $file_name, 'file_url' => $path . $file_name);
	} else {
		return array('status' => 'fail', 'message' => 'Upload failed!');
	}
	//}else{
	// return array('status' =>'fail','message' => 'This Base64 String is invalid !');
	//}
}


function check_file_type($base64string)
{
	$mime_type = @mime_content_type($base64string);
	$allowed_file_types = ['image/png', 'image/jpeg', 'application/pdf', 'application/x-zip-compressed', 'application/zip'];
	if (!in_array($mime_type, $allowed_file_types)) {
		// File type is NOT allowed
		print_r(json_encode(array('status' => false, 'message' => 'File type is NOT allowed !')));
		exit;
	}
	return true;
}


function get_date_difference($date1, $date2, $return_type = '')
{
	$start = new DateTime($date1);
	$end = new DateTime($date2);
	$interval = $start->diff($end);

	$default = (($interval->format('%d') * 24) + $interval->format('%h')) * 60 + $interval->format('%i');

	if (!empty($return_type)) {
		switch ($return_type) {
			case 'hours':
				$value = $interval->format('%h') * 60;
				break;
			case 'days':
				$value = (($interval->format('%d') * 24) + $interval->format('%h')) * 60 + $interval->format('%i');
				break;
			case 'months':
				$value = $interval->format('%m');
				break;
			case 'years':
				$value = $interval->format('%y');
				break;
			default:
				$value = $default;
				break;
		}
	} else {
		$value = $default;
	}
	return $value;
}

function get_interface_url()
{
	$ci =& get_instance();
	$uploads_data = $ci->db->get_where('web_settings', array('data_type' => 'interface_url'))->row_array();
	$uploads_url = $uploads_data['data_value'] . '/';
	return $uploads_url;
}

if (!function_exists('get_micro_time')) {
	function get_micro_time()
	{
		$mt = explode(' ', microtime());
		return intval($mt[1] * 1E6) + intval(round($mt[0] * 1E6));
	}
}


if (!function_exists('json_value_by_key')) {
	function json_value_by_key($table_name, $key, $column_name, $id, $asValue)
	{
		$ci =& get_instance();
		$q = "SELECT JSON_UNQUOTE(JSON_EXTRACT(" . $column_name . ", '$[0].userData[0]')) AS " . $asValue . " FROM " . $table_name . " WHERE id = $id";
		$res = $ci->db->query($q)->row_array();
		$value = '';
		if (!empty($res['display_name'])) {
			$value = $res['display_name'];
		}
		return $value;
	}
}

if (!function_exists('sort_components')) {
	function sort_components($components, $sortKey)
	{
		// Custom comparison function for usort
		$compare = function ($a, $b) use ($sortKey) {
			return $a[$sortKey] <=> $b[$sortKey];
		};

		// Perform the sorting using usort
		usort($components, $compare);

		// Return the sorted $components array
		return $components;
	}
}

function get_backend_url()
{
	$ci =& get_instance();
	$get_data = $ci->db->get_where('web_settings', array('data_type' => 'backend_url'))->row_array();
	$url = $get_data['data_value'] . '/';
	return $url;
}


if (!function_exists('get_micro_time')) {
	function get_micro_time()
	{
		$mt = explode(' ', microtime());
		return intval($mt[1] * 1E6) + intval(round($mt[0] * 1E6));
	}
}


function delete_files_folder($folder)
{
	if (file_exists($folder)) {
		if (is_dir($folder)) {
			foreach (scandir($folder) as $item) {
				if ($item == '.' || $item == '..') {
					continue;
				}
				if (!unlink($folder . DIRECTORY_SEPARATOR . $item)) {
					return false;
				}
			}
		}
	}
	rmdir($folder);
}

if (!function_exists('getMyDbDate')) {
	function getMyDbDate($dateFormat, $date)
	{
		return mdate($dateFormat, strtotime(trim($date)));
	}
}


// send_sms function
if (!function_exists('send_sms')) {
	function send_sms($toMobile, $message)
	{
		$guid = '';
		$errorcode = '';
		$result = 0;
		$status = array();
		if (!empty($toMobile)) {
			$ci =& get_instance();
			$ci->load->library('Mydb', true);
			$sWhere = array('id' => 1);
			$settings = $ci->Mydb->get_table_data('sms_settings', $sWhere, '*', true);
			$raw_msg = $message;

			$username = $settings->row()->username;
			$password = $settings->row()->password;
			$senderId = $settings->row()->senderId;
			$gateway_url = $settings->row()->gateway_url;
			$message = urlencode($message);
			if (is_array($toMobile)) {
				$toMobile = implode(',', $toMobile);
			}
			$sms_apikey = "af10cc-a3a34d-1cb69c-cb6b12-0fbf70";
			$msgURL = "$gateway_url?username=$username&password=$password&to=$toMobile&from=$senderId&udh=&text=$message&dlr-mask=19&dlr-url&category=bulk";
			// print_R($msgURL);exit();
			$user_agents = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';

			$_h = curl_init();
			$headers = array();
			curl_setopt($_h, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($_h, CURLOPT_HTTPGET, 1);
			curl_setopt($_h, CURLOPT_URL, $msgURL);
			curl_setopt($_h, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($_h, CURLOPT_DNS_CACHE_TIMEOUT, 2);
			curl_setopt($_h, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($_h, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($_h, CURLOPT_USERAGENT, $user_agents);

			$html = curl_exec($_h);
			curl_close($_h);

			$response = explode('&', $html);
			// print_R($response);exit();
			$data = array();
			foreach ($response as $value) {
				$resp_array = explode('=', $value);
				if (!empty($resp_array)) {
					$data[$resp_array[0]] = $resp_array[1];
				}
			}
			if (isset($data['errorcode']) && $data['errorcode'] == 0) {
				$msg = 'SMS sent successfully';
				$result = 1;
				$guid = str_replace(' ', '', $data['guid']);
				$errorcode = $data['errorcode'];
				// update_stat_counter('sms', $raw_msg);

			} else {
				$msg = "Unable to send SMS, API error code";
				if (!empty($data['errorcode'])) {
					$msg .= " - " . $data['errorcode'];
				}
			}
		} else {
			$msg = 'Mobile number is required';
		}
		// log_message('error', print_R($response, true));
		$value = array('msg' => $msg, 'result' => $result, 'guid' => $guid);
		return $value;
	}
}


function generate_otp()
{
	$otp = '';
	$length = 6;
	$characters = '0123456789';
	$char_length = strlen($characters);

	for ($i = 0; $i < $length; $i++) {
		$otp .= $characters[rand(0, $char_length - 1)];
	}
	return $otp;
}



if (!function_exists('get_ipaddress')) {
	function get_ipaddress()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip is pass from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}

function send_whatsapp($toMobile, $message, $customerId='', $reference_module='', $reference_table='', $reference_id='')
{
	$ci =& get_instance();
	$data_row = $ci->db->get_where('whatsapp_settings', array('id' => 1))->row_array();
	if (empty($data_row)) {
		return ['status' => 'fail', 'message' => 'Whatsapp Config Not Found'];
	}
	$baseURL = $data_row['base_url'];
	$fromMobile = $data_row['from_mobile'];
	$baseURL = 'https://103.229.250.114';
	$url = $baseURL . '/unified/v2/send?=null';
	$logData['mobile_number'] = $toMobile;
	$logData['message'] = $message;
	$logData['reference_module'] = $reference_module;
	$logData['reference_table'] = $reference_table;
	$logData['reference_id'] = $reference_id;
	$logData['customer_id'] = $customerId;
	$data = [
		"apiver" => "1.0",
		"whatsapp" => [
			"ver" => "2.0",
			"dlr" => [
				"url" => ""
			],
			"messages" => [
				[
					"coding"=> 1,
					"id"=> "",
					"msgtype"=> 1,
					"type"=> "",
					"contenttype"=> "",
					"b_urlinfo"=> "",       
					"mediadata"=> "",	  
					"templateinfo"=>"",
					"text" => $message,
					"addresses" => [
						[
							"seq" => generateRandom10DigitNumber(),
							"to" => '91'.$toMobile,
							"from" => '918762453738',
							"tag" => ""
						]
					]
				]
			]
		]
	];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Authorization: Basic bmFsYWFid2FhcGlvdGo0bG5qcXA4ZjB5Omc4cGNvbjl4M25tNzdqYnUwaWJxaWtxamRrZDV1bjVp',
		'Content-Type: application/json'
	]);
	$result = curl_exec($ch);
	$logData['response_json'] = $result;
	if (curl_errno($ch)) {
		curl_close($ch);
		$logData['http_status'] = 500;
		$logData['response_message'] = 'Whatsapp Initiation Failed, Network Error';

		update_whatsapp_sms_log($logData);
		return ['status'=>'fail', 'message'=>'Whatsapp initiation failed'];
	}else{
		curl_close($ch);
		$result = json_decode($result, true);
		$logData['guid'] = $result['messageack']['guids'][0]['guid'];
		$logData['http_status'] = $result['statuscode'];
		if(is_array($result)){
			if($result['statuscode']!=200){
				$logData['status_text'] = $result['statustext'];  
				update_whatsapp_sms_log($logData);
				$logData['response_message'] = 'The message couldn\'t be sent. Network error.';
				return ['status'=>'success', 'message'=>'The message couldn\'t be sent. Network error.'];
			}else{
				$logData['status_text'] = $result['statustext'];
				if(isset($result['messageack']) && isset($result['messageack']['guids']) && isset($result['messageack']['guids'][0]['errors'])){
					$logData['response_message'] = 'Whatsapp Returned failure status, please try again';
					update_whatsapp_sms_log($logData);
					return ['status'=>'fail', 'message'=>'Whatsapp Returned failure status, please try again'];
				}else{
					$logData['response_message'] = 'Whatsapp Message Sent Successfully';
					update_whatsapp_sms_log($logData);
					return ['status'=>'success', 'message'=>'Whatsapp Message Sent Successfully'];
				}
			}
		}else{
			$logData['http_status'] = 500;
			$logData['response_message'] = 'Couldn\'t explode the response of the api';
			update_whatsapp_sms_log($logData);
			return ['status'=>'fail', 'message'=>'Couldn\'t explode the response of the api'];
		}
	}
}

function update_whatsapp_sms_log($logData){
	if(!empty($logData)){
		$ci =& get_instance();
		$data_row = $ci->db->insert('whatsapp_logs', $logData);
	}
}

function generateRandom10DigitNumber() {
    $min = 1000000000; // Smallest 10-digit number
    $max = 9999999999; // Largest 10-digit number

    return rand($min, $max);
}

// function send_whatsapp_v2($toMobile, $message)
// {
// 	$ci =& get_instance();
// 	$data_row = $ci->db->get_where('whatsapp_settings', array('id' => 1))->row_array();
// 	if (empty($data_row)) {
// 		return ['status' => 'fail', 'message' => 'Whatsapp Config Not Found'];
// 	}
// 	$baseURL = $data_row['base_url'];
// 	$fromMobile = $data_row['from_mobile'];
// 	$curl = curl_init();
// 	$payload = json_encode([
// 		"apiver" => "1.0",
// 		"whatsapp" => [
// 			"ver" => "2.0",
// 			"dlr" => [
// 				"url" => ""
// 			],
// 			"messages" => [
// 				[
// 					"coding" => 1,
// 					"id" => "15b0cc79c0da45771662021901495",
// 					"msgtype" => 3,
// 					"type" => "image",
// 					"contenttype" => "image/png",
// 					"mediadata" => "https://sample-videos.com/img/Sample-png-image-100kb.png",
// 					"text" => $message,
// 					"addresses" => [
// 						[
// 							"seq" => "6310710c80900d37f7b99f56-20220901",
// 							"to" => $toMobile,
// 							"from" => $fromMobile,
// 							"tag" => ""
// 						]
// 					]
// 				]
// 			]
// 		]
// 	]);

// 	curl_setopt_array(
// 		$curl,
// 		array(
// 			CURLOPT_URL => $baseURL . 'unified/v2/send',
// 			CURLOPT_RETURNTRANSFER => true,
// 			CURLOPT_ENCODING => '',
// 			CURLOPT_MAXREDIRS => 10,
// 			CURLOPT_TIMEOUT => 0,
// 			CURLOPT_FOLLOWLOCATION => true,
// 			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 			CURLOPT_CUSTOMREQUEST => 'POST',
// 			CURLOPT_POSTFIELDS => $payload,
// 			CURLOPT_HTTPHEADER => array(
// 				$data_row['auth_val'],
// 				'Content-Type: application/json'
// 			),
// 		)
// 	);
// 	$response = curl_exec($curl);
// 	// print_r($response);exit();
// 	curl_close($curl);
// 	echo $response;
// }

function send_whatsapp_old($toMobile, $message)
{
	$token = '64365d39a2a7b751e885f039';
	$ci =& get_instance();
	$data = array();
	$data['status'] = 'fail';
	$data['sms_guid'] = '';
	$data['step_name'] = 'opt_verification';
	$data['created_type'] = 'system';
	$data['ip_address'] = get_ipaddress();
	$url = 'http://www.truebulksms.co.in/api/sendText';
	// Remove non-digit characters and ensure 10-digit number
	$cleanedMobile = preg_replace('/\D/', '', $toMobile);
	if (strlen($cleanedMobile) > 10) {
		$cleanedMobile = substr($cleanedMobile, -10); // Keep last 10 digits
	}
	$params = array('token' => $token, 'phone' => '91' . $cleanedMobile, 'message' => $message);
	$getResponse = $ci->curl->apicall($url, 'GET', '', $params);
	// print_r($getResponse);exit();
	if ($getResponse['status'] == 'success' && isset($getResponse['result'])) {
		if (isset($data['whatsapp_messageIDs'])) {
			$data['whatsapp_guid'] = isset($getResponse['result']['messageIDs']) ? $getResponse['result']['messageIDs'][0] : '';
		} else {
			$data['whatsapp_guid'] = '';
		}
		$data['status'] = 'success';
		// update_stat_counter('whatsapp', $message);
	}
	return $data;
}




// function validate_otp($entered_otp) {
// 	$stored_otp = $this->ci->session->userdata('otp');

// 	return false;
// }


function generate_order_id()
{
	$order_id = '';
	for ($i = 0; $i < 15; $i++) {
		$order_id .= mt_rand(0, 9);
	}
	return $order_id;
}


function generate_order_token()
{
	$order_id = '';
	for ($i = 0; $i < 6; $i++) {
		$order_id .= mt_rand(0, 9);
	}
	return $order_id;
}

function getCurrentMonthDateRange()
{
	$currentYear = date('Y');
	$currentMonth = date('m');
	$startDate = date('Y-m-01', strtotime("$currentYear-$currentMonth-01"));
	$endDate = date('Y-m-t', strtotime("$currentYear-$currentMonth-01"));
	return array('start_date' => $startDate . ' 00:00:00', 'end_date' => $endDate . ' 23:59:59');
}



if (!function_exists('get_geocode')) {
    function get_geocode($address) {
        // Replace YOUR_API_KEY with your actual Google Maps API key
        $apiKey = 'AIzaSyDXL19yva1nEHaZmHLlzPh8_0CajExS_ks';
        // Prepare the address
        $address = urlencode($address);
        // Geocoding API URL
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";
        // Initialize cURL session
        $ch = curl_init();
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Execute cURL request
        $response = curl_exec($ch);
        // Close cURL session
        curl_close($ch);
        // Decode the JSON response
        $data = json_decode($response, true);
        // Check if the status is OK
        if ($data['status'] == 'OK') {
            // Extract the latitude and longitude
            $latitude = $data['results'][0]['geometry']['location']['lat'];
            $longitude = $data['results'][0]['geometry']['location']['lng'];
            // Return the coordinates
            return array('latitude' => $latitude, 'longitude' => $longitude);
        } else {
            // Return null if the geocoding was unsuccessful
            return null;
        }
    }
}


function replace_dot_with_hyphen($string)
{
    return str_replace('.', '-', $string);
}


function generateRandom() {
    $min = 1000000; // Smallest 10-digit number
    $max = 9999999; // Largest 10-digit number

    return rand($min, $max);
}
