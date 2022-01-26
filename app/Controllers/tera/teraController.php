<?php

namespace App\Controllers\Tera;

use App\Models\JSON;
use App\Models\SQL;
use stdClass;

class teraController
{
	public function __construct()
	{
	}
	
	public function login()
	{
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
				
				$accountList = SQL::query("SELECT passWord, charCount, isBlocked, accountDBID FROM accountinfo WHERE userName = '$userName'");
				if ($accountList[1] != 1) {
					$msg = "Account doesn't exist";
					$returnCode = 50000;
				} else {
					$accountInfo = $accountList[0]->fetch_assoc();
					$accountList[0]->close();
					$secret_salt = 'TERAISNOTTHATGOODLMAO';
					$pwd_salt = $secret_salt . $password;
					$pass_sha512 = hash('sha512', $pwd_salt);
					
					if ($pass_sha512 != $accountInfo['passWord']) {
						$msg = 'Password not correct';
						$returnCode = 50015;
					} else {
						$newAuthKey = uniqid(more_entropy: true);
						$msg = $newAuthKey;
						$sql_return = SQL::query("UPDATE accountinfo SET authKey = '$newAuthKey' WHERE userName = '$userName'");
						if ($sql_return[1] <= 0) {
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
			
			JSON::send_json($data);
		}
	}
}