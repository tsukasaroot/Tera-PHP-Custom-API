<?php

namespace App\Controllers\AuthApi;

use App\Models\JSON;
use App\Models\Users;

class GameLoginController
{
	public function login()
	{
		$_POST = JSON::get_json_input(file_get_contents('php://input'));
		
		$returnCode = 0;
		$data = [];
		$accountInfo = [];
		
		if (!isset($_POST['userNo']) || !isset($_POST['authKey'])) {
			$returnCode = 15000;
			$msg = 'Error GameLogin';
		} else {
			$user = new Users();
			
			$msg = 'success';
			$id = $_POST['userNo'];
			$accountInfo = $user->select('authKey')->where([ 'accountDBID' => $id ])->get_row();
		}
		
		if ($returnCode === 0 && !$accountInfo) {
			$msg = 'Invalid login request';
			$returnCode = 50000;
		} else if ($returnCode === 0) {
			if ($_POST['authKey'] != $accountInfo['authKey']) {
				$msg = 'authKey mismatch';
				$returnCode = 50011;
			} else {
				$data['msg'] = $msg;
				$data['isUsedOTP'] = false;
				$data['Return'] = !$returnCode;
				$data['ReturnCode'] = $returnCode;
				$data['UserID'] = $_POST['userNo'];
				$data['AuthKey'] = $_POST['authKey'];
				$data['UserNo'] = $id;
				$data['UserType'] = 'PURCHASE';
				
			}
		}
		
		if ($returnCode > 0)
			$data['msg'] = $msg;
		
		file_put_contents('logs.txt', print_r($data, true));
		
		JSON::send_json($data);
	}
}