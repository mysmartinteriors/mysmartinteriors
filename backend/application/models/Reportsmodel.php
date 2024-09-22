<?php
class Reportsmodel extends CI_Model{

    /*
     * Reports
     */

    function get_customer_reports($arg,$dateType,$from,$to){
        $sql="SELECT c.*, l.l_value as status_name, l.color_name as status_color_name 
                FROM customers c
                left join lookups as l on c.status = l.id   ";
        if($from!="" && $to!=""){
            $sql.=" WHERE c.$dateType "
                    . "BETWEEN '$from'"
                    . "AND '$to'";
        }
        if($arg!=""){
                $sql.=" AND c.status=$arg ";
        }
        // print_r($sql);exit();
        $query=$this->db->query($sql)->result_array();
        // if($query->num_rows()>0){
        // 	$from=$this->admin->getCustomDate("%Y-%m-%d",strtotime($from)); 
        // 	$to=$this->admin->getCustomDate("%Y-%m-%d",strtotime($to)); 
	    //     $logData['action']='Generate';
	    //     $logData['description']='generated customers report from '.$from.' to '.$to;
	    //     $logData['dataId']='';
	    //     $logData['module']='Reports';
	    //     $logData['table_name']='customers';
	    //     insert_aLog($logData);
	    // }
        return $query;
	}

	function get_product_reports($arg,$dateType,$from,$to){
        $sql = "SELECT p.*,c.text as categoryName, l.l_value as status_name, l.color_name as status_color_name 
                FROM products p 
                INNER JOIN categories c
                ON c.id=p.categoryId
                left join lookups as l on c.status = l.id";
        if($from!="" && $to!=""){
            $sql.=" WHERE p.$dateType "
                    . "BETWEEN '$from'"
                    . "AND '$to'";
        }
        if($arg!=""){
                $sql.=" AND p.status=$arg ";
        }
        //print_r($sql);
        $query=$this->db->query($sql)->result_array();
        // if($query->num_rows()>0){
        // 	$from=$this->admin->getCustomDate("%Y-%m-%d",strtotime($from)); 
        // 	$to=$this->admin->getCustomDate("%Y-%m-%d",strtotime($to)); 
	    //     $logData['action']='Generate';
	    //     $logData['description']='generated products report from '.$from.' to '.$to;
	    //     $logData['dataId']='';
	    //     $logData['module']='Reports';
	    //     $logData['table_name']='products';
	    //     insert_aLog($logData);
	    // }
        return $query;
	}

	function get_order_reports($arg,$dateType,$from,$to){
        $sql = "SELECT ob.*
                FROM orders ob";
        if($from!="" && $to!=""){
            $sql.=" WHERE ob.$dateType "
                    . "BETWEEN '$from'"
                    . "AND '$to'";
        }
        if($arg!=""){
                $sql.=" AND ob.status=$arg ";
        }
        //print_r($sql);
        $query=$this->db->query($sql)->result_array();
        // if($query->num_rows()>0){
        // 	$from=$this->admin->getCustomDate("%Y-%m-%d",strtotime($from)); 
        // 	$to=$this->admin->getCustomDate("%Y-%m-%d",strtotime($to)); 
	    //     $logData['action']='Generate';
	    //     $logData['description']='generated orders report from '.$from.' to '.$to;
	    //     $logData['dataId']='';
	    //     $logData['module']='Reports';
	    //     $logData['table_name']='orders';
	    //     insert_aLog($logData);
	    // }
        return $query;
	}

