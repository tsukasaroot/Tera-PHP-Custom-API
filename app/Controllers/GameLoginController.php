<?php

namespace App\Controllers;

use App\Models\Users;

class GameLoginController extends Controller
{
	public function login(): bool
	{
		$data = [];
		
		if (!isset($this->request['userNo']) || !isset($this->request['authKey'])) {
			$data['returnCode'] = 15000;
			$data['msg'] = 'Error GameLogin';
			return $this->response($data);
		}
		$user = new Users();
		$accountInfo = $user->getAuthKey($this->request['userNo']);
		
		if (!$accountInfo) {
			$data['msg'] = 'Invalid login request';
			$data['returnCode'] = 50000;
			return $this->response($data);
		}
		if ($this->request['authKey'] != $accountInfo['authKey']) {
			$data['msg'] = 'authKey mismatch';
			$data['returnCode'] = 50011;
			return $this->response($data);
		}
		$data['msg'] = 'success';
		$data['isUsedOTP'] = false;
		$data['ReturnCode'] = 0;
		$data['Return'] = !$data['ReturnCode'];
		$data['UserID'] = $_POST['userNo'];
		$data['AuthKey'] = $_POST['authKey'];
		$data['UserNo'] = $this->request['userNo'];
		$data['UserType'] = 'PURCHASE';
		
		return $this->response($data);
	}
}
