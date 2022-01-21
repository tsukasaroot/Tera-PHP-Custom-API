<?php

require_once(SQL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

$returnCode = 0;

if (count($_POST) !== 1) {
	$data['Return'] = false;
	$data['ReturnCode'] = 50500;
	$data['msg'] = 'Parameter error';
	header('Content-Length: ' . strlen(json_encode($data)));
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data);
	die();
}

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
		
		$data['last_connected_server'] = null;
		$data['last_play_time'] = null;
		$data['logout_time_diff'] = null;
		$data['vip_pub_exp'] = 0;
		$data['permission'] = $accountInfo->isBlocked;
		$data['result_code'] = $returnCode;
		$data['privilege'] = $accountInfo->privilege;
		$data['char_count_info'] = $charCount;
		$data['benefit'] = $benefits;
		
		//$log = print_r($benefits, true);
	}
}

if ($returnCode > 0)
	$data['msg'] = $msg;
header('Content-Length: ' . strlen(json_encode($data)));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);