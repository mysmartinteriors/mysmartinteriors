<?php

require APPPATH . 'libraries/REST_Controller.php';

class Forms extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = 'form_templates';
        $this->model_name = 'vendor_forms_model';
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
        $data = array();
        if (!empty($id)) {
            $data = $this->db->get_where('form_templates', array('services_id'=>$id))->result_array();
        }else{
            $data = '';
        }
        if (!empty($data)) {
            $value  = withSuccess($message, $data);
        } else {
            $value  = withSuccess($this->lang->line('no_result_found'));
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }
}
