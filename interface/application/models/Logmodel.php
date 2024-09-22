<?php
class logmodel extends CI_Model{
	/* Activity logs */
	
	function get_side_activity_log(){
		$sql = "SELECT l.*,a.username 
				FROM activity_log l
				LEFT JOIN admin_users a
				ON a.adminId=l.adminId
				ORDER BY l.logId DESC limit 20";
		$query=$this->db->query($sql);
		return $query;
	}

	function filterActivityLogs($filter_data,$item,$page){					
		$sql = "SELECT al.*,au.username as adminName,c.email as customerName
				FROM activity_log al
				LEFT JOIN admin_users au 
				ON al.adminId=au.adminId 
				LEFT JOIN customers c 
				ON al.customerId=c.customerId 
				WHERE (al.logId>0";
		foreach ($filter_data as $k => $v) {
            if(($v['type']=='search')&&($v['value']!="")){
	 			$value = $v['value'];
                $sql .= " ) AND (au.username like '%" . $value . "%' or c.email like '%" . $value . "%' or al.module like '%" . $value . "%' or al.action like '%" . $value . "%' or al.description like '%" . $value . "%' or al.createdDate like '%" . $value . "%'";
			}
			if(($v['type']=='user')&&($v['value']!="")){
	 			$value = $v['value'];
                $sql .= " ) AND (c.email like '%".$value."%'";
			}
			if(($v['type']=='action')&&($v['value']!="")){
	 			$value = $v['value'];
                $sql .= " ) AND (al.action like '%" . $value . "%'";
			}
			if(($v['type']=='module')&&($v['value']!="")){
	 			$value = $v['value'];
                $sql .= " ) AND (al.module like '%" . $value . "%'";
			}
			if(($v['type']=='logType')&&($v['value']!="")){
	 			$value = $v['value'];
                $sql .= " ) AND (al.type like '%" . $value . "%'";
			}
			if($v['type']=='sortBy'){
				$value = $v['value'];
				if($value=='createdAsc'){
					$sql .= ")   ORDER BY al.createdDate ASC";
				}else if($value=='createdDesc'){
					$sql .= ")   ORDER BY al.createdDate DESC";
				}else{
					$sql .= ")   ORDER BY al.createdDate DESC";
				}
			}
		}
		//print_r($sql);
		if(($item==0) &&($page==0)){
			$query = $this->db->query($sql);
			return $query->num_rows();
		}
		else{
			$sql .= " limit $page,$item";
			$query=$this->db->query($sql);
			return $query;
		}
	}

    function export_logData($adminId,$uname,$module,$action,$from,$to){
        $sql="SELECT al.ip_address as IP_Address,a.username as User,
        		al.action as Action,al.module as Module,al.description as Description,al.createdDate as Date
                FROM activity_log al
                LEFT JOIN admin_users a
                ON a.adminId=al.adminId WHERE al.adminId='$adminId' ";
        if($from!="" && $to!=""){
            $sql.=" AND al.createdDate "
                    . "BETWEEN '$from' "
                    . " AND '$to' ";
        }
		if($module!=''){
			$sql.=" AND al.module='$module' ";
		}
		if($action!=''){
			$sql.=" AND al.action='$action'";
		}
		//print_r($sql);
        $query=$this->db->query($sql);
        return $query;
	}
	
	function get_log_actions(){
		$sql="SELECT DISTINCT(action) FROM activity_log WHERE action!='' ORDER BY action ASC";
		$query=$this->db->query($sql);
        return $query;
	}
	
	function get_log_modules(){
		$sql="SELECT DISTINCT(module) FROM activity_log WHERE module!='' ORDER BY module ASC";
		$query=$this->db->query($sql);
        return $query;
	}
}