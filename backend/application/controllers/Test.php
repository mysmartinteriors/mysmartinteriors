<?php

// defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Test extends REST_Controller {

	 

    public function __construct() {

        parent::__construct();
 
        $this->load->database();
     }
 
	function index_get(){
        $message = "Dear ".'Dhurba'.", ".PHP_EOL.PHP_EOL."Your order with the order Id ".'NALAA-0001'." is delivered to you successfully. ".PHP_EOL.PHP_EOL."Thank you for shopping with us.".PHP_EOL.PHP_EOL."Nalaa organic";
        $send_sms = send_sms($userdata['phone'], $message);
        $whatsapp_msg = "Dear ".$userdata['firstName']. ' '.$userdata['lastName'].",

Your order with the order Id ".$orderDetails['orderId']." is delivered to you successfully.

Thank you for shopping with us.

Nalaa Organic";
        $send_sms = send_sms('7892790889', $msg);
        print_R($send_sms);exit();
	}
}