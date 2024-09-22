<?php

require APPPATH . 'libraries/REST_Controller.php';

class Customer_repurchase_amount extends REST_Controller
{


    public function __construct()
    {

        parent::__construct();

        $this->load->database();
        $this->load->library('excelvalidation');
        $this->lang->load('response', 'english');
        $this->table = 'customer_repurchase_amount';
        $this->model_name = 'customer_repurchase_amount_model';
        $this->load->model($this->model_name, "", true);

    }



    /**

     * Get All Data from this method.

     *

     * @return Response

    */

    public function index_get($id = 0)
    {
        $message = "success";
        $data = array();
        if (!empty($id)) {
            $data = $this->Mydb->get_single_result($id, $this->table, $this->model_name);
        } else {
            $data = $this->Mydb->do_search($this->table, $this->model_name);
        }
        if (!empty($data)) {
            $value = withSuccess($message, $data);
        } else {
            $value = withSuccess($this->lang->line('no_result_found'));
        }

        $this->response($value, REST_Controller::HTTP_OK);
    }


    public function settle_put($customerId = 0)
    {
        $input = $this->put();
        if (!$customerId) {
            $value = withErrors('Required Payload Not Found, Please contact support for further assitance');
            $this->response($value, REST_Controller::HTTP_OK);
        }
        $q = "SELECT customer_repurchase_amount.* FROM customer_repurchase_amount
            WHERE customer_repurchase_amount.customer_id='$customerId' AND 
            MONTH(customer_repurchase_amount.createdDate) = MONTH(CURRENT_DATE())-1";
        // get the data and the price to be settled
        $result = $this->db->query($q)->result_array();
        $settlementAmount = 0;
        if (!empty($result)) {
            foreach ($result as $row) {
                $this->db->query("UPDATE customer_repurchase_amount SET status='2' WHERE id='".$row['id']."'");
                $settlementAmount += $row["amount"];
            }
        } else {
            $value = withErrors('No Records Found For Settlement, Everything is settled.');
        }
        $settleQuery = "UPDATE customer_repurchase_amount SET status = '2' WHERE customer_id='$customerId' AND MONTH(createdDate) = MONTH(CURRENT_DATE()) - 1";
        if ($settlementAmount < 3000) {
            $value = withErrors('For the Repurchase Settlement, The earning should be more than 3000. The customer has only earned ' . $settlementAmount . ' Rs. Repurchase Amount is settled for 0');
            $runQuery = $this->db->query($settleQuery);
        } else {
            $runQuery = $this->db->query($settleQuery);
            if ($runQuery) {
                $value = withSuccess('Settlement Completed');
            } else {
                $value = withError('Couldn\'t Settle the repurchase Bonus, Maybe everything is settled out');
            }
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }

    public function level_wise_customer_repurchase_get($id)
    {
        $q = "SELECT 
        customer_repurchase_amount.id,
        previous_month.total_amount AS previous_month_amount,
        previous_month.total_count AS previous_month_count,
        current_month.total_amount AS current_month_amount,
        current_month.total_count AS current_month_count,
        SUM(CASE WHEN customer_repurchase_amount.amount IS NOT NULL THEN customer_repurchase_amount.amount ELSE 0 END) AS total_amount,
        COUNT(customer_repurchase_amount.amount) AS total_count,
        customer_repurchase_amount.level,
        customers.firstName,
        customers.lastName 
    FROM 
        customer_repurchase_amount
    LEFT JOIN 
        customers ON customer_repurchase_amount.customer_id = customers.id
    LEFT JOIN (
        SELECT 
            customer_id,
            level,
            SUM(CASE WHEN amount IS NOT NULL THEN amount ELSE 0 END) AS total_amount,
            COUNT(amount) AS total_count
        FROM 
            customer_repurchase_amount
        WHERE 
            MONTH(createdDate) = MONTH(CURRENT_DATE()) - 1
        GROUP BY 
            customer_id, level
    ) AS previous_month ON customer_repurchase_amount.customer_id = previous_month.customer_id AND customer_repurchase_amount.level = previous_month.level
    LEFT JOIN (
        SELECT 
            customer_id,
            level,
            SUM(CASE WHEN amount IS NOT NULL THEN amount ELSE 0 END) AS total_amount,
            COUNT(amount) AS total_count
        FROM 
            customer_repurchase_amount
        WHERE 
            MONTH(createdDate) = MONTH(CURRENT_DATE())
        GROUP BY 
            customer_id, level
    ) AS current_month ON customer_repurchase_amount.customer_id = current_month.customer_id AND customer_repurchase_amount.level = current_month.level
    WHERE 
        customer_repurchase_amount.customer_id = '$id'
    GROUP BY 
        customer_repurchase_amount.customer_id, customer_repurchase_amount.level
    ORDER BY 
        customer_repurchase_amount.level";
        // print_r($q);exit();
        $query = $this->db->query($q)->result_array();
        if (!empty($query)) {
            $value = withSuccess('Data retrieved Successfully', array('data_list' => $query));
        } else {
            $value = array('status' => 'fail', 'message' => 'No data found for the customer');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }



    public function last_month_pending_get($id)
    {
        // print_r($id);exit();
        $q = "SELECT customer_repurchase_amount.* FROM customer_repurchase_amount 
        WHERE customer_repurchase_amount.status = 1 AND MONTH(customer_repurchase_amount.createdDate) = MONTH(CURRENT_DATE())-1 AND customer_repurchase_amount.customer_id='$id'";
        $result = $this->db->query($q)->result_array();
        log_message('error', $q);
        if (!empty($result)) {
            $value = withSuccess('Pending Reference Amount Settlement', array('data_list' => $result));
        } else {
            $value = withErrors('Settled Reference Amount Settlement');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }



    // public function index_post()
    // {
    //     $input = $this->input->post();
    //     // print_r($input);exit();
    //     $rules = [

    //         // 'invoice_id' => ['Invoice ID','required'],
    //         'customers_id' => ['Customers ID', 'required'],
    //         'via_method' => ['Via Method', 'required'],
    //         'token' => ['Token', 'required'],

    //     ];

    //     $message = [
    //         'is_unique' => 'The %s is already exists',
    //     ];
    //     Validator::setMessage($message);
    //     Validator::make($rules);

    //     //print_r(Validator::fails());
    //     if (!Validator::fails()) {
    //         Validator::error();
    //     } else {
    //         // $password = $this->Mydb->hash($input['password']);
    //         $data = array(
    //             'customer_id' => $input['customers_id'],
    //             'token' => $input['token'],
    //             'status' => 1,
    //             'createdDate' => cur_date_time(),
    //         );
    //         $getCustomer = $this->db->get_where('customers', array('id'=> $input['customers_id']))->row_array();
    //         if(empty($getCustomer)) {
    //             $value = withErrors('Unknown Request');
    //             $this->response($value, REST_Controller::HTTP_OK);
    //         }else{
    //             $id = $this->Mydb->insert_table_data($this->table, $data);
    //             $loginUrl = '';
    //             $interfaceUrl = $this->db->get_where('web_settings', array('data_type' => 'interface_url'))->row_array();
    //             if(!empty($interfaceUrl) && !empty($interfaceUrl['data_value'])){
    //                 $loginUrl = $interfaceUrl['data_value'].'account/login';
    //             }
    //             if ($id > 0) {
    //                 if ($input['via_method'] == 'email') {
    //                     $email = $input['email'];
    //                     $emaildata = array();
    //                     $emaildata['email_heading'] = "Referal Link";
    //                     $emaildata['name'] = $email;
    //                     $emaildata['token'] = $input['token'];
    //                     $emaildata['url_link'] = !empty($interfaceUrl) ? $loginUrl : "https://sartdev.in/demo_sites/nalaaorganics/interface/account/login";
    //                     $txt = $this->load->view("email-templates/customers/referal-view", $emaildata, true);
    //                     $msg_send = $this->admin->sendmail($txt, $emaildata['email_heading'], $email);
    //                 } else {
    //                     $mobile = $input['whatsapp_number'];
    //                     $whatsapp_text = "Dear Customer, You're being referred by ".$getCustomer['firstName'].' '.$getCustomer['lastName'].' for NalaaOrganic' . PHP_EOL;
    //                     $whatsapp_text .= "Use the code " . $input['token'] . " to register " . PHP_EOL;
    //                     $whatsapp_text .= !empty($interfaceUrl) ? $loginUrl : "https://sartdev.in/demo_sites/nalaaorganics/interface/account/login".PHP_EOL;
    //                     $whatsapp_text .= PHP_EOL."and get amazing discounts and purchase comissions on every order." . PHP_EOL;
    //                     $whatsapp_text .= "Thank You" . PHP_EOL;
    //                     $msg_send = send_whatsapp($mobile, $whatsapp_text);
    //                 }
    //                 $value = withSuccess("Refered Successfully...");
    //             } else {
    //                 $result = "fail";
    //                 $value = withErrors("Unable to refer! Try after sometime...");
    //             }
    //             $this->response($value, REST_Controller::HTTP_OK);
    //         }

    //     }
    // }
}