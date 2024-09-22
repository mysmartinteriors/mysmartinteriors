<?php
class Customer_reference_model extends CI_Model
{


	function __construct()
	{
		// Set table name 
		$this->table = 'customer_references';
    

	}

	protected $in_field = ['name'];
	

	function filter($filter_data, $item = 1, $page = 1, $sortby = '', $orderby = '', $all = true)
	{
		// print_r("HELLO");exit();
		
		// print_r($search_field);exit();
		if (($item == 0) && ($page == 0)) {
			$sql = "select count($this->table.id)  as countt "
				. "FROM $this->table where ($this->table.id>0";
		} else {
			$sql = "select * from $this->table where ($this->table.id>0";
		}
		// print_r($sql);exit();
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
// print_r($sql);exit();

		if (($item == 0) && ($page == 0)) {

			$sql .= ")  order by $sortby  $orderby  ";
// print_r($sql);exit();
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
				$sql .=	 "ORDER BY $sortby  $orderby ";
			}

			if (!$all) {
				$sql .= "limit $page,$item";
			}
		}
        // print_r($sql);
        // exit();
		
		$query = $this->db->query($sql);
		return $query;
	}
}