<?php
class Vendor_analytics_model extends CI_Model
{
	function __construct(){
        // Set table name 
        $this->workorders_table= 'workorders';
        $this->profiles_table= 'workorder_profiles'; 
        $this->checks_table= 'workorder_profiles_checks';
		
        $this->customers_table= 'customers'; 
        $this->customer_branches_table= 'customer_branches';
        $this->customer_branch_person_table= 'customer_branches_persons';
		
		$this->workflow_table= 'workflow'; 
		$this->lookups_table = 'lookups';
		$this->tickets_table = 'workorder_tickets';
		
		$this->vendors_table= 'vendors';
		
		$this->services_table= 'services';
		$this->categories_table= 'categories';
	}
	
	function get_statistics_summary($inputs){
		// $sql = "SELECT
				// SUM(CASE WHEN $this->checks_table.status>0 THEN 1 END) AS 'Total',
				// SUM(CASE WHEN $this->checks_table.status=3 THEN 1 ELSE 0 END) AS 'Insufficiency',
				// SUM(CASE WHEN $this->checks_table.status=4 THEN 1 ELSE 0 END) AS 'New',
				// SUM(CASE WHEN $this->checks_table.status=5 THEN 1 ELSE 0 END) AS 'Failed',
				// SUM(CASE WHEN $this->checks_table.status=6 THEN 1 ELSE 0 END) AS 'Submitted',
				// SUM(CASE WHEN $this->checks_table.status=7 THEN 1 ELSE 0 END) AS 'SME',
				// SUM(CASE WHEN $this->checks_table.status=9 THEN 1 ELSE 0 END) AS 'QA Rejected',
				// SUM(CASE WHEN $this->checks_table.status=10 THEN 1 ELSE 0 END) AS 'Completed'
				// FROM $this->checks_table 				
				// WHERE $this->checks_table.executor_id='".$inputs['uid']."'
				// AND $this->checks_table.execution_type='55'";
		
		//$sql.=" GROUP BY status_name";
		
		$sql = "SELECT
				COUNT($this->checks_table.id) as countt,
				$this->workflow_table.name as status_name,
				$this->checks_table.status
				FROM $this->checks_table 				
				INNER JOIN $this->workflow_table
				ON $this->workflow_table.id=$this->checks_table.status 
				WHERE $this->checks_table.executor_id='".$inputs['uid']."' 
				AND $this->checks_table.execution_type='55'";
		$sql.=" AND $this->checks_table.status >=3 ";
		
		$sql.=" GROUP BY $this->checks_table.status";
		// print_R($sql);exit();
		
		
		$query=$this->db->query($sql);
		//print_r($sql);exit();
		//$count=$query['countt'];
		return $query->result_array();
	}
	
}