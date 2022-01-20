<?php

require_once(SQL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

$returnCode = 0;

if (count($_POST) !== 4) {
	$data['Return'] = false;
	$data['ReturnCode'] = 50500;
	$data['msg'] = 'Parameter error';
	header('Content-Length: ' . strlen(json_encode($data)));
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data);
	die();
}

if ($_POST['r'] !== '478c98a0b14387f3966ebeec6b570348fffac684b96f1d2e48d0caa51b4b4adb') {
	$returnCode = 58007;
	$msg = 'invalid encoded parameter(base64)';
} else {
	$sql = new SQL();
	$start = microtime();
	$msg = 'success';
	$accountList = [];
	$id = $_POST['userNo'];
	$q = "SELECT * FROM accountinfo WHERE accountDBID = $id";
	$accountList = $sql->conn->query($q);
	
	if ($accountList->num_rows <= 0) {
		$msg = 'Invalid login request';
		$returnCode = 50000;
	} else {
		$accountInfo = $accountList->fetch_object();
		if ($_POST['authKey'] != $accountInfo->authKey) {
			$msg = 'authKey mismatch';
			$returnCode = 50011;
		} else {
			$data['UserNo'] = $id;
			$data['msg'] = $msg;
			$data['AuthKey'] = $_POST['authKey'];
			$data['UserType'] = 'PURCHASE';
			$data['UserID'] = $_POST['userNo'];
			$data['isUsedOTP'] = false;
		}
	}
}

if ($returnCode > 0)
	$data['msg'] = $msg;
if (isset($start))
	$data['ms'] = microtime() - $start;
header('Content-Length: ' . strlen(json_encode($data)));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);