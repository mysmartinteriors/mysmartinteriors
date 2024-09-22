<?php
class Dashboardmodel extends CI_Model{

	function get_stats_counts($table){
		$sql = "SELECT count(*) as totCount
			 	FROM $table";
		$query=$this->db->query($sql);
		return $query->row()->totCount;
	}

	function get_logs(){
		$sql = "SELECT al.*,au.username 
				FROM activity_log al
				INNER JOIN admin_users au
				ON al.adminId=au.adminId
				ORDER BY al.createdDate DESC LIMIT 30";
		$query=$this->db->query($sql);		
		return $query;
	}

	function get_enquiries(){
		$sql = "SELECT s.* 
				FROM support_enquiries s
				LEFT JOIN customers c
				ON s.customerId=c.customerId
				ORDER BY s.submitDate DESC LIMIT 30";
		$query=$this->db->query($sql);		
		return $query;
	}

	function get_orders(){
		$sql = "SELECT o.* 
				FROM orders_booking o
				LEFT JOIN customers c
				ON o.customerId=c.customerId
				ORDER BY o.createdDate DESC LIMIT 30";
		$query=$this->db->query($sql);		
		return $query;
	}

	function get_best_sales(){
		$sql = "SELECT sum(o.qty) as max_sale, count(o.productId) as prd_count,
				sum(o.price) as tot_sale,
				cc.text as categoryName,cp.text as parentName,
				p.productName,p.color_name,p.model_no,p.productImage
				FROM orders_booking_details o
				INNER JOIN products p
				ON o.productId=p.productId
				INNER JOIN categories cc
				ON cc.id=p.categoryId
				INNER JOIN categories cp
				ON cp.id=p.parentId
				GROUP BY o.productId ORDER BY o.createdDate DESC LIMIT 5";
		$query=$this->db->query($sql);		
		return $query;
	}
}