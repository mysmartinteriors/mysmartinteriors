<?php
class Ratingsmodel extends CI_Model
{
	function __construct() { 
        $this->table = 'product_ratings';  
		$this->customer_table = 'customers';
		$this->lookups_table = 'lookups'; 
		$this->users_table = 'users';
    } 
     
	protected $in_field = ['table_name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
         $search_field = array("$this->table.id", "$this->table.productId");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id) as countt 
					FROM $this->table 
					INNER JOIN $this->customer_table ON $this->customer_table.customerId = $this->table.customerId  
                    Where($this->table.id>0";
        } else {
            $sql = "select $this->table.*, $this->customer_table.firstName, $this->customer_table.lastName, $this->customer_table.email
					from $this->table 
					INNER JOIN $this->customer_table ON $this->customer_table.customerId=$this->table.customerId
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
		// print_R($sql);exit();
        $query = $this->db->query($sql);
        return $query;
    }
	
}

// }







