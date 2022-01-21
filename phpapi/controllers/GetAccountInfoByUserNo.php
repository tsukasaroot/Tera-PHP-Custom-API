<?php

// Step 2 from web call launcher

require_once(SQL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

$returnCode = 0;

if (!$_POST['id']) {
	$returnCode = 2;
	$msg = "ID error";
} else {
	$id = $_POST['id'];
	$accountList = [];
	$q = "SELECT * FROM accountinfo WHERE accountDBID = $id";
	$accountList = $conn->query($q);
	
	if ($accountList->num_rows != 1) {
		$msg = "Account doesn't exist";
		$returnCode = 50000;
	} else {
		$accountInfo = $accountList->fetch_object();
		$accountList->close();
		$characterCount = match ($accountInfo->charCount) {
			1 => '0|2800,1',
			2 => '0|2800,2',
			3 => '0|2800,3',
			default => '0|2800,0'
		};
		$data['Charcountstr'] = $characterCount . '|';
		$data['PassitemInfo'] = false;
		$data['Permission'] = $accountInfo->isBlocked;
		$data['VipitemInfo'] = false;
		
		$logfile = "$accountInfo->userName getting info " . date('Y-m-d h:m:s' . "\n");
	}
}
if (isset($logfile))
	file_put_contents('logs.txt', $logfile, FILE_APPEND);

if ($returnCode > 0)
	$data['msg'] = $msg;
header('Content-Length: ' . strlen(json_encode($data)));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);