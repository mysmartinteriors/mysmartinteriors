<?php

require APPPATH . 'libraries/REST_Controller.php'; 

class Lookups extends REST_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->database();
        $this->table= 'lookups';
        $this->model_name='lookups_model';   

        $this->load->model($this->model_name, "", true);
		$this->lang->load('response', 'english');
    }       

    /**

     * Get All Data from this method.

     *

     * @return Response

    */

    public function index_get($id = 0)
    {
      $message = "success";
      $data=array();
      if(!empty($id)){
         $data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
      }else{       
         $data = $this->Mydb->do_search($this->table,$this->model_name);
      }
      if(!empty($data)){
         $value  = withSuccess($message,$data);
      }else{
         $value  = withSuccess($this->lang->line('no_result_found'));
      }
      
      $this->response($value, REST_Controller::HTTP_OK);
    }
	
}