<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Curl {

	protected $_ci;                 // CodeIgniter instance
	protected $response = '';       // Contains the cURL response for debug
	public $error_code;             // Error code returned as an int
	public $error_string;           // Error message returned as a string
	public $info;                   // Returned after request (elapsed time, etc)
	protected $user_agents = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
	// protected $api_base_url='http://localhost/SARTBizExcel/backend/'; // Place API base URL here
	// protected $api_base_url='https://credapi.sartdev.in/'; // Place API base URL here
	// protected $api_base_url='https://sartdev.in/demo_sites/nalaaorganics/backend/'; // Place API base URL here
	// protected $api_base_url='https://api.nalaaorganic.com/'; // Place API base URL here
	protected $api_base_url='https://api.mysmartinteriors/';  // Place API base URL here

	protected $api_key = "X-API-KEY:MySmartInteriorsApi2024";   // Populates extra HTTP headers also add API Key

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
				if(is_array($params) && !empty($params) ){
					$params = http_build_query($params, '', '&');
					$url=$url.'?'.$params;
				}
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
		// print_R($api_json);
		// echo "<hr>";

		if($debugMode==true){
			$return_data=array('status'=>'fail','message'=>'Debug mode enabled','url'=>$url,'result'=>$response);
			return $return_data;
			exit;
		}
		// print_r($response);exit();

    	// print_r($response);

	    $return_data['status']='fail';
	    $return_data['message']='Api call error!';
	    $return_data['data_list']='';
	    $return_data['data_count']=0;
	    $return_data['error_result']='';
	    //$return_data['pagination_data']='';

	    if(isset($api_json['message'])){
	        $return_data['message']=$api_json['message'];
	    } 
		// print_r($return_data);

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

		// print_R($api_json);exit();
		// log_message('debug', print_r($api_json, true));


		// print_r("REAACHED HERE");
		// print_r($returnMethod);
		// echo "<hr>";
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

	public function apicall($url,$method,$api_auth='',$params=array())
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
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
				break;
			case 'PUT':
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
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
		$headers = array("Content-Type: application/json",$api_auth);
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
       	$http_code=$returnGroup['info']['http_code'];
		curl_close($curl);
		$api_json=json_decode($response,true); 
		//print_r($api_json);

		$return_data['status']='';
	    $return_data['message']='';
	    $return_data['result']='';
	    $return_data['http_code']=$http_code;

	    if(isset($api_json['message'])){
	        $return_data['message']=$api_json['message'];
	    }

	    if($http_code=='200'){
	    	$return_data['status']='success';
	    	if(isset($api_json['result']) && !empty($api_json['result'])){
	        	$return_data['result']=is_array($api_json['result']) ? implode($api_json['result']) : $api_json['result'];
	        }
	        if(isset($api_json['data']) && !empty($api_json['data'])){
	        	$return_data['result']=$api_json['data'];
	        }
	    }else{
	    	$return_data=$api_json;
	    }	    
		return $return_data;
	}


}
