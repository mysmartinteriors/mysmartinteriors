<?php

require APPPATH . 'libraries/REST_Controller.php';

     
class Organization_branches extends REST_Controller {

   
	 

    public function __construct() {

       parent::__construct();

       $this->load->database();
	  $this->load->model("organization_branchesmodel", "", true);
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
			$data=array('details'=>$data);	

        }else{
			
			$data = $this->search();
			if(empty($data['data_list'])){
				$message = $this->lang->line('no_result_found');
			}
			
        }
		//$result = array('details'=>$data);
		$value  = withSuccess($message,$data);
		//print_r($this->db->last_query());

		$this->response($value, REST_Controller::HTTP_OK);
	}

    
    public function get_single_result($id){
		
		$filter_data[0]['type'] = 'organization_branches.id'; $filter_data[0]['value'] = $id;
		
		$getData = $this->organization_branchesmodel->filter($filter_data);	

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
			'name' => ['Branch name','required|max_length[400]'],
			'organization_id' => ['Organization name','required'],
			'address' => ['Address','required|max_length[500]|min_length[10]'],
			'countries_id' => ['Country','required'],
			'cities_id' => ['City','required'],
			'states_id' => ['State','required'],
			'pincode' => ['Pincode','required|max_length[6]|min_length[6]'],
			'address' => ['Address','required|max_length[500]|min_length[10]'],
			'email' => ['Email','required|valid_email|max_length[100]|min_length[5]'],
			'phone' => ['Phone No.','required|max_length[20]|min_length[10]'],
			'GSTIN' => ['GSTIN.','required|regex_match[/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([A-Za-z]){2}?$/]'],
			'CIN' => ['CIN.','required|regex_match[/^([L|U]{1})([0-9]{5})([A-Za-z]{2})([0-9]{4})([A-Za-z]{3})([0-9]{6})$/]'],
			'PAN' => ['PAN.','required|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]'],
			];
		
		
		Validator::make($rules);
		 if (!Validator::fails()) {
             Validator::error();
        } else {
			
			$data = array(	
			'name' =>$input['name'],
			'address'=>$input['address'],
			'organization_id'=>$input['organization_id'],
			'cities_id'=>$input['cities_id'],
			'countries_id'=>$input['countries_id'],
			'states_id'=>$input['states_id'],
			'pincode'=>$input['pincode'],
			'email'=>$input['email'],
			'phone'=>$input['phone'],
			'GSTIN'=>$input['GSTIN'],
			'CIN'=>$input['CIN'],
			'PAN'=>$input['PAN'],
			'created_at'=>cur_date_time(),
			'created_by'=>$input['created_by'],
			'status'=>3
		);		
		
		$id = $this->Mydb->insert_table_data('organization_branches',$data);
		$result['details'] = $this->get_single_result($id);
		$value  = withSuccess($this->lang->line('org_branch_created_success'),$result);
        $this->response($value, REST_Controller::HTTP_OK);
		}

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
        //print_r('id='.$id);
		
		if(!empty($input['name'])){
			$rules['name'] = ['Branch name','required|min_length[3]|max_length[400]'];
			$data['name'] = $input['name'];
		}

		if(!empty($input['address'])){
			$rules['address'] = ['Address','required|max_length[500]|min_length[10]'];
			$data['address'] = $input['address'];
		}
		
		if(!empty($input['organization_id'])){
			$rules['organization_id'] = ['Organization name','required'];
			$data['organization_id'] = $input['organization_id'];
		}
		
		if(!empty($input['country'])){
			$rules['country'] = ['Country','required'];
			$data['countries_id'] = $input['country'];
		}
		if(!empty($input['state'])){
			$rules['state'] = ['State','required'];
			$data['states_id'] = $input['state'];
		}
		if(!empty($input['city'])){
			$rules['city'] = ['City','required'];
			$data['cities_id'] = $input['city'];
		}
		
		if(!empty($input['email'])){
			$rules['email'] = ['Email','required|valid_email|max_length[100]|min_length[5]'];
			$data['email'] = $input['email'];
		}
		if(!empty($input['phone'])){
			$rules['phone'] = ['Phone No.','required|max_length[20]|min_length[10]'];
			$data['phone'] = $input['phone'];
		}
		if(!empty($input['GSTIN'])){
			$rules['GSTIN'] = ['GSTIN.','required|regex_match[/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([A-Za-z]){2}?$/]'];
			$data['GSTIN'] = $input['GSTIN'];
		}
		if(!empty($input['CIN'])){
			$rules['CIN'] = ['CIN.','required|regex_match[/^([L|U]{1})([0-9]{5})([A-Za-z]{2})([0-9]{4})([A-Za-z]{3})([0-9]{6})$/]'];
			$data['CIN'] = $input['CIN'];
		}
		if(!empty($input['PAN'])){
			$rules['PAN'] = ['PAN.','required|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]'];
			$data['PAN'] = $input['PAN'];
		}
		


		if(array_filter($input)) {
			if(!empty($rules)){
				Validator::make($rules);	
			}
		
		 if (!Validator::fails()) {
             Validator::error();
        } 
			
		}
		//print_r($data);
			
		$data['updated_at'] = cur_date_time();
		
		$is_update = $this->Mydb->update_table_data('organization_branches', array('id'=>$id), $data);
		//print_r($this->db->last_query());
		$result['details'] = $this->get_single_result($id);
		if($is_update>0){
		$value  = withSuccess($this->lang->line('org_branch_updated_success'),$result);
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
		$p_data = $this->db->get_where("users", ['organization_branches_id' => $id])->num_rows();
		if($p_data==0){
			$p_data = $this->db->get_where("departments", ['organization_branches_id' => $id])->num_rows();
			if($p_data==0){
				$data = $this->get_single_result($id);
				$res = $this->Mydb->delete_table_data('organization_branches', array('id'=>$id));				
				if ($res == 1){
					$result = array('details'=>$data);
					 $value  = withSuccess($this->lang->line('org_branch_deleted_success'),$result);
				}else if ($res == - 1451)
					{
					$value = withErrors($this->lang->line('failed_to_delete'));
				}else{
					$value = withErrors($this->lang->line('failed_to_delete'));
				}
			}else{
				$value = withErrors($this->lang->line('org_branch_has_depts'));
			}
		}else{
			$value = withErrors($this->lang->line('org_branch_has_users'));
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
		$parameters = array_filter($this->input->get());
		$diff = array();
		$data = array();
		$data['data_list'] = array();
		$search = "";
		$perpage = 10;
		$page = 1;
		$sortby = "organization_branches.id";
		$orderby = "DESC";
		$all = false;
		$data['slno'] ='';
		
		
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
		
		
		$total_rows = $this->organization_branchesmodel->filter($filter_data, 0, 0,$sortby,$orderby,$all);	   
		$udata = $this->Mydb->getPaginationData($perpage, $page, $total_rows);
		$data = array_merge($data,$udata);			
		$getData = $this->organization_branchesmodel->filter($filter_data, $perpage, $data['page_position'],$sortby,$orderby,$all);			
       $data['pagination'] = $this->Mydb->paginate_function($perpage, $page, $total_rows, $data['total_pages']);
		
		
		//print_r($this->db->last_query());
        if ($getData->num_rows() > 0) {            
           foreach ($getData->result() as $row) {
                $data['data_list'][] = $row;
	            if ($page == 1) {
	                $data['slno'] = 1;
	            } else {
	                $data['slno'] = (($page - 1) * $perpage) + 1;
	        	}	
		   }
		}
		if($all){
			array_splice($data,1);
		}
		return $data;
		
    }
	
	
}