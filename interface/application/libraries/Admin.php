<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class admin
{
	var $CI;
	function __construct()
	{
		date_default_timezone_set('Asia/Calcutta');
		$this->CI =& get_instance();
		if (!class_exists("phpmailer")) {
			require_once('PHPMailer/PHPMailerAutoload.php');
		}
	}
	function nocache()
	{
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}

	function commonEmptyFiles()
	{
		$data = array(
			'commonCss' => $this->CI->load->view('commonFiles/commonCss', NULL, TRUE),
			'commonJs' => $this->CI->load->view('commonFiles/commonJs', NULL, TRUE),
			'header_main' => $this->CI->load->view('commonFiles/empty_header', NULL, TRUE),
			'mobile_menu' => $this->CI->load->view('commonFiles/mobile_menu', NULL, TRUE),
			'footer' => $this->CI->load->view('commonFiles/empty_footer', NULL, TRUE),
		);
		return $data;
	}

	function commonFiles($url = '')
	{
		$data = array(
			'common_css' => $this->CI->load->view('public/common_files/common_css', NULL, TRUE),
			'common_js' => $this->CI->load->view('public/common_files/common_js', NULL, TRUE),
			'common_header' => $this->CI->load->view('public/common_files/common_header', ['url' => $url], TRUE),
			'common_banner' => $this->CI->load->view('public/common_files/common_banner', NULL, TRUE),
			'common_footer' => $this->CI->load->view('public/common_files/common_footer', NULL, TRUE),
			// 'mobile_menu' => $this->CI->load->view('commonFiles/mobile_menu', NULL, TRUE),
		);
		return $data;
	}

	function commonadminFiles()
	{
		$data = array(
			'commonCss' => $this->CI->load->view('admin/commonadminFiles/commonCss', NULL, TRUE),
			'commonJs' => $this->CI->load->view('admin/commonadminFiles/commonJs', NULL, TRUE),
			'header_main' => $this->CI->load->view('admin/commonadminFiles/header_main', NULL, TRUE),
			'header_menu' => $this->CI->load->view('admin/commonadminFiles/header_menu', NULL, TRUE),
			'footer' => $this->CI->load->view('admin/commonadminFiles/footer', NULL, TRUE),
		);
		return $data;
	}

	function commondeliveryFiles()
	{
		$data = array(
			'commonCss' => $this->CI->load->view('delivery/commondeliveryFiles/commonCss', NULL, TRUE),
			'commonJs' => $this->CI->load->view('delivery/commondeliveryFiles/commonJs', NULL, TRUE),
			'header_main' => $this->CI->load->view('delivery/commondeliveryFiles/header_main', NULL, TRUE),
			'header_menu' => $this->CI->load->view('delivery/commondeliveryFiles/header_menu', NULL, TRUE),
			'footer' => $this->CI->load->view('delivery/commondeliveryFiles/footer', NULL, TRUE),
		);
		return $data;
	}

	function commonvendorFiles()
	{
		$data = array(
			'commonCss' => $this->CI->load->view('vendors/commonVendorsFiles/commonCss', NULL, TRUE),
			'commonJs' => $this->CI->load->view('vendors/commonVendorsFiles/commonJs', NULL, TRUE),
			'header_main' => $this->CI->load->view('vendors/commonVendorsFiles/header_main', NULL, TRUE),
			'header_menu' => $this->CI->load->view('vendors/commonVendorsFiles/header_menu', NULL, TRUE),
			'footer' => $this->CI->load->view('vendors/commonVendorsFiles/footer', NULL, TRUE),
		);
		return $data;
	}

	function sendmail($from, $fromtxt, $to, $cc, $sub, $txt)
	{
		$ci =& get_instance();
		$ci->load->model("adminmodel", "", true);
		$sett = $ci->adminmodel->get_mail_settings(1);
		$mail = new PHPMailer;
		$mail->IsSMTP(); // set mailer to use SMTP
		$mail->SMTPDebug = true;
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
		if (!empty($sett->row()->ccEmails) || $sett->row()->ccEmails != "") {
			$adminCC = explode(",", $sett->row()->ccEmails);
			foreach ($adminCC as $acc) {
				$mail->AddCC($acc);
			}
		}
		//	$mail->AddReplyTo('info@photostop.in', 'Info-PhotoSTOP'); //optional 
		$mail->IsHTML(true);
		$mail->CharSet = "utf-8";
		$mail->MsgHTML($txt);
		$mail->Subject = $sub;
		$mail->Body = $txt;
		$resData = $mail->Send();
		return $resData;
		if (!$mail->send()) {
			$resData = 0;
		} else {
			$resData = 1;
		}
		return $resData;

	}

	function randomCodenum($num)
	{
		$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < $num; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}

	function getCustomDate($dateFormat, $date)
	{
		if (is_nan($date)) {
			return mdate($dateFormat, strtotime(trim($date)));

		} else {
			return mdate($dateFormat, trim($date));
		}
	}

	function escapespecialchrs($text)
	{
		return str_replace("'", "''", $text);
	}

	function convertNumber($number)
	{
		if (($number < 0) || ($number > 999999999)) {
			throw new Exception("Number is out of range");
		}

		$Gn = floor($number / 1000000);
		/* Millions (giga) */
		$number -= $Gn * 1000000;
		$kn = floor($number / 1000);
		/* Thousands (kilo) */
		$number -= $kn * 1000;
		$Hn = floor($number / 100);
		/* Hundreds (hecto) */
		$number -= $Hn * 100;
		$Dn = floor($number / 10);
		/* Tens (deca) */
		$n = $number % 10;
		/* Ones */

		$res = "";

		if ($Gn) {
			$res .= $this->convertNumber($Gn) . "Million";
		}

		if ($kn) {
			$res .= (empty($res) ? "" : " ") . $this->convertNumber($kn) . " Thousand";
		}

		if ($Hn) {
			$res .= (empty($res) ? "" : " ") . $this->convertNumber($Hn) . " Hundred";
		}

		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
		$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");

		if ($Dn || $n) {
			if (!empty($res)) {
				$res .= " and ";
			}

			if ($Dn < 2) {
				$res .= $ones[$Dn * 10 + $n];
			} else {
				$res .= $tens[$Dn];

				if ($n) {
					$res .= "-" . $ones[$n];
				}
			}
		}

		if (empty($res)) {
			$res = "zero";
		}

		return $res;
	}

	function setWordbycount($string, $count)
	{
		$string = strip_tags($string);
		$string = trim($string);
		if (strlen($string) < $count + 1) {
			$res = $string . " " . str_repeat("&nbsp;", $count - strlen($string));
		} else {

			$res = substr($string, 0, $count) . "...";
		}
		return $res;
	}

	function get_duration($t)
	{
		$to_time = strtotime($t);
		$from_time = now();
		$duration = "";
		$minute = round(abs($to_time - $from_time) / 60, 0);
		switch ($minute) {
			case $minute < 60:
				$duration = $minute . " minute(s) ago";
				break;
			case $minute >= 60 && $minute < 1440:
				$duration = floor($minute / 60) . " hour(s) ago";
				break;
			case $minute >= 1440:
				$duration = floor($minute / 1440) . " day(s) ago";
				break;
			default:
				break;
		}
		return $duration;
	}

	function cal_rating_per($ratingCount, $ratingValue)
	{
		$prdTotRating = '0%';
		if ($ratingCount > 0) {
			$totRating = round($ratingValue / $ratingCount);
			if ($totRating == 1) {
				$prdTotRating = '20%';
			} else if ($totRating == 2) {
				$prdTotRating = '40%';
			} else if ($totRating == 3) {
				$prdTotRating = '60%';
			} else if ($totRating == 4) {
				$prdTotRating = '80%';
			} else if ($totRating == 5) {
				$prdTotRating = '100%';
			}
		}
		return $prdTotRating;
	}
}