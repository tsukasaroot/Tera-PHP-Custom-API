<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Models\Users;
use App\Models\AccountBenefits;

class getUserInfoController extends Controller
{
	public function info(): bool
	{
		if (empty($this->request['user_srl']) || empty($this->request['server_id']) ||
			empty($this->request['ip']) || empty($this->request['serviceCode'])) {
			$data['returnCode'] = 15000;
			$data['msg'] = 'user_srl=' . isset($_POST['user_srl']) . '&server_id=' . isset($_POST['server_id'])
				. "&ip=" . isset($_POST['ip']) . "&serviceCode=" . isset($_POST['serviceCode']);
			return $this->response($data);
		}
		
		$user = new Users();
		$benef = new AccountBenefits();
		
		$id = $this->request['user_srl'];
		
		$accountInfo = $user->getUserInfo(['isBlocked', 'privilege', 'charCount'], 'accountDBID', $id);
		
		if ($accountInfo <= 0) {
			$data['msg'] = 'Invalid login request';
			$data['returnCode'] = 50000;
			return $this->response($data);
		}

		$charCount = "0|2800," . $accountInfo['charCount'] . '|';
		$all_benefits = $benef->select()->where(['accountDBID' => $id])->get();
		$benefits = [];
		$i = 0;
		while ($i < count($all_benefits)) {
			$benefits[$i][] = intval($all_benefits[$i][1]);
			$benefits[$i][] = $all_benefits[$i][2] - time();
			$i++;
		}
		
		$data['vip_pub_exp'] = 0;
		$data['permission'] = intval($accountInfo['isBlocked']);
		$data['result_code'] = 0;
		$data['privilege'] = intval($accountInfo['privilege']);
		$data['char_count_info'] = $charCount;
		$data['benefit'] = $benefits;
		
		return $this->response($data);
	}
}