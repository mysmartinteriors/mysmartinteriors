<?php
class cartmodel extends CI_Model{

	function addtocart($data){
		$uid=$data['customerId'];
		$pid=$data['productId'];
		
		$sql="SELECT * 
			FROM shopping_cart 
			WHERE productId='$pid' AND customerId='$uid'";
			
		$query=$this->db->query($sql);
		if ($query->num_rows() > 0)
		{
		   $array = array('customerId' => $uid, 'productId' => $pid);
		   $this->db->where($array);
		   $this->db->update("shopping_cart",$data);
		   $id=$this->db->affected_rows();
		   $msg='Your cart has been updated';
		}else{
			$this->db->insert("shopping_cart",$data);
			$id=$this->db->insert_id();
			$msg='Successfully added to cart';
		}
		$result=array(
			'id'=>$id,
			'msg'=>$msg
		);
		return $result;
	}

	function getMinQuantity($id){
		$sql = "select minQuantity from products where productId='".$id."'";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->minQuantity;	
	}

	function showcartquantity($uid){
		$sql="SELECT COUNT(*) AS quantity
			FROM `shopping_cart` 
			WHERE `customerId`='$uid' AND status=1";
			
		$query=$this->db->query($sql);
		if ($query->num_rows() > 0)
		{
		   $row = $query->row();
		   $result = $row->quantity;
		}else{
			$result=0;
		}
		return $result;
	}

	function get_cart_data($id){
		$sql = "SELECT c.cartId,c.customerId,c.quantity,c.status as cartStatus,c.quantity,
				p.price AS totalPrice,p.*
				FROM shopping_cart c
				INNER JOIN products p
				ON p.productId=c.productId
				WHERE c.customerId='$id'";
		$query=$this->db->query($sql);

		return $query;
	}

	function update_cart($data){
    	$updatedDate=$this->admin->getCustomDate("%Y-%m-%d %H:%i:%s",now());
    	$customerId=$data['customerId'];
		for($i=0;$i<count($data['cartId']);$i++){
			$arr=array(
				'cartId'=>$data['cartId'][$i],
				'customerId'=>$customerId,
				'productId'=>$data['productId'][$i],
				'quantity'=>$data['quantity'][$i],
				'updatedDate'=>$updatedDate
				);
			$this->db->where('cartId',$arr['cartId']);
			$this->db->where('productId',$arr['productId']);
			$this->db->where('customerId',$arr['customerId']);
			$this->db->update('shopping_cart',$arr);
		}
		return $this->db->affected_rows();
	}

	function remove_cart($data){
		$this->db->where('cartId',$data['cartId']);
		$this->db->where('customerId',$data['customerId']);
		$this->db->delete('shopping_cart');
		return $this->db->affected_rows();
	}

	function clear_cart($customerId){
		$this->db->where('customerId',$customerId);
		$this->db->delete('shopping_cart');
		return $this->db->affected_rows();
	}

	function get_cart_total($customerId){		
		$sql="SELECT c.productId,c.quantity * p.price AS totalPrice FROM shopping_cart c
			INNER JOIN products p
			ON p.productId=c.productId
			WHERE c.customerId='$customerId' AND c.status=1";		
		$res=$this->db->query($sql);		
		if($res->num_rows()>0){
			$total=0;			
			foreach($res->result() as $netprice){				
				$taxPrice = $this->find_product_tax($netprice->productId,$netprice->totalPrice);				
				$total = $total + $taxPrice;
			}		
		return round($total,2);	
		}				
	}

	function find_product_tax($productId,$price){		
		$sql = "SELECT CGST+SGST as taxpercent FROM products WHERE productId='$productId'";		
		$res=$this->db->query($sql);		
		if($res->num_rows()>0){			
			foreach($res->result() as $tax){			
				$taxprice=$tax->taxpercent;		
			}			
			//For tax in percentage
			$totalprice=(($taxprice*$price)/100)+$price;
			//For tax in rupees
			//$totalprice = $taxprice+$price;			
		}else{			
			$totalprice=0;			
		}		
		return $totalprice;		
		
	}

   	function gen_ordbook_code(){
		$datas="";
		$orderNo='';
		$sql="SELECT count(*) as totalRow FROM orders_booking";
		$query=$this->db->query($sql);
		$genRand=$this->admin->randomCodenum(5);
		if($query->num_rows()>0){
			$datas=$query->row()->totalRow;
			$orderNo="O".($datas+1)."B".$genRand;			
		}else{
			$orderNo="O1B".$genRand;
		}
		return $orderNo;
	}

	function book_user_order($uId){
		$currentDate=get_curentTime();
    	$get_cart=$this->get_cart_data($uId);

		$data['customerId']=$uId;
		$data['createdDate']=$currentDate;
		$data['updatedDate']=$currentDate;
		$data['status']=0;
		$tax=$subTotal=$grandTotal=$count=0;

		$result='fail';
		$dataId=0;
		$msg='';
		$urlredirect='cart';

    	if($get_cart->num_rows()>0){
    		$where = array('customerId' => $uId );
	    	$get_cust=$this->usermodel->get_table_data('customers',$where);
	    	$data['code']=$this->gen_ordbook_code();
	    	foreach ($get_cust->result() as $cust_data) {
	    		$data['name']=$cust_data->firstName.' '.$cust_data->lastName;
	    		$data['email']=$cust_data->email;
	    		$data['phone']=$cust_data->phone;
	    		$data['address']=$cust_data->address;
	    		if($cust_data->city!=''){
	    			$data['address'].=', '.$cust_data->city;
	    		}if($cust_data->state!=''){
	    			$data['address'].=', '.$cust_data->state;	    		
	    		}if($cust_data->country!=''){
	    			$data['address'].=', '.$cust_data->country;
	    		}if($cust_data->country!=''){
	    			$data['address'].='-'.$cust_data->postalCode;
	    		}
	    	}

    		foreach ($get_cart->result() as $cart_data){
    			$subTotal+=$cart_data->price*$cart_data->quantity;
                $prdPrice=$cart_data->price*$cart_data->quantity;
                $tax=$cart_data->CGST+$cart_data->SGST;
                $grandTotal+=(($tax*$prdPrice)/100)+$prdPrice;
    			$count++;
    		}
			$data['prd_count']=$count;
			$data['subtotal']=$subTotal;
			$data['tax']=$grandTotal-$subTotal;
			$data['total_amount']=$grandTotal;
	    	$res_id=$this->usermodel->insert_table_data('orders_booking',$data);
	    	if($res_id>0){
				foreach ($get_cart->result() as $cart_data){				
                $prdPrice=$cart_data->price*$cart_data->quantity;
               	$tax=$cart_data->CGST+$cart_data->SGST;
                $total=(($tax*$prdPrice)/100)+$prdPrice;
					$arr=array(
						'orderId'=>$res_id,
						'productId'=>$cart_data->productId,
						'qty'=>$cart_data->quantity,
						'price'=>$cart_data->price,
						'tax'=>$tax,
						'total'=>$total,
						'createdDate'=>$currentDate,
						'updatedDate'=>$currentDate,
						'status'=>0
					);
					$this->db->insert('orders_booking_details',$arr);
				}
				//print_r($this->db->last_query());
				$this->clear_cart($uId);
				$result='success';
				$dataId=$res_id;
				$msg="We have received your booking request.\nWe will get back to you soon...";
				$urlredirect='account/orders';

				//insert log
	            $logData['action']='orders';
	            $logData['description']=get_uEmail().' requested to book '.$count.' product(s) from shop';
	            $logData['dataId']=$dataId;
	            $logData['module']='orders booking';
	            $logData['table_name']='orders_booking';
	            insert_uLog($logData);
	    	}
    	}else{
			$msg='Unable to fetch your cart items, Please checkback!';
    	}
    	$value = array('result' => $result,'dataId'=>$dataId,'msg'=>$msg,'urlredirect'=>$urlredirect);
		return $value;
	}
}