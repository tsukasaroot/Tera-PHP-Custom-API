<?php

require_once(SQL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

$returnCode = 0;

if ($_POST['r'] !== '478c98a0b14387f3966ebeec6b570348fffac684b96f1d2e48d0caa51b4b4adb') {
	$returnCode = 58007;
	$msg = 'invalid encoded parameter(base64)';
} else {
	if (!$_POST['id']) {
		$returnCode = 2;
		$msg = "ID error";
	} else {
		$id = $_POST['id'];
		$sql = new SQL();
		$accountList = [];
		$q = "SELECT * FROM accountinfo WHERE accountDBID = $id";
		$accountList = $sql->conn->query($q);
		
		if ($accountList->num_rows != 1) {
			$msg = "Account doesn't exist";
			$returnCode = 50000;
		} else {
			$accountInfo = $accountList->fetch_object();
			$characterCount = match ($accountInfo->charCount) {
				1 => '0|2800,1',
				2 => '0|2800,2',
				3 => '0|2800,3',
				default => '0|2800,0'
			};
			$data['Permission'] = $accountInfo->isBlocked;
			$data['VipitemInfo'] = false;
			$data['Charcountstr'] = $characterCount;
			$data['PassitemInfo'] = false;
			
			$logfile = "$accountInfo->userName getting info " . date('Y-m-d h:m:s' . "\n");
		}
	}
}
if (isset($logfile))
	file_put_contents('logs.txt', $logfile, FILE_APPEND);

if ($returnCode > 0)
	$data['msg'] = $msg;
header('Content-Length: ' . strlen(json_encode($data)));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);