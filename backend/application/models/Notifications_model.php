<?php
class Notifications_model extends CI_Model
{

	function __construct() { 
        // Set table name 
        $this->table = 'notifications'; 
		$this->users_table = 'users';
		$this->lookups_table = 'lookups';
    } 
     
	protected $in_field = ['table_name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
        $search_field = array("$this->table.title","$this->table.reference_url");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt "
                    . "FROM ".$this->table."  "           
                    . "Where($this->table.id>0";
        } else {
            $sql = "select $this->table.*,
					CASE 
						WHEN $this->table.is_seen=1 THEN 'Unseen'
						WHEN $this->table.is_seen=0 THEN 'Seen'
					END as seen_status,
					reference_table.l_value as refer_type 
					FROM $this->table 
					left join $this->lookups_table as reference_table on $this->table.reference_type = reference_table.id 
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
            if(!empty($sortby)){
				$sql .=	 ") ORDER BY $sortby  $orderby ";
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
				if(!empty($sortby)){
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