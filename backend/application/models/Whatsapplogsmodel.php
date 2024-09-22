<?php
class Whatsapplogsmodel extends CI_Model
{
	function __construct() { 
        // Set table name 
        $this->table = 'whatsapp_logs'; 
		$this->users_table = 'users'; 
		$this->lookups_table = 'lookups';
    } 
     
	protected $in_field = ['name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
        $search_field = array("$this->table.mobile_number");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt "
                    . "FROM ".$this->table           
                    . " where ($this->table.id>0";
        }else{
            $sql = "select $this->table.* 
					from $this->table where ($this->table.id>0";
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
					$sql .= " ) AND ( $this->table.created_at BETWEEN '$rangeFrom' AND '$rangeTo' ";
				}
			}
		}
        if (($item == 0) && ($page == 0)) {
			$sql .= ")";

			if (!empty($sort_by) && !empty($orderby)) {
				$sql .= " ORDER BY $sort_by  $orderby ";
			}
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
}