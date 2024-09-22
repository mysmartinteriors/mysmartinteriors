<?php
class Customer_subscription_model extends CI_Model
{
	function __construct() { 
        // Set table name 
        $this->table = 'user_subscription';  
        $this->subscription_table = 'subscription';  

		$this->lookups_table = 'lookups'; 
    } 
     
	protected $in_field = ['table_name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
         $search_field = array("$this->table.name", "$this->table.code");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id) as countt 
					FROM $this->table 
					LEFT JOIN $this->subscription_table ON $this->table.subscription_id = $this->subscription_table.id
                    Where($this->table.id>0 ";
        } else {
            $sql = "select $this->table.*, $this->subscription_table.basic_amount as subscription_base_amount, $this->subscription_table.wallet_points as subscription_wallet_points, $this->subscription_table.name as subscription_name
					from $this->table 
					LEFT JOIN $this->subscription_table ON $this->table.subscription_id = $this->subscription_table.id
					where ($this->table.id>0 ";
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
			// print_R($sql);exit();
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
	
}







