<?php

// Step 2 from web call launcher

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

file_put_contents('logs.txt', 'Tring GetAccountInfoByUserNo');

require_once(SQL);
require_once(JSON);

$returnCode = 0;
$data = [];

if (!isset($_POST['id'])) {
	$returnCode = 2;
	$msg = "ID error";
} else {
	$id = $_POST['id'];
	$accountList = [];
	$q = "SELECT charCount,isBlocked  FROM accountinfo WHERE accountDBID = $id";
	$accountList = $conn->query($q);
	
	if ($accountList->num_rows != 1) {
		$msg = "Account doesn't exist";
		$returnCode = 50000;
	} else {
		$accountInfo = $accountList->fetch_assoc();
		$accountList->close();
		$characterCount = match ($accountInfo['charCount']) {
			1 => '0|2800,1',
			2 => '0|2800,2',
			3 => '0|2800,3',
			default => '0|2800,0'
		};
		$data['charcountstr'] = $characterCount . '|';
		$data['passitemInfo'] = false;
		$data['permission'] = $accountInfo['isBlocked'];
		$data['vipitemInfo'] = false;
	}
}

if ($returnCode > 0)
	$data['msg'] = $msg;

file_put_contents('logs.txt', print_r($data, true));

send_json($data);