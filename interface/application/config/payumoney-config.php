<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
// Paypal IPN Class
// ------------------------------------------------------------------------

// Use PayPal on Sandbox or Live
//$config['sandbox'] = FALSE; // FALSE for live environment
$config['MERCHANT_KEY'] = 'fB7m8s';

$config['SALT'] = 'eRis5Chv';
// Testing URL
$config['PAYU_BASE_URL'] = 'https://test.payu.in';
// Actual URL
//$config['PAYU_BASE_URL'] = 'https://secure.payu.in';

$config['SUCCESS_URL'] = base_url().'transaction/order_success';

$config['FAIL_URL'] =  base_url().'transaction/order_fail';


?>