	function get_prd_order_reports($arg,$dateType,$from,$to){
        $sql = "SELECT p.*,c.text as categoryName,o.total,
        		(SELECT COUNT(*) FROM orders_booking_details ob 
        		WHERE productId=p.id ";
        if($from!="" && $to!=""){
	        $sql.=" AND ob.$dateType BETWEEN '$from 'AND '$to') as productCount, 
	        		(SELECT SUM(qty) FROM orders_booking_details ob 
        			WHERE productId=p.id) as totalQty,
        			(SELECT SUM(total) FROM orders_booking_details ob 
        			WHERE productId=p.id) as totalAmt
	        		FROM products p 
	        		INNER JOIN categories c
	        		ON c.id=p.categoryId
	        		INNER JOIN orders_booking_details o 
	        		WHERE  o.productId=p.id GROUP BY o.productId";
	    }
        //print_r($sql);
        $query=$this->db->query($sql)->result_array();
        // if($query->num_rows()>0){
        // 	$from=$this->admin->getCustomDate("%Y-%m-%d",strtotime($from)); 
        // 	$to=$this->admin->getCustomDate("%Y-%m-%d",strtotime($to)); 
	    //     $logData['action']='Generate';
	    //     $logData['description']='generated products sale report from '.$from.' to '.$to;
	    //     $logData['dataId']='';
	    //     $logData['module']='Reports';
	    //     $logData['table_name']='orders';
	    //     insert_aLog($logData);
	    // }
        return $query;
	}


    /* Download */

    function customer_download($arg,$dateType,$from,$to){
        $sql="SELECT c.firstName as First_Name,c.lastName as Last_Name,c.email as Email, 
            c.phone as Phone,c.address as Address, c.city as City, c.state as State, c.country as Country,
            c.postalCode as Postal_Code,c.type as Type,c.createdDate as Created_Date
            FROM customers c ";

        if($from!="" && $to!=""){
            $sql.=" WHERE c.$dateType "
                    . " BETWEEN '$from'"
                    . " AND '$to'";
        }
        if($arg!=""){
            $sql.=" AND c.status='$arg' ";
        }
        $query=$this->db->query($sql);
        return $query;
    }

    function product_download($arg,$dateType,$from,$to){
        $sql="SELECT p.productCode as Product_Code, c.text as Category, p.productName as Product_Name,
            p.description as Description, p.productImage as Product_Image, p.color_code as Color_Code,
            p.color_name as Color_Name, p.style as Style, p.closure_type as Closure_Type,
            p.heel_type as Heel_Type, p.sole as Sole, p.outer_mtr as Outer_Material,
            p.is_primary as Is_Primary, p.hsn as HSN, 
            p.price as Price, p.CGST as CGST, p.SGST as SGST, p.in_stock as In_Stock,
            p.minQuantity as Min_Quantity, p.productURL as Product_URL,
            c.createdDate as Created_Date
            FROM products p 
            LEFT JOIN categories c
            ON c.id=p.categoryId ";

        if($from!="" && $to!=""){
            $sql.=" WHERE p.$dateType "
                    . " BETWEEN '$from'"
                    . " AND '$to'";
        }
        if($arg!=""){
            $sql.=" AND p.status='$arg' ";
        }
        //print_r($sql);
        $query=$this->db->query($sql);
        return $query;
    }

    function orders_download($arg,$dateType,$from,$to){
        $sql = "SELECT o.code as ID, o.order_type as Order_Type, o.transaction_id as Transaction_Id,
                o.name as Name, o.email as Email, o.phone as Phone, o.address as Address,
                o.prd_count as Total_products, o.subtotal as Subtotal,
                o.tax_total as Tax, o.total_amount as Total, o.offer_price as Off, o.payble_price as Net_Payble,
                o.createdDate as Order_Date
                FROM orders o ";

        if($from!="" && $to!=""){
            $sql.=" WHERE o.$dateType "
                    . " BETWEEN '$from'"
                    . " AND '$to'";
        }
        if($arg!=""){
            $sql.=" AND o.status='$arg' ";
        }
        //print_r($sql);
        $query=$this->db->query($sql);
        return $query;
    }

    function prd_sales_download($arg,$dateType,$from,$to){
        $sql="SELECT p.productCode as Product_Code, c.text as Category, p.productName as Product_Name,
            p.description as Description, p.productImage as Product_Image, p.color_code as Color_Code,
            p.color_name as Color_Name, p.style as Style, p.closure_type as Closure_Type,
            p.heel_type as Heel_Type, p.sole as Sole, p.outer_mtr as Outer_Material,
                (SELECT COUNT(*) FROM orders_details ob 
                WHERE productId=p.productId  ";
        if($from!="" && $to!=""){
            $sql.=" AND ob.$dateType BETWEEN '$from 'AND '$to') as Count, 
                    (SELECT SUM(qty) FROM orders_details ob 
                    WHERE productId=p.productId) as Qty_Sold,
                    (SELECT SUM(total) FROM orders_details ob 
                    WHERE productId=p.productId) as Total_Amount
                    FROM products p 
                    INNER JOIN categories c
                    ON c.id=p.categoryId
                    INNER JOIN orders_details o 
                    WHERE o.productId=p.productId GROUP BY o.productId ";
            }
        //print_r($sql);
        $query=$this->db->query($sql);
        return $query;
    }

    function support_download($arg,$dateType,$from,$to){
        $sql="SELECT s.code as ID, s.name as Name,s.email as Email, 
            s.phone as Phone, s.subject as Subject, s.message as Message,
            s.submitDate as Submit_Date, s.closedDate as Closed_Date, s.status as Status
            FROM support_enquiries s ";

        if($from!="" && $to!=""){
            $sql.=" WHERE s.$dateType "
                    . " BETWEEN '$from'"
                    . " AND '$to'";
        }
        if($arg!=""){
            $sql.=" AND s.status='$arg' ";
        }
        //print_r($sql);
        $query=$this->db->query($sql);
        return $query;
    }

    function newsletter_download($arg,$dateType,$from,$to){
        $sql = "SELECT n.name as Name, n.email as Email,
                n.subscribeDate as Subscribe_Date, n.unsuscribeDate as Unsuscribe_Date, n.status as Status
                FROM newsletter n ";

        if($from!="" && $to!=""){
            $sql.=" WHERE n.$dateType "
                    . " BETWEEN '$from'"
                    . " AND '$to'";
        }
        if($arg!=""){
            $sql.=" AND n.status='$arg' ";
        }
        //print_r($sql);
        $query=$this->db->query($sql);
        return $query;
    }

}