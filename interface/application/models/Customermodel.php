<?php

class Customermodel extends CI_Model{

    /*
     * Customers
     */

    function filter_customers($filter_data,$item=0,$page=0){                  
        $sql = "SELECT c.* FROM customers c WHERE (c.customerId>0";
        foreach ($filter_data as $k => $v) {
            if(($v['type']=='search')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (c.firstName like '%" . $value . "%' or c.lastName like '%" . $value . "%' or c.email like '%" . $value . "%'";
            }
            if(($v['type']=='user')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (c.email like '%".$value."%'";
            }
            if(($v['type']=='status')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (c.status=".$value;
            }
            if($v['type']=='sortBy'){
                $value = $v['value'];
                if($value=='createdAsc'){
                    $sql .= ")   ORDER BY c.createdDate ASC";
                }else if($value=='createdDesc'){
                    $sql .= ")   ORDER BY c.createdDate DESC";
                }else if($value=='updatedDesc'){
                    $sql .= ")   ORDER BY c.updatedDate DESC";
                }else{
                    $sql .= ")   ORDER BY c.createdDate ASC";
                }
            }
        }
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

    function save_customer($data){
        $customerId=$data['customerId'];
        if($customerId!=""){
            $this->db->where("customerId",$customerId);
            $this->db->update("customers",$data);
            $msg="Customer updated successfully";
            $logData['action']='update';
            $logData['description']='updated the customer details of '.$data['email'];
        }else{
            unset($data['customerId']);
            $data['createdDate']=$data['updatedDate'];
            $this->db->insert("customers",$data);
            $customerId=$this->db->insert_id();
            $msg="Customer saved successfully";
            $logData['action']='create';
            $logData['description']='created a new customer '.$data['email'];
        }
        if($customerId!=""){            
            //insert log data
            $logData['dataId']=$customerId;
            $logData['module']='customers';
            $logData['table_name']='customers';
            insert_aLog($logData);
        }
        
        $value=array('msg'=>$msg,'result'=>$customerId);
        return $value;
    }

    function get_customer($customerId){
        $this->db->where('customerId',$customerId);
        $query=$this->db->get('customers');
        return $query;
    }

    function delete_customer($id,$canDelete=0){
        $res=0;
        $b=1;
        $msg='Unable to delete at this time!';
        $sql="SELECT 1 
            FROM (
                SELECT customerId FROM shopping_cart
                UNION ALL
                SELECT customerId FROM users_support
            ) a
            WHERE customerId = $id";
        
        $query1=$this->db->query($sql);

        if($query1->num_rows()>0){
            $b=0;
            $msg='Customer is linked with other datas like cart, support etc...';
        }else if($b>0){
            $this->db->where('customerId',$id);
            $get_res=$this->db->get('customers');

            $this->db->where('customerId',$id);
            $this->db->delete('customers');
            $res= $this->db->affected_rows();

            //insert log data
            if($res>0){
                $msg='Customer has been removed from database';
                $logData['dataId']=$id;
                $logData['module']='customers';
                $logData['table_name']='customers';
                $logData['action']='delete';
                $logData['description']='deleted the customer '.$get_res->row()->email.' from database';
                insert_aLog($logData);
            }
        }
        $value=array('msg'=>$msg,'result'=>$res,'canDelete'=>$b);
        return $value;
    }

}