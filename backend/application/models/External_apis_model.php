<?php
class External_apis_model extends CI_Model
{
	function __construct() { 
        // Set table name 
        $this->table = 'external_apis'; 
		$this->users_table = 'users';
		$this->lookups_table = 'lookups';
		$this->services_table = 'services';
    } 
     
	protected $in_field = ['name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
        $search_field = array("$this->table.name");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt "
                    . "FROM ".$this->table."  "           
                    . "Where($this->table.id>0";
        } else {
            $sql = "select $this->table.*, 
					created_users_table.login_id as created_username, 
					updated_users_table.login_id as updated_username 
					from ".$this->table." 
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
            $sql .= ")";
			if(!empty($orderby) && !empty($sortby)){
				$sql .=	 "ORDER BY $sortby  $orderby ";
			}          

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
			if(!empty($orderby) && !empty($sortby)){
				$sql .=	 "ORDER BY $sortby  $orderby ";
			}			
			if(!$all){
			 $sql .= "limit $page,$item";
			}
		}
		$query = $this->db->query($sql);
		return $query;
	}
	

		
    
}