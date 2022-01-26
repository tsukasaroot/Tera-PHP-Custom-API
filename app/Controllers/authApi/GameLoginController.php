<?php

namespace App\Controllers\authApi;

use App\Models\JSON;
use App\Models\SQL;

class GameLoginController
{
	public function login()
	{
		$_POST = JSON::get_json_input(file_get_contents('php://input'));
		
		$returnCode = 0;
		$data = [];
		$accountList = '';
		
		if (!isset($_POST['userNo']) || !isset($_POST['authKey'])) {
			$returnCode = 15000;
			$msg = 'Error GameLogin';
		} else {
			$msg = 'success';
			$accountList = [];
			$id = $_POST['userNo'];
			$q = "SELECT authKey FROM accountinfo WHERE accountDBID = $id";
			$accountList = SQL::query($q);
		}
		
		if ($returnCode === 0 && $accountList[1] <= 0) {
			$msg = 'Invalid login request';
			$returnCode = 50000;
		} else if ($returnCode === 0) {
			$accountInfo = $accountList[0]->fetch_assoc();
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