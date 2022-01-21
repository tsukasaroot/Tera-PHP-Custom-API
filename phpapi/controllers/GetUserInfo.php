<?php

require_once(SQL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

$json = file_get_contents('php://input');
if ($json) {
	$json = json_decode($json);
	foreach ($json as $k => $v) {
		$_POST[$k] = $v;
	}
}

$returnCode = 0;

if (!$_POST['user_srl']) {
	$msg = 'id=null';
	$returnCode = 15000;
} else {
	$accountList = [];
	$id = $_POST['user_srl'];
	$q = "SELECT * FROM accountinfo WHERE accountDBID = $id";
	$accountList = $conn->query($q);
	
	if ($accountList->num_rows <= 0) {
		$msg = 'Invalid login request';
		$returnCode = 50000;
	} else {
		$accountInfo = $accountList->fetch_object();
		$accountList->close();
		$charCount = "0|2800," . $accountInfo->charCount . '|';
		$q = "SELECT * FROM account_benefits WHERE accountDBID = $id";
		$result = $conn->query($q);
		$benefits = [];
		
		$i = 0;
		while ($benefit = $result->fetch_row()) {
			$benefits[$i][] = $benefit[1];
			$benefits[$i][] = $benefit[2] - time();
			$i++;
		}
		
		$data['vip_pub_exp'] = 0;
		$data['permission'] = intval($accountInfo->isBlocked);
		$data['result_code'] = $returnCode;
		$data['privilege'] = intval($accountInfo->privilege);
		$data['char_count_info'] = $charCount;
		$data['benefit'] = $benefits;
	}
}

$res = print_r($_POST, true);
file_put_contents('logs.txt', $res, FILE_APPEND);

if ($returnCode > 0)
	$data['msg'] = $msg;
header('Content-Length: ' . strlen(json_encode($data)));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);