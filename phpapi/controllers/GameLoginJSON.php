<?php

require_once(SQL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

$json = file_get_contents('php://input');
if ($json) {
	$_POST = json_decode($json);
	foreach ($json as $k => $v) {
		$_POST[$k] = $v;
	}
}

$returnCode = 0;

$res = print_r($_POST, true);
file_put_contents('logs.txt', $res, FILE_APPEND);

$msg = 'success';
$accountList = [];
$id = $_POST['userNo'];
$q = "SELECT * FROM accountinfo WHERE accountDBID = $id";
$accountList = $conn->query($q);

if ($accountList->num_rows <= 0) {
	$msg = 'Invalid login request';
	$returnCode = 50000;
} else {
	$accountInfo = $accountList->fetch_object();
	if ($_POST['authKey'] != $accountInfo->authKey) {
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
header('Content-Length: ' . strlen(json_encode($data)));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);