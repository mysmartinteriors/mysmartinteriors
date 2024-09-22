<?php
class Categories_model extends CI_Model
{


	 function __construct() { 
        // Set table name 
        $this->table = 'categories'; 
		$this->users_table = 'users';
		$this->lookups_table = 'lookups';
        $this->uploads_table = "uploads";
    } 
     
	 protected $in_field = ['table_name'];
     
        // public function get_categories_json(){
        //     $where  = array('parent'=>0);
        //     $this->db->select(array('icon','href','text','id'));
        //     $this->db->order_by("orders", "asc");
        //     $this->db->from('categories');
        //     $this->db->where($where);
    
        //     $parent = $this->db->get();
        //     $categories = $parent->result();
        //     $i=0;
        //     foreach($categories as $p_cat){
        //         $children = $this->sub_categories_json($p_cat->id);
        //         if(!empty($children)){
        //             $categories[$i]->$children = $children;
        //         }
        //         $i++;
        //     }
        //     return $categories;
        // }
    
        // public function sub_categories_json($id){
        //     $where  = array('parent'=>$id);
        //     $this->db->select(array('icon','href','text','id'));
        //     $this->db->order_by("orders", "asc");
        //     $this->db->from('categories');
        //     $this->db->where($where);
    
        //     $child = $this->db->get();
        //     $categories = $child->result();
        //     $i=0;
        //     foreach($categories as $p_cat){            
        //         $children = $this->sub_categories_json($p_cat->id);
        //         if(!empty($children)){
        //             $categories[$i]->$children = $children;
        //         }
        //         $i++;
        //     }
        //     return $categories;       
        // }
    
        public function get_categories_json(){
            $where  = array('parent'=>0);
            $this->db->select(array('icon','href','text','id', 'code', 'image'));
            $this->db->order_by("orders", "asc");
            $this->db->from('categories');
            $this->db->where($where);
        
            $parent = $this->db->get();
            $categories = $parent->result_array();
            $i=0;
            foreach($categories as $p_cat){
                $children = $this->sub_categories_json($p_cat['id']);
                if(!empty($children)){
                    $categories[$i]['children'] = $children;
                }
                $i++;
            }
            return $categories;
        }
        
        public function sub_categories_json($id){
            $where  = array('parent'=>$id);
            $this->db->select(array('icon','href','text','id', 'code'));
            $this->db->order_by("orders", "asc");
            $this->db->from('categories');
            $this->db->where($where);
        
            $child = $this->db->get();
            $categories = $child->result_array();
            $i=0;
            foreach($categories as $p_cat){            
                $children = $this->sub_categories_json($p_cat['id']);
                if(!empty($children)){
                    $categories[$i]['children'] = $children;
                }
                $i++;
            }
            return $categories;       
        }
    
    
        function category_dropdown_list1(){
            $cat_list=array();
            $where = array('status'=>1,'parent'=>0);
            $select = "*";
            $sort_by = "orders";
            $order_by ="asc";
            $get_cats = $this->adminmodel->get_table_data('categories',$where,$select,false,$sort_by,$order_by);
            foreach($get_cats as $m) {
                $select = "*";
                $where = array('status'=>1,'parent'=>$m['id']);
                $check_child = $this->adminmodel->get_table_data('categories',$where,$select,false,$sort_by,$order_by);
                if(!empty($check_child)){                
                    echo $cat_list='<optgroup label="'.$m['text'].'">';
                    foreach($check_child as $c) {
                    echo  $cat_list=$this->category_nested_dropdown($c['id']);
                    }
                }else{
                    echo $cat_list='<option>'.$m['text'].'</option>';
                }
            }
        }
    
