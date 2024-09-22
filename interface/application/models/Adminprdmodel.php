<?php
class adminprdmodel extends CI_Model{

    /*
	 * Admin Products Module
	 */

    function filter_products($filter_data,$item,$page){                  
        $sql = "SELECT p.*,cc.text as categoryName,cp.text as parentName
                FROM products p 
                INNER JOIN categories cc
                ON cc.id=p.categoryId
                INNER JOIN categories cp
                ON cp.id=p.parentId
                WHERE (p.productId>0";
        foreach ($filter_data as $k => $v) {
            if(($v['type']=='search')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (p.productCode like '%" . $value . "%' or p.productName like '%" . $value . "%' or cp.text like '%" . $value . "%' or cc.text like '%" . $value . "%'";
            }
            if(($v['type']=='prdCode')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (p.productCode like '%".$value."%'";
            }
            if(($v['type']=='prdName')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (p.productName like '%".$value."%'";
            }
            if(($v['type']=='catType')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (p.categoryId=".$value;
            }
            if(($v['type']=='status')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (p.status=".$value;
            }
            if($v['type']=='sortBy'){
                $value = $v['value'];
                if($value=='createdAsc'){
                    $sql .= ")   ORDER BY p.createdDate ASC";
                }else if($value=='createdDesc'){
                    $sql .= ")   ORDER BY p.createdDate DESC";
                }else if($value=='updatedDesc'){
                    $sql .= ")   ORDER BY p.updatedDate DESC";
                }else{
                    $sql .= ")   ORDER BY p.createdDate ASC";
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


	function save_product($data){
        $productId=$data['productId'];
        if($productId!=""){
            $this->db->where("productId",$productId);
            $this->db->update("products",$data);
            $res=$this->db->affected_rows();
            $msg="Product updated successfully";
            $logData['action']='update';
            $logData['description']='updated the product '.$data['productCode'].'-'.$data['productName'];
        }else{
            unset($data['productId']);
            $data['createdDate']=$data['updatedDate'];
            $this->db->insert("products",$data);
            $productId=$this->db->insert_id();
            $featureData['productId']=$productId;
            $featureData['content']='';
            $featureData['content_html']='';
            $featureData['createdDate']=$data['updatedDate'];
            $featureData['updatedDate']=$data['updatedDate'];
            $this->db->insert("product_features",$featureData);

            $res=$productId;
            $msg="Product saved successfully";
            $logData['action']='create';
            $logData['description']='created a new product '.$data['productCode'].'-'.$data['productName'];
        }
        if($res>0){
            //insert log data
            $logData['dataId']=$productId;
            $logData['module']='products';
            $logData['table_name']='products';
            insert_aLog($logData);
        }
        
        $value=array('msg'=>$msg,'dataId'=>$productId,'result'=>$res);
        return $value;
	}

	function import_product_data($data){
		$productCode=$data['productCode'];
		$this->db->where("productCode",$productCode);
        $this->db->get("products");
        $res1=$this->db->affected_rows();
        if($res1>0){
            $this->db->where("productCode",$productCode);
            $this->db->get("products",$data);
            $type="update";
        }else{
            unset($data['productId']);
            $data['createdDate']=$data['updatedDate'];
            $this->db->insert("products",$data);
            $productCode=$this->db->insert_id();
            $type="insert";
        }
        
        $value=array('type'=>$type,'result'=>$productCode);
        return $value;
	}

	function get_product($id){
		$sql="SELECT p.* FROM products p
				INNER JOIN categories c
				ON p.categoryId=c.categoryId
				WHERE p.productId='$id'";
		$result=$this->db->query($sql);
		return $result;
	}

	function delete_product($id,$canDelete=0){
        $res=0;
        $b=1;
        $msg='Unable to delete at this time!';
        $sql="SELECT 1 
            FROM (
                SELECT productId FROM shopping_cart
                UNION ALL
                SELECT productId FROM offers
            ) a
            WHERE productId = $id";
        
        $query1=$this->db->query($sql);

        if($query1->num_rows()>0){
            $b=0;
            $msg='Product is linked with other datas like cart, offers etc...';
        }else if($b>0){
            $this->db->where('productId',$id);
            $get_res=$this->db->get('products');

    		$this->db->where('productId',$id);
    		$this->db->delete('products');
    		$res= $this->db->affected_rows();

            //insert log data
            if($res>0){
                $msg='Product has been removed from database';
                $logData['dataId']=$id;
                $logData['module']='products';
                $logData['table_name']='products';
                $logData['action']='delete';
                $logData['description']='deleted the product '.$get_res->row()->productCode.'-'.$get_res->row()->productName.' from database';
                insert_aLog($logData);
            }
        }
        $value=array('msg'=>$msg,'result'=>$res,'canDelete'=>$b);
        return $value;
	}

    function filter_feature_products($filter_data,$item,$page){           
        $sql = "SELECT fp.featureId,.status as fp_stfpatus,p.*,c.text as categoryName
                FROM featured_products fp
                INNER JOIN products p 
                ON p.productId=fp.productId
                LEFT JOIN categories c
                ON p.categoryId=c.id
                WHERE (fp.featureId>0";
        foreach ($filter_data as $k => $v) {
            
            if(($v['type']=='search')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (p.productCode like '%" . $value . "%' or p.productName like '%" . $value . "%' or p.categoryName like '%" . $value . "%'";
            }
            if(($v['type']=='prdCode')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (p.productCode like '%".$value."%'";
            }
            if(($v['type']=='prdName')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (p.productName like '%".$value."%'";
            }
            if(($v['type']=='catType')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (p.categoryId=".$value;
            }
            if(($v['type']=='status')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (fp.status=".$value;
            }
            if($v['type']=='sortBy'){
                $value = $v['value'];
                if($value=='createdAsc'){
                    $sql .= ")   ORDER BY fp.createdDate ASC";
                }else if($value=='createdDesc'){
                    $sql .= ")   ORDER BY fp.createdDate DESC";
                }else if($value=='updatedDesc'){
                    $sql .= ")   ORDER BY fp.updatedDate DESC";
                }else{
                    $sql .= ")   ORDER BY fp.createdDate ASC";
                }
            }
        }
            
        if(($item==0) &&($page==0)){
        $query = $this->db->query($sql);
        return $query->num_rows();
        }
        else{
        $query=$this->db->query($sql);
            return $query;
        }
    }

    function check_product_bycode($productCode){
        $this->db->where('productCode',$productCode);
        $query=$this->db->get('products');
        $result=$query->num_rows();
        $productId='';
        $categoryId='';
        if($result>0){
            $productId=$query->row()->productId;
            $categoryId=$query->row()->categoryId;
        }
        $value=array('result' => $result,'productId' => $productId,'categoryId' => $categoryId);
        return $value;
    }

    function save_Featureproduct($data){
        $productId=$data['productId'];
        $this->db->where("productId",$productId);
        $query1=$this->db->get("featured_products");
        if($query1->num_rows()>0){
            $msg="Product already exists in featured list!";
            $result=0;
        }else{
            $this->db->where('productId',$productId);
            $query2=$this->db->get('products');
            $this->db->insert("featured_products",$data);
            $result=1;
            $msg="Product has been added to the featured list";
        }

        if($result>0){
            //insert log data
            $logData['dataId']=$productId;
            $logData['module']='Featured Products';
            $logData['table_name']='featured_products';
            $logData['action']='add';
            $logData['description']='added the product '.$query2->row()->productCode.'-'.$query2->row()->productName.' into featured list';
            insert_aLog($logData);
        }
        
        $value=array('msg'=>$msg,'result'=>$productId);
        return $value;
    }

    function delete_feature_product($id){
        $this->db->where('featureId',$id);
        $query=$this->db->get('featured_products');

        $this->db->where('productId',$query->row()->productId);
        $query2=$this->db->get('products');

        $this->db->where('featureId',$id);
        $this->db->delete('featured_products');
        $res=$this->db->affected_rows();
        if($res>0){
            //insert log data
            $logData['dataId']=$id;
            $logData['module']='Featured Products';
            $logData['table_name']='featured_products';
            $logData['action']='delete';
            $logData['description']='removed the product '.$query2->row()->productCode.'-'.$query2->row()->productName.' from featured list';
            insert_aLog($logData);
        }
        return $res;
    }

    function feature_product_status($id,$status){
        $sql="UPDATE featured_products SET status='$status' WHERE featureId='$id'";
        $this->db->query($sql);
        $result=$this->db->affected_rows();
        if($status==0){
            $msg='Product made inactive to display from featured list';
        }else{
            $msg='Product made active to display in featured list';
        }
        $value=array('msg'=>$msg,'result'=>$result);
        return $value;
    }

    function save_prd_features($data){
        $productId=$data['productId'];
        $id=$data['id'];
        $this->db->where("productId",$productId);
        $get_prd=$this->db->get("products",$data);
        if($get_prd->num_rows()>0){
            if($id!=''){
                $this->db->where("productId",$productId);
                $this->db->update("product_features",$data);
                $res=$this->db->affected_rows();
                $msg="Product details updated successfully";
                $logData['action']='update';
                $logData['description']='updated the product details of '.$get_prd->row()->productCode.'-'.$get_prd->row()->productName;
            }else{
                $data['createdDate']=$data['updatedDate'];
                $this->db->insert("product_features",$data);
                $id=$this->db->insert_id();
                $res=$id;
                $msg="Product details created successfully";
                $logData['action']='create';
                $logData['description']='created product details for '.$get_prd->row()->productCode.'-'.$get_prd->row()->productName;
            }
            if($res>0){
                //insert log data
                $logData['dataId']=$id;
                $logData['module']='product features';
                $logData['table_name']='product_features';
                insert_aLog($logData);
            }
        }
        
        $value=array('msg'=>$msg,'dataId'=>$id,'result'=>$res);
        return $value;
    }

    function save_prd_images($data){
        $productId=$data['productId'];
        $this->db->where("productId",$productId);
        $get_prd=$this->db->get("products",$data);
        if($get_prd->num_rows()>0){
            if($productId!=''){
                $this->db->where("productId",$productId);
                $this->db->delete("product_images");
                for($i=0;$i<count($data['filePath']);$i++){
                    if(isset($data['filePath'][$i]) && $data['filePath'][$i]!=''){
                        $arr = array(
                            'productId' => $productId,
                            'filePath' => $data['filePath'][$i],
                            'createdDate' => $data['createdDate'],
                        );
                        $this->db->insert("product_images",$arr);
                    }
                }
                $res=1;
                $msg="Product images updated successfully";
                $logData['action']='update';
                $logData['description']='updated the product images of '.$get_prd->row()->productCode.'-'.$get_prd->row()->productName;
            }else{
                $res=0;
                $msg="Product not found!";
            }
            if($res>0){
                //insert log data
                $logData['dataId']=$productId;
                $logData['module']='product images';
                $logData['table_name']='product_images';
                insert_aLog($logData);
            }
        }
        
        $value=array('msg'=>$msg,'result'=>$res);
        return $value;
    }

}