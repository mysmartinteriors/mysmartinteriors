<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class State extends REST_Controller {

   
	 

    public function __construct() {

       parent::__construct();

       $this->load->database();
    }

       

    /**

     * Get All Data from this method.

     *

     * @return Response

    */
	
	
	
	public function index_get($id = 0)

	{

        if(!empty($id)){

            $data = $this->db->get_where("states", ['id' => $id])->row_array();
			$result = array('details'=>$data);
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}else{
			$message = "Success";
		}
		$value  = withSuccess($message,$result);

		$this->response($value, REST_Controller::HTTP_OK);

        }else{
			
				
				$data = $this->search();
				
        }
		

	}

	 public function search()

    {
	
		$parameters = $this->input->get();
		$value  = withErrors("Country id required");
		if(!empty($parameters)){
	

			if(array_key_exists('country_id',$parameters)){
				
           $data = $this->db->get_where("states", ['country_id' => $parameters['country_id']])->result();

			$result = array('details'=>$data);	
			$value  = withSuccess('success',$result);
			$this->response($value, REST_Controller::HTTP_OK);	
			}
			
		}else{
			$this->response($value, REST_Controller::HTTP_BAD_REQUEST);	
		}
		
			
		
	}
  

}