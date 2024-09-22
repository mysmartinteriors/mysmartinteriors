<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Curl {

	protected $_ci;                 // CodeIgniter instance
	protected $response = '';       // Contains the cURL response for debug
	public $error_code;             // Error code returned as an int
	public $error_string;           // Error message returned as a string
	public $info;                   // Returned after request (elapsed time, etc)
	protected $user_agents = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
	// protected $api_base_url='http://localhost/SARTBizExcel/backend/api/'; // Place API base URL here
	protected $api_base_url='http://credapi.sartdev.in/'; // Place API base URL here
	protected $api_key = "X-API-KEY:DHICRM123";   // Populates extra HTTP headers also add API Key
 
	function __construct($url = '')
	{
		$this->_ci = & get_instance();
	}

	public function execute($url,$method,$params=array(),$returnMethod='',$debugMode=false)
	{
		$url=$this->api_base_url.$url;
		$curl = curl_init($url);
		switch ($method) {
			case 'GET':
				if(is_array($params) && !empty($params) ){
					$params = http_build_query($params, '', '&');
					$url=$url.'?'.$params;
				}
				$headers = array($this->api_key);
				break;
			case 'POST':
				$headers = array($this->api_key);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
				break;
			case 'PUT':
				$headers = array($this->api_key,"Content-Type: application/json",);
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
				break;
			case 'DELETE':
				$headers = array($this->api_key);
				break;
		}
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST,$method);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agents);
		$response = curl_exec($curl);
		$returnGroup['info'] = curl_getinfo($curl);
       	$returnGroup['errno']= curl_errno($curl);
       	$returnGroup['error']= curl_error($curl);
       	$return_data['http_code']=$returnGroup['info']['http_code'];
		curl_close($curl);


		$api_json=json_decode($response,true); 

		if($debugMode==true){
			$return_data=array('status'=>'fail','message'=>'Debug mode enabled','url'=>$url,'result'=>$response);
			return $return_data;
			exit;
		}
		//print_r($api_json['result']);

    	

	    $return_data['status']='fail';
	    $return_data['message']='Api call error!';
	    $return_data['data_list']='';
	    $return_data['data_count']=0;
	    $return_data['error_result']='';
	    //$return_data['pagination_data']='';

	    if(!empty($api_json['message'])){
	        $return_data['message']=$api_json['message'];
	    } 

	    if(isset($api_json['status'])){
	        $return_data['status']=$api_json['status'];
	        if($api_json['status']=='error'){
	        	if(isset($api_json['result']) && !empty($api_json['result'])){
		        	$return_data['message']=is_array($api_json['result']) ? implode($api_json['result']) : $api_json['result'];
		        }else if(isset($api_json['message'])){
		        	$return_data['message']=$api_json['message'];
		        }
	        }
	    }



	    if($returnMethod=='filter' && isset($api_json['result']) && !empty($api_json['result'])){
		    	$return_data['data_list']=$api_json['result']['data_list'];
		    	$paginate_array=array('total_rows','total_pages','page_position','page_number','pagination','perpage','slno');
		    	foreach($paginate_array as $paginate){
		    		if(isset($api_json['result'][$paginate])){
		    			if($paginate=='pagination'){
		    				$return_data['pagination_data']['pagination_links']=$api_json['result'][$paginate];
		    			}else{
		    				$return_data['pagination_data'][$paginate]=$api_json['result'][$paginate];
		    			}
		    		}
		    	}
	    }else{	    
		    if(isset($api_json['result']['details'])){
		        if($api_json['result']['details']!=null || $api_json['result']['details']!=''){
		        	if(!empty($api_json['result']['details']['data_list'])){
			            $return_data['data_list']=$api_json['result']['details']['data_list'];
		        		$return_data['data_count']=count($api_json['result']['details']['data_list']);  
			        }else{
			        	$return_data['data_list']=$api_json['result']['details'];
		        		//$return_data['data_count']=count($api_json['result']['details']);
			        }
		        }else{
		        	$return_data['data_list']=$api_json['result']['details'];
		        	$return_data['data_count']=count($api_json['result']['details']); 
		        }         
		    }else if(isset($api_json['result']['data_list'])){    	
	        	$return_data['data_list']=$api_json['result']['data_list'];
	        	$return_data['data_count']=count($api_json['result']['data_list']); 
		    }
		}
		if($return_data['status']=='fail'){
			if(isset($api_json['result']) && isset($api_json['message']) && !empty($api_json['message'])){
	        	$return_data['message']=$api_json['message'];
	        }else{
	        	$return_data['message']=$response;
	        }
		}

	    //print_r($return_data);
	    return $return_data;
	}

	public function apicall($url,$method,$api_auth='',$params=array(),$post_data_type='json')
	{
		$curl = curl_init($url);
		switch ($method) {
			case 'GET':
				if(!empty($params)){
					if(is_array($params)){
						$params = http_build_query($params, '', '&');
						$url=$url.'?'.$params;
					}else{
						$url=$url.'/'.$params;
					}
				}
				break;
			case 'POST':
				if($post_data_type=='json'){
					curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
				}else{
					curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
				}
				break;
			case 'PUT':
				if($post_data_type=='json'){
					curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
				}else{
					curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
				}
				break;
			case 'DELETE':
				if(!empty($params)){
					if(is_array($params)){
						$params = http_build_query($params, '', '&');
						$url=$url.'?'.$params;
					}else{
						$url=$url.'/'.$params;
					}
				}
				break;
		}
		if($post_data_type=='json'){
			$headers = array("Content-Type: application/json",$api_auth);
		}else{
			$headers = array($api_auth);
		}
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST,$method);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		// curl_setopt($curl, CURLOPT_HTTP_REFERER, 'credapi.sartdev.in');
		curl_setopt($curl, CURLOPT_REFERER, $_SERVER['SERVER_NAME']);
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agents);
		$response = curl_exec($curl);
		$returnGroup['info'] = curl_getinfo($curl);
       	$returnGroup['errno']= curl_errno($curl);
       	$returnGroup['error']= curl_error($curl);
       	$http_code=$returnGroup['info']['http_code'];
		curl_close($curl);
		$api_json=json_decode($response,true); 
		//print_r($response);
		//exit();
		
		//print_r($response);exit();

		$return_data['status']='fail';
	    $return_data['message']='';
	    $return_data['result']='';
	    $return_data['http_code']=$http_code;

	    if(!empty($api_json['message'])){
	        $return_data['message']=$api_json['message'];
	    }
		
		if(isset($api_json['result']) && !empty($api_json['result'])){
			$return_data['result']=is_array($api_json['result']) ? implode($api_json['result']) : $api_json['result'];
		}else if(isset($api_json['data']) && !empty($api_json['data'])){
			$return_data['result']=$api_json['data'];
		}else{
	    	$return_data['result']=$api_json;
	    }
		
		if(!empty($api_json['status'])){
			$return_data['status']=$api_json['status'];	
		}else if($http_code=='200'){
	    	$return_data['status']='success';	    	
	    }
		
		if(!empty($api_json['message_code']) && $api_json['message_code']=='success'){
			$return_data['status']='success';
		}
		
		if(!empty($api_json['status']) && strtolower($api_json['status'])=='filed'){
			$return_data['status']='success';
		}
		
		return $return_data;
		//return $api_json;
	}


	public function get_report_old($url, $method = "POST", $params = array(), $api_key = '')
    {
        //$url=$this->api_base_url.$url;
        $curl = curl_init($url);
        $headers = array("Content-Type: application/json");
        if (!empty($api_key)) {
            array_push($headers, $api_key);
        }
        switch ($method) {
            case 'GET':
                if (is_array($params) && !empty($params)) {
                    $params = http_build_query($params, '', '&');
                    $url = $url . '?' . $params;
                }
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
                break;
            case 'DELETE':
                break;
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agents);
        curl_setopt($curl, CURLOPT_HEADER, true);

        curl_setopt(
            $curl,
            CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$headers) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                    return $len;

                $headers[strtolower(trim($header[0]))] = trim($header[1]);

                return $len;
            }
        );

        $response = curl_exec($curl);
        $response_json = json_decode($response, true);
        $curl_info = curl_getinfo($curl);
        $http_code = $curl_info['http_code'];
        curl_close($curl);

        if ($http_code == '200') {
            // return $headers;
            $api_json = array('status'=>'success', "response"=>$headers);
        } else {
            $api_json = array('status'=>'fail', "response"=>$response_json);
        }
        return $api_json;
    }
	
	
	public function get_report($url, $method = "POST", $params = array(), $api_key = '')
    {
		$curl = curl_init($url);
		$headers = array("Content-Type: application/json");
		//if (!empty($api_key)) {
			//array_push($headers, $api_key);
		//}
		switch ($method) {
			case 'GET':
				if (is_array($params) && !empty($params)) {
						$params = http_build_query($params, '', '&');
						$url = $url . '?' . $params;
				}
				break;
			case 'POST':
				curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
				break;
			case 'PUT':
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
				break;
			case 'DELETE':
				break;
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
		//for login auth
		if (!empty($api_key)) {
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($curl, CURLOPT_USERPWD, "$api_key");
		}
		
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agents);

		$response = curl_exec($curl);

		$curl_info = curl_getinfo($curl);
		$http_code = $curl_info['http_code'];
		$content_type='';
		if(!empty($curl_info['content_type'])){
			$content_type = $curl_info['content_type'];
		}
		curl_close($curl);
		
		if ($http_code == '200') {
			
			$ext_name = '';
			$type = '';
			switch ($content_type) {
				case 'application/pdf':
					$ext_name = ".pdf";
					$type = 'pdf';
					break;
				case 'image/jpeg':
					$ext_name = ".jpeg";
					$type = 'jpeg';
					break;
				case 'image/png':
					$ext_name = ".png";
					$type = 'png';
					break;
				case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
					$ext_name = ".docx";
					$type = 'docx';
					break;
				default:
					$ext_name = ".txt";
					$type = 'txt';
			}
			$content = base64_encode($response);
			$api_json = array('status'=>'success', 'message' => $type . ' report is generated & ready for download', "content"=>$content,'file_ext'=>$ext_name);
		} else {
			$api_json = array('status'=>'fail','message' => 'Report generate failed.','response'=>$response);
		}
		return $api_json;
	}

	function download_file_from_url($url, $save_path, $file_name) {
		$result = false;
		$file_url = '';
	
		// Encode the URL
		$url = str_replace(" ", "%20", $url);
		$url = preg_replace('/[^\x20-\x7E]/', '', $url); // Remove non-ASCII characters
	
		create_directory($save_path);
	
		$newfname = $save_path . $file_name;
		$curl = curl_init($url);
		$newf = fopen($newfname, 'wb');
	
		if ($curl === false || $newf === false) {
			return ['result' => false, 'file_url' => ''];
		}
	
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
		$result = curl_exec($curl);


		$number = 0;
		if(preg_match_all("/\/Page\W/", $result, $matches)){
			$number = preg_match_all("/\/Page\W/", $result, $matches);
		}

		$curl_info = curl_getinfo($curl);
		$http_code = $curl_info['http_code'];
	
		if ($result === false) {
			$file_url = '';
			$err = curl_error($curl);
			echo 'cURL Error: ' . curl_error($curl) . PHP_EOL; // Print cURL error message
		} else {
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($statusCode >= 200 && $statusCode < 300) {
				fwrite($newf, $result); // Write the result directly to the file
				$file_url = $newfname;
			} else {
				$file_url = '';
				$result = false;
				echo 'HTTP Status Code: ' . $statusCode . PHP_EOL;
			}
		}
	
		fclose($newf);
		curl_close($curl);
	
		return ['result' => $result !== false, 'file_url' => $file_url, 'pages'=>$number];
	}
	
}
