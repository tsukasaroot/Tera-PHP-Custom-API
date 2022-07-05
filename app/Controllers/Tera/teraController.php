<?php

namespace App\Controllers\Tera;

use App\Controllers\Controller;
use App\Models\Users;
use stdClass;

class teraController extends Controller
{
	public function getAccountInfoByUserNo(): bool
	{
		$auth = $_SERVER['HTTP_AUTHORIZATION'];
		$auth = str_replace('Bearer ', '', $auth);
		$user = new Users();
		
		$accountInfo = $user->getUserInfo(['accountDBID', 'charCount, isBlocked'], 'authKey', $auth);
		if (!$accountInfo) {
			$data['msg'] = "Account doesn't exist";
			$data['ReturnCode'] = 50000;
			return $this->response($data);
		}

		$data['result-message'] = 'OK';
		$data['result-code'] = 200;
		$data['account_bits'] = '0x00000000';
		$data['ticket'] = $auth;
		$data['last_connected_server_id'] = 1;
		$data['master_account_name'] = $accountInfo['accountDBID'];
		$class = [];
		$class[] = new stdClass();
		$class[0]->id = 2800;
		$class[0]->char_count = '-8';
		$data['chars_per_server'] = $class;
		$data['user_permission'] = 0;
		$data['game_account_name'] = 'TERA';

		return $this->response($data);
	}
	
	public function register(): bool
	{
		if (!isset($this->request['r']) || $this->request['r'] !== '478c98a0b14387f3966ebeec6b570348fffac684b96f1d2e48d0caa51b4b4adb'
			|| empty($this->request['userID']) || empty($this->request['password'])) {
			$data['ReturnCode'] = 58007;
			$data['Return'] = !$data['ReturnCode'];
			$data['msg'] = 'LauncherLoginAction got a parameter error';
			return $this->response($data);
		}
		
		$user = new Users();
		
		$secret_salt = $GLOBALS['salt'];
		$pwd_salt = $secret_salt . $this->request['password'];
		$pass_sha512 = hash('sha512', $pwd_salt);
		
		$state = $user->createUser([
			'userName' => $this->request['userID'],
			'passWord' => $pass_sha512
		], 'userName,passWord');
		
		return $this->login();
	}
	
	public function login(): bool
	{
		$user = new Users();
		
		$accountInfo = $user->getUserInfo([
			'passWord',
			'charCount',
			'isBlocked',
			'accountDBID'
		], 'userName', $this->request['username']);
		
		if (!$accountInfo) {
			$data['msg'] = "Account doesn't exist";
			$data['ReturnCode'] = 58000;
			$data['Return'] = !$data['ReturnCode'];
			return $this->response($data);
		}
		
		if ($accountInfo['isBlocked']) {
			$data['msg'] = "Account is blocked";
			$data['ReturnCode'] = 58000;
			$data['Return'] = !$data['ReturnCode'];
			$this->response($data, 401);
			die();
		}
		
		$secret_salt = $GLOBALS['salt'];
		$pwd_salt = $secret_salt . $this->request['password'];
		$pass_sha512 = hash('sha512', $pwd_salt);
		
		if ($pass_sha512 != $accountInfo['passWord']) {
			$data['msg'] = 'Password not correct';
			$data['returnCode'] = 50015;
			$data['Return'] = !$data['returnCode'];
			return $this->response($data);
		}
		
		$newAuthKey = uniqid(more_entropy: true);
		$newAuthKey = str_replace('.', '', $newAuthKey);
		$authKeySuccess = $user->updateAuthKey($this->request['username'], $newAuthKey);
		
		if (!$authKeySuccess) {
			$data['msg'] = 'Error occurred with auth token';
			$data['returnCode'] = 50811;
			$data['Return'] = !$data['ReturnCode'];
			return $this->response($data);
		}

		$data['token'] = $newAuthKey;
		$data['id'] = $accountInfo['accountDBID'];
		$data['username'] = $this->request['username'];

		return $this->response($data, 201);
	}
}
