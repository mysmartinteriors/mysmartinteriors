<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class Country extends REST_Controller {

   
	 

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

            $data = $this->db->get_where("countries", ['id' => $id])->row_array();

        }else{
			
			$this->db->where("status",1);
			$this->db->order_by("name");
			$data = $this->db->get("countries")->result();
				
        }
		$result = array('details'=>$data);
		if(empty($data)){
			$message = $this->lang->line('no_result_found');
		}else{
			$message = "Success";
		}
		$value  = withSuccess($message,$result);

		$this->response($value, REST_Controller::HTTP_OK);

	}

      

  

}