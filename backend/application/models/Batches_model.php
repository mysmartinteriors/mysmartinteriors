<?php
class Batches_model extends CI_Model
{

	function __construct(){
        // Set table name 
        $this->table= 'workorders';
        $this->customers_table= 'customers'; 
        $this->customer_branches_table= 'customer_branches';
        $this->customer_branch_person_table= 'customer_branches_persons';
        $this->plans_table= 'plans'; 
		$this->users_table = 'users';
		$this->lookups_table = 'lookups';
		$this->log_table="workorders_log";
		$this->checks_table= 'workorder_profiles_checks';
		
		$this->reports_table= 'workorder_profiles_checks_reports';  
		$this->batch_table="workorder_exec_batches";
		$this->services_table="services";
    }
	
	function get_batches_list($input){
		$sql = "SELECT 
				$this->batch_table.*,
				$this->table.code as workorder_code,
				$this->services_table.name as services_name,
				$this->users_table.login_id as created_username				
				FROM $this->batch_table
				INNER JOIN $this->table
				ON $this->batch_table.workorders_id=$this->table.id
				INNER JOIN $this->services_table
				ON $this->batch_table.services_id=$this->services_table.id
				INNER JOIN $this->users_table
				ON $this->batch_table.created_by=$this->users_table.id
				WHERE 
				$this->batch_table.status IN ('0','-1') ";
		if(!empty($input['services_id'])){
			$sql.=" AND $this->batch_table.services_id='".$input['services_id']."'";
		}
		if(!empty($input['workorders_id'])){
			$sql.=" AND $this->batch_table.workorders_id='".$input['workorders_id']."'";
		}
		if(!empty($input['customers_id'])){
			$sql.=" AND $this->table.customers_id='".$input['customers_id']."'";
		}
		// print_r($sql);exit();
		$query = $this->db->query($sql);
		return $query->result_array();
	}		
	
	function get_batch_pending_checks($ids){
		$sql = "SELECT id
				FROM $this->checks_table
				WHERE id IN(".implode(',',$ids).")
				AND status IN(4,5)";
		$query = $this->db->query($sql);
		//print_r($sql);exit();
		$result=$query->result_array();
		$data=array();
		if(!empty($result))
		foreach($result as $row){
			$data[]=$row['id'];
		}
		return $data;
	}	
	
}