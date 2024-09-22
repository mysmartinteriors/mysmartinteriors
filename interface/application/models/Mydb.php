<?php
class Mydb extends CI_Model{
	

	function paginate_function($item_per_page, $current_page, $total_records, $total_pages) {
		
		 $pagination = '';
		 if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) { //verify total pages and current page number
		 $pagination .= '<ul class="pagination pagination-split">';

		 $right_links = $current_page + 3;
		 $previous = $current_page - 3; //previous link 
		 $next_link = $current_page + 1; //next link
		 $previous_link = $current_page - 1;
		 $first_link = true; //boolean var to decide our first link 

		 if ($current_page > 1) {
		 //$previous_link = ($previous==0)?1:$previous;
		 $pagination .= '<li class="first page-item"><a class="page-link" href="javascript:;" data-page="1" title="First">&laquo;</a></li>'; //first link
		 $pagination .= '<li class="page-item"><a href="javascript:;" class="page-link" data-page="' . $previous_link . '" title="Previous">&lt;</a></li>'; //previous link
		 for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
		 if ($i > 0) {
		 $pagination .= '<li class="page-item"><a class="page-link" href="javascript:;" data-page="' . $i . '" title="Page' . $i . '">' . $i . '</a></li>';
		 }
		 }
		 $first_link = false; //set first link to false
		 }

		 if ($first_link) { //if current active page is first link
		 $pagination .= '<li class="first active page-item"><a class="page-link" href="javascript:;">' . $current_page . '</a></li>';
		 } elseif ($current_page == $total_pages) { //if it's the last active link
		 $pagination .= '<li class="last active page-item"><a class="page-link" href="javascript:;">' . $current_page . '</a></li>';
		 } else { //regular current link
		 $pagination .= '<li class="active page-item"><a class="page-link" href="javascript:;">' . $current_page . '</a></li>';
		 }

		 for ($i = $current_page + 1; $i < $right_links; $i++) { //create right-hand side links
		 if ($i <= $total_pages) {
		 $pagination .= '<li class="page-item"><a class="page-link" href="javascript:;" data-page="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
		 }
		 }
		 if ($current_page < $total_pages) {
		 //$next_link = ($i > $total_pages)? $total_pages : $i;
		 $pagination .= '<li class="page-item"><a class="page-link" href="javascript:;" data-page="' . $next_link . '" title="Next">&gt;</a></li>'; //next link
		 $pagination .= '<li class="last page-item"><a class="page-link" href="javascript:;" data-page="' . $total_pages . '" title="Last">&raquo;</a></li>'; //last link
		 }

		 $pagination .= '</ul>';
		 }
		 return $pagination; //return pagination links
	}
	
	function getPaginationData($item_per_page,$current_page,$total_rows){
			$page = $current_page;
			if($page!=""){
				$page_number=$page;
					}else{
				$page_number = 1; //if there's no page number, set it to 1
				}
	    	$total_pages = ceil($total_rows/$item_per_page);
			$data['page_position'] = (($page_number-1) * $item_per_page);
			$data['item_per_page'] = $item_per_page;
			$data['page_number']= $page_number;
			$data['get_total_rows']= $total_rows;
			$data['total_pages'] = $total_pages;
			return $data;
	}
	
	public function hash($password){
       $hash = password_hash($password,PASSWORD_DEFAULT);
       return $hash;
   	}

   	public function verifyHash($password,$vpassword){
       if(password_verify($password,$vpassword))
       {
           return TRUE;
       }
       else{
           return FALSE;
       }
   	}

   	function get_depts_by_branch($id=0){
    	$apidata=$this->curl->execute("department","GET",array('branch_id'=>$id));
    	$datas=[];
    	$i=0;
    	$list_options='';
    	if($apidata['status']=='success' && is_array($apidata['data_list'])){
    		foreach ($apidata['data_list'] as $row) {
				$datas[] = array(
					'id' => $row['id'],
					'name' => $row['name'],
					'parent' => $row['parent'],
					'organization_branches_id' => $row['organization_branches_id'],
					'status' => $row['status'],
					'created_at' => $row['created_at'],
					'created_by' => $row['created_by'],
					'updated_at' => $row['updated_at'],
					'updated_by' => $row['updated_by'],
				);
				if(isset($row['_children']) && !empty($row['_children'])){
					$datas[$i]['_children']=$row['_children'];
				}
				$i++;
			}

			if(!empty($datas)){
				$data['datas']=$datas;
            	$list_options=$this->load->view('users/department/department_list_options',$data,true);
			}
    	}
    	//print_r($datas);
    	$value=array('message'=>$apidata['message'],'status'=>$apidata['status'],'data_list'=>$datas,'list_options'=>$list_options);
    	return $value;
    }

   	
}