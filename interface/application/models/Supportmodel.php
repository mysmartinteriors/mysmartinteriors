<?php
class supportmodel extends CI_Model{

	function create_support($data){
		$this->db->insert('support_enquiries',$data);
		$id=$this->db->insert_id();
		$res=0;
		if($id!=''){
			$code=$this->admin->randomCodenum(8);
			$date=getMyDbDate('%y',now());
			$up_data['code']="TKT".$code.$date.'-'.$id;
			$this->db->where('id',$id);
			$this->db->update('support_enquiries',$up_data);
			$res=$this->db->affected_rows();
			//print_r($up_data['code']);
		}
		if($res>0 && $id!=''){
            $logData['action']='create';
            $logData['description']=$data['email'].' sent a new support enquiry. This has been assigned to '.$up_data['code'];
			$logData['dataId']=$id;
            $logData['module']='support enquiries';
            $logData['table_name']='support_enquiries';
            insert_aLog($logData);
		}
		return $res;
	}

    function filter_datas($filter_data,$item,$page){                  
        $sql = "SELECT s.* FROM support_enquiries s WHERE (s.id>0";
        foreach ($filter_data as $k => $v) {
            if(($v['type']=='search')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (s.name like '%" . $value . "%' or s.email like '%" . $value . "%' or s.code like '%" . $value . "%'";
            }
            if(($v['type']=='user')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (s.email like '%".$value."%'";
            }
            if(($v['type']=='status')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (s.status=".$value;
            }
            if($v['type']=='sortBy'){
                $value = $v['value'];
                if($value=='createdAsc'){
                    $sql .= ")   ORDER BY s.submitDate ASC";
                }else{
                    $sql .= ")   ORDER BY s.submitDate DESC";
                }
            }
        }
        //print_r($sql);
        if(($item==0) &&($page==0)){
            $query = $this->db->query($sql);
            return $query->num_rows();
        }
        else{
            if (($item > 0) && ($page > 0)) {
                $sql .= " LIMIT $page, $item";
            }
            // $sql .= " limit $page,$item";
            $query=$this->db->query($sql);
            return $query;
        }
    }

    function get_ticket_comments($id){
        $sql = "SELECT s.*,c.firstName
                FROM support_enquiries_details s
                LEFT JOIN support_enquiries e
                ON e.id=s.support_id
                INNER JOIN customers c
                ON c.customerId=e.customerId
                WHERE s.support_id='$id'";
        $query=$this->db->query($sql);
        return $query;
    }

}