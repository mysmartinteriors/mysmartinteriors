<?php
class Notification_settings_model extends CI_Model
{

	function __construct(){
        // Set table name 
        $this->table = 'notification_settings';
		$this->users_table = 'users';
		$this->services_table = 'services';
		$this->customers_table = 'customers';
		$this->customer_branches_table = 'customer_branches'; 
		$this->customer_branch_person_table = 'customer_branches_persons';
		$this->lookups_table = 'lookups';
        $this->checks_table = 'workorder_profiles_checks';
        $this->checks_model= 'workorder_profiles_checks_model';
		$this->load->model('workorder_profiles_checks_model', "", true);
		$this->load->model($this->checks_model, "", true);
    } 
     
	protected $in_field = ['table_name'];
	 
	function filter($filter_data, $item=1, $page=1,$sortby='',$orderby='',$all=true) {
        $search_field = array("$this->customers_table.name","$this->customer_branches_table.name");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt ";
        } else {
            $sql = "select 
					$this->table.*,
					$this->customers_table.name as customers_name,
					$this->customer_branches_table.name as customer_branch_name,
					$this->customer_branch_person_table.name as customer_branches_persons_name,
					created_users_table.login_id as created_username ";
		}
		$sql.=" FROM $this->table 
				
				INNER JOIN $this->customers_table
				ON $this->table.customers_id = $this->customers_table.id 
				
				LEFT JOIN $this->customer_branches_table
				ON $this->table.customer_branches_id = $this->customer_branches_table.id 
				
				LEFT JOIN $this->customer_branch_person_table
				ON $this->table.customer_branches_persons_id = $this->customer_branch_person_table.id 
				
				INNER JOIN $this->users_table as created_users_table
				ON $this->table.created_by = created_users_table.id 
				
				WHERE ($this->table.id>0";
		if(!empty($filter_data)){
			foreach ($filter_data as $k => $v) {
				if (($v['type'] == 'search') && ($v['value'] != "")) {
					$values = $v['value'];
					array_walk($search_field, function(&$value, $key) use ($values) {
						$value .= " like '%" . $values . "%'";
					});
					$sql .= ") AND (" . implode(" || ", $search_field);
				}else{				
					if($v['value']!=""){						
						if(in_array($v['type'],$this->in_field)){
							$v['type'] = $this->table.".".$v['type'];
						}						
						$sql .= ") AND ( ".$v['type']." ='".$v['value']."'";
					}				
				}			
			}
		}
        if (($item == 0) && ($page == 0)) {
            if(!empty($sortby)){
				$sql .=	 ") ORDER BY $sortby  $orderby ";
			}
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
				if(!empty($sortby)){
					$sql .=	 "ORDER BY $sortby  $orderby ";
				}				
				if(!$all){
					$sql .= "limit $page,$item";
				}
            }
            $query = $this->db->query($sql);
            return $query;
        }
		
		
	function get_owners($input){
		$sql = "SELECT 
				$this->table.* 
				FROM $this->table
				WHERE $this->table.id>0 ";
		if(!empty($input['customers_id'])){
			$sql.=" OR $this->table.customers_id=".$input['customers_id'];
		}
		if(!empty($input['customer_branches_id'])){
			$sql.=" OR $this->table.customer_branches_id=".$input['customer_branches_id'];
		}
		if(!empty($input['customer_branches_persons_id'])){
			$sql.=" OR $this->table.customer_branches_persons_id=".$input['customer_branches_persons_id'];
		}
		$query = $this->db->query($sql);
		$result=$query->result_array();
		
		$services=array();
		$users=array();
		$data=array();
		
		if(!empty($result)){
			foreach($result as $row){
				if(!empty($row['services_id']) && !empty($input['services_id'])){
					$serv=json_decode($row['services_id'],true);
					if(in_array($input['services_id'],$serv)){
						$services=array_merge($services,$serv);
						
						$usr=json_decode($row['users_id'],true);
						$users=array_merge($users,$usr);
					}
				}else{
					$usr=json_decode($row['users_id'],true);
					$users=array_merge($users,$usr);
				}
			}
		}
		if(!empty($users)){
			$sql1 = "SELECT id,login_id, CONCAT(first_name,' ', last_name) as name, email
					FROM users
					WHERE id IN(".implode(',',$users).".)";
			$query1 = $this->db->query($sql1);
			$data=$query1->result_array();
		}
		return $data;
	}
	
