<?php

session_start();
if($_SESSION["verify"] != "FileManager4TinyMCE") die('forbiden');

include("../../../../php/class.emailer.php");
$app = new Emailer();
$app->checkToken();

include 'config.php';
include('utils.php');

$path=$_POST['path'];
$path_thumbs=$_POST['path_thumb'];

if(strpos($path,$upload_dir)===FALSE || strpos($path_thumbs,'thumbs')!==0) die('wrong path');

create_folder($path,$path_thumbs);

