<?php
class Calendar_model extends CI_Model
{

    function __construct()
    {
        $this->table = 'calendar_events';
        $this->lookups_table = 'lookups';
    }

    protected $in_field = ['name'];

    function filter($filter_data, $item = 1, $page = 1, $sortby = '', $orderby = '', $all = true)
    {
        $arr = [];
        $arr1 = [];
        foreach($filter_data as $key=>$val){
            if($val['type']=='daterange'){
                array_push($arr,$key);
                $arr1['daterange'] = $val;
            }
            if($val['type']=='datetype'){
                array_push($arr, $key);
                $arr1['datetype'] = $val;
            }
        }

        $from = '';
        $to = '';
        if (!empty($arr1['daterange'])) {
            $daterange = $arr1['daterange']['value'];
            $fromTime = substr($daterange, 0, 10);
            $toTime = substr($daterange, 13, 10);
            $fromTime = $fromTime != "" ? custom_date("Y-m-d", $fromTime) : "";
            $toTime = $toTime != "" ? custom_date("Y-m-d", $toTime) : "";
            $from = $fromTime . ' 00:00:00';
            $to = $toTime . ' 23:59:59';
        }

        $datetype = '';
        if(!empty($arr1['datetype'])){
            $datetype = $arr1['datetype']['value'];
        }

        for($i=0; $i<count($arr); $i++){
            unset($filter_data[$arr[$i]]);
        }


        $search_field = array("$this->table.id");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt 
                    FROM $this->table   

                    where ($this->table.id>0";
        } else {
            $sql = "select $this->table.*, $this->lookups_table.l_value as event_name, $this->lookups_table.color_name as event_color
            
					FROM $this->table 

					LEFT JOIN $this->lookups_table on $this->table.event_type = $this->lookups_table.id	

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
        $sql .= ') ';
        if (($item == 0) && ($page == 0)) {
            $sql .= " ORDER BY $sortby  $orderby  ";
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                $count = $row['countt'];
            } else {
                $count = 0;
            }

            return $count;
        } else {
            if (!empty($orderby)) {
                $sql .=     " ORDER BY $sortby  $orderby ";
            }
            if (!$all) {
                $sql .= "limit $page,$item";
            }
        }
        $query = $this->db->query($sql);
        // print_r($this->db->last_query());
        // exit();
        return $query;
    }
}
