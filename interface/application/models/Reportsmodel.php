<?php
class reportsmodel extends CI_Model{

    /*
     * Reports
     */

    function get_customer_reports($arg,$dateType,$from,$to){
        $sql="SELECT c.*
                FROM customers c";
        if($from!="" && $to!=""){
            $sql.=" WHERE c.$dateType "
                    . "BETWEEN '$from'"
                    . "AND '$to'";
        }
        if($arg!=""){
                $sql.=" AND c.status=$arg ";
        }
        //print_r($sql);
        $query=$this->db->query($sql);
        if($query->num_rows()>0){
        	$from=$this->admin->getCustomDate("%Y-%m-%d",strtotime($from)); 
        	$to=$this->admin->getCustomDate("%Y-%m-%d",strtotime($to)); 
	        $logData['action']='Generate';
	        $logData['description']='generated customers report from '.$from.' to '.$to;
	        $logData['dataId']='';
	        $logData['module']='Reports';
	        $logData['table_name']='customers';
	        insert_aLog($logData);
	    }
        return $query;
	}

	function get_product_reports($arg,$dateType,$from,$to){
        $sql = "SELECT p.*,c.text as categoryName
                FROM products p 
                INNER JOIN categories c
                ON c.id=p.categoryId";
        if($from!="" && $to!=""){
            $sql.=" WHERE p.$dateType "
                    . "BETWEEN '$from'"
                    . "AND '$to'";
        }
        if($arg!=""){
                $sql.=" AND p.status=$arg ";
        }
        //print_r($sql);
        $query=$this->db->query($sql);
        if($query->num_rows()>0){
        	$from=$this->admin->getCustomDate("%Y-%m-%d",strtotime($from)); 
        	$to=$this->admin->getCustomDate("%Y-%m-%d",strtotime($to)); 
	        $logData['action']='Generate';
	        $logData['description']='generated products report from '.$from.' to '.$to;
	        $logData['dataId']='';
	        $logData['module']='Reports';
	        $logData['table_name']='products';
	        insert_aLog($logData);
	    }
        return $query;
	}

	function get_order_reports($arg,$dateType,$from,$to){
        $sql = "SELECT ob.*
                FROM orders_booking ob";
        if($from!="" && $to!=""){
            $sql.=" WHERE ob.$dateType "
                    . "BETWEEN '$from'"
                    . "AND '$to'";
        }
        if($arg!=""){
                $sql.=" AND ob.status=$arg ";
        }
        //print_r($sql);
        $query=$this->db->query($sql);
        if($query->num_rows()>0){
        	$from=$this->admin->getCustomDate("%Y-%m-%d",strtotime($from)); 
        	$to=$this->admin->getCustomDate("%Y-%m-%d",strtotime($to)); 
	        $logData['action']='Generate';
	        $logData['description']='generated orders report from '.$from.' to '.$to;
	        $logData['dataId']='';
	        $logData['module']='Reports';
	        $logData['table_name']='orders';
	        insert_aLog($logData);
	    }
        return $query;
	}

	function get_prd_order_reports($arg,$dateType,$from,$to){
        $sql = "SELECT p.*,c.text as categoryName,o.total,
        		(SELECT COUNT(*) FROM orders_booking_details ob 
        		WHERE productId=p.productId ";
        if($from!="" && $to!=""){
	        $sql.=" AND ob.$dateType BETWEEN '$from 'AND '$to') as productCount, 
	        		(SELECT SUM(qty) FROM orders_booking_details ob 
        			WHERE productId=p.productId) as totalQty,
        			(SELECT SUM(total) FROM orders_booking_details ob 
        			WHERE productId=p.productId) as totalAmt
	        		FROM products p 
	        		INNER JOIN categories c
	        		ON c.id=p.categoryId
	        		INNER JOIN orders_booking_details o 
	        		WHERE  o.productId=p.productId GROUP BY o.productId";
	    }
        //print_r($sql);
        $query=$this->db->query($sql);
        if($query->num_rows()>0){
        	$from=$this->admin->getCustomDate("%Y-%m-%d",strtotime($from)); 
        	$to=$this->admin->getCustomDate("%Y-%m-%d",strtotime($to)); 
	        $logData['action']='Generate';
	        $logData['description']='generated products sale report from '.$from.' to '.$to;
	        $logData['dataId']='';
	        $logData['module']='Reports';
	        $logData['table_name']='orders';
	        insert_aLog($logData);
	    }
        return $query;
	}
}