	function custom_notification($inputs){
		
		$get_data = $this->Mydb->get_single_result($inputs['id'], $inputs['table_name'], $inputs['model_name']);
		$get_data=$get_data['details'];
		
		$where_owners=array(
			'customers_id'=>$get_data['customers_id'],
			'customer_branches_id'=>$get_data['customer_branches_id'],
			'customer_branches_persons_id'=>$get_data['customer_branches_persons_id']
		);
		if(!empty($inputs['services_id'])){
			$where_owners['services_id']=$inputs['services_id'];
		}
		$get_owners=$this->get_owners($where_owners);
			
		if(!empty($get_owners)){
			$tbl_data=array();
			foreach($get_owners as $row){
				$tbl_data[]=array(
					'dataId'=>$inputs['id'],
					'reference_id'=>$row['id'],
					'reference_type'=>54,
					'module'=>$inputs['table_name'],
					'action'=>$inputs['action'],
					'title'=>$inputs['description'],
					'is_seen'=>1,
					'created_at'=>cur_date_time()
				);
			}
			$this->db->insert_batch('notifications', $tbl_data);
		}
	}
	
	function send_notification($check_id){
		
		$get_check = $this->Mydb->get_single_result($check_id, $this->checks_table, $this->checks_model);
		$check_data=$get_check['details'];
		
		$where_owners=array(
			'customers_id'=>$check_data['customers_id'],
			'customer_branches_id'=>$check_data['customer_branches_id'],
			'customer_branches_persons_id'=>$check_data['customer_branches_persons_id'],
			'services_id'=>$check_data['services_id']
		);
		$get_owners=$this->get_owners($where_owners);
			//print_r($get_owners);exit();
			
		$subject=$check_data['status_name'].', the component '.$check_data['services_name'].' for the check of Reference ID '.$check_data['workorders_profiles_ref_id'].' - '.$check_data['workorders_profiles_name'].')';
		$reference_url='checks#workorder_profiles_checks-code='.$check_data['code'];
			
		if(!empty($get_owners)){
			$tbl_data=array();
			foreach($get_owners as $row){
				$tbl_data[]=array(
					'dataId'=>$check_data['id'],
					'reference_id'=>$row['id'],
					'reference_type'=>54,
					'reference_status'=>$check_data['status'],
					'module'=>'workorders_profiles_checks',
					'action'=>$check_data['status_name'],
					'title'=>$subject,
					'reference_url'=>$reference_url,
					'is_seen'=>1,
					'created_at'=>cur_date_time()
				);
			}
			$this->db->insert_batch('notifications', $tbl_data);
		}
		
		if(!in_array($check_data['status'],array(3,4,9))){
			return true;
			exit();
		}
		
		$email_data = array(
			'description'=>'You have a '.$check_data['status_name'].' check',
			'to_name'=>'',
			'to_email'=>''
		);
		
		$interface_url=get_interface_url();
		
		if(!empty($check_data['reference_url'])){
			$email_data['reference_url']=$check_data['reference_url'];
		}
		
		if(!empty($check_data['updated_username'])){
			$email_data['description'].=' by '.$check_data['updated_username'];
		}else if(!empty($check_data['created_username'])){
			$email_data['description'].=' by '.$check_data['created_username'];
		}
		$get_executor=$this->workorder_profiles_checks_model->get_check_executor_details($check_data['id'],$check_data['executed_by_type']);
		//print_r($get_executor);exit();
			
		$description='<p>'.$email_data['description'].'</p></br>';
		
		if($get_executor['executor_table']=='vendors'){
			$mail_subject='CRM Check, '.$check_data['workorders_profiles_ref_id'].', '.$check_data['status_name'];
			$description='You have a '.$check_data['status_name'].' check';
			$description.='<p>Reference ID: '.$check_data['workorders_profiles_ref_id'].'</p></br>';
			$description.='<p>Check ID: '.$check_data['vendor_check_id'].'</p></br>';
			$description.='<p>Name: '.$check_data['workorders_profiles_name'].'</p></br>';
			$description.='<p>Check Type: '.$check_data['services_name'].'</p></br>';
			
			$reference_url='vendor/checks/view?id='.$check_data['id'];
			
			$tbl_data=array(
				'dataId'=>$check_data['id'],
				'reference_id'=>$get_executor['id'],
				'reference_type'=>55,
				'reference_status'=>$check_data['status'],
				'module'=>'workorders_profiles_checks',
				'action'=>$check_data['status_name'],
				'title'=>$mail_subject,
				'reference_url'=>$reference_url,
				'is_seen'=>1,
				'created_at'=>cur_date_time()
			);
			$this->db->insert('notifications', $tbl_data);
				
		}else if($get_executor['executor_table']=='users'){
			
			$mail_subject='CRM Check -'.$check_data['services_name'].' - '.$check_data['workorders_profiles_ref_id'].'-'.$check_data['status_name'];
			$description.='<p>Customer: '.$check_data['customers_name'].'</p></br>';
			$description.='<p>Check ID: '.$check_data['code'].'</p></br>';
			$description.='<p>Name: '.$check_data['workorders_profiles_name'].'</p></br>';
			$description.='<p>Check Type: '.$check_data['services_name'].'</p></br>';
			$description.='<p>Status: '.$check_data['status_name'].'</p></br>';
			$description.='<p>Updated By:';
			if(!empty($check_data['updated_username'])){
				$description.=$check_data['updated_username'];
			}else if(!empty($check_data['created_username'])){
				$description.=$check_data['created_username'];
			}
			$description.='</p></br>';
			$reference_url='checks#workorder_profiles_checks-code='.$check_data['code'];
		}	
		
		if(!empty($get_executor) && !empty($get_executor['email']) && !empty($mail_subject)){
			$email_data['description']=$description;
			$email_data['reference_url']=$interface_url.$reference_url;		
			$email_data['to_email']=$get_executor['email'];
			$email_data['to_name']=ucwords($get_executor['username']);
			$str=$this->load->view('email-templates/common_view',$email_data,true);
			$this->admin->sendmail($str,$mail_subject,$email_data['to_email']);
		}
		return true;
	}
	
	
	function send_bulk_notification($data){
		
		$description='';
		$action='';
		
		if(!empty($data['user_id'])){
			$get_user=$this->db->get_where('users',array('id'=>$data['user_id']))->row_array();
		}
		if(!empty($data['services_id'])){
			$get_service=$this->db->get_where('services',array('id'=>$data['services_id']))->row_array();
		}
		if(!empty($data['workorders_id'])){
			$get_workorder=$this->db->get_where('workorders',array('id'=>$data['workorders_id']))->row_array();
		}
		
		if(!empty($data['action'])){
			$action=str_replace('_',' ',$data['action']);
			$description.='There is a request for '.$action;
		}
		if(!empty($data['counts'])){
			$description.=' of '.$data['counts'].' checks';
		}
		if(!empty($get_user['login_id'])){
			$description.=' by '.$get_user['login_id'];
		}
		if(!empty($get_service['name'])){
			$description.=' for '.$get_service['name'].' service';
		}
		
		$where_owners=array(
			'customers_id'=>$get_workorder['customers_id'],
			'customer_branches_id'=>$get_workorder['customer_branches_id'],
			'customer_branches_persons_id'=>$get_workorder['customer_branches_persons_id'],
			'services_id'=>$data['services_id']
		);
		$get_owners=$this->get_owners($where_owners);
		
		$interface_url=get_interface_url();
		$reference_url='workorders/view?id='.$data['workorders_id'];
			
		if(!empty($get_owners)){
			$to_email=array();
			$insert_data=array();
			foreach($get_owners as $row){
				//array_push($to_email,$row['email']);
				
				$insert_data[]=array(
					'dataId'=>!empty($data['workorders_id'])?$data['workorders_id']:'',
					'reference_id'=>$row['id'],
					'reference_type'=>54,
					'module'=>'workorders_profiles_checks',
					'action'=>$action,
					'title'=>$description,
					'reference_url'=>$reference_url,
					'is_seen'=>1,
					'created_at'=>cur_date_time()
				);
			}
			$this->db->insert_batch('notifications',$insert_data);
			// $email_data['to_name']='User';
			// $str=$this->load->view('email-templates/common_view',$email_data,true);
			// $this->admin->sendmail($str,$subject,$to_email);		
		}
		
		// executor notification
		
		if(!empty($data['check_id']) && !empty($data['executed_by_type'])){
		
			$email_data = array(
				'description'=>'There is a '.$action.' in CRM',
				'to_name'=>'',
				'to_email'=>''
			);
			
			$get_executor=$this->workorder_profiles_checks_model->get_check_executor_details($data['check_id'],$data['executed_by_type']);
			//print_r($get_executor);exit();
				
			$description='<p>'.$email_data['description'].'</p></br>';
			
			if($get_executor['executor_table']=='vendors'){
				
				$mail_subject=ucwords(str_replace('_',' ',$data['action']));
				if(!empty($get_service['name'])){
					$mail_subject.=' for '.$get_service['name'];
				}
				$mail_subject.=' | CRM';				
				$email_description='<p>There is a '.ucwords(str_replace('_',' ',$data['action'])).' in the CRM.<p></br></br>';
					
				if(!empty($get_service['name'])){
					$email_description.='<p>Component : '.$get_service['name'].'</p></br>';
				}
				if(!empty($data['counts'])){
					$email_description.='<p>No.of Checks: '.$data['counts'].'</p></br>';
				}
				
				$reference_url='vendor/checks';
				
				$tbl_data=array(
					'dataId'=>$data['workorders_id'],
					'reference_id'=>$get_executor['id'],
					'reference_type'=>55,
					'module'=>'workorders_profiles_checks',
					'action'=>ucwords(str_replace('_',' ',$data['action'])),
					'title'=>$mail_subject,
					'reference_url'=>$reference_url,
					'is_seen'=>1,
					'created_at'=>cur_date_time()
				);
				$this->db->insert('notifications', $tbl_data);
				
			}else if($get_executor['executor_table']=='users'){
				
				$mail_subject=ucwords(str_replace('_',' ',$data['action']));
				if(!empty($get_service['name'])){
					$mail_subject.=' for '.$get_service['name'];
				}
				$mail_subject.=' | CRM';
				
				$email_description='<p>There is a '.ucwords(str_replace('_',' ',$data['action'])).' in the CRM.<p></br></br>';
				
				if(!empty($get_workorder)){
					$email_description.='<p>Workorder: '.$get_workorder['code'].'</p></br>';
				}		
				if(!empty($get_service['name'])){
					$email_description.='<p>Component : '.$get_service['name'].'</p></br>';
				}
				if(!empty($data['counts'])){
					$email_description.='<p>No.of Checks: '.$data['counts'].'</p></br>';
				}
				if(!empty($get_user['login_id'])){
					$email_description.='<p>Updated By: '.ucwords($get_user['login_id']).'</p></br>';
				}
				
				if(!empty($data['workorders_id'])){
					$reference_url.='workorders/view?id='.$data['workorders_id'];
				}
			}
			
			$email_data['description']=$description;
			$email_data['reference_url']=$interface_url.$reference_url;
			
			if(!empty($get_executor) && !empty($get_executor['email'])){
				$email_data['to_email']=$get_executor['email'];
				$email_data['to_name']=ucwords($get_executor['username']);
				$str=$this->load->view('email-templates/common_view',$email_data,true);
				$this->admin->sendmail($str,$mail_subject,$email_data['to_email']);;
			}
			return true;		
		}else {
			return true;
			exit();
		}
	}	
    
}