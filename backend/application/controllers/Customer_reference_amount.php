<?php

require APPPATH . 'libraries/REST_Controller.php';

class Customer_reference_amount extends REST_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->load->database();
        $this->load->library('excelvalidation');
        $this->lang->load('response', 'english');
        $this->table = 'customer_reference_amount';
        $this->model_name = 'customer_reference_amount_model';
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
            if (!empty($data['data_list']))
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
        $q = "SELECT customer_reference_amount.* FROM customer_reference_amount
            WHERE customer_reference_amount.customer_id='$customerId' AND 
            MONTH(customer_reference_amount.createdDate) = MONTH(CURRENT_DATE())-1";
        // get the data and the price to be settled
        $result = $this->db->query($q)->result_array();
        $settlementAmount = 0;
        if (!empty($result)) {
            foreach ($result as $row) {
                $settlementAmount = $row["amount"];
                $qq = "UPDATE customer_reference_amount SET status=2 WHERE id='".$row['id']."'";
                $this->db->query($qq);
            }
            $value = withSuccess('Settlement Completed');
        } else {
            $value = withError('Couldn\'t Settle the repurchase Bonus, Maybe everything is settled out');
        }
        // $settleQuery = "UPDATE customer_reference_amount SET status = '2' WHERE customer_id='$customerId' AND MONTH(createdDate) = MONTH(CURRENT_DATE()) - 1";
        // $runQuery = $this->db->query($settleQuery);
        $this->response($value, REST_Controller::HTTP_OK);
    }


    public function level_wise_customer_reference_get($id)
    {
        $q = "SELECT 
        customer_reference_amount.id,
        previous_month.total_amount AS previous_month_amount,
        previous_month.total_count AS previous_month_count,
        current_month.total_amount AS current_month_amount,
        current_month.total_count AS current_month_count,
        SUM(CASE WHEN customer_reference_amount.amount IS NOT NULL THEN customer_reference_amount.amount ELSE 0 END) AS total_amount,
        COUNT(customer_reference_amount.amount) AS total_count,
        customer_reference_amount.level,
        customers.firstName,
        customers.lastName 
    FROM 
        customer_reference_amount
    LEFT JOIN 
        customers ON customer_reference_amount.customer_id = customers.id
    LEFT JOIN (
        SELECT 
            customer_id,
            level,
            SUM(CASE WHEN amount IS NOT NULL THEN amount ELSE 0 END) AS total_amount,
            COUNT(amount) AS total_count
        FROM 
            customer_reference_amount
        WHERE 
            MONTH(createdDate) = MONTH(CURRENT_DATE()) - 1 
        GROUP BY 
            customer_id, level
    ) AS previous_month ON customer_reference_amount.customer_id = previous_month.customer_id AND customer_reference_amount.level = previous_month.level
    LEFT JOIN (
        SELECT 
            customer_id,
            level,
            SUM(CASE WHEN amount IS NOT NULL THEN amount ELSE 0 END) AS total_amount,
            COUNT(amount) AS total_count
        FROM 
            customer_reference_amount
        WHERE 
            MONTH(createdDate) = MONTH(CURRENT_DATE())  -- Current month's data
        GROUP BY 
            customer_id, level
    ) AS current_month ON customer_reference_amount.customer_id = current_month.customer_id AND customer_reference_amount.level = current_month.level
    WHERE 
        customer_reference_amount.customer_id = '$id'
    GROUP BY 
        customer_reference_amount.customer_id, customer_reference_amount.level
    ORDER BY 
        customer_reference_amount.level";
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
        $q = "SELECT customer_reference_amount.* FROM customer_reference_amount 
        WHERE customer_reference_amount.status = 1 AND MONTH(customer_reference_amount.createdDate) = MONTH(CURRENT_DATE())-1 AND customer_reference_amount.customer_id='$id'";
        $result = $this->db->query($q)->result_array();
        if (!empty($result)) {
            $value = withSuccess('Pending Reference Amount Settlement', array('data_list' => $result));
        } else {
            $value = withErrors('Settled Reference Amount Settlement');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }


    public function customer_wise_get()
    {
        $q = "SELECT customer_reference_amount.level, customers.firstName, customers.lastName, count(customer_reference_amount.id) as totalCount
        FROM customer_reference_amount
        LEFT JOIN customers ON customer_reference_amount.customer_id = customers.id
        GROUP BY customer_reference_amount.level, customer_reference_amount.customer_id;
            ";
        $result = $this->db->query($q)->result_array();
        $this->response(withSuccess('Data Fetched Successfully', array('data_list' => $result)));
    }

}