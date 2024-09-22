<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Google_maps {

	protected $ci;                 // CodeIgniter instance
	protected $response = '';       // Contains the cURL response for debug
	protected $user_agents = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';

	//API'S
	protected $geocode_url='https://maps.googleapis.com/maps/api/geocode/json';
	protected $staticmap_url='http://maps.googleapis.com/maps/api/staticmap';
	protected $embed_url='https://www.google.com/maps/embed/v1/place';
	protected $distnace_url='https://maps.googleapis.com/maps/api/distancematrix/json';
	// $this->mytable = 'verifications';
	// $this->my_model_name = 'verifications_model';
	protected $mytable = 'verifications';
	protected $my_model_name = 'verifications_model';

	function __construct($url = '')
	{
		$this->ci = & get_instance(); 
		$this->ci->load->model('Mydb', '', true);
	}

	public function get_geocode($address='')
	{
		if(!empty($address)){
			$key=$this->get_key();
			$exclude_address=array('#','no','no.','ring road','/');
            $address=str_replace($exclude_address,'',strtolower($address));
		    if(!empty($key)){
		        $data=array(
		            'address'=>$address,
		            'key'=>$key
		        );
		        $response=$this->curl_execute($this->geocode_url,$data);
		        $return_data=array();
				$logData=array(
					'api_name'=>'geocode',
					'description'=>'Get lat,long by address',
					'api_url'=>$this->geocode_url,
					'input_data'=>json_encode($data),
					'ip_address'=>get_ipaddress(),
					'created_at'=>cur_date_time(),
					'response_data'=>json_encode($response)
				);
				$this->ci->Mydb->insert_table_data('maps_api_log',$logData);
		        if(!empty($response) && $response['status']=="OK" && is_array($response['results']) && isset($response['results'][0]['geometry']) && $response['results'][0]['geometry']['location']){
		            $latitude=$response['results'][0]['geometry']['location']['lat'];
		            $longitude=$response['results'][0]['geometry']['location']['lng'];
		            if(isset($response['results'][0]['geometry']['location_type'])){
		            	$location_type=$response['results'][0]['geometry']['location_type'];
		            	if($location_type=='ROOFTOP'){
		            		$locate_type='A1';
		            	}else if($location_type=='GEOMETRIC_CENTER'){
		            		$locate_type='A2';
		            	}else if($location_type=='APPROXIMATE'){
		            		$locate_type='A3';
		            	}else{
		            		$locate_type='Unknown';
		            	}
		            }
		            $return_data=array('status'=>'success','message'=>'Geo coordinates found','location_type'=>$location_type,'latitude'=>$latitude,'longitude'=>$longitude);
		        }else{
		            $return_data=array('status'=>'fail','message'=>'Error - '.$response['status']);  
		        }
		    }else{
		        $return_data=array('status'=>'fail','message'=>'Error - API key not found in database.');
		    }
		}else{
			$return_data=array('status'=>'fail','message'=>'Error - Address is required.');
		}
	    return $return_data;
	}


	public function get_key()
	{
		$key='';
		$data=$this->ci->db->get('maps')->row_array();
	    if(!empty($data)){
	        $key=$data['googlemaps_apikey'];
	    }
	    return $key;
	}

	public function curl_execute($url,$params=array())
	{
		$curl = curl_init($url);
		$headers=array();
		if(is_array($params) && !empty($params) ){
			$params = http_build_query($params, '', '&');
			$url=$url.'?'.$params;
		}
		//print_r(urldecode($url));
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST,'GET');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agents);
		curl_setopt($curl, CURLOPT_REFERER, base_url());
  		// curl_setopt($curl, CURLOPT_FOLLOWLOCATION,true);
  		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
  		$information = curl_getinfo($curl);

		$response = curl_exec($curl);
		curl_close($curl);
		$api_json=json_decode($response,true); 
		// print_r($url);
		// print_r($information); 
	    return $api_json;
	}
}
