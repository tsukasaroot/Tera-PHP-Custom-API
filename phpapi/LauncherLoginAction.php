<?php

require_once('SQL.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
	echo 'nope';
	die();
}

if ($_POST['r'] !== '478c98a0b14387f3966ebeec6b570348fffac684b96f1d2e48d0caa51b4b4adb') {
	$returnCode = 2;
}

if ($_POST['userID'] && $_POST['password']) {
	$returnCode = 0;
	$logfile = '';
	$sql = new SQL();
	$userName = $_POST['userID'];
	$password = $_POST['password'];
	$data = [];
	$msg = 'success';
	
	$accountList = $sql->conn->query("SELECT * FROM accountinfo WHERE userName = '$userName'");
	if ($accountList->num_rows != 1) {
		$msg = "Account doesn't exist";
		$returnCode = 50000;
	} else {
		$accountInfo = $accountList->fetch_object();
		$accountList->close();
		$secret_salt = 'TERAISNOTTHATGOODLMAO';
		$pwd_salt = $secret_salt . $password;
		$pass_sha512 = hash('sha512', $pwd_salt);
		
		if ($pass_sha512 != $accountInfo->passWord) {
			$msg = 'Password not correct';
			$returnCode = 50015;
		} else {
			$newAuthKey = uniqid(more_entropy: true);
			$msg = $newAuthKey;
			$sql_return = $sql->conn->query("UPDATE accountinfo SET authKey = '$newAuthKey' WHERE userName = '$userName'");
			if ($sql->conn->affected_rows <= 0) {
				$msg = "Error occurred with auth token";
				$returnCode = 50811;
			}
			
			if ($returnCode === 0) {
				$characterCount = match ($accountInfo->charCount) {
					1 => '0|2800,1',
					2 => '0|2800,2',
					3 => '0|2800,3',
					default => '0|2800,0'
				};
			}
			$data['FailureCount'] = 0;
			$data['Permission'] = $accountInfo->isBlocked;
			$data['AuthKey'] = $newAuthKey;
			$data['UserNo'] = $accountInfo->accountDBID;
			$data['VipitemInfo'] = false;
			$data['CharacterCount'] = $characterCount;
			$data['PassitemInfo'] = false;
			$data['phoneLock'] = false;
			$obj = new stdClass();
			$obj->enumType = 'com.common.auth.User$UserStatus';
			$obj->name = 'JOIN';
			$data['UserStatus'] = $obj;
			
			$logfile = "$userName is connecting now " . date('Y-m-d h:m:s' . "\n");
		}
	}
	
	$data['msg'] = $msg;
	$data['ReturnCode'] = $returnCode;
	$data['Return'] = !$returnCode;
	
	file_put_contents('logs.txt', $logfile, FILE_APPEND);
	
	header('Content-Length: ' . strlen(json_encode($data)));
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data);
}
