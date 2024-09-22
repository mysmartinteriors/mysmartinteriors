<?php
class Vendor_challan_history_model extends CI_Model
{
	function __construct(){
        $this->table = "vendors_challan_history";
        $this->vendors_challan_history_products = "vendors_challan_products";
        $this->vendors_table = "vendors";
		$this->lookups_table = "lookups";
		$this->users_table = "users";
	}
	

	protected $in_field = ['login_id', 'first_name', 'last_name', 'email', 'mobile'];

	function filter($filter_data, $item = 1, $page = 1, $sortby = '', $orderby = '', $all = true)
	{
		// print_R($filter_data);exit();
		$search_field = array("$this->vendors_table.id", "$this->vendors_table.first_name", "$this->vendors_table.last_name");
		if (($item == 0) && ($page == 0)) {
			$sql = "select count($this->table.id)  as countt "
				. "FROM $this->table 
					left join $this->vendors_table on $this->vendors_table.id = $this->table.vendor_id
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					where ($this->table.id>0";
		} else {
			$sql = "select $this->table.*, 
					$this->vendors_table.name as vendor_name,
					created_user.first_name as created_user_firstname,
					created_user.last_name as created_user_lastname,
					updated_user.first_name as updated_user_firstname,
					updated_user.last_name as updated_user_lastname,
					status_table.l_value as l_status_name,
					status_table.color_name as l_color_name 
					from $this->table
					left join $this->vendors_table on $this->vendors_table.id = $this->table.vendor_id
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					left join $this->users_table as created_user on $this->table.created_by = created_user.id 
					left join $this->users_table as updated_user on $this->table.updated_by = updated_user.id 
					where ($this->table.id>0";
		}
		if (!empty($filter_data)) {
			foreach ($filter_data as $k => $v) {
				if (($v['type'] == 'search') && ($v['value'] != "")) {
					$values = $v['value'];
					array_walk($search_field, function (&$value, $key) use ($values) {
						$value .= " like '%" . $values . "%'";
					});
					$sql .= ") AND (" . implode(" || ", $search_field);
				} else {
					if ($v['value'] != "") {
						if (in_array($v['type'], $this->in_field)) {
							$v['type'] = $this->table . "." . $v['type'];
						}
						$sql .= ") AND ( " . $v['type'] . " ='" . $v['value'] . "'";
					}
				}
			}
		}
		if (($item == 0) && ($page == 0)) {
			$sql .= ")  order by $sortby  $orderby  ";
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
			if (!empty($orderby)) {
				$sql .= "ORDER BY $sortby  $orderby ";
			}
			if (!$all) {
				$sql .= "limit $page,$item";
			}
		}
		$query = $this->db->query($sql);
		return $query;
	}
	
}