        function category_nested_dropdown1($parent){
            $array_list=array();
            $select = "*";
            $sort_by = "orders";
            $order_by ="asc";
            $where = array('status'=>1,'parent'=>$parent);
            $get_cats = $this->adminmodel->get_table_data('categories',$where,$select,false,$sort_by,$order_by);
            foreach($get_cats as $m) {
                $where=array('status'=>1,'parent'=>$m['id']);
                $child_list = $this->adminmodel->get_table_data('categories',$where,$select,false,$sort_by,$order_by);
                if(!empty($child_list)){
                    $level++;
                    if($level>1){
                        echo $array_list= '<optgroup label="'.$m['text'].'">';
                    }
                    foreach ($child_list as $c) {
                        echo $array_list= '<option>'.$c['text'].'</option>';
                        $array_list= $this->category_nested_dropdown($parent);
                    }
                    if($level>1){            
                        echo $array_list= "</optgroup>";
                    }
                }else{
                    echo $array_list= '<option>'.$m['text'].'</option>';
                }
            }
            return $array_list;
    
        }
    
    function fetchCategoryTreeList($parent = 0, $user_tree_array = '',$level=0) {
            if (!is_array($user_tree_array))
            $user_tree_array = array();
    
                $select = "*";
                $sort_by = "orders";
                $order_by ="asc";
                $where = array('status'=>1,'parent'=>$parent);
                $get_cats = $this->adminmodel->get_table_data('categories',$where,$select,true,$sort_by,$order_by);
        if ($get_cats->num_rows() > 0) {
            $level++;
            
            foreach ($get_cats->result() as $row) {
                if($level>1){
                $user_tree_array[] = "<optgroup>";
                }
            $user_tree_array[] = "<option>". $row->text."</option>";
            $user_tree_array = $this->fetchCategoryTreeList($row->id, $user_tree_array,$level);
            if($level>1){
            $user_tree_array[] = "<optgroup>";
            }
            }
        }
        return $user_tree_array;
    }
    
        function get_mega_menu_rows(){
            $data = array();
            $sql = "select distinct(mm_row) as mm_row from mega_menu";
            $query = $this->db->query($sql);    
            foreach($query->result_array() as $row){
                $data[] = array(
                    'mm_row' => $row['mm_row'],
                    //'menu_id'=>$row['menu_id'],
                );
            }
            return $data;
        }
    
        function get_mega_menu_column($rows){
            $data = array();
            $sql = "SELECT * FROM mega_menu WHERE mm_row='".$rows."'";
            $query = $this->db->query($sql);
            if($query->num_rows()>0){
                foreach($query->result_array() as $row){
                    $data[] = array(
                        'mm_column'=>$row['mm_column'],
                        'span'=>$row['span'],
                        'items'=> $this->mega_menu_items($row['id'])
                    );                
                }
            }        
            return $data;        
        }
    
        function mega_menu_items($id){
            $data = array();
            $sql = "SELECT mmi.*, c.* 
                    FROM mega_menu_items mmi 
                    INNER JOIN categories c 
                    ON mmi.items = c.id 
                    WHERE mmi.mega_menu_id='".$id."'";
            $query = $this->db->query($sql);
            if($query->num_rows() > 0){
                foreach($query->result_array() as $row){
                    $data[] = array(
                    'id'=>$row['items'],
                    'text'=>$row['text'],
                    'href' =>$row['href']
                    );                
                }            
            }
            return $data;
        }
    
        function get_header_categories(){
            $where = array('status'=>1);
            $get_cats = $this->adminmodel->get_table_data('categories',$where,'*',false,'orders','asc');
    
            $branch = array();
            function buildTree(array $elements, array $branch, $parentId=0) {
                // group elements by parents if it does not comes on the parameters
                if (empty($branch)) {
                    $branch = array();
    
                    foreach ($elements as $element) {
                        $branch[$element["parent"]][$element["id"]] = $element;
                    }
                }
    
                // echo the childs referenced by the parentId parameter
                if (isset($branch[$parentId])) {
                    echo'<ul>';
    
                    foreach ($branch[$parentId] as $keyBranch => $itemBranch) {
                        echo '<li id="'.$itemBranch['id'].'" data-parent="'.$itemBranch['id'].'">'.$itemBranch['text'];
                        buildTree($elements, $branch, $itemBranch["id"]); // iterate with the actual Id to check if this record have childs
                        echo '</li>';
                    }
    
                    echo '</ul>';
                }
            }
    
            buildTree($get_cats, array());
        }    
}