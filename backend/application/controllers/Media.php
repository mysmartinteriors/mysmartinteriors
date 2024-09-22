<?php

require APPPATH . 'libraries/REST_Controller.php';   

class Media extends REST_Controller {
    public function __construct() {
       parent::__construct();
       $this->load->database();
	   $this->table = "media_library";
	//    $this->model_name = "media_model";
	   $this->load->model("media_model", "", true);
	   $this->lang->load('response', 'english');
    }
       
 
    /**

     * Get All Data from this method.

     *

     * @return Response

    */

	public function index_get($id = 0){
		$message = "success";
        if(!empty($id)){
            $data = $this->db->get_where($this->table, ['id' => $id])->row_array();
			if(empty($data)){
				$message = $this->lang->line('no_result_found');
			}
			$data = array('details'=>$data);
        }else{			
			$data = $this->search();
			if(empty($data['data_list'])){
				$message = $this->lang->line('no_result_found');
			}			
        }
		$value  = withSuccess($message,$data);
		$this->response($value, REST_Controller::HTTP_OK);
	}

     

    /**

     * Insert data from this method.

     *

     * @return Response

    */

    public function index_post(){
        $input = $this->input->post();
		// print_r($input);exit();
		$rules = [
			'folder' => ['Folder Name','required'],
			'fileName' => ['File Encrypted Name','required'],
			'fileTitle' => ['File Title','required'],
			'fileType' => ['File Type','required'],
			'fileSize' => ['File Size','required'],
		];
		Validator::make($rules);
		if (!Validator::fails()){
            Validator::error();
        }else{
		// 'folder' => $config['upload_path'],
		// 'fileName' => $uploadData['file_name'],
		// 'fileTitle'=>$uploadData['raw_name'],
		// 'fileType' => strtolower($uploadData['file_ext']),
		// 'fileSize' => $uploadData['file_size'],
		// 'createdDate' => $this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now()),
		// 'updatedDate' => $this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now()),
		// 'status' => 1,
			$data = array(
				'folder'=>$input['folder'],
				'fileName' =>$input['fileName'],
				'fileTitle' =>$input['fileTitle'],
				'fileType' =>$input['fileType'],
				'fileSize'=>$input['fileSize'],
				'createdDate'=>cur_date_time(),
				'updatedDate'=>cur_date_time(),
				'status'=>1
			);
			$id = $this->Mydb->insert_table_data('media_library',$data);
			$result['details'] = $this->Mydb->get_table_data('media_library',array('id'=>$id));
			$value  = withSuccess($this->lang->line('uploads_success'),$result);
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

		if(!empty($input['fileName'])){
			$rules['fileName'] = ['File Name','required|min_length[3]|max_length[200]'];
			$data['fileName'] = $input['fileName'];
		}
		if(!empty($input['fileTitle'])){
			$rules['fileTitle'] = ['File Title','required|min_length[3]|max_length[200]'];
			$data['fileTitle'] = $input['fileTitle'];
		}		
		if(array_filter($input)) {
			if(!empty($rules)){
				Validator::make($rules);	
			}		
			if (!Validator::fails()) {
				Validator::error();
			}			
		}		
		
		$data['updatedDate'] = cur_date_time();
		
		$is_update = $this->Mydb->update_table_data('media_library', array('id'=>$id), $data);
		$result['details'] = $this->db->get_where('media_library',array('id'=>$id))->row_array();
		if($is_update>0){
			$value  = withSuccess('File Update Successful',$result);
		}else{
			$value  = withErrors('File Updated Unsuccessful',$result);
		}       
		$this->response($value, REST_Controller::HTTP_OK);      
    }


    /**

     * Delete data from this method.

     *

     * @return Response

    */

    public function index_delete($id){
		$data = $this->db->get_where("media_library", ['id' => $id])->row_array();
		$res = $this->Mydb->delete_table_data('media_library', array('id'=>$id));	
		if ($res == 1){
			$result = array('details'=>$data);		
			$value  = withSuccess($this->lang->line('uploads_deleted_success'),$result);
		}else if ($res == - 1451){
			$value = withErrors($this->lang->line('failed_to_delete'));
		}else{
			$value = withErrors($this->lang->line('failed_to_delete'));
		}
		$this->response($value, REST_Controller::HTTP_OK);
    }
	
	/**
	
	* Search data from this method.
	
	*
	
	* @return Response
	
	*/

	 public function search(){
		$param_default = array('search','perpage','page','sortby','orderby');
		$parameters = $this->input->get();
		$diff = array();
		$data = array();
		$data['data_list'] = array();
		$search = "";
		$perpage = 10;
		$page = 1;
		$sortby = "media_library.id";
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
		
		$total_rows = $this->media_model->filter($filter_data, 0, 0,$sortby,$orderby,$all);	   
		$udata = $this->Mydb->getPaginationData($perpage, $page, $total_rows);
		$data = array_merge($data,$udata);			
		$getData = $this->media_model->filter($filter_data, $perpage, $data['page_position'],$sortby,$orderby,$all);			
       $data['pagination'] = $this->Mydb->paginate_function($perpage, $data['page_number'], $total_rows, $data['total_pages']);
		
		
        if ($getData->num_rows() > 0) {            
           foreach ($getData->result() as $row) {
                $data['data_list'][] = $row;
		   }
		   if ($page == 1) {
				$data['slno'] = 1;
			} else {
				$data['slno'] = (($page - 1) * $perpage) + 1;
			}
		}
		if($all){
			array_splice($data,1);
		}
		return $data;
		
    }
    	
	

}