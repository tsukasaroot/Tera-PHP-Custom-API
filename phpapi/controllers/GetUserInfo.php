<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

require_once(SQL);
require_once(JSON);

$_POST = get_json_input(file_get_contents('php://input'));

$returnCode = 0;
$data = [];
$msg = '';

if (!isset($_POST['user_srl']) || !isset($_POST['server_id']) ||
	!isset($_POST['ip']) || !isset($_POST['serviceCode'])) {
	$returnCode = 2;
	$msg = 'user_srl=' . isset($_POST['user_srl']) . '&server_id=' . isset($_POST['server_id'])
	. "&ip=" . isset($_POST['ip']) . "&serviceCode=" . isset($_POST['serviceCode']);
}

if (!$_POST['user_srl']) {
	$msg = 'id=null';
	$returnCode = 15000;
} else {
	$accountList = [];
	$id = $_POST['user_srl'];
	$q = "SELECT isBlocked, privilege, charCount FROM accountinfo WHERE accountDBID = $id";
	$accountList = $conn->query($q);
	
	if ($accountList->num_rows <= 0) {
		$msg = 'Invalid login request';
		$returnCode = 50000;
	} else {
		$accountInfo = $accountList->fetch_assoc();
		$accountList->close();
		$charCount = "0|2800," . $accountInfo['charCount'] . '|';
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
		$data['permission'] = intval($accountInfo['isBlocked']);
		$data['result_code'] = $returnCode;
		$data['privilege'] = intval($accountInfo['privilege']);
		$data['char_count_info'] = $charCount;
		$data['benefit'] = $benefits;
	}
}

if ($returnCode > 0)
	$data['msg'] = $msg;

send_json($data);