<?php

require APPPATH . 'libraries/REST_Controller.php';

class Calendar extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = 'calendar_events';
        $this->model_name = 'calendar_model';

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
            $data = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
        } else {
            $data = $this->Mydb->do_search($this->table, $this->model_name);
        }
        if (!empty($data)) {
            $value  = withSuccess($message, $data);
        } else {
            $value  = withSuccess($this->lang->line('no_result_found'));
        }

        $this->response($value, REST_Controller::HTTP_OK);
    }

    public function index_post()
    {
        $input = $this->input->post();
        $event_type = $input['event_type'];
        $event = $this->db->get_where('lookups', array('id' => $event_type))->row_array();
        if (!empty($event)) {
            $data = array();
            $data['event_type'] = $input['event_type'];
            $data['event_title'] = $event['l_value'];
            $data['event_date'] = $input['event_date']. ' 00:00:00';
            $data['event_description'] = $input['event_description'];
            $data['created_by'] = $input['createdBy'];
            $data['created_at'] = $input['createdDate'];
            $id = $this->Mydb->insert_table_data($this->table, $data);
            $result = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
            $message = 'Successfully added an event';
            $value  = withSuccess($message, $result);
        } else {
            $value  = withErrors($this->lang->line($this->table . '_create_fail'));
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }


    /**

     * Delete data from this method.

     *

     * @return Response

     */

    public function index_delete($id = 0)
    {
        if (empty($id)) {
            $value = withErrors('Event id is required');
            $this->response($value, REST_Controller::HTTP_OK);
            exit;
        }
        //Delete the workorders only if there are no workorder_profiles against the workorder
        // $workorder_profiles_data = $this->db->get_where("workorder_profiles", ['workorders_id' => $id])->row_array();
        // if(!empty($workorder_profiles_data)){
        //     $value = withErrors($this->lang->line($this->table.'_has_profiles'));
        // }else{        
        //     $data = $this->Mydb->get_single_result($id,$this->table,$this->model_name);
        //     if(!empty($data)){
        //         $res = $this->Mydb->delete_table_data('workorders', array('id'=>$id));  
        //         if ($res == 1){
        // 			$this->Mydb->delete_table_data('workorders_log', array('workorders_id'=>$id));  
        //             $value  = withSuccess($this->lang->line($this->table.'_deleted_success'),$data);
        //         }else if ($res == - 1451){
        //             $value = withErrors($this->lang->line('failed_to_delete'));
        //         }else{
        //             $value = withErrors($this->lang->line('failed_to_delete'));
        //         }
        //     }else{
        //         $value = withErrors($this->lang->line($this->table.'_not_found'));
        //     }
        // }
        $data = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
        if (!empty($data)) {
            $res = $this->Mydb->delete_table_data('calendar_events', array('id' => $id));
            $value  = withSuccess($this->lang->line($this->table . '_deleted_success'), $data);
        } else {
            $value = withErrors('Event details not found, try again later');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }
}
