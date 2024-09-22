<?php
class productsmodel extends CI_Model{


	/* User frontend */

	function get_featured_products(){
		$sql="SELECT p.*,fp.*,sum(r.ratingValue) as ratingValue,count(r.ratingId) as ratingCount
			FROM products p
			LEFT JOIN featured_products fp
			ON p.productId=fp.productId
			LEFT JOIN product_ratings r
			ON r.productId=fp.productId
			WHERE fp.status='1'
			GROUP BY fp.productId ORDER BY fp.featureId";
		$query=$this->db->query($sql);
		return $query;
	}

	function get_new_products(){
		$sql="SELECT p.*,sum(r.ratingValue) as ratingValue,count(r.ratingId) as ratingCount
			FROM products p
			LEFT JOIN product_ratings r
			ON r.productId=p.productId
			WHERE p.status='1' AND p.is_primary=1
			GROUP BY p.productId ORDER BY p.updatedDate DESC limit 10";
		$query=$this->db->query($sql);
		return $query;
	}

	function get_product_qview($id){
		$sql="SELECT p.*,sum(r.ratingValue) as ratingValue,count(r.ratingId) as ratingCount
			FROM products p
			LEFT JOIN product_ratings r
			ON p.productId=r.productId
			WHERE p.productId='$id'";
		$query=$this->db->query($sql);
		return $query;
	}



	function update_exist_new_cart($where,$data){
		$updata = $this->update_table_data('shopping_cart',$where,$data);			
	}

	function update_table_data($table, $where, $data) {
        $this->db->where($where);
        $this->db->update($table, $data);
        $id = $this->db->affected_rows();
        return $id;
    }

    function insert_table_data($table,$data){
    	$this->db->insert($table,$data);
    	return $this->db->insert_id();
    }

    function get_table_data($table, $where){
    	$this->db->where($where);	
		$query=$this->db->get($table);
		return $query;
    }

    function getProduct($producURL){
		$sql = "SELECT p.*,
				cc.text as categoryName,cp.text as parentName,cc.code as s_code, cp.code as p_code,
				f.content_html as prdDetails,
				sum(r.ratingValue) as ratingValue,
				count(r.ratingId) as ratings,r.reviewContent
				FROM products p
				INNER JOIN categories cc
				ON cc.id=p.categoryId
				INNER JOIN categories cp
				ON cp.id=p.parentId
				LEFT JOIN product_features f
				ON p.productId=f.productId
				LEFT JOIN product_ratings r
				ON p.productId=r.productId
				WHERE p.productURL='$producURL' AND p.status='1'";
		$query=$this->db->query($sql);
		return $query;
    }

    function get_offer_of_product($id){
    	$this->db->where("status", 1);
		$this->db->where("productCode", $id);
        $query = $this->db->get('offers');
        return $query;
    }

    function get_suggested_products($categoryId,$productId){
    	$sql = "SELECT *
    			FROM products 
    			WHERE categoryId='$categoryId' AND productId!='$productId' 
    			ORDER BY updatedDate DESC LIMIT 10";
    	$query=$this->db->query($sql);
    	return $query;
    }

    function filter_products_bycat($filter_data,$item,$page,$parent_cat){
		
		$ct = 0; 
		$sqlOrderBY = " ORDER BY p.createdDate DESC ";
		$sql = "SELECT p.*,c.text as categoryName,sum(r.ratingValue) as ratingValue,count(r.productId) as ratingCount
				FROM products p
				LEFT JOIN categories c
				ON c.id=p.categoryId
				LEFT JOIN product_ratings r
				ON p.productId=r.productId
				WHERE (p.is_primary=1 ";
		foreach ($filter_data as $k => $v) {
            if(($v['type']=='search')&&($v['value']!="")){
		 			$value = $v['value'];
                    $sql .= " ) AND (p.productCode like '%" . $value . "%' or p.productName like '%" . $value . "%' or p.description like '%" . $value . "%' or c.text like '%" . $value . "%";
			}
			if(($v['type']=='scat_type')&&($v['value']!="")){
                ++$ct;
				$value = $v['value'];
                if ($ct > 1) {
                    $sql .= " OR c.code like '%" . $value . "%'";
                } else {
                    $sql .= " ) AND (c.code like '%" . $value . "%'";
                }
			}
			if(($v['type']=='min-price')&&($v['value']!="")){
		 			$value = $v['value'];
                    $sql .= " ) AND (p.price>=".$value;
			}
			if(($v['type']=='max-price')&&($v['value']!="")){
		 			$value = $v['value'];
                    $sql .= " ) AND (p.price<=".$value;
			}
			if (($v['type'] == 'sort')&&($v['value']!="")) {
                $value = $v['value'];
                if ($value == "low to high") {
                    $sqlOrderBY = " ORDER BY p.price ASC ";
                }else if ($value == "high to low") {
                    $sqlOrderBY = " ORDER BY p.price DESC ";
                }else if ($value == "newest") {
                    $sqlOrderBY = " ORDER BY p.updatedDate DESC ";
                }else {
                    $sqlOrderBY = " ORDER BY p.updatedDate DESC ";
                }
            }
		}
		$sql.=")  GROUP BY p.productId";
			
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

	function filter_products_all($filter_data,$item,$page,$parent_cat){
		
		$ct = 0;
		$sqlOrderBY = " ORDER BY p.createdDate DESC ";
		$sql = "SELECT p.*,cc.text as categoryName,cp.text as parentName,
				sum(r.ratingValue) as ratingValue,count(r.productId) as ratingCount
				FROM products p
				INNER JOIN categories cc
				ON cc.id=p.categoryId
				INNER JOIN categories cp
				ON cp.id=p.parentId
				LEFT JOIN product_ratings r
				ON p.productId=r.productId
				WHERE (p.is_primary=1 ";
		foreach ($filter_data as $k => $v) {
            if(($v['type']=='q')&&($v['value']!="")){
		 			$value = $v['value'];
                    $sql .= " ) AND (p.productName like '%".$value."%' or cc.text like '%".$value."%' or cp.text like '%".$value."%'";
			}

			if(($v['type']=='cat_type')&&($v['value']!="")){
		 			$value = $v['value'];
                    $sql .= " ) AND (cp.code ='".$value."'";
			}
			if(($v['type']=='scat_type')&&($v['value']!="")){
                ++$ct;
				$value = $v['value'];
                if ($ct > 1) {
                    $sql .= " OR cc.code like '%" . $value . "%'";
                } else {
                    $sql .= " ) AND (cc.code like '%" . $value . "%'";
                }
			}
			if(($v['type']=='min-price')&&($v['value']!="")){
		 			$value = $v['value'];
                    $sql .= " ) AND (p.price>=".$value;
			}
			if(($v['type']=='max-price')&&($v['value']!="")){
		 			$value = $v['value'];
                    $sql .= " ) AND (p.price<=".$value;
			}
			if (($v['type'] == 'sort')&&($v['value']!="")) {
                $value = $v['value'];
                if ($value == "low to high") {
                    $sqlOrderBY = " ORDER BY p.price ASC ";
                }else if ($value == "high to low") {
                    $sqlOrderBY = " ORDER BY p.price DESC ";
                }else if ($value == "newest") {
                    $sqlOrderBY = " ORDER BY p.updatedDate DESC ";
                }else {
                    $sqlOrderBY = " ORDER BY p.updatedDate DESC ";
                }
            }
		}
		$sql.=")  GROUP BY p.productId";
			
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

	function get_product_ratings($id){
		$sql="SELECT r.*,u.firstName,u.lastName
				FROM product_ratings r
				LEFT JOIN customers u
				ON u.customerId=r.customerId
				WHERE r.productId='$id'
				ORDER BY r.ratedDate";
		$query=$this->db->query($sql);
		return $query;
	}

	function filter_prices(){
		$sql = "SELECT MIN(p.price) as min_price, MAX(p.price) as max_price 
				FROM products p";
		$query=$this->db->query($sql);
		return $query;
	}

	function filter_category($categoryName){
    	$this->db->where("status", 1);
		$this->db->where("code", $categoryName);
        $query1 = $this->db->get('categories');
        $cat_list='';
		if($query1->num_rows()>0){
			$cat_list.='<li>
	            <label class="">
	            <input type="checkbox" class="refine_filter" data-name="category" data-type="cat_type" value="'.$query1->row()->code .'"  data-text="'.$query1->row()->text .'" disabled>'.ucwords($query1->row()->text).'</label>';
			$parentId=$query1->row()->id;

			//if(isset($parentId) && $parentId!=0){
		    	$this->db->where("status", 1);
				$this->db->where("parent", $parentId);
		        $query2 = $this->db->get('categories');

				if($query2->num_rows()>0){
					$cat_list.='<ul>';
					foreach ($query2->result() as $sub_cats) {
						$cat_list.='<li>
				            <label class="checkbox checkbox-success">
				            <input type="checkbox" class="refine_filter" data-name="subcategory" data-type="scat_type" value="'.$sub_cats->code .'" data-text="'.$sub_cats->text .'"><span class="input-span"></span>'.ucwords($sub_cats->text).'</label></li>';
					}
					$cat_list.='</ul>';
				}
				$cat_list.='</li>';

		}
		return $cat_list;
	}

	function get_avail_options($tag_id,$id){
		$sql = "SELECT * 
				FROM products 
				WHERE tag_id='$tag_id' AND productId!='$id'";
		$query = $this->db->query($sql);
		return $query;
	}
}