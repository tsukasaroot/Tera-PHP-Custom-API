<?php

// Step 1, logs the user in the launcher

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

require_once(SQL);
require_once(JSON);

$returnCode = 0;
$data = [];

if (!isset($_POST['r']) && $_POST['r'] !== '478c98a0b14387f3966ebeec6b570348fffac684b96f1d2e48d0caa51b4b4adb') {
	$returnCode = 58007;
	$msg = 'invalid encoded parameter(base64)';
} else {
	if ($_POST['userID'] && $_POST['password']) {
		$logfile = '';
		$userName = $_POST['userID'];
		$password = $_POST['password'];
		$msg = 'success';
		
		$accountList = $conn->query("SELECT passWord, charCount, isBlocked, accountDBID FROM accountinfo WHERE userName = '$userName'");
		if ($accountList->num_rows != 1) {
			$msg = "Account doesn't exist";
			$returnCode = 50000;
		} else {
			$accountInfo = $accountList->fetch_assoc();
			$accountList->close();
			$secret_salt = 'TERAISNOTTHATGOODLMAO';
			$pwd_salt = $secret_salt . $password;
			$pass_sha512 = hash('sha512', $pwd_salt);
			
			if ($pass_sha512 != $accountInfo['passWord']) {
				$msg = 'Password not correct';
				$returnCode = 50015;
			} else {
				$newAuthKey = uniqid(more_entropy: true);
				$msg = $newAuthKey;
				$sql_return = $conn->query("UPDATE accountinfo SET authKey = '$newAuthKey' WHERE userName = '$userName'");
				if ($conn->affected_rows <= 0) {
					$msg = "Error occurred with auth token";
					$returnCode = 50811;
				}
				
				if ($returnCode === 0) {
					$characterCount = match ($accountInfo['charCount']) {
						1 => '0|2800,1',
						2 => '0|2800,2',
						3 => '0|2800,3',
						default => '0|2800,0'
					};
				}
				$data['VipitemInfo'] = false;
				$data['msg'] = 'success';
				$data['FailureCount'] = 0;
				$data['PassitemInfo'] = false;
				$data['ReturnCode'] = $returnCode;
				$data['Return'] = !$returnCode;
				$data['CharacterCount'] = $characterCount . '|';
				$data['Permission'] = $accountInfo['isBlocked'];
				$data['AuthKey'] = $newAuthKey;
				$data['UserNo'] = $accountInfo['accountDBID'];
				
				$obj = new stdClass();
				$obj->enumType = 'com.common.auth.User$UserStatus';
				$obj->name = 'JOIN';
				$data['UserStatus'] = $obj;
				$data['phoneLock'] = false;
			}
		}
	}
	
	if ($returnCode > 0)
		$data['msg'] = $msg;
	
	send_json($data);
}