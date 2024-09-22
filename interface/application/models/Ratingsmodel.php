<?php
class ratingsmodel extends CI_Model{

    /*
	 * Admin Product ratings
	 */

    function get_admnprd_ratings($id){
    	$sql = "SELECT c.firstName, c.lastName,c.email,r.*
    			FROM product_ratings r
    			INNER JOIN customers c
    			ON c.customerId=r.customerId
    			WHERE r.productId='$id'";
    	$query=$this->db->query($sql);
    	return $query;
    }

} 