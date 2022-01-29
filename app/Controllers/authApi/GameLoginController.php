<?php

namespace App\Controllers\AuthApi;

use App\Controllers\Controller;
use App\Models\Users;

class GameLoginController extends Controller
{
	public function login(): bool
	{
		$data = [];
		
		if (!isset($_POST['userNo']) || !isset($_POST['authKey'])) {
			$data['returnCode'] = 15000;
			$data['msg'] = 'Error GameLogin';
			return $this->response($data);
		}
		$user = new Users();
		$id = $_POST['userNo'];
		
		$accountInfo = $user->getAuthKey($id);
		
		if (!$accountInfo) {
			$data['msg'] = 'Invalid login request';
			$data['returnCode'] = 50000;
			return $this->response($data);
		}
		if ($_POST['authKey'] != $accountInfo['authKey']) {
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
		$data['UserNo'] = $id;
		$data['UserType'] = 'PURCHASE';
		
		return $this->response($data);
	}
}