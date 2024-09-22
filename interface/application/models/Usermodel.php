<?php
class usermodel extends CI_Model{
	private $table_name= 'customers';			// user accounts
	private static $db;
 
    function __construct(){
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    function unset_only(){
		$user_data = $this->session->all_userdata();
		foreach ($user_data as $key => $value) {
			if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
				$this->session->unset_userdata($key);
			}
		}		
	}

	function addguestuser($data){
		$this->db->insert("customers",$data);
		$id=$this->db->insert_id();
		return $id;
	}

	function checkuseremail($email){
		$this->db->where('email',$email);
		$query=$this->db->get('customers');
		return $query;
	}

	function getUserAuth($uname,$pwd)
	{	
		$this->db->where("(email='".$uname."' )");
		$this->db->where('password',$pwd);	
		$query=$this->db->get('customers');
		return $query;		
	}

	function register_user($data){
		$this->db->insert("customers",$data);
		$id=$this->db->insert_id();
		return $id;
	}

	function get_user_token($token){
		$this->db->where('token',$token);	
		$query=$this->db->get('customers');
		return $query;
	}

	function update_user($userData){
		$customerId=$userData['customerId'];
		$this->db->where('customerId',$customerId);	
		$query=$this->db->update('customers',$userData);
		return $query;
	}
	
    function insert_table_data($table,$data){
    	$this->db->insert($table,$data);
    	return $this->db->insert_id();
    }

	function update_table_data($table, $where, $data) {
       // $data = $this->adminmodel->removeEmptyvalue($data);
        $this->db->where($where);
        $this->db->update($table, $data);
        $id = $this->db->affected_rows();
        return $id;
    }

    function get_table_data($table, $where){
    	$this->db->where($where);	
		$query=$this->db->get($table);
		return $query;
    }

    function get_user_details($id){
    	$this->db->where('customerId',$id);	
		$query=$this->db->get('customers');
		return $query;
    }

    function get_user_shipping_addr($id){
    	$this->db->where('customerId',$id);	
		$query=$this->db->get('shipping_address');
		return $query;
    }

    function save_profile_ship_addr($data){
    	$customerId=$data['customerId'];
    	$this->db->where('customerId',$customerId);	
		$query=$this->db->get('shipping_address');
		if($query->num_rows()>0){
			return 0;
		}else{
			$data['name']=$data['firstName'].' '.$data['lastName'];
			unset($data['firstName']);
			unset($data['lastName']);
			unset($data['email']);
			$data['createdDate']=$data['updatedDate'];
			$data['pri_address']=1;
			$this->db->insert('shipping_address',$data);
			return $this->db->affected_rows();
		}
    }

    function get_shipping_address($customerId,$addressId){
		$this->db->where('addressId',$addressId);	
		$this->db->where('customerId',$customerId);	
		$query=$this->db->get('shipping_address');
		return $query;
    }

    function save_shipping_address($data){
    	$addressId=$data['addressId'];
    	$customerId=$data['customerId'];
    	$pri_address=0;
    	$sql="UPDATE shipping_address SET pri_address=0 WHERE customerId='$customerId'";
		$this->db->query($sql);

    	if($addressId!=''){
			$this->db->where('addressId',$addressId);	
			$this->db->where('customerId',$customerId);	
			$query=$this->db->update('shipping_address',$data);
			$id= $this->db->affected_rows();
    	}else{
    		$data['createdDate']=$data['updatedDate'];
    		$this->db->insert('shipping_address',$data);
			$id= $this->db->insert_id();
		}
		return $id;
    }

    function delete_shipping_address($customerId,$addressId){
    	$this->db->where('addressId',$addressId);	
    	$this->db->where('customerId',$customerId);	
		$this->db->delete('shipping_address');
		return $this->db->affected_rows();
    }

    function update_pri_shipAddr($customerId,$addressId){
    	$data['pri_address']=0;
		$this->db->update('shipping_address',$data);
		$data['pri_address']=1;
		$this->db->where('addressId',$addressId);	
		$this->db->where('customerId',$customerId);	
		$this->db->update('shipping_address',$data);
		return $this->db->affected_rows();
    }

	function create_support($data){
		$this->db->insert('support_enquiries',$data);
		$id=$this->db->insert_id();
		$res=0;
		if($id!=''){
			$code=$this->admin->randomCodenum(6);
			$date=getMyDbDate('%y',now());
			$up_data['code']="TKT".$code.$date.'-'.$id;
			$this->db->where('id',$id);
			$this->db->update('support_enquiries',$up_data);
			$res=$this->db->affected_rows();
			//print_r($up_data['code']);
		}
		if($res>0 && $id!=''){
			$msg_data['support_id']=$id;
			$msg_data['customerId']=$data['customerId'];
			$msg_data['from_type']=1;
			$msg_data['subject']=$data['subject'];
			$msg_data['message']=$data['message'];
			$msg_data['reply_date']=$data['submitDate'];
			$msg_data['is_read']=1;
    		$this->db->insert('support_enquiries_details',$msg_data);

            $logData['action']='create';
            $logData['description']=$data['email'].' sent a new support enquiry. This has been assigned to '.$up_data['code'];
			$logData['dataId']=$id;
            $logData['module']='support enquiries';
            $logData['table_name']='support_enquiries';
            insert_uLog($logData);
		}
		return $res;
	}

	function get_ticket_comments($id){
		$sql = "SELECT s.*,c.firstName
				FROM support_enquiries_details s
				LEFT JOIN support_enquiries e
				ON e.id=s.support_id
				INNER JOIN customers c
				ON c.customerId=e.customerId
				WHERE s.support_id='$id'";
		$query=$this->db->query($sql);
		return $query;
	}

	function get_dashboard_bookings($uId){
		$sql = "SELECT * FROM orders_booking
				WHERE customerId='$uId'
				ORDER BY createdDate DESC LIMIT 5";
		$query=$this->db->query($sql);
		return $query;
	}

	function get_my_bookings($id){
		$sql = "SELECT p.productName,p.CGST,p.SGST,p.productImage,p.productURL,
				c.text as categoryName,bd.qty,bd.price,bd.createdDate,bd.tax,bd.total
				FROM products p
				LEFT JOIN categories c
				ON p.categoryId=c.id
				INNER JOIN orders_booking_details bd
				ON bd.productId=p.productId
				WHERE bd.orderId='$id'";
		$query=$this->db->query($sql);
		return $query;
	}

}