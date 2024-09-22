<?php

require APPPATH . 'libraries/REST_Controller.php';

class Customer_reference extends REST_Controller
{


    public function __construct()
    {

        parent::__construct();

        $this->load->database();
        $this->load->library('excelvalidation');
        $this->lang->load('response', 'english');
        $this->table = 'customer_references';
        $this->model_name = 'customer_reference_model';

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


    public function token_data_get($id)
    {
        $sql = "select * from $this->table where token='$id'";
        $query = $this->db->query($sql);
        $data = $query->row_array();
        if ($data) {
            $value = withSuccess('success', array('details' => $data));
        } else {
            $value = withErrors($this->lang->line('no_result_found'));
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }

    /**

     * Insert data from this method.

     *

     * @return Response

    */

    public function index_post()
    {
        $input = $this->input->post();
        $rules = [
            'customers_id' => ['Customers ID', 'required'],
            'via_method' => ['Via Method', 'required'],
            'token' => ['Token', 'required'],

        ];
        $message = [
            'is_unique' => 'The %s is already exists',
        ];
        Validator::setMessage($message);
        Validator::make($rules);
        if (!Validator::fails()) {
            Validator::error();
        } else {
            $data = array(
                'customer_id' => $input['customers_id'],
                'token' => $input['token'],
                'status' => 1,
                'createdDate' => cur_date_time(),
            );
            $getCustomer = $this->db->get_where('customers', array('id' => $input['customers_id']))->row_array();
            if (empty($getCustomer)) {
                $value = withErrors('Unknown Request');
                $this->response($value, REST_Controller::HTTP_OK);
            } else {
                $id = $this->Mydb->insert_table_data($this->table, $data);
                $loginUrl = '';
                $interfaceUrl = $this->db->get_where('web_settings', array('data_type' => 'interface_url'))->row_array();
                if (!empty($interfaceUrl) && !empty($interfaceUrl['data_value'])) {
                    $loginUrl = $interfaceUrl['data_value'] . 'account/login';
                }
                if ($id > 0) {
                    if ($input['via_method'] == 'email') {
                        $email = $input['email'];
                        $emaildata = array();
                        $emaildata['email_heading'] = "Referal Link";
                        $emaildata['name'] = $email;
                        $emaildata['token'] = $input['token'];
                        $emaildata['url_link'] = !empty($interfaceUrl) ? $loginUrl : "https://nalaaorganic.com/account/login";
                        $txt = $this->load->view("email-templates/customers/referal-view", $emaildata, true);
                        $msg_send = $this->admin->sendmail($txt, $emaildata['email_heading'], $email);
                    } else {
                        $mobile = $input['whatsapp_number'];
                          $whatsapp_text = "Dear Customer, You're being referred by " . $getCustomer['firstName'] . ' ' . $getCustomer['lastName'] . ' for NalaaOrganic' . PHP_EOL;
                        $whatsapp_text .= "Use the code " . $input['token'] . " to register " . PHP_EOL;
                        $whatsapp_text .= !empty($interfaceUrl) ? $loginUrl : "https://nalaaorganic.com/account/login" . PHP_EOL;
                        $whatsapp_text .= PHP_EOL . "and get amazing discounts and purchase comissions on every order." . PHP_EOL;
                        $whatsapp_text .= "Thank You" . PHP_EOL;
                        $msg_send = send_whatsapp($mobile, $whatsapp_text);
                    }
                    $value = withSuccess("Refered Successfully...");
                } else {
                    $result = "fail";
                    $value = withErrors("Unable to refer! Try after sometime...");
                }
                $this->response($value, REST_Controller::HTTP_OK);
            }

        }
    }



    public function referCustomer_post()
    {
        $input = $this->input->post();
        $data = array(
            'customer_id' => $input['customers_id'],
            'token' => $input['token'],
            'status' => 1,
            'createdDate' => cur_date_time(),
        );
        $referValue = $input['referValue'];
        $findCustomer = "SELECT * FROM customers WHERE phone='$referValue' OR email='$referValue'";
        $result = $this->db->query($findCustomer)->row_array();
        $getCustomer = $this->db->get_where('customers', array('id' => $input['customers_id']))->row_array();
        if(!$result){
            if (empty($getCustomer)) {
                $value = withErrors('Unknown Request');
                $this->response($value, REST_Controller::HTTP_OK);
            } else {
                $id = $this->Mydb->insert_table_data($this->table, $data);
                $loginUrl = '';
                $interfaceUrl = $this->db->get_where('web_settings', array('data_type' => 'interface_url'))->row_array();
                if (!empty($interfaceUrl) && !empty($interfaceUrl['data_value'])) {
                    $loginUrl = $interfaceUrl['data_value'] . 'account/login';
                }
                if ($id > 0) {
                    if ($input['via_method'] == 'email') {
                        $email = $input['referValue'];
                        $emaildata = array();
                        $emaildata['email_heading'] = "Referal Link";
                        $emaildata['name'] = $email;
                        $emaildata['token'] = $input['token'];
                        $emaildata['customer_name'] = $getCustomer['firstName']. ' '.$getCustomer['lastName'];
                        $emaildata['url_link'] = !empty($interfaceUrl) ? $loginUrl : "https://nalaaorganic.com/account/login";
                        $txt = $this->load->view("email-templates/customers/referal-view", $emaildata, true);
                        $msg_send = $this->admin->sendmail($txt, $emaildata['email_heading'], $email);
                    } else if($input['via_method']=='whatsapp') {
                        $loginURL = !empty($interfaceUrl)?$loginUrl:'https://nalaaorganic.com/account/login';
                        $mobile = $input['referValue'];
                        $name = $getCustomer['firstName'];
                        $token = $input['token'];
                        $android_url = "https://play.google.com/store/apps/details?id=io.nalaaorganic.app&hl=en";
                        $ios_url = "https://apps.apple.com/in/app/nalaa-organic/id6499208933";
                        $whatsapp_text="Hello There,
You've been kindly referred by $name to *Nalaa Organic*.
Unlock fantastic discounts on each order and receive commission bonuses by using the code $token when you register on our 
website: $loginURL

For seamless shopping experiences, download our app:

Android: 
$android_url

iOS: 
$ios_url

Thank you for choosing Nalaa Organic. Happy shopping!
Best regards,
Nalaa Organic";
                        $msg_send = send_whatsapp($mobile, $whatsapp_text, 'customer_reference', 'customer_references', $id);
                    }else if($input['via_method']=='sms'){
                        $loginURL = !empty($interfaceUrl)?$loginUrl:'https://nalaaorganic.com/account/login';
                        $mobile = $input['referValue'];
                        $name = $getCustomer['firstName'];
                        $token = $input['token'];
                        $android_url = "https://play.google.com/store/apps/details?id=io.nalaaorganic.app&hl=en";
                        $ios_url = "https://apps.apple.com/in/app/nalaa-organic/id6499208933";
                        $sms_text="Hello there,
You've been kindly referred by $name to Nalaa Organic.
Unlock fantastic discounts on each order and receive commission bonuses by using the code $token when you register on our website:$loginURL

For seamless shopping experiences, download our app:

Android: $android_url
iOS: $ios_url

Thank you for choosing Nalaa Organic. Happy shopping!
Best regards,
Nalaa Organic";
                        $msg_send = send_sms($mobile, $sms_text);
                    }
                    $value = withSuccess("Refered Successfully...");
                } else {
                    $result = "fail";
                    $value = withErrors("Unable to refer! Try after sometime...");
                }
            }
        }else if($getCustomer['phone']==$result['phone']){
            $value = withErrors('Uh Oh!!, It seems you\'re referring to yourself. Please verify the input');
        }else{
            $value = withErrors('Customer is already registered with us, cannot re-refer the same');
        }
        $this->response($value, REST_Controller::HTTP_OK);

    }

    public function refer_post(){
        $raw_input_stream = $this->input->raw_input_stream;
        $input = json_decode($raw_input_stream, true);
        if(empty($input)){
            $this->response(withErrors('Unable to refer'), REST_Controller::HTTP_OK);
        }
        $data = array(
            'customer_id' => $input['customers_id'],
            'token' => $input['token'],
            'status' => 1,
            'createdDate' => cur_date_time(),
        );
        $referValue = $input['referValue'];
        $findCustomer = "SELECT * FROM customers WHERE phone='$referValue' OR email='$referValue'";
        $result = $this->db->query($findCustomer)->row_array();
        $getCustomer = $this->db->get_where('customers', array('id' => $input['customers_id']))->row_array();
        if(!$result){
            if (empty($getCustomer)) {
                $value = withErrors('Unknown Request');
                $this->response($value, REST_Controller::HTTP_OK);
            } else {
                $id = $this->Mydb->insert_table_data($this->table, $data);
                $loginUrl = '';
                $interfaceUrl = $this->db->get_where('web_settings', array('data_type' => 'interface_url'))->row_array();
                if (!empty($interfaceUrl) && !empty($interfaceUrl['data_value'])) {
                    $loginUrl = $interfaceUrl['data_value'] . 'account/login';
                }
                if ($id > 0) {
                    if ($input['via_method'] == 'email') {
                        $email = $input['referValue'];
                        $emaildata = array();
                        $emaildata['email_heading'] = "Referal Link";
                        $emaildata['name'] = $email;
                        $emaildata['token'] = $input['token'];
                        $emaildata['customer_name'] = $getCustomer['firstName']. ' '.$getCustomer['lastName'];
                        $emaildata['url_link'] = !empty($interfaceUrl) ? $loginUrl : "https://nalaaorganic.com/account/login";
                        $txt = $this->load->view("email-templates/customers/referal-view", $emaildata, true);
                        $msg_send = $this->admin->sendmail($txt, $emaildata['email_heading'], $email);
                    } else if($input['via_method']=='whatsapp') {
                        $loginURL = !empty($interfaceUrl)?$loginUrl:'https://nalaaorganic.com/account/login';
                        $mobile = $input['referValue'];
                        $name = $getCustomer['firstName'];
                        $token = $input['token'];
                        $android_url = "https://play.google.com/store/apps/details?id=io.nalaaorganic.app&hl=en";
                        $ios_url = "https://apps.apple.com/in/app/nalaa-organic/id6499208933";
                        $whatsapp_text="Hello There,
You've been kindly referred by $name to *Nalaa Organic*.
Unlock fantastic discounts on each order and receive commission bonuses by using the code $token when you register on our 
website: $loginURL

For seamless shopping experiences, download our app:

Android: 
$android_url

iOS: 
$ios_url

Thank you for choosing Nalaa Organic. Happy shopping!
Best regards,
Nalaa Organic";
                        $msg_send = send_whatsapp($mobile, $whatsapp_text, 'customer_reference', 'customer_references', $id);
                    }else if($input['via_method']=='sms'){
                        $loginURL = !empty($interfaceUrl)?$loginUrl:'https://nalaaorganic.com/account/login';
                        $mobile = $input['referValue'];
                        $name = $getCustomer['firstName'];
                        $token = $input['token'];
                        $android_url = "https://play.google.com/store/apps/details?id=io.nalaaorganic.app&hl=en";
                        $ios_url = "https://apps.apple.com/in/app/nalaa-organic/id6499208933";
                        $sms_text="Hello there,
You've been kindly referred by $name to Nalaa Organic.
Unlock fantastic discounts on each order and receive commission bonuses by using the code $token when you register on our website:$loginURL

For seamless shopping experiences, download our app:

Android: $android_url
iOS: $ios_url

Thank you for choosing Nalaa Organic. Happy shopping!
Best regards,
Nalaa Organic";
                        $msg_send = send_sms($mobile, $sms_text);
                    }
                    $value = withSuccess('Referred Successfully');
                } else {
                    $result = "fail";
                    $value = withErrors("Unable to refer! Try after sometime...");
                }
            }
        }else if($getCustomer['phone']==$result['phone']){
            $value = withErrors('Uh Oh!!, It seems you\'re referring to yourself. Please verify the input');
        }else{
            $value = withErrors('Customer is already registered with us, cannot re-refer the same');
        }
        $this->response($value, REST_Controller::HTTP_OK);
    }
}