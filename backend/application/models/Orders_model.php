<?php
class Orders_model extends CI_Model
{

	function __construct()
	{
		// Set table name 
		$this->table = 'orders';
		$this->lookups_table = 'lookups';
		$this->order_details_table = 'order_details';
		$this->products_table = 'products';
		$this->categories_table = 'categories';
		$this->customers_table = "customers";
		$this->delivery_table = "delivery";
	} 

	protected $in_field = ['orderId', 'deliveryAddress', 'firstName', 'email', 'phone'];

	function filter($filter_data, $item = 1, $page = 1, $sortby = '', $orderby = '', $all = true)
	{
		$search_field = array("customers_table.firstName", "customers_table.lastName", "customers_table.phone", "customers_table.email", "$this->table.orderId", "$this->table.totalAmount");
		if (($item == 0) && ($page == 0)) {
			$sql = "select count($this->table.id)  as countt,
					delivery_table.name as delivery_boy_name, delivery_table.code as delivery_boy_code, delivery_table.email as delivery_boy_email, delivery_table.phone as delivery_boy_phone,
					status_table.l_value as status_name, status_table.color_name as status_color_name,payment_status_table.l_value as payment_status_name, payment_status_table.color_name as payment_status_color_name,
					customers_table.firstName, customers_table.lastName, customers_table.email as email, customers_table.phone as phone,
					customers_table.address as address";
		} else {
			$sql = "select $this->table.*,
			delivery_table.name as delivery_boy_name, delivery_table.code as delivery_boy_code, delivery_table.email as delivery_boy_email, delivery_table.phone as delivery_boy_phone,
			status_table.l_value as status_name, status_table.color_name as status_color_name,payment_status_table.l_value as payment_status_name, payment_status_table.color_name as payment_status_color_name,
			customers_table.firstName, customers_table.lastName, customers_table.email as email, customers_table.phone as phone,
			customers_table.address as address";
		}

		// left join $this->order_details_table as details_table on $this->table.id = details_table.orderId
		// left join $this->products_table as products_table on details_table.productId = products_table.id
		// left join $this->categories_table as categories_table on products_table.categoryId = categories_table.id 
		// left join $this->categories_table as parent_table on products_table.parentId = parent_table.id 
		$sql .= " FROM $this->table
			left join $this->lookups_table as status_table on $this->table.status = status_table.id 
			left join $this->lookups_table as payment_status_table on $this->table.status = payment_status_table.id
			left join $this->customers_table as customers_table on $this->table.customerId = customers_table.id 
			left join $this->delivery_table as delivery_table on $this->table.delivery_by = delivery_table.id
					where ($this->table.id>0";

		$dateRangeType = '';
		if (!empty($filter_data)) {
			foreach ($filter_data as $k => $v) {
				if ($v['type'] == 'dateRangeType') {
					if ($v['value'] != "") {
						$dateRangeType = $v['value'];
					}
				}
			}
		}
		if (!empty($filter_data)) {
			foreach ($filter_data as $k => $v) {
				if (($v['type'] == 'search') && ($v['value'] != "")) {
					$values = $v['value'];
					array_walk($search_field, function (&$value, $key) use ($values) {
						$values = trim(replace_dot_with_hyphen($values));
						$value .= " like '%" . $values . "%'";
					});

					$sql .= ") AND (" . implode(" || ", $search_field);

				} else if ($v['type'] == 'date_range' && $v['value'] != '') {

					$fromTime = substr($v['value'], 0, 10);

					$toTime = substr($v['value'], 13, 10);

					$fromTime = $fromTime != "" ? custom_date("Y-m-d", $fromTime) : "";

					$toTime = $toTime != "" ? custom_date("Y-m-d", $toTime) : "";

					$rangeFrom = $fromTime . ' 00:00:00';

					$rangeTo = $toTime . ' 23:59:59'; 
					if (!empty($dateRangeType)) {
						if ($dateRangeType == "decision") {
							$sql .= " ) AND ( $this->case_table.qa_date BETWEEN '$rangeFrom' AND '$rangeTo' ";
						} else if ($dateRangeType == "verification") {
							$sql .= " ) AND ( $this->data_table.updated_at BETWEEN '$rangeFrom' AND '$rangeTo' ";
						} else {
							$sql .= " ) AND ($this->case_table.created_at BETWEEN '$rangeFrom' AND '$rangeTo' ";
						}
					} else {
						$sql .= " ) AND ( $this->table.createdDate BETWEEN '$rangeFrom' AND '$rangeTo' ";
					}

				} else {
					if ($v['value'] != "" && $v['type'] != 'dateRangeType') {

						if (in_array($v['type'], $this->in_field)) {
							$v['type'] = $this->table . "." . $v['type'];
						}

						$sql .= ") AND ( " . $v['type'] . " ='" . $v['value'] . "'";
					}
				}
			}
		}
		// createdDate
		// updatedDate
		// deliveredDate
		if(!empty($sortby)){
            if($sortby=='orders.createdDate'){
                $sort_by='orders.createdDate';
            }else if($sortby=='orders.updatedDate'){
                $sort_by='orders.updatedDate';
            }else if($sortby=='orders.deliveredDate'){
                $sort_by='orders.deliveredDate';
            }else{
                $sort_by='orders.createdDate';            
            }
        }else{
            $sort_by='orders.createdDate';            
        }
		if (empty($orderby)) {
			$orderby = 'DESC';
		}

		$sql .= " ) AND (" . $sort_by . " IS NOT NULL ";
		// print_R($sql);exit();

		if (($item == 0) && ($page == 0)) {
			$sql .= ")";

			if (!empty($sort_by) && !empty($orderby)) {
				$sql .= " ORDER BY $sort_by  $orderby ";
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

			if (!empty($sort_by) && !empty($orderby)) {
				$sql .= " ORDER BY $sort_by  $orderby ";
			}
			if (!$all) {
				$sql .= " limit $page,$item";
			}
		}
		$query = $this->db->query($sql);
		return $query;
	}

	public function order_details($orderId = "")
	{
		$sql = "SELECT * from $this->order_details_table WHERE $this->order_details_table.id > 0 AND $this->order_details_table.orderId = $orderId";
		$query = $this->db->query($sql)->result_array();
		return $query;
	}


	public function product_details($orderId = "")
	{
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