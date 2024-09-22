<?php 
if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin
{
	var $CI;
	function __construct()
	{
		date_default_timezone_set('Asia/Calcutta');
		$this->CI =& get_instance();
		if(!class_exists("phpmailer")){
			require_once('PHPMailer/PHPMailerAutoload.php');
		}
	}
	
	function sendmail($txt,$sub,$to,$cc='',$from='',$fromtxt='')
	{	
		$where = array('id' => 1 );
	    $sett=$this->CI->Mydb->get_table_data('email_settings',$where,'*',true);
		$mail = new PHPMailer;
		$mail->IsSMTP(); // set mailer to use SMTP
		// $mail->SMTPDebug = 2;
		$mail->SMTPAuth = $sett->row()->smtpAuth; // turn on SMTP authentication 
		$mail->SMTPSecure = $sett->row()->smtpSecure;   
		$mail->Host = $sett->row()->host; // specify main and backup server 
		$mail->Port = $sett->row()->port; 
		$mail->Username = $sett->row()->hostUsername; // SMTP username 
		$mail->Password = $sett->row()->hostPassword; // SMTP password 
		$mail->From = $from == "" ? $sett->row()->from : $from; 
		$mail->FromName = $fromtxt == "" ? $sett->row()->fromText : $fromtxt; 
		// $address = explode(",", $to); 
		if(is_array($to)){
			// $address = implode(",", $to);
			foreach($to as $t){
				$mail->AddAddress($t);
			}
		}else{
			$mail->AddAddress($to);
		}
		if ($cc != "") {
			$addresscc = explode(",", $cc); 
			foreach ($addresscc as $tcc) {
				$mail->AddCC($tcc);
			}
		}
		if($sett->row()->ccEmails!=''){
			$mail->AddCC($sett->row()->ccEmails);
		}
		$mail->IsHTML(true);
		$mail->CharSet = "utf-8";
		$mail->MsgHTML($txt); 
		$mail->Subject = $sub.' | '.$sett->row()->fromText; 
		$mail->Body = $txt;
		// print_r($txt);exit();
		$result=$mail->Send();
		return $result;		
	}

	function sendmailattachments($from, $fromtxt, $to, $cc, $sub, $txt,$attachment,$file_name)
	{	
		$where = array('settingId' => 1 );
	    $sett=$this->CI->adminmodel->get_table_data('mail_settings',$where,'*',true);
		$mail = new PHPMailer;
		$mail->IsSMTP(); // set mailer to use SMTP
		//$mail->SMTPDebug = 2;
		$mail->SMTPAuth = $sett->row()->smtpAuth; // turn on SMTP authentication 
		$mail->SMTPSecure = $sett->row()->smtpSecure; 
		$mail->Host = $sett->row()->host; // specify main and backup server 
		$mail->Port = $sett->row()->port; 
		$mail->Username = $sett->row()->hostUsername; // SMTP username 
		$mail->Password = $sett->row()->hostPassword; // SMTP password 
		$mail->From = $from == "" ? $sett->row()->from : $from; 
		$mail->FromName = $fromtxt == "" ? $sett->row()->fromText : $fromtxt; 
		$address = explode(",", $to); 
		foreach ($address as $t) {
			$mail->AddAddress($t); // Email on which you want to send mail
		}
		if ($cc != "") {
			$addresscc = explode(",", $cc); 
			foreach ($addresscc as $tcc) {
				$mail->AddCC($tcc);
			}
		}
		if($attachment!=""){
			$mail->addAttachment($attachment, $file_name);			
		}
	//	$mail->AddReplyTo('info@photostop.in', 'Info-PhotoSTOP'); //optional 
		$mail->IsHTML(true);
		$mail->CharSet = "utf-8";
		$mail->MsgHTML($txt); 
		$mail->Subject = $sub; 
		$mail->Body = $txt;
		return $mail->Send();		
	}


		

}