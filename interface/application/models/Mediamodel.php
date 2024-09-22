
<?php
class mediamodel extends CI_Model{
    /*
	 * Media Library
	 */
	
	function filter_medias($filter_data,$item,$page){		
		$i=0;
		$j=0;
					
		$sql = "SELECT m.* FROM media_library m WHERE (m.fileId>0";
		foreach ($filter_data as $k => $v) {
             if(($v['type']=='search')&&($v['value']!="")){
		 			$value = $v['value'];
                    $sql .= " ) AND (m.fileName like '%" . $value . "%' or m.fileTitle like '%" . $value . "%'";
			}
		}
			
		if(($item==0) &&($page==0)){
			$sql .= ")   ORDER BY m.updatedDate DESC";
			$query = $this->db->query($sql);
			return $query->num_rows();
		}else{
			$sql .= ")  ORDER BY m.updatedDate DESC";
			if (($item > 0) && ($page > 0)) {
				$sql .= " LIMIT $page, $item";
			}
			$query=$this->db->query($sql);
			return $query;
		}
	}

	function get_media_details($fileId){
		$this->db->where('fileId',$fileId);
		$query=$this->db->get('media_library');
		return $query;
	}

	function delete_media_file($fileId){
		$this->db->where('fileId',$fileId);
		$query=$this->db->get('media_library');
		$fileName=$query->row()->fileName;
		$folder=$query->row()->folder;
		$this->db->where('fileId',$fileId);
		$this->db->delete('media_library');
		$res=$this->db->affected_rows();
		if($res>0){	
			$path = $folder.$fileName;
			unlink($path);
            //insert log data
            $logData['dataId']=$fileId;
            $logData['module']='media';
            $logData['table_name']='media_library';
            $logData['action']='delete';
            $logData['description']='removed the file '.$fileName.' from database';
            insert_aLog($logData);
		}
		return $res;
	}

	function save_media_details($data){
		$fileId=$data['fileId'];
		$oldName=$data['oldName'];
		$newName=$data['fileName'];
		unset($data['oldName']);
		unset($data['fileId']);
		$oldURL='uploads/products/'.$oldName;
		$newURL='uploads/products/'.$newName;
		$result=0;
		if (file_exists($oldURL)){
			$renamed= rename($oldURL,$newURL);
			if ($renamed){
				$msg= "The file has been successfully renamed";
				$result=1;
			}else{
				$msg= "The file has not been successfully renamed";
			}
		}else{
			$msg= "The original file that you want to rename doesn't exist";
		}
		if($result>0){
			$this->db->where('fileId',$fileId);
			$this->db->where('fileName',$oldName);
			$this->db->update('media_library',$data);
			$res= $this->db->affected_rows();
			if($res>0){	
		        //insert log data
		        $logData['dataId']=$fileId;
		        $logData['module']='media';
		        $logData['table_name']='media_library';
		        $logData['action']='update';
		        $logData['description']='updated the file details '.$data['fileName'];
	        	insert_aLog($logData);
			}
		}
		$value = array('msg' => $msg, 'result'=>$result);
		return $value;
	}

	function save_library($data){
		$this->db->insert('media_library', $data);			
        $id = $this->db->insert_id();	
        if($id!=''){	
	        //insert log data
	        $logData['dataId']=$id;
	        $logData['module']='media';
	        $logData['table_name']='media_library';
	        $logData['action']='upload';
	        $logData['description']='uploaded media file '.$data['fileName'];
        insert_aLog($logData);
		}
		return $id;
	}

}