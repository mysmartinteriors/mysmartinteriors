<?php
class Customersmodel extends CI_Model
{
	function __construct() { 
        // Set table name 
        $this->table = 'customers'; 
		$this->users_table = 'users'; 
		$this->lookups_table = 'lookups';
    } 
     
	protected $in_field = ['name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
        $search_field = array("$this->table.firstName","$this->table.lastName", "$this->table.type", "$this->table.email", "$this->table.phone", "$this->table.city", "$this->table.state", "$this->table.postalCode");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt "
                    . "FROM ".$this->table."  "           
                    . "where ($this->table.id>0";
        }else{
            $sql = "select $this->table.* 
					from $this->table where ($this->table.id>0";
        }
		if(!empty($filter_data)){
			foreach ($filter_data as $k => $v) {
				if (($v['type'] == 'search') && ($v['value'] != "")) {
					$values = $v['value'];
					array_walk($search_field, function(&$value, $key) use ($values) {
						$value .= " like '%" . $values . "%'";
					});
					$sql .= ") AND (" . implode(" || ", $search_field);
				}else if($v['type']=='customers.status' && !empty($v['value'])){
					if($v['value']=='active'){
						$sql.=") AND (customers.status='1'";
					}else{
						$sql.=") AND (customers.status='0'";
					}
					unset($filter_data[$k]);
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

			if (!empty($sort_by) && !empty($orderby)) {
				$sql .= " ORDER BY $sort_by  $orderby ";
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

			if (!empty($sort_by) && !empty($orderby)) {
				$sql .= " ORDER BY $sort_by  $orderby ";
			}
			if (!$all) {
				$sql .= " limit $page,$item";
			}
		}
		$query = $this->db->query($sql);
		return $query;
	}
}