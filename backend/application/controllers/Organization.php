<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class Organization extends REST_Controller {

   
	 

    public function __construct() {

       parent::__construct();

       $this->load->database();
	   $this->load->model("organizationmodel", "", true);
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
        if(!empty($id)){

            $data = $this->get_single_result($id);
			if(empty($data)){
				$message = $this->lang->line('no_result_found');
			}
        }else{			
			$data = $this->search();
			if(empty($data['data_list'])){
				$message = $this->lang->line('no_result_found');
			}			
        }
		$result = array('details'=>$data);
		$value  = withSuccess($message,$result);
		$this->response($value, REST_Controller::HTTP_OK);

	}
	
	public function get_single_result($id){	
		$filter_data[0]['type'] = 'organization.id'; $filter_data[0]['value'] = $id;		
		$getData = $this->organizationmodel->filter($filter_data);
		return $getData->row_array();
	}

     

    /**

     * Insert data from this method.

     *

     * @return Response

    */

    public function index_post()

    {

        $input = $this->input->post();
		$rules = [
			'name' => ['Organization name','required|max_length[400]|is_unique[organization.name]'],
			'address' => ['Address','required|max_length[500]|min_length[10]'],
			'tagline' => ['Tagline','max_length[500]|min_length[3]'],
		];		
		$message = [
			'is_unique' => 'The %s is already exists',
		];	
		Validator::setMessage($message);
		Validator::make($rules);
		if (!Validator::fails()) {
            Validator::error();
        } else {
			$data = array(	
				'name' =>$input['name'],
				'address'=>$input['address'],
				'tagline'=>$input['tagline'],
				//'data_fields'=>$input['data_fields'],
				'created_at'=>cur_date_time(),
				'created_by'=>$input['created_by'],
			);
			$id = $this->Mydb->insert_table_data('organization',$data);
			$result['details'] = $this->get_single_result($id);
			$value  = withSuccess($this->lang->line('org_created_success'),$result);
	        $this->response($value, REST_Controller::HTTP_OK);
		}	

    } 




	/**
     * Update custom field data from this method.
     *
     * @return Response
     */


	public function custom_fields_put($id) {
		$input = $this->put();
		$data['data_fields'] = $input['data_fields'];
		$op = $this->perform_update_data_field_operation($id, $data['data_fields']);
		if($op){
		$value = withSuccess($this->lang->line('data_updated_success'));
		}else{
		$value = withErrors($this->lang->line('failed_to_update'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	public function perform_update_data_field_operation($id, $data_fields) {
		$status = 0;
		$data_fields=json_decode($data_fields,true);
		$data = $this->Mydb->get_table_data('organization');
		if (empty($data[0]['data_fields'])) {
			foreach ($data_fields as $k => $v) {
				$data_key = $k;
				$data_type = $v['data_type'];
				$up_array[$data_type] = $v['data_value'];
			}
			$update_data['data_fields'] = json_encode($up_array);
			$update = $this->Mydb->update_table_data('organization', array('id' => $id), $update_data);
			$status = 1;
		} else {
			foreach ($data_fields as $k => $v) {
				$data_key = $k;
				$data_type = $v['data_type'];
				$data_value = json_encode($v['data_value']);
				$sql = "UPDATE organization SET data_fields = JSON_SET(data_fields, '$.$data_type', JSON_COMPACT('$data_value')) WHERE id = $id";
				$query = $this->db->query($sql);
				$status = 1;
			}
			$r_sql = "UPDATE organization SET data_fields = replace(data_fields,'\\\','') WHERE id = $id";
			$query = $this->db->query($r_sql);
		}
		return $status;
	}


	/**
	* Get custom field data from this method.
	*
	* @return Response
	*/
	public function custom_fields_get() {
		$input = $this->input->get();
		$id = $input['id'];
		$custom_field = $input['search'];
		$op = $this->perform_search_data_field_operation($id, $custom_field);
		if(empty($op)){
			$value = withErrors($this->lang->line('failed_to_fetch'));
		}else{
			$value = withSuccess("sucess",$op);
		}
		$this->response($value, REST_Controller::HTTP_OK);
	}

	public function perform_search_data_field_operation($id, $data_fields) {
		$result = array();
		$data = $this->Mydb->get_table_data('organization');
		if (!empty($data[0]['data_fields'])) {
			$pa = str_replace('-', '.', $data_fields);
			$sql = " SELECT JSON_EXTRACT(data_fields, '$.".$pa."') AS 'details' FROM organization WHERE id = $id ";
			$query = $this->db->query($sql);
			$result = $query->row_array();
		}
		//print_r($this->db->last_query());
		return $result;
	}



    /**

     * Update data from this method.

     *

     * @return Response

    */

    public function index_put($id)

    {
		$rules = array();
		$data = array();
		
        $input = $this->put();
		
		if(!empty($input['name'])){
			$rules['name'] = ['Organization name','required|max_length[400]|edit_unique[organization.name.id.'.$id.']'];
			$data['name'] = $input['name'];
		}
		if(!empty($input['address'])){
			$rules['address'] = ['Address','required|max_length[500]|min_length[10]'];
			$data['address'] = $input['address'];
		}
		if(!empty($input['caption'])){
			$rules['caption'] = ['Tagline','max_length[500]|min_length[3]'];
			$data['caption'] = $input['caption'];
		}
		if(!empty($input['logo'])){
			$rules['logo'] = ['Logo','required'];
			$data['logo'] = $input['logo'];
		}
		if(!empty($input['GSTIN'])){
			$rules['GSTIN'] = ['GSTIN','required'];
			$data['GSTIN'] = $input['GSTIN'];
		}
		if(!empty($input['phone'])){
			$rules['phone'] = ['phone','required|numeric'];
			$data['phone'] = $input['phone'];
		}
		if(!empty($input['mobile'])){
			$rules['mobile'] = ['mobile','required|numeric'];
			$data['mobile'] = $input['mobile'];
		}
		if(!empty($input['email'])){
			$rules['email'] = ['email','required|valid_email'];
			$data['email'] = $input['email'];
		}
		if(!empty($input['website'])){
			$rules['website'] = ['website','required'];
			$data['website'] = $input['website'];
		}
		if(!empty($input['updatedBy'])){
			$rules['updatedBy'] = ['updatedBy','required'];
			$data['updated_by'] = $input['updatedBy'];
		}
		
		$message = [
			'edit_unique' => 'The %s is already exists',
		];
		Validator::setMessage($message);
		
		if(array_filter($input)) {
			if(!empty($rules)){
				Validator::make($rules);	
			}		
			if (!Validator::fails()) {
				Validator::error();
			}			
		}		
		$data['updated_at'] = cur_date_time();			
		$is_update = $this->Mydb->update_table_data('organization', array('id'=>$id), $data);
		$result['details'] = $this->get_single_result($id);
		if($is_update>0){
			$value  = withSuccess($this->lang->line('org_updated_success'),$result);
		}else{
			$value  = withErrors($this->lang->line('failed_to_update'),$result);
		}       
		$this->response($value, REST_Controller::HTTP_OK);     
    }

    

    /**

     * Delete data from this method.

     *

     * @return Response

    */

    public function index_delete($id)

    {
	
		$data = $this->get_single_result($id);
       	$res = $this->Mydb->delete_table_data('organization', array('id'=>$id));	
	
		if ($res == 1)
			{
			$result = array('details'=>$data);		
			 $value  = withSuccess($this->lang->line('org_deleted_success'),$result);
			}
		  else
		if ($res == - 1451)
			{
			$value = withErrors($this->lang->line('failed_to_delete'));
			}
		  else
			{
			$value = withErrors($this->lang->line('failed_to_delete'));
			}


		$this->response($value, REST_Controller::HTTP_OK);

    }
	
	/**
	
	* Search data from this method.
	
	*
	
	* @return Response
	
	*/

	 public function search()

    {
		$param_default = array('search','perpage','page','sortby','orderby');
		$parameters = $this->input->get();
		$diff = array();
		$data = array();
		$data['data_list'] = array();
		$search = "";
		$perpage = 10;
		$page = 1;
		$sortby = "id";
		$orderby = "DESC";
		$all = true;
		
		if(!empty($parameters)){
				$parem_key = array_keys($parameters);
				$diff = array_diff($parem_key,$param_default);
				$intersect = array_intersect($parem_key,$param_default);
		}
		
		if(array_key_exists('page',$parameters)){
			$all = false;
		}
		
		if(!empty($intersect)){
			foreach($intersect as $inst){	
				$rml =  str_replace('-','.',$parameters[$inst]);
				$$inst = $rml;
			}
		}
	

		$filter_data[0]['type'] = 'search'; $filter_data[0]['value'] = $search;
		
		if(!empty($diff)){
			$i = count($filter_data);
			foreach($diff as $p){
				if(!empty($this->input->get($p))){
					$pa = str_replace('-','.',$p);
					$filter_data[$i]['type'] = $pa;
					$filter_data[$i]['value'] = $this->input->get($p);
				}
				$i++;
			}
		}
		
		$total_rows = $this->organizationmodel->filter($filter_data, 0, 0,$sortby,$orderby,$all);	   
		$udata = $this->Mydb->getPaginationData($perpage, $page, $total_rows);
		$data = array_merge($data,$udata);			
		$getData = $this->organizationmodel->filter($filter_data, $perpage, $data['page_position'],$sortby,$orderby,$all);			
       $data['pagination'] = $this->Mydb->paginate_function($perpage, $data['page_number'], $total_rows, $data['total_pages']);
		
		
        if ($getData->num_rows() > 0) {            
           foreach ($getData->result() as $row) {
                $data['data_list'][] = $row;
		   }
		}
		if($all){
			array_splice($data,1);
		}
		return $data;
		
    }
    	
	

}