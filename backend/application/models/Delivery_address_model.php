<?php
class Delivery_address_model extends CI_Model
{


	 function __construct() { 
        // Set table name 
        $this->table = 'delivery_address'; 
		$this->lookups_table = 'lookups';
    } 
     
	protected $in_field = ['pincode','delivery_charge','phone','address'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
         $search_field = array("$this->table.address","$this->table.pincode","$this->table.delivery_charge");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt "
                    . "FROM $this->table 
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					where ($this->table.id>0";
        } else {
            $sql = "select $this->table.*,
			status_table.l_value as status_name, status_table.color_name as status_color_name
			from $this->table
			left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					where ($this->table.id>0";
        }
		// GROUP_CONCAT(CONCAT(products_table.productCode)) as product_name
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
		  
              $sql .= ") group by $this->table.id order by $sortby  $orderby  ";
            
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
                $sql .= " group by $this->table.id"; 

				if(!empty($orderby)){
					$sql .=	 " ORDER BY $sortby  $orderby ";
				}
				
				if(!$all){
				 $sql .= "limit $page,$item";
				}
            }

			// print_R($sql);exit();
            $query = $this->db->query($sql);
            return $query;
        }

		public function order_details($orderId = ""){
			$sql = "SELECT * from $this->order_details_table WHERE $this->order_details_table.id > 0 AND $this->order_details_table.orderId = $orderId";
			$query = $this->db->query($sql)->result_array();
            return $query;
		}


		public function product_details($orderId = ""){
			$sql = "SELECT products_table.name,products_table.product_image,products_table.product_uRL,order_details_table.qty,order_details_table.price,order_details_table.createdDate,order_details_table.productId,order_details_table.id,order_details_table.tax,order_details_table.total,
			categories_table.text as category_name, parent_table.text as parent_name
				FROM $this->products_table products_table
				INNER JOIN $this->order_details_table order_details_table
				ON order_details_table.productId=products_table.id
				left join $this->categories_table as categories_table on products_table.categoryId = categories_table.id left join $this->categories_table as parent_table on products_table.parentId = parent_table.id 
				WHERE order_details_table.orderId='$orderId'";
			$query = $this->db->query($sql)->result_array();
            return $query;
		}

    
}