<?php
class Services_model extends CI_Model
{
	function __construct() { 
        // Set table name 
        $this->table = 'services'; 
        $this->category_table = 'categories'; 
		$this->users_table = 'users';
		$this->lookups_table = 'lookups';
		$this->uploads_table = 'uploads'; 
		$this->customer_table = 'customers'; 
		$this->plans_table = 'plans'; 
		$this->plans_services_table = 'plans_services';  
        $this->workorder_table = 'workorders'; 
    } 
     
	protected $in_field = ['table_name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
         $search_field = array("$this->table.name");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id) as countt 
					FROM $this->table 
					left join $this->category_table on $this->table.categories_id = $this->category_table.id 
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					left join $this->users_table as created_users_table on  $this->table.created_by = created_users_table.id 
					left join $this->users_table as updated_users_table on   $this->table.updated_by = updated_users_table.id 
                    Where($this->table.id>0";
        } else {
            $sql = "select $this->table.*, 
					$this->category_table.name as categories_name,$this->category_table.name as categories_description,
					created_users_table.login_id as created_username, updated_users_table.login_id as updated_username, 
					status_table.l_value as status_name, status_table.color_name as status_color_name
					from $this->table 
					left join $this->category_table on $this->table.categories_id = $this->category_table.id 
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					left join $this->users_table as created_users_table on  $this->table.created_by = created_users_table.id
					left join $this->users_table as updated_users_table on   $this->table.updated_by = updated_users_table.id
					where ($this->table.id>0";
        }
		if(!empty($filter_data)){
			foreach ($filter_data as $k => $v) {
				if (($v['type'] == 'search') && ($v['value'] != "")) {
					$values = $v['value'];
					array_walk($search_field, function(&$value, $key) use ($values) {
						$value .= " like '%" . $values . "%'";
					});
					$sql .= ") AND (" . implode(" || ", $search_field);
				}else{				
					if($v['value']!=""){						
						if(in_array($v['type'],$this->in_field)){
							$v['type'] = $this->table.".".$v['type'];
						}						
						$sql .= ") AND ( ".$v['type']." ='".$v['value']."'";
					}				
				}			
			}
		}
        if (($item == 0) && ($page == 0)) {           
            $sql .= ")  order by $sortby  $orderby  ";
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                $count = $row['countt'];
            } else {
                $count = 0;
            }
            return $count;
        } else {         
            $sql .= ")"; 
			if(!empty($orderby)){
				$sql .=	 "ORDER BY $sortby  $orderby ";
			}	
			if(!$all){
				$sql .= "limit $page,$item";
			}
        }
        $query = $this->db->query($sql);
        return $query;
    }

	public function check_duplicate_service_code($data){		
		$this->db->where("(service_code='".$data['service_code']."')");
		$query=$this->db->get('services');
		return $query->row_array();			
	}
	
	function get_service_list($id=0,$categories_id=0){
		$sql = "select $this->table.*, 
				$this->category_table.name as categories_name,$this->category_table.name as categories_description,
				created_users_table.login_id as created_username, updated_users_table.login_id as updated_username, 
				status_table.l_value as status_name, status_table.color_name as status_color_name
				from $this->table 
				left join $this->category_table on $this->table.categories_id = $this->category_table.id 
				left join $this->lookups_table as status_table on $this->table.status = status_table.id 
				left join $this->users_table as created_users_table on  $this->table.created_by = created_users_table.id
				left join $this->users_table as updated_users_table on  $this->table.updated_by = updated_users_table.id 
				WHERE ($this->table.id>0) AND ($this->table.status=29) ";
		if(!empty($id)){
			$sql.=" AND ($this->table.id='".$id."')";
		}
		if(!empty($categories_id)){
			$sql.=" AND ($this->table.categories_id='".$categories_id."')";
		}
		$query = $this->db->query($sql);
		$data  = array();        
        foreach ($query->result_array() as $row){
            $data[] = $row;            
        }
		return $data;
	}
	
	function get_category_list(){
		$sql = "select $this->category_table.*, 
				created_users_table.login_id as created_username, updated_users_table.login_id as updated_username, 
				status_table.l_value as status_name, status_table.color_name as status_color_name 
				from $this->category_table 
				left join $this->lookups_table as status_table on $this->category_table.status = status_table.id 
				left join $this->users_table as created_users_table on  $this->category_table.created_by = created_users_table.id
				left join $this->users_table as updated_users_table on   $this->category_table.updated_by = updated_users_table.id";
		$query = $this->db->query($sql);
		$data  = array();        
        foreach ($query->result_array() as $row){
            $data[] = $row;            
        }
		return $data;
	}
	
	function buildTree(Array $data){
		$tree = array();
        foreach ($data as $d) {
			$services=$this->get_service_list(0,$d['id']);
            if (!empty($services)) {
                $d['_children'] = $services;
                $tree[] = $d;
            }
        }
        return $tree;
	}
	
	function get_active_plan($customer_id){
		$sql = "SELECT $this->plans_table.*
				FROM $this->plans_table
				WHERE $this->plans_table.reference_id='".$customer_id."'
				AND $this->plans_table.reference_type='customers'
				AND $this->plans_table.status=33
				ORDER BY $this->plans_table.id DESC LIMIT 1";
		$query = $this->db->query($sql);
		//print_r($this->db->last_query());
		return $query->row_array();
	}
	
	function get_customer_services($customer_id){
		$message='';
		$status='fail';
		
		$plan=$this->get_active_plan($customer_id);
		if(!empty($plan)){
			$sql = "SELECT 
					$this->plans_services_table.*,
					$this->table.service_code as services_code,
					$this->table.name as services_name,
					$this->table.icon as services_icon,
					$this->table.icon_color as services_icon_color
					FROM $this->plans_services_table
					INNER JOIN $this->table
					ON $this->plans_services_table.services_id=$this->table.id
					WHERE $this->plans_services_table.plans_id='".$plan['id']."'
					AND $this->table.status='29'";
			//print_r($sql);
			$query = $this->db->query($sql);
			if($query->num_rows()>0){
				$data=$query->result_array();
				$value=array('status'=>'success','message'=>'Found '.$query->num_rows().' services for this customer','data_list'=>$data);
			}else{
				$value=array('status'=>$status,'message'=>'No services found for this customer');
			}
		}else{
			$value=array('status'=>$status,'message'=>'Active plan not found for this customer');
		}
		return $value;
	}
	
	function get_workorder_services($workorders_id){
		$message='';
		$status='fail';
		$sql = "SELECT 
				$this->plans_services_table.*,
				$this->table.allow_multiple as allow_multiple,
				$this->table.edit_output as edit_output,
				$this->table.service_code as services_code,
				$this->table.name as services_name,
				$this->table.icon as services_icon,
				$this->table.icon_color as services_icon_color,
				$this->table.departments_id as services_departments_id
				FROM $this->plans_services_table
				INNER JOIN $this->workorder_table
				ON $this->workorder_table.plans_id=$this->plans_services_table.plans_id
				INNER JOIN $this->table
				ON $this->plans_services_table.services_id=$this->table.id
				WHERE $this->workorder_table.id='".$workorders_id."'
				AND $this->table.status='29'
				ORDER BY $this->table.name ASC";
		//print_r($sql);
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$data=$query->result_array();
			$value=array('status'=>'success','message'=>'Found '.$query->num_rows().' services for this customer','data_list'=>$data);
		}else{
			$value=array('status'=>$status,'message'=>'No services found for this customer');
		}
		return $value;
	}
		
    
}