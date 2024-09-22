<?php
class Datetime_model extends CI_Model
{

    function __construct()
    {
        $this->table = 'datetime_data';
    }

    protected $in_field = ['name'];

    function filter($filter_data, $item = 1, $page = 1, $sortby = '', $orderby = '', $all = true)
    {
        $search_field = array("$this->table.id");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt 
                    FROM $this->table   
                    where ($this->table.id>0";
        } else {
            $sql = "select $this->table.*
            FROM $this->table
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
