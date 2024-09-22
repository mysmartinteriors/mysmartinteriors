<?php
class Departmentmodel extends CI_Model
{


	 function __construct() { 
        // Set table name 
        $this->table = 'departments'; 
		$this->organization_branches_table = 'organization_branches';
    } 
     
    function get_department($id)
    {
        $sql ="SELECT d.*,p.name as parent_name, ob.name as organization_branches_name, lup.l_value as status_name
                from $this->table d 
                left join departments p
                on d.parent=p.id
                left join $this->organization_branches_table ob 
                on d.organization_branches_id = ob.id 
                left join lookups lup 
                on d.status = lup.id 
                where d.id ='".$id."'";
        $query=$this->db->query($sql);
        return $query;
    }

	
    function departmentTreeList($branch_id)
    {
		
		 $sql   = "select d.*, lup.l_value  from $this->table d left join lookups lup on d.status = lup.id where d.organization_branches_id ='".$branch_id."' order by d.id asc ";
        $query = $this->db->query($sql);
        $data  = array();
        
        foreach ($query->result_array() as $row) {
            
            $data[] = $row;
            
        }
        return $data;
    }
	
	function buildTree(Array $data, $parent = 0)
    {
        $tree = array();
        foreach ($data as $d) {
            if ($d['parent'] == $parent) {
                $children = $this->buildTree($data, $d['id']);
                // set a trivial key
                if (!empty($children)) {
                    $d['_children'] = $children;
                }
                $tree[] = $d;
            }
        }
        return $tree;
    }
    
   


}