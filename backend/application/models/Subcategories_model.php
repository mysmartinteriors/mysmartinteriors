<?php
class Subcategories_model extends CI_Model
{


	 function __construct() { 
        // Set table name 
        $this->table = 'subcategories'; 
		$this->users_table = 'users';
		$this->lookups_table = 'lookups';
        $this->uploads_table = "uploads";
    } 
     
	 protected $in_field = ['table_name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
         $search_field = array("$this->table.name");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt "
                    . "FROM ".$this->table."  "           
                    . "Where($this->table.id>0";
        } else {
            $sql = "select $this->table.*, 
					created_users_table.login_id as created_username, updated_users_table.login_id as updated_username, 
					status_table.l_value as status_name, status_table.color_name as status_color_name ,
                    upload_table.base_path, upload_table.file_path, upload_table.file_name
					from $this->table 
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					left join $this->users_table as created_users_table on  $this->table.created_by = created_users_table.id
					left join $this->users_table as updated_users_table on   $this->table.updated_by = updated_users_table.id
                    left join $this->uploads_table as upload_table on $this->table.image_id = upload_table.id
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

		
    
}