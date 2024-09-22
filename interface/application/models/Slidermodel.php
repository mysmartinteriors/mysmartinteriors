<?php
class slidermodel extends CI_Model{

    function filter_home_sliders($filter_data,$item,$page){
		$sqlOrderBY='';
		$sql = "SELECT s.*
				FROM sliders s
				WHERE (s.id>0";

		if(!empty($filter_data)){
			foreach ($filter_data as $k => $v) {
	            if(($v['type']=='search')&&($v['value']!="")){
			 			$value = $v['value'];
	                    $sql .= " ) AND (s.slider_name like '%" . $value . "%' or s.main_text like '%" . $value . "%' or s.sub_text like '%";
				}
				if(($v['type']=='slideName')&&($v['value']!="")){
			 			$value = $v['value'];
	                    $sql .= " ) AND (s.slider_name=".$value;
				}
				if(($v['type']=='status')&&($v['value']!="")){
			 			$value = $v['value'];
	                    $sql .= " ) AND (s.status=".$value;
				}
				if (($v['type']== 'sort')&&($v['value']!="")) {
	                $value = $v['value'];
	                if ($value == "createdAsc") {
	                    $sqlOrderBY = " ORDER BY s.createdDate ASC ";
	                }else if ($value == "updatedDesc") {
	                    $sqlOrderBY = " ORDER BY s.updatedDate DESC ";
	                }else {
	                    $sqlOrderBY = " ORDER BY s.updatedDate DESC ";
	                }
	            }
			}
		}
		$sql.=")  GROUP BY s.id";

		if($sqlOrderBY==''){
			$sqlOrderBY=' ORDER BY s.updatedDate DESC ';
		}
			
		if(($item==0) &&($page==0)){
			$sql.=$sqlOrderBY;
			//print_r($sql);
	        $query = $this->db->query($sql);
	        return $query->num_rows();
		}
		else{
            if($page>0){
                $sql .= $sqlOrderBY." limit $page,$item";
            }
			$query=$this->db->query($sql);
			return $query;
		}
	}

    function save_slider($data){
        $id=$data['id'];
        $res=0;
        $msg='';
        if($id!=''){
            $this->db->where("id",$id);
            $this->db->update("sliders",$data);
            $res=$this->db->affected_rows();
            $msg="Slider details has been updated successfully";
            $logData['action']='update';
            $logData['description']='updated the homepage slider details of '.$data['slider_name'];
        }else{
            unset($data['id']);
            $data['createdDate']=get_curentTime();
            $this->db->insert("sliders",$data);
            $res=$this->db->insert_id();
            $msg="New slider has been added to homepage";
            $logData['action']='create';
            $logData['description']='created a new product collection '.$data['slider_name'];
        }
        //print_r($this->db->last_query());
        if($res>0){
            //insert log data
            $logData['dataId']=$id;
            $logData['module']='sliders';
            $logData['table_name']='sliders';
            insert_aLog($logData);
        }
        
        $value=array('msg'=>$msg,'result'=>$res);
        return $value;
    }

    function delete_slider($id){
        $res=0;
        $b=0;
        $msg='Unable to delete at this time!';
            $this->db->where('id',$id);
            $get_res=$this->db->get('sliders');

            $this->db->where('id',$id);
            $this->db->delete('sliders');
            $res= $this->db->affected_rows();

            //insert log data
            if($res>0){
                $msg='Slider has been removed from database';
                $logData['dataId']=$id;
                $logData['module']='sliders';
                $logData['table_name']='sliders';
                $logData['action']='delete';
                $logData['description']='deleted the slider from database '.$get_res->row()->slider_name;
                insert_aLog($logData);
            }
        $value=array('msg'=>$msg,'result'=>$res,'canDelete'=>$b);
        return $value;
    }

}