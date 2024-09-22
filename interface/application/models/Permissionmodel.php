<?php
class permissionmodel extends CI_Model{

	function filter_users($filter_data,$item,$page){
		$sql = "SELECT a.adminId,a.username,a.password,a.firstName,a.lastName,
				a.email,a.roleId,a.status,r.roleName 
				FROM admin_users a
				INNER JOIN admin_roles r
				ON a.roleId=r.roleId
				WHERE(a.adminId>0";
		foreach($filter_data as $k => $v) {
            if(($v['type']=='search')&&($v['value']!="")){
	 			$value = $v['value'];
                $sql .= " ) AND (a.username like '%" . $value . "%' or a.email like '%" . $value . "%' or a.firstName like '%" . $value . "%' or a.lastName like '%" . $value . "%'";
			}
			if(($v['type']=='uName')&&($v['value']!="")){
				$value = $v['value'];
				$sql .= ")   AND (a.username='".$value."'";
			}
			if(($v['type']=='uRole')&&($v['value']!="")){
				$value = $v['value'];
				$sql .= ")   AND (a.roleId='".$value."'";
			}
			if($v['type']=='sortBy'){
				$value = $v['value'];
				if($value=='unameAsc'){
					$sql .= ") ORDER BY a.username ASC";
				}else if($value=='unameDesc'){
					$sql .= ") ORDER BY a.username DESC";
				}else{
					$sql .= ") ORDER BY a.username ASC";
				}
			}
		}
			
		if(($item==0) &&($page==0)){
	        $query = $this->db->query($sql);
	        return $query->num_rows();
		}
		else{
			$sql .= " limit $page,$item";
			$query=$this->db->query($sql);
			return $query;
		}
	}

	function save_user($data){
		$id=$data['adminId'];		
		if($id!=""){
			$this->db->where("adminId",$id);
			$this->db->update("admin_users",$data);
			$res=$this->db->affected_rows();
			$msg='Updated '.$data['username'].' user details';
	        $logData['action']='update';
			$logData['description']='updated '.$data['username'].' user details';
		}else{
			$this->db->insert("admin_users",$data);
			$id=$this->db->insert_id();
			$res=$this->db->affected_rows();
			$msg='Created a new user '.$data['username'];
        	$logData['action']='create';
			$logData['description']='created a new user '.$data['username'];
		}
		if($res<=0){
			$msg='No changes saved!';
		}
		//print_r($this->db->last_query());
		$logData['dataId']=$id;
		$logData['module']='Users';
		$logData['table_name']='adminusers';
		insert_aLog($logData);

		$result=array(
			'result'=>$res,
			'msg'=>$msg
		);
		return $result;
	}
	
	function delete_user($id){            
		$sql="SELECT 1 
			FROM (
				SELECT createdBy FROM products
				UNION ALL
				SELECT createdBy FROM quotations
				UNION ALL
				SELECT createdBy FROM dcs
				UNION ALL
				SELECT createdBy FROM invoices
			) a
			WHERE createdBy = $id";
		
		$query1=$this->db->query($sql);
		$b=1;
		if($query1->num_rows()>0){
			$b=0;
		}else{
			//insert log data
			$this->db->where("adminId",$id);
			$res=$this->db->get("admin_users");
			$username=$res->row()->username;

			$this->db->where("adminId",$id);
			$this->db->delete("admin_users");

			$logData['action']='Delete';
			$logData['description']='deleted the user <strong>'.$username.'</strong>';
			$logData['dataId']=$id;
			$logData['module']='Admin Users';
			$logData['table_name']='admin_users';
			insert_aLog($logData);
		}          
		$result['canDelete']=$b;
		$result['result']=$this->db->affected_rows();
		return $result;
   }


   /*  user roles */

   	function filter_roles($filter_data,$item,$page){
		
		$i=0;
		$j=0;
		$sql = "SELECT * from admin_roles
				WHERE(roleId>0";
		foreach($filter_data as $k => $v) {
            if(($v['type']=='search')&&($v['value']!="")){
	 			$value = $v['value'];
                $sql .= " ) AND (roleName like '%" . $value . "%'";
			}
			if($v['type']=='sortBy'){
				$value = $v['value'];
				if($value=='rNameAsc'){
					$sql .= ") ORDER BY roleName ASC";
				}else if($value=='rNameDesc'){
					$sql .= ") ORDER BY roleName DESC";
				}else{
					$sql .= ") ORDER BY roleName ASC";
				}
			}
		}
			
		if(($item==0) &&($page==0)){
	        $query = $this->db->query($sql);
	        return $query->num_rows();
		}
		else{
			$sql .= " limit $page,$item";
			$query=$this->db->query($sql);
			return $query;
		}
	}

	function saverole($data1){
		$rid=$data1['roleId'];
		if($rid!=""){
            unset($data1['roleId']);
            $this->db->where("roleId",$rid);
            $this->db->update("admin_roles",$data1);

			$logData['action']='Update';
			$logData['description']='updated the '.$data1['roleName'].' role and resets the permissions';

		}else{
			$this->db->insert("admin_roles",$data1);
			$rid=$this->db->insert_id();
			$where = array('status' =>1);
			$q=$this->adminmodel->get_table_data('admin_modules',$where,'*',true);
			foreach ($q->result() as $row){
				$data['moduleId']=$row->moduleId;
				$data['roleId']=$rid;
				$data['status']=1;
				$this->db->insert("admin_permissions",$data);
			}

			$logData['action']='Create';
			$logData['description']='created a new role '.$data1['roleName'].' and assigned the permissions';
		}

		$logData['dataId']=$rid;
		$logData['module']='User Roles';
		$logData['table_name']='admin_roles';
		insert_aLog($logData);

		return $rid;
	}

	function getrolepermission($rid){
		$sql="SELECT m.moduleId,m.moduleName,r.roleId,r.roleName,p.permissionId,
			p.view,p.add,p.edit,p.delete 
			FROM admin_modules m 
			LEFT JOIN admin_permissions p 
			ON m.moduleId=p.moduleId
			AND p.roleId='$rid'
			LEFT JOIN admin_roles r 
			ON p.roleId=r.roleId
			WHERE m.status=1
			ORDER BY m.moduleId";
		$query=$this->db->query($sql);
		
		return $query;
	}
	
	function savepermission($data1){
		$rid=$data1['roleId'];
		$pid='';
		$this->db->where("moduleId",$data1['moduleId']);
		$this->db->where("roleId",$rid);
		$query = $this->db->get('admin_permissions');
		if($query->num_rows()>0){
			$data1['permissionId']=$query->row()->permissionId;
			$this->db->where("moduleId",$data1['moduleId']);
			$this->db->where("roleId",$rid);
			$this->db->update("admin_permissions",$data1);
		}else{
			$this->db->insert("admin_permissions",$data1);
			$pid=$this->db->insert_id();
		}
		return $pid;
	}

	function delete_role($id){
		$sql="SELECT * 
			FROM admin_users where roleId='$id'";
		$query=$this->db->query($sql);
		//$query=$this->db->affected_rows();
		if($query->num_rows()>0){
			$res['canDelete']=0;
			$res['result']=0;
		}else{
			$where = array('roleId' => $id );
	        $uQ=$this->adminmodel->get_table_data('admin_roles',$where,'*',true);
			$logData['action']='Delete';
			$logData['description']='deleted the role '.$uQ->row()->roleName.' and the permissions assigned to it';
			$logData['dataId']=$id;
			$logData['module']='User Roles';
			$logData['table_name']='admin_roles';
			insert_aLog($logData);

			$this->db->where("roleId",$id);
			$this->db->delete("admin_permissions");

			$this->db->where("roleId",$id);
			$this->db->delete("admin_roles");
			$res['canDelete']=1;
			$res['result']=$this->db->affected_rows();
		}
		return $res;
	}

}