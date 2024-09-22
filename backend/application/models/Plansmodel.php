<?php
class Plansmodel extends CI_Model
{
	function __construct() { 
        // Set table name 
        $this->table = 'plans';  
		$this->users_table = 'users';
		$this->lookups_table = 'lookups'; 
        $this->plan_services = 'plans_services';  
        $this->services = 'services'; 
        $this->category_table = 'categories'; 
    } 
     
	protected $in_field = ['table_name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
         $search_field = array("$this->table.name","$this->table.plan_code","ids.reference_code");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id) as countt 
					FROM $this->table 
					LEFT JOIN (
						 (SELECT id,customer_code as reference_code, 'customers' AS type FROM customers)
					  UNION
						 (SELECT id,login_id as reference_code, 'vendors' AS type FROM vendors)
					) ids ON $this->table.reference_id = ids.id AND $this->table.reference_type = ids.type
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					left join $this->users_table as created_users_table on  $this->table.created_by = created_users_table.id 
					left join $this->users_table as updated_users_table on   $this->table.updated_by = updated_users_table.id 
                    Where($this->table.id>0";
        } else {
            $sql = "select $this->table.*, ids.reference_code,ids.reference_name,
					created_users_table.login_id as created_username, updated_users_table.login_id as updated_username, 
					status_table.l_value as status_name, status_table.color_name as status_color_name
					from $this->table 
					LEFT JOIN (
						(SELECT id,customer_code as reference_code,name as reference_name, 'customers' AS type FROM customers)
					  UNION
						(SELECT id,login_id as reference_code,name as reference_name, 'vendors' AS type FROM vendors)
					) ids ON $this->table.reference_id = ids.id AND $this->table.reference_type = ids.type
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					left join $this->users_table as created_users_table on  $this->table.created_by = created_users_table.id
					left join $this->users_table as updated_users_table on   $this->table.updated_by = updated_users_table.id
					where ($this->table.id>0";
		}
				// left join $this->services_table
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
			//print_r($sql); 
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
	
	function get_plan_detail($id){
		$sql = "select $this->table.*, ids.reference_code,ids.reference_name,
				created_users_table.login_id as created_username, updated_users_table.login_id as updated_username, 
				status_table.l_value as status_name, status_table.color_name as status_color_name
				from $this->table 
				LEFT JOIN (
					(SELECT id,customer_code as reference_code,name as reference_name, 'customers' AS type FROM customers)
				  UNION
					(SELECT id,login_id as reference_code,name as reference_name, 'vendors' AS type FROM vendors)
				) ids ON $this->table.reference_id = ids.id AND $this->table.reference_type = ids.type
				left join $this->lookups_table as status_table on $this->table.status = status_table.id 
				left join $this->users_table as created_users_table on  $this->table.created_by = created_users_table.id
				left join $this->users_table as updated_users_table on   $this->table.updated_by = updated_users_table.id
				where ($this->table.id='".$id."')";
		$query = $this->db->query($sql);
        $data  = array();
        foreach ($query->result_array() as $row){
            $data = $row;
        }
		$categories=$this->db->get($this->category_table);
		$tree = array();
		foreach ($categories->result_array() as $d){
			$services=$this->get_plan_services($id,$d['id']);
			if(!empty($services)){
				$d['_children']=$services;
				$tree[]=$d;
			}
		}
		if(!empty($tree)){
			$data['services'] = $tree;
		}
        return $data;
	}
	
	function get_plan_services($plan_id=0,$categories_id=0){
		$sql = "select $this->plan_services.*, $this->services.allow_multiple,
				$this->services.service_code,$this->services.categories_id,$this->services.name,$this->services.icon,$this->services.icon_color,
				created_users_table.login_id as created_username
				from $this->plan_services 
				inner join $this->services on $this->services.id = $this->plan_services.services_id 
				left join $this->users_table as created_users_table on  $this->plan_services.created_by = created_users_table.id 
				WHERE ($this->plan_services.id>0) ";
		if(!empty($plan_id)){
			$sql.=" AND ($this->plan_services.plans_id='".$plan_id."')";
		}
		if(!empty($categories_id)){
			$sql.=" AND ($this->services.categories_id='".$categories_id."')";
		}
		$query = $this->db->query($sql);
		$data  = array();        
        foreach ($query->result_array() as $row){
            $data[] = $row;            
        }
		return $data;
	}


	public function check_create_plan($input){
		$status='fail';
		$year_count=0;
		$year=date('Y');
		$code='';
		
		$sql="SELECT *
				FROM ".$input['reference_type']."
				WHERE id='".$input['reference_id']."'";
		$query = $this->db->query($sql);
		//print_r($this->db->last_query());
		if($query->num_rows()>0){
			$reference_data=$query->row_array();
			$this->db->where("reference_id",$input['reference_id']);
			$this->db->where("reference_type",$input['reference_type']);			
			$plan_query=$this->db->get('plans');
			if($plan_query->num_rows()>0){
				foreach($plan_query->result() as $datas){
					//$up_data=array(
					//	'status'=>34,
					//	'updated_by'=>$input['created_by'],
					//	'updated_at'=>cur_date_time()
					//);				
					//$this->db->where("reference_id",$input['reference_id']);
					//$this->db->where("reference_type",$input['reference_type']);
					//$this->db->where("id",$datas->id);
					//$this->db->where("status",33);
					//$this->db->update('plans',$up_data);
					
					$year_created=custom_date('Y',$datas->created_at);
					if($year==$year_created){
						$year_count++;
					}
				}
			}
			
			$variable = rtrim($input['reference_type'],"s");
			$variable = $variable.'_code';
			$code=$reference_data[$variable].'-'.$year.'-'.($year_count+1);
			
			$status='success';
			$value=array('status'=>$status,'message'=>'','code'=>$code);
		}else{
			$value=array('status'=>$status,'message'=>'Reference data not found, it seems reference id is invalid','code'=>$code);
		}
		return $value;
		
	}
	
	
	function get_customer_plans($customer_id){
		$cur_time=cur_date_time();
		$sql = "SELECT $this->table.*
				FROM $this->table
				WHERE $this->table.reference_id='".$customer_id."'
				AND $this->table.reference_type='customers'
				AND $this->table.status=33
				AND ($this->table.start_date<='".$cur_time."') AND ($this->table.end_date>='".$cur_time."')
				ORDER BY $this->table.name ASC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
		
    
}