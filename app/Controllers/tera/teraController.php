<?php

namespace App\Controllers\Tera;

use App\Models\JSON;
use App\Models\SQL;
use App\Models\Users as Users;
use stdClass;

class teraController
{
	public function getAccountInfoByUserNo()
	{
		$returnCode = 0;
		$data = [];
		
		if (!isset($_POST['id'])) {
			$returnCode = 2;
			$msg = "ID error";
		} else {
			$user = new Users();
			$id = $_POST['id'];
			
			$accountInfo = $user->select(['charCount, isBlocked'])
				->where(['accountDBID' => $id])
				->get_row();
			
			if (!$accountInfo) {
				$msg = "Account doesn't exist";
				$returnCode = 50000;
			} else {
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
		
		JSON::send_json($data);
	}
	
	public function login()
	{
		$returnCode = 0;
		$data = [];
		
		if (!isset($_POST['r']) && $_POST['r'] !== '478c98a0b14387f3966ebeec6b570348fffac684b96f1d2e48d0caa51b4b4adb') {
			$returnCode = 58007;
			$msg = 'invalid encoded parameter(base64)';
		} else {
			$user = new Users();
			if ($_POST['userID'] && $_POST['password']) {
				$logfile = '';
				$userName = $_POST['userID'];
				$password = $_POST['password'];
				$msg = 'success';
				
				$accountInfo = $user->select([
					'passWord',
					'charCount',
					'isBlocked',
					'accountDBID'
				])
					->where(['userName' => "'$userName'" ])
					->get_row();
				
				if (!$accountInfo) {
					$msg = "Account doesn't exist";
					$returnCode = 50000;
				} else {
					$secret_salt = 'TERAISNOTTHATGOODLMAO';
					$pwd_salt = $secret_salt . $password;
					$pass_sha512 = hash('sha512', $pwd_salt);
					
					if ($pass_sha512 != $accountInfo['passWord']) {
						$msg = 'Password not correct';
						$returnCode = 50015;
					} else {
						$newAuthKey = uniqid(more_entropy: true);
						
						$accountInfo = $user->select([
							'passWord',
							'charCount',
							'isBlocked',
							'accountDBID'
						])
							->where(['userName' => "'$userName'" ])
							->get_row();
						
						$authKeySuccess = $user->update("authKey = '$newAuthKey'")
						->where([ 'userName' => $userName ])
						->execute();
						
						if (!$authKeySuccess) {
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