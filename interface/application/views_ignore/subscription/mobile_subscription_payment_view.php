<?php 
include(APPPATH.'third_party/ccavenue/Crypto.php');
$amount='0';
$customer_id='0';
$payable_amount=0;
$merchant_data='';
$merchant_id = '3407273';
$working_key='04716BADDDEE5F1738E6476B905EFF9C';//Shared by CCAVENUES
$access_code='AVCY79LD18BG56YCGB';//Shared by CCAVENUES

// $working_key='0D2FFC91AB0D3DE34307F156A15C2A08';//Testing
// $access_code='AVRZ05LC61CB88ZRBC';//Testing
    
$customer_name='';

if((isset($userData) && !empty($userData)) && (isset($subscriptionData) && !empty($subscriptionData))){
    $customer_id = $userData['id'];
    $customer_code = $userData['phone'];
    $customer_email = $userData['email'];
    $customer_phone = $userData['phone'];
    $amount = $subscriptionData['basic_amount'];
    $payable_amount = $subscriptionData['basic_amount'];
    $customer_name = ucwords($userData['firstName'].' '.$userData['lastName']);

    $merchant_data.='tid'.'=&';
    $merchant_data.='merchant_id'.'='.$merchant_id.'&';
    $merchant_data.='order_id'.'='.create_underscore_slug($userData['phone']).'_'.date('d_m_Y').'&';
    $merchant_data.='amount'.'='.$amount.'&';
    $merchant_data.='currency'.'=INR&';
    $merchant_data.='redirect_url'.'='.base_url().'subscription/mobile_pay_status&';
    $merchant_data.='cancel_url'.'='.base_url().'subscription/mobile_pay_status&';
    $merchant_data.='language'.'=EN&';
    $merchant_data.='billing_name'.'='.pay_gateway_chars_slug($customer_name,"60").'&';
    $merchant_data.='billing_email'.'='.$userData['email'].'&';
    $merchant_data.='billing_tel'.'='.$userData['phone'].'&';
    // $merchant_data.='billing_address'.'='.pay_gateway_chars_slug($subscriptionData['deliveryAddress'],"150").'&';
    // $merchant_data.='billing_city'.'='.pay_gateway_chars_slug($student['pr_address'],"30").'&';
    // $merchant_data.='billing_state'.'='.pay_gateway_chars_slug($student['pr_address'],"30").'&';
    $merchant_data.='billing_zip'.'='.'&';
    $merchant_data.='billing_country'.'=India&';
    //$merchant_data.='merchant_param1'.'=Payment for '.$student['course_name'].' course admission booking&';
    $merchant_data.='merchant_param2'.'='.$userData['id'].'&';
    $merchant_data.='merchant_param3'.'='.$subsData['id'].'&';
    $merchant_data.='merchant_param4'.'='.$amount.'&';
    $merchant_data.='merchant_param5'.'=customer';
}
$encrypted_data=encrypt_gateway($merchant_data,$working_key); // Method for encrypting the data.
?>
<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<!-- <form method="post" name="redirect" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction">  -->


<input type=hidden name=encRequest value="<?php echo $encrypted_data ?>" readonly />
<input type=hidden name=access_code value="<?php echo $access_code ?>" readonly/>
</form>
</center>
<script language='javascript'>document.redirect.submit();</script>

</body>
</html>