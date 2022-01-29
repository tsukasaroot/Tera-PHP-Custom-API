<?php

namespace App\Controllers\Tera;

use App\Controllers\Controller;
use App\Models\Users;
use stdClass;

class teraController extends Controller
{
	public function getAccountInfoByUserNo(): bool
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
		
		return $this->response($data);
	}
	
	public function login(): bool
	{
		if (!isset($_POST['r']) || $_POST['r'] !== '478c98a0b14387f3966ebeec6b570348fffac684b96f1d2e48d0caa51b4b4adb'
			|| empty($_POST['userID']) || empty($_POST['password'])) {
			$data['ReturnCode'] = 58007;
			$data['Return'] = !$data['ReturnCode'];
			$data['msg'] = 'LauncherLoginAction got a parameter error';
			return $this->response($data);
		}
		
		$user = new Users();
		$userName = $_POST['userID'];
		$password = $_POST['password'];
		
		$accountInfo = $user->getUserInfo([
			'passWord',
			'charCount',
			'isBlocked',
			'accountDBID'
		], $userName);
		
		if (!$accountInfo) {
			$data['msg'] = "Account doesn't exist";
			$data['ReturnCode'] = 58000;
			$data['Return'] = !$data['ReturnCode'];
			return $this->response($data);
		}
		
		$secret_salt = $GLOBALS['salt'];
		$pwd_salt = $secret_salt . $password;
		$pass_sha512 = hash('sha512', $pwd_salt);
		
		if ($pass_sha512 != $accountInfo['passWord']) {
			$data['msg'] = 'Password not correct';
			$data['returnCode'] = 50015;
			$data['Return'] = !$data['returnCode'];
			return $this->response($data);
		}
		
		$newAuthKey = uniqid(more_entropy: true);
		$authKeySuccess = $user->updateAuthKey($userName, $newAuthKey);
		
		if (!$authKeySuccess) {
			$data['msg'] = 'Error occurred with auth token';
			$data['returnCode'] = 50811;
			$data['Return'] = !$data['ReturnCode'];
			return $this->response($data);
		}
		
		$characterCount = match ($accountInfo['charCount']) {
			1 => '0|2800,1',
			2 => '0|2800,2',
			3 => '0|2800,3',
			default => '0|2800,0'
		};
		
		$data['VipitemInfo'] = false;
		$data['msg'] = 'success';
		$data['FailureCount'] = 0;
		$data['PassitemInfo'] = false;
		$data['ReturnCode'] = 0;
		$data['Return'] = !$data['ReturnCode'];
		$data['CharacterCount'] = $characterCount . '|';
		$data['Permission'] = $accountInfo['isBlocked'];
		$data['AuthKey'] = $newAuthKey;
		$data['UserNo'] = $accountInfo['accountDBID'];
		
		$obj = new stdClass();
		$obj->enumType = 'com.common.auth.User$UserStatus';
		$obj->name = 'JOIN';
		$data['UserStatus'] = $obj;
		$data['phoneLock'] = false;
		
		return $this->response($data);
	}
}