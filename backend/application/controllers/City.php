<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class City extends REST_Controller {

   
	 

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
            $data = $this->db->get_where("cities", ['id' => $id])->row_array();
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
	
	public function by_name_get()
	{
		$name=$this->input->get('name');
		$result='';
        if(!empty($name)){
			$get_state= $this->db->get_where("states", ['name' => $name])->row_array();
			if(!empty($get_state)){
				$data = $this->db->get_where("cities", ['state_id' => $get_state['id']])->result();
				$result = array('details'=>$data);
				if(empty($data)){
					$message = $this->lang->line('no_result_found');
				}else{
					$message = "Success";
					$value  = withSuccess($message,$result);
				}
			}else{
				$value  = withErrors('State not found');
			}			
			$this->response($value, REST_Controller::HTTP_OK);
        }else{
			$value  = withErrors('State name is required');				
        }
	}	

	 public function search()

    {
	
		$parameters = $this->input->get();
		$value  = withErrors("State id required");
		if(!empty($parameters)){
	

			if(array_key_exists('state_id',$parameters)){
				
           $data = $this->db->get_where("cities", ['state_id' => $parameters['state_id']])->result();

			$result = array('details'=>$data);	
			$value  = withSuccess('success',$result);
			$this->response($value, REST_Controller::HTTP_OK);		
			}
			
		}else{
			$this->response($value, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
		
	}
  

  

}