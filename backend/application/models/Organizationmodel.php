<?php
class Organizationmodel extends CI_Model
{


	 function __construct() { 
        // Set table name 
        $this->table = 'organization'; 
		$this->uploads_table = 'uploads'; 
    } 
     

	 function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
         $search_field = array("name","address");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count(id)  as countt "
                    . "FROM ".$this->table."  "           
                    . "Where(id>0";
        } else {
            $sql = "select $this->table.*,
					$this->uploads_table.file_name as logo_name,$this->uploads_table.file_ext as logo_ext,$this->uploads_table.file_path as logo_path
					from $this->table					
					left join $this->uploads_table on $this->table.logo = $this->uploads_table.id
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

		
    
}