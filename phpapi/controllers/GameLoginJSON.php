<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

file_put_contents('logs.txt', 'Tring GameLoginJSON');

require_once(SQL);
require_once(JSON);

$_POST = get_json_input(file_get_contents('php://input'));

$returnCode = 0;
$data = [];
$accountList = '';

if (!isset($_POST['userNo']) || !isset($_POST['authKey'])) {
	$returnCode = 15000;
	$msg = 'Error GameLogin';
} else {
	$msg = 'success';
	$accountList = [];
	$id = $_POST['userNo'];
	$q = "SELECT authKey FROM accountinfo WHERE accountDBID = $id";
	$accountList = $conn->query($q);
}

if ($returnCode === 0 && $accountList->num_rows <= 0) {
	$msg = 'Invalid login request';
	$returnCode = 50000;
} else if ($returnCode === 0) {
	$accountInfo = $accountList->fetch_assoc();
	if ($_POST['authKey'] != $accountInfo['authKey']) {
		$msg = 'authKey mismatch';
		$returnCode = 50011;
	} else {
		$data['msg'] = $msg;
		$data['isUsedOTP'] = false;
		$data['Return'] = !$returnCode;
		$data['ReturnCode'] = $returnCode;
		$data['UserID'] = $_POST['userNo'];
		$data['AuthKey'] = $_POST['authKey'];
		$data['UserNo'] = $id;
		$data['UserType'] = 'PURCHASE';
		
	}
}

if ($returnCode > 0)
	$data['msg'] = $msg;

file_put_contents('logs.txt', print_r($data, true));

send_json($data);