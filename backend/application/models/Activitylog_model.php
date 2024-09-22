<?php
class Activitylog_model extends CI_Model
{


	function __construct() { 
        // Set table name 
        $this->table = 'activity_log'; 
		$this->users_table = 'users';
		$this->lookups_table = 'lookups';
    } 
     
	protected $in_field = ['table_name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
        $search_field = array("$this->table.action,$this->table.module,$this->table.reference_name");
		$dateRange = ""; 
        foreach($filter_data as $key=>$val){
            if($val['type']=='daterange'){
                $dateRange = $val['value'];
				unset($filter_data[$key]);
            }
        }
        $from = '';
        $to = '';
        if (!empty($dateRange)) {
            $fromTime = substr($dateRange, 0, 10);
            $toTime = substr($dateRange, 13, 10);
            $fromTime = $fromTime != "" ? custom_date("Y-m-d", $fromTime) : "";
            $toTime = $toTime != "" ? custom_date("Y-m-d", $toTime) : "";
            $from = $fromTime . ' 00:00:00';
            $to = $toTime . ' 23:59:59';
        }

        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt "
                    . "FROM ".$this->table."  "           
                    . "Where($this->table.id>0";
        } else {
            $sql = "select $this->table.*,
					reference_table.l_value as refer_type 
					FROM $this->table 
					left join $this->lookups_table as reference_table on $this->table.reference_type = reference_table.id 
					where ($this->table.id>0";
        }


		if(!empty($from) && !empty($to)){
			$sql.=" ) AND ($this->table.created_at BETWEEN '$from' AND '$to'";
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

    function get_actions_log(){
		$sql="SELECT DISTINCT(action) FROM activity_log WHERE action!='' ORDER BY action ASC";
		$query=$this->db->query($sql);
        return $query;
	}

    function get_modules_log(){
		$sql="SELECT DISTINCT(module) FROM activity_log WHERE module!='' ORDER BY module ASC";
		$query=$this->db->query($sql);
        return $query;
	}
    
}