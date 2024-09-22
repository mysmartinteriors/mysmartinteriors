<?php
class Adminmodel extends CI_Model{

    function __construct() { 
    } 
    
	function auth($data){
		// $sql="SELECT * 
		// 	FROM admin_users
		// 	WHERE username='". $data['username'] . "'
		// 	AND password='". $data['password']."'";
		// $query=$this->db->query($sql);
        
		
		// return $query;
	}

    function get_table_data($table,$where='',$select='*',$mode=false,$sortby='',$orderby="") {
        $this->db->select($select);
        $this->db->from($table);
        if($where!=""){
            $this->db->where($where);
        }
        if($orderby!=""){           
            $this->db->order_by($sortby,$orderby);
        }
        $query = $this->db->get();
         if ($mode==true) {
            return $query;
        } else {
            return $query->result_array();
        }       
    }

    function update_table_data($table,$where,$data){
        $this->db->where($where);
        $this->db->update($table,$data);
        return $this->db->affected_rows();
    }

    function insert_table_data($table,$data){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }

    function delete_table_data($table,$where){
        $this->db->where($where);
        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    function get_admin_details($adminId){
        $this->db->where("adminId",$adminId);
        $query=$this->db->get("admin_users");
        return $query;
    }
    
    function get_mail_settings($settingId){
        $this->db->where("settingId",$settingId);
        $query=$this->db->get("mail_settings");
        return $query;
    }

    function save_company_settings($data){
        $settingId=$data['settingId'];
        $this->db->where("settingId",$settingId);
        $this->db->update("admin_settings",$data);
        return $this->db->affected_rows();
    }

    function save_profile_settings($data){
        $adminId=$data['adminId'];
        $this->db->where("adminId",$adminId);
        $this->db->update("admin_users",$data);
        return $this->db->affected_rows(); 
    }

    function paginate_function($item_per_page, $current_page, $total_records, $total_pages) {
        $pagination = '';
        if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) { //verify total pages and current page number
            $pagination .= '<ul class="pagination pagination-split">';

            $right_links = $current_page + 3;
            $previous = $current_page - 3; //previous link 
            $next_link = $current_page + 1; //next link
            $previous_link = $current_page - 1;
            $first_link = true; //boolean var to decide our first link

            if ($current_page > 1) {
                //$previous_link = ($previous==0)?1:$previous;
                $pagination .= '<li class="first page-item"><a class="page-link" href="javascript:void(0)" data-page="1" title="First">&laquo;</a></li>'; //first link
                $pagination .= '<li class="page-item"><a href="javascript:void(0)" class="page-link"  data-page="' . $previous_link . '" title="Previous">&lt;</a></li>'; //previous link
                for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                    if ($i > 0) {
                        $pagination .= '<li class="page-item"><a class="page-link"  href="javascript:void(0)" data-page="' . $i . '" title="Page' . $i . '">' . $i . '</a></li>';
                    }
                }
                $first_link = false; //set first link to false
            }

            if ($first_link) { //if current active page is first link
                $pagination .= '<li class="first active page-item"><a class="page-link"  href="javascript:void(0)">' . $current_page . '</a></li>';
            } elseif ($current_page == $total_pages) { //if it's the last active link
                $pagination .= '<li class="last active page-item"><a class="page-link"  href="javascript:void(0)">' . $current_page . '</a></li>';
            } else { //regular current link
                $pagination .= '<li class="active page-item"><a class="page-link"  href="javascript:void(0)">' . $current_page . '</a></li>';
            }

            for ($i = $current_page + 1; $i < $right_links; $i++) { //create right-hand side links
                if ($i <= $total_pages) {
                    $pagination .= '<li class="page-item"><a class="page-link"  href="javascript:void(0)" data-page="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
                }
            }
            if ($current_page < $total_pages) {
                //$next_link = ($i > $total_pages)? $total_pages : $i;
                $pagination .= '<li class="page-item"><a class="page-link"  href="javascript:void(0)" data-page="' . $next_link . '" title="Next">&gt;</a></li>'; //next link
                $pagination .= '<li class="last page-item"><a class="page-link"  href="javascript:void(0)" data-page="' . $total_pages . '" title="Last">&raquo;</a></li>'; //last link
            }

            $pagination .= '</ul>';
        }
        return $pagination; //return pagination links
    }
	
	 function getPaginationData($item_per_page, $current_page, $total_rows) {
        $page = $current_page;
        if ($page != "") {
            $page_number = $page;
        } else {
            $page_number = 1; //if there's no page number, set it to 1
        }
        $total_pages = ceil($total_rows / $item_per_page);
        $data['page_position'] = (($page_number - 1) * $item_per_page);
        $data['item_per_page'] = $item_per_page;
        $data['page_number'] = $page_number;
        $data['get_total_rows'] = $total_rows;
        $data['total_pages'] = $total_pages;
        return $data;
    }



      function get_settings_table_data($table,$where='',$select='*',$mode=false) {
        $this->db->select($select);
        $this->db->from($table);
        if($where!=""){ 
            $this->db->where_in("type",$where);
        }
        $query = $this->db->get();
         if ($mode==true) {
            return $query;
        } else {            
            $s = array();
            foreach($query->result_array() as $row){
               $s [$row['type']][$row['name']] = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'value' => $row['value']
                );             
            }
            return $s;
        }
    }
    
    function updateSettings($data,$type){
        foreach($data as $x=>$value){
            $where = array('type'=>$type,'name'=>$x);
            $updata = array('value'=>$value);
            $this->adminmodel->update_table_data('web_settings',$where,$updata);
        }
        return 1;
    }

}