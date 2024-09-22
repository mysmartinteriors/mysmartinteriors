<?php
class Couponsmodel extends CI_Model{

    function filter_datas($filter_data,$item,$page){
		$sqlOrderBY='';
		$sql = "SELECT c.*
				FROM product_coupons c
				WHERE (c.id>0";

		if(!empty($filter_data)){
			foreach ($filter_data as $k => $v) {
	            if(($v['type']=='search')&&($v['value']!="")){
			 			$value = $v['value'];
	                    $sql .= " ) AND (c.coupon_code like '%" . $value . "%' or c.coupon_value like '%" . $value . "%'";
				}
				if(($v['type']=='c_code')&&($v['value']!="")){
			 			$value = $v['value'];
	                    $sql .= " ) AND (c.coupon_code='".$value."'";
				}
                if(($v['type']=='status')&&($v['value']!="")){
                        $value = $v['value'];
                        $sql .= " ) AND (c.status=".$value;
                }
				if(($v['type']=='p_type')&&($v['value']!="")){
			 			$value = $v['value'];
	                    $sql .= " ) AND (c.price_type='".$value."'";
				}
                if(($v['type']=='applicable')&&($v['value']!="")){
                        $value = $v['value'];
                        $sql .= " ) AND (c.applicable_to='".$value."'";
                }
				if (($v['type']== 'sort')&&($v['value']!="")) {
	                $value = $v['value'];
	                if ($value == "createdAsc") {
	                    $sqlOrderBY = " ORDER BY c.createdDate ASC ";
	                }else if ($value == "updatedDesc") {
	                    $sqlOrderBY = " ORDER BY c.updatedDate DESC ";
	                }else {
	                    $sqlOrderBY = " ORDER BY c.updatedDate DESC ";
	                }
	            }
			}
		}

		if($sqlOrderBY==''){
			$sqlOrderBY=') ORDER BY c.updatedDate DESC ';
		}
			
		if(($item==0) &&($page==0)){
			$sql.=$sqlOrderBY;
			//print_r($sql);
	        $query = $this->db->query($sql);
	        return $query->num_rows();
		}
		else{
			$sql .= $sqlOrderBY." limit $page,$item";
			$query=$this->db->query($sql);
			return $query;
		}
	}

    function save_coupon($data){
        $id=$data['id'];
        $res=0;
        $msg='';
        if($id!=''){
            $this->db->where("id",$id);
            $this->db->update("product_coupons",$data);
            $res=$this->db->affected_rows();
            $msg="Coupon details has been updated successfully";
            $logData['action']='update';
            $logData['description']='updated the coupon '.$data['coupon_code'];
        }else{
            unset($data['id']);
            $data['createdDate']=get_curentTime();
            $this->db->insert("product_coupons",$data);
            $res=$this->db->insert_id();
            $msg="New coupon has been created successfully";
            $logData['action']='create';
            $logData['description']='created a new coupon '.$data['coupon_code'];
        }
        //print_r($this->db->last_query());
        if($res>0){
            //insert log data
            $logData['dataId']=$id;
            $logData['module']='product coupons';
            $logData['table_name']='product_coupons';
            insert_aLog($logData);
        }
        
        $value=array('msg'=>$msg,'result'=>$res);
        return $value;
    }

    function delete_coupon($id){
        $res=0;
        $b=0;
        $result='fail';
        $msg='Unable to delete at this time!';
            $this->db->where('id',$id);
            $get_res=$this->db->get('product_coupons');

            $this->db->where('id',$id);
            $this->db->delete('product_coupons');
            $res= $this->db->affected_rows();

            //insert log data
            if($res>0){
                $result='success';
                $msg='Coupon has been deleted from database';
                $logData['dataId']=$id;
                $logData['module']='product coupons';
                $logData['table_name']='product_coupons';
                $logData['action']='delete';
                $logData['description']='deleted the coupon from database '.$get_res->row()->coupon_code;
                insert_aLog($logData);
            }
        $value=array('msg'=>$msg,'result'=>$result,'canDelete'=>$b);
        return $value;
    }

}