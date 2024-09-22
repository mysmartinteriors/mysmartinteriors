<?php
class Support_model extends CI_Model
{
	function __construct() { 
        // Set table name 
        $this->table = 'support_enquiries';  
		// $this->support_details_table = 'support_enquiries_details';
		$this->lookups_table = 'lookups'; 
		$this->customer_table = 'customers'; 
        $this->users_table = 'users';  
        // $this->services = 'services'; 
        // $this->category_table = 'categories'; 
    } 
     
	protected $in_field = ['table_name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
         $search_field = array("$this->table.name", "$this->table.code");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id) as countt 
					FROM $this->table 
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					left join $this->customer_table as customer_table on $this->table.customerId = customer_table.id 
                    Where($this->table.id>0";
        } else {
            $sql = "select $this->table.*, customer_table.firstName as customer_name, 
					status_table.l_value as status_name, status_table.color_name as status_color_name
					from $this->table 
					left join $this->customer_table as customer_table on $this->table.customerId = customer_table.id 
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					where ($this->table.id>0";
				}
				// left join $this->services_table
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
			// print_R($sql);exit();
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
		// print_r($sql);exit();
        $query = $this->db->query($sql);
        return $query;
    }
	
	function get_plan_detail($id){
		$sql = "select $this->table.*, ids.reference_code,ids.reference_name,
				created_users_table.login_id as created_username, updated_users_table.login_id as updated_username, 
				status_table.l_value as status_name, status_table.color_name as status_color_name
				from $this->table 
				LEFT JOIN (
					(SELECT id,customer_code as reference_code,name as reference_name, 'customers' AS type FROM customers)
				  UNION
					(SELECT id,login_id as reference_code,name as reference_name, 'vendors' AS type FROM vendors)
				) ids ON $this->table.reference_id = ids.id AND $this->table.reference_type = ids.type
				left join $this->lookups_table as status_table on $this->table.status = status_table.id 
				left join $this->users_table as created_users_table on  $this->table.created_by = created_users_table.id
				left join $this->users_table as updated_users_table on   $this->table.updated_by = updated_users_table.id
				where ($this->table.id='".$id."')";
		$query = $this->db->query($sql);
        $data  = array();
        foreach ($query->result_array() as $row){
            $data = $row;
        }
		$categories=$this->db->get($this->category_table);
		$tree = array();
		foreach ($categories->result_array() as $d){
			$services=$this->get_plan_services($id,$d['id']);
			if(!empty($services)){
				$d['_children']=$services;
				$tree[]=$d;
			}
		}
		if(!empty($tree)){
			$data['services'] = $tree;
		}
        return $data;
	}
	
	function get_plan_services($plan_id=0,$categories_id=0){
		$sql = "select $this->plan_services.*, $this->services.allow_multiple,
				$this->services.service_code,$this->services.categories_id,$this->services.name,$this->services.icon,$this->services.icon_color,
				created_users_table.login_id as created_username
				from $this->plan_services 
				inner join $this->services on $this->services.id = $this->plan_services.services_id 
				left join $this->users_table as created_users_table on  $this->plan_services.created_by = created_users_table.id 
				WHERE ($this->plan_services.id>0) ";
		if(!empty($plan_id)){
			$sql.=" AND ($this->plan_services.plans_id='".$plan_id."')";
		}
		if(!empty($categories_id)){
			$sql.=" AND ($this->services.categories_id='".$categories_id."')";
		}
		$query = $this->db->query($sql);
		$data  = array();        
        foreach ($query->result_array() as $row){
            $data[] = $row;            
        }
		return $data;
	}


	public function check_create_plan($input){
		$status='fail';
		$year_count=0;
		$year=date('Y');
		$code='';
		
		$sql="SELECT *
				FROM ".$input['reference_type']."
				WHERE id='".$input['reference_id']."'";
		$query = $this->db->query($sql);
		//print_r($this->db->last_query());
		if($query->num_rows()>0){
			$reference_data=$query->row_array();
			$this->db->where("reference_id",$input['reference_id']);
			$this->db->where("reference_type",$input['reference_type']);			
			$plan_query=$this->db->get('plans');
			if($plan_query->num_rows()>0){
				foreach($plan_query->result() as $datas){
					//$up_data=array(
					//	'status'=>34,
					//	'updated_by'=>$input['created_by'],
					//	'updated_at'=>cur_date_time()
					//);				
					//$this->db->where("reference_id",$input['reference_id']);
					//$this->db->where("reference_type",$input['reference_type']);
					//$this->db->where("id",$datas->id);
					//$this->db->where("status",33);
					//$this->db->update('plans',$up_data);
					
					$year_created=custom_date('Y',$datas->created_at);
					if($year==$year_created){
						$year_count++;
					}
				}
			}
			
			$variable = rtrim($input['reference_type'],"s");
			$variable = $variable.'_code';
			$code=$reference_data[$variable].'-'.$year.'-'.($year_count+1);
			
			$status='success';
			$value=array('status'=>$status,'message'=>'','code'=>$code);
		}else{
			$value=array('status'=>$status,'message'=>'Reference data not found, it seems reference id is invalid','code'=>$code);
		}
		return $value;
		
	}
	
	
	function get_customer_plans($customer_id){
		$cur_time=cur_date_time();
		$sql = "SELECT $this->table.*
				FROM $this->table
				WHERE $this->table.reference_id='".$customer_id."'
				AND $this->table.reference_type='customers'
				AND $this->table.status=33
				AND ($this->table.start_date<='".$cur_time."') AND ($this->table.end_date>='".$cur_time."')
				ORDER BY $this->table.name ASC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
		
    
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
			//}
				$cat_list.='</li>';
	
		}
		//print_r($this->db->last_query());
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

// }







