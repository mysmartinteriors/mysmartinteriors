<?php
class Customer_reference_amount_model extends CI_Model
{


	function __construct()
	{
		$this->table = 'customer_reference_amount';
		$this->customers_table = 'customers';
	}

	protected $in_field = ['name'];
	function filter($filter_data, $item = 1, $page = 1, $sortby = '', $orderby = '', $all = true)
	{
		$arr1 = [];
        foreach($filter_data as $key=>$val){
            if($val['type']=='daterange'){
                $arr1['daterange'] = $val;
				unset($filter_data[$key]);
            }
        }
		$fromTime = '';
		$toTime = '';
        if (!empty($arr1['daterange'])) {
            $daterange = $arr1['daterange']['value'];
            $fromTime = substr($daterange, 0, 18);
            $toTime = substr($daterange, 21, 40);
        }
		if (($item == 0) && ($page == 0)) {
			$sql = "select count($this->table.id)  as countt "
				. "FROM $this->table where ($this->table.id>0";
		} else {
			$sql = "SELECT $this->table.*, $this->customers_table.firstName as ref_by_first_name, $this->customers_table.lastName as ref_by_last_name 
					from $this->table 
					inner join $this->customers_table ON $this->customers_table.id = $this->table.ref_comission_by
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

		if(!empty($fromTime) && !empty($toTime)){
			$sql.=" AND ($this->table.createdDate BETWEEN '$fromTime' AND '$toTime')";
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
				$sql .=	 "ORDER BY $sortby  $orderby ";
			}

			if (!$all) {
				$sql .= "limit $page,$item";
			}
		}
		$query = $this->db->query($sql);
		return $query;
	}
}