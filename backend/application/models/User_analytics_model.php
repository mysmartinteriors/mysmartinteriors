<?php
class User_analytics_model extends CI_Model
{
	function __construct(){
        // Set table name 
        $this->categories_table= 'categories';
        $this->products_table= 'products'; 
        $this->customers_table= 'customers';
		
        $this->orders_table= 'orders'; 
        $this->customer_branches_table= 'customer_branches';
        $this->activity_log_table= 'activity_log';
		
		$this->admin_users= 'users'; 
		$this->lookups_table = 'lookups';
		$this->enquiry_table = 'support_enquiries';
		
		$this->vendors_table= 'vendors';
		
		$this->services_table= 'services';
		$this->categories_table= 'categories';
	}

	function get_logs(){
		$sql = "SELECT al.*
				FROM $this->activity_log_table al
				ORDER BY al.created_at DESC LIMIT 30";
		$query=$this->db->query($sql)->result_array();		
		return $query;
	}

	function get_enquiries(){
		$sql = "SELECT s.* 
				FROM $this->enquiry_table s
				LEFT JOIN $this->customers_table c
				ON s.customerId=c.id
				ORDER BY s.submitDate DESC LIMIT 30";
		$query=$this->db->query($sql)->result_array();		
		return $query;
	}

	function get_orders(){
		$sql = "SELECT o.* 
				FROM $this->orders_table o
				LEFT JOIN $this->customers_table c
				ON o.customerId=c.id
				ORDER BY o.createdDate DESC LIMIT 30";
		$query=$this->db->query($sql)->result_array();		
		return $query;
	}
	
	function get_dashboard_counts($inputs=array()){
		$delivery_by = '';
		
		$sql = "SELECT 
				(SELECT count($this->categories_table.id) FROM $this->categories_table) as categories,
				(SELECT count($this->products_table.id) FROM $this->products_table) as products,
				(SELECT count($this->customers_table.id) FROM $this->customers_table) as customers,
				(SELECT count($this->orders_table.id) FROM $this->orders_table) as orders";
				if(!empty($inputs['delivery_by'])){
					$delivery_by = $inputs['delivery_by'];
					$sql .= ",(SELECT count($this->orders_table.id) FROM $this->orders_table where $this->orders_table.delivery_by = $delivery_by AND $this->orders_table.status = 27) as pending_orders,
					(SELECT count($this->orders_table.id) FROM $this->orders_table where $this->orders_table.delivery_by = $delivery_by AND $this->orders_table.status = 28) as completed_orders";
				}
				// print_R($sql);exit();
		$query=$this->db->query($sql);
		return $query->row_array();
	}

}