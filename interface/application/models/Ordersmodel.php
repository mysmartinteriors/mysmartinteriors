<?php

class Ordersmodel extends CI_Model{

    function filter_bookings($filter_data,$item,$page){                  
        $sql = "SELECT ob.*
        		FROM orders_booking ob
        		WHERE (ob.id>0";
        foreach ($filter_data as $k => $v) {
            if(($v['type']=='search')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (ob.name like '%" . $value . "%' or ob.phone like '%" . $value . "%' or ob.email like '%" . $value . "%'";
            }
            if(($v['type']=='user')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (ob.email like '%".$value."%'";
            }
            if(($v['type']=='status')&&($v['value']!="")){
                $value = $v['value'];
                $sql .= " ) AND (ob.status=".$value;
            }
            if($v['type']=='sortBy'){
                $value = $v['value'];
                if($value=='createdAsc'){
                    $sql .= ")   ORDER BY ob.createdDate ASC";
                }else if($value=='createdDesc'){
                    $sql .= ")   ORDER BY ob.createdDate DESC";
                }else if($value=='updatedDesc'){
                    $sql .= ")   ORDER BY ob.updatedDate DESC";
                }else{
                    $sql .= ")   ORDER BY ob.createdDate DESC";
                }
            }
        }
        //print_r($sql);
        if(($item==0) &&($page==0)){
            $query = $this->db->query($sql);
            return $query->num_rows();
        }
        else{
			if (($item > 0) && ($page > 0)) {
                $sql .= " LIMIT $page, $item";
            }
            // $sql .= " limit $page,$item";
            $query=$this->db->query($sql);
            return $query;
        }
    }

    function get_booking_details($id){
    	$sql="SELECT ob.*
        		FROM orders_booking ob
        		WHERE ob.id='$id'";
		$query=$this->db->query($sql);
		return $query;
    }

    function get_user_booking_prd($id){
    	$sql = "SELECT p.productName,p.productImage,p.productURL,
				c.text as categoryName,bd.qty,bd.price,bd.createdDate,bd.productId,bd.id,bd.tax,bd.total
				FROM products p
				LEFT JOIN categories c
				ON p.categoryId=c.id
				INNER JOIN orders_booking_details bd
				ON bd.productId=p.productId
				WHERE bd.orderId='$id'";
		$query=$this->db->query($sql);
		return $query;
    }

   	function gen_bill_no(){
		$datas="";
		$orderNo='';
	    $sql="SELECT id FROM invoices ORDER BY id DESC LIMIT 1";
		$query=$this->db->query($sql);
		if($query->num_rows()>0){
			$datas=$query->row()->id;
			$orderNo="2019-".($datas+1);			
		}else{
			$orderNo="2019-1";
		}
		return $orderNo;
	}

    function create_invoice($data){ 
		$data['bill_no']=$this->gen_bill_no();
		$data['createdDate']=get_curentTime();
		$data['updatedDate']=$data['createdDate'];
		$this->db->insert('invoices',$data);
		$result= $this->db->insert_id();
		$value = array('bill_no' => $data['bill_no'],'result'=>$result);
		return $value;
    }

    function update_booking($data){
    	$orderId=$data['orderId'];
    	$code=$data['code'];
    	$customerId=$data['customerId'];
    	$total_amount=$data['total_amount'];
    	$mainData['status']=$data['status'];
		$currentDate=get_curentTime();
		$subtotal=$count=0;
    	for($i=0;$i<count($data['id']);$i++){
    		$arr = array(
    			'price' => $data['price'][$i],
    			'qty' => $data['qty'][$i],
    			'tax' => $data['tax'][$i],
    			'total' => $data['total'][$i],
    			'updatedDate' => $currentDate
    		);
    		$this->db->where('id',$data['id'][$i]);
    		$this->db->where('productId',$data['productId'][$i]);
    		$this->db->update('orders_booking_details',$arr);
    		$subtotal+=$data['price'][$i]*$data['qty'][$i];
    		$count++;
    	}
    	if($mainData['status']==3){
    		$invoiceData['customerId']=$customerId;
    		$invoiceData['type']='booking';
    		$invoiceData['orderId']=$orderId;
    		$inv_res=$this->create_invoice($invoiceData);
    		if($inv_res['result']>0){
    			$mainData['bill_no']=$inv_res['bill_no'];
				$uNdata['text']='Bill copy is ready for your order '.$code.' having '.$count.' product(s)';
				$uNdata['data_url']='account/myorders';
				$uNdata['data_table']='orders_booking';
				$uNdata['data_id']=$orderId;
				$uNdata['customerId']=$customerId;
				user_notify($uNdata);
    		}
    	}
    	$mainData['subtotal']=$subtotal;
    	$mainData['tax']=$total_amount-$subtotal;
    	$mainData['total_amount']=$total_amount;
    	$mainData['updatedDate']=$currentDate;

		$this->db->where('id',$orderId);
		$res=$this->db->update('orders_booking',$mainData);
		if($mainData['status']<0){
			$uNdata['text']='Your order '.$code.' having '.$count.' product(s) has been cancelled by the administrator';
			$uNdata['data_url']='account/myorders';
			$uNdata['data_table']='orders_booking';
			$uNdata['data_id']=$orderId;
			$uNdata['customerId']=$customerId;
			user_notify($uNdata);
		}
		//print_r($this->db->last_query());

		return $res;
    }


}