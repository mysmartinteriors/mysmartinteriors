<?php
class Organization_branchesmodel extends CI_Model
{


	 function __construct() { 
        // Set table name 
        $this->table = 'organization_branches'; 
		$this->organization_table = 'organization';
		$this->countries_table = 'countries';
		$this->states_table = 'states';
		$this->cities_table = 'cities';
		$this->lookups_table = 'lookups';
		$this->users_table = 'users';
		
    } 
     
	  protected $in_field = ['name', 'address', 'phone', 'GSTIN','PAN','CIN'];
	  
	  
	 

	 function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
       
		 $search_field = array("$this->table.name","$this->table.address","$this->table.phone"," $this->table.GSTIN");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt "
                    . "FROM $this->table
					left join $this->lookups_table  on $this->table.status = $this->lookups_table.id 
					inner join $this->organization_table  on $this->table .organization_id = $this->organization_table.id
					inner join $this->countries_table  on $this->table.countries_id = $this->countries_table.id
					inner join $this->states_table  on $this->table.states_id = $this->states_table.id
					inner join $this->cities_table  on $this->table.cities_id = $this->cities_table.id 
					left join $this->users_table as created_users_table on  $this->organization_table.created_by = created_users_table.id
					left join $this->users_table as updated_users_table on   $this->organization_table.updated_by = updated_users_table.id "         
                    . "Where($this->table.id>0";
        } else {
            $sql = "select $this->organization_table.name as organization_name, $this->table.*,  $this->cities_table.name as city, $this->states_table.name as state, $this->countries_table.name as country ,  $this->lookups_table.l_value, 
					 created_users_table.login_id as created_username, updated_users_table.login_id as updated_username
						FROM  $this->table
					left join $this->lookups_table  on $this->table.status = $this->lookups_table.id 
					inner join $this->organization_table  on $this->table .organization_id = $this->organization_table.id
					inner join $this->countries_table  on $this->table.countries_id = $this->countries_table.id
					inner join $this->states_table  on $this->table.states_id = $this->states_table.id
					inner join $this->cities_table  on $this->table.cities_id = $this->cities_table.id 
					left join $this->users_table as created_users_table on  $this->organization_table.created_by = created_users_table.id
					left join $this->users_table as updated_users_table on   $this->organization_table.updated_by = updated_users_table.id "             
                    . "Where($this->table.id>0";
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
					$sql .=	 " ) ORDER BY $sortby  $orderby ";
				}
            

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