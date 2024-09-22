<?php

defined('BASEPATH') or exit ('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
use Cashfree\Cashfree;
use Cashfree\Model\CreateOrderRequest;
use Cashfree\Model\CustomerDetails;
use Cashfree\Model\OrderMeta;

class Test extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
        $this->load->database();
	}



    function index(){
        $result = $this->db->query("SELECT * FROM order_details")->result_array();
        if(!empty($result)){
            foreach($result as $res){
                $metricsData = $this->db->query("SELECT * FROM product_metrics WHERE id='".$res['metricsId']."'")->row_array();
                if(!empty($metricsData)){
                    $data = [
                        'price'=>$metricsData['price'],
                        'quantity'=>$metricsData['quantity'],
                        'unit'=>$metricsData['unit'],
                        'mrp'=>$metricsData['mrp'],
                        'cgst'=>$metricsData['CGST'],
                        'sgst'=>$metricsData['SGST']
                    ];
                    // $update = $this->db->update('order_details', $data, ['id'=>$res['id']]);
                }
            }
        }
    }

}