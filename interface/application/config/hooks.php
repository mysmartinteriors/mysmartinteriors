<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
$hook['post_controller_constructor'][] = array(
                               'class'    => 'Authenticate',
                               'function' => 'check_user_login',
                               'filename' => 'Authenticate.php',
                               'filepath' => 'hooks',
                               'params'   => array()
                               );
