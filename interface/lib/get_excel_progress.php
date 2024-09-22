<?php
header('Content-Type: application/json');

require_once '../application/libraries/Iprogress.php';
$progress = new iProgress('excel', 200);

function array_flat($arr) {
	$result = array();
	foreach ($arr as $el) {
		if (is_array($el)) {
			$result = array_merge($result, array_flat($el));
		} else {
			$result[] = $el;
		}
	}
	return $result;
}

$json = array(
	'msgs' => array_flat($progress->getMessages()),
	'percent' => $progress->getProgress()
);

echo json_encode($json);exit;