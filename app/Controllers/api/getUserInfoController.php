<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Models\Users;
use App\Models\AccountBenefits;

class getUserInfoController extends Controller
{
	public function info(): bool
	{
		$returnCode = 0;
		$data = [];
		$msg = '';
		
		if (!isset($_POST['user_srl']) || !isset($_POST['server_id']) ||
			!isset($_POST['ip']) || !isset($_POST['serviceCode'])) {
			$returnCode = 2;
			$msg = 'user_srl=' . isset($_POST['user_srl']) . '&server_id=' . isset($_POST['server_id'])
				. "&ip=" . isset($_POST['ip']) . "&serviceCode=" . isset($_POST['serviceCode']);
		}
		
		if (!$_POST['user_srl']) {
			$msg = 'id=null';
			$returnCode = 15000;
		} else {
			$user = new Users();
			$benef = new AccountBenefits();

			$id = $_POST['user_srl'];
			$accountInfo = $user->select([ 'isBlocked', 'privilege', 'charCount' ])
				->where([ 'accountDBID' => $id ])
				->get_row();
			
			if ($accountInfo <= 0) {
				$msg = 'Invalid login request';
				$returnCode = 50000;
			} else {
				$charCount = "0|2800," . $accountInfo['charCount'] . '|';
				$all_benefits = $benef->select()->where([ 'accountDBID' => $id ])
					->get();
				
				$benefits = [];
				
				$i = 0;
				while ($i < count($all_benefits)) {
					$benefits[$i][] = intval($all_benefits[$i][1]);
					$benefits[$i][] = $all_benefits[$i][2] - time();
					$i++;
				}
				
				$data['vip_pub_exp'] = 0;
				$data['permission'] = intval($accountInfo['isBlocked']);
				$data['result_code'] = $returnCode;
				$data['privilege'] = intval($accountInfo['privilege']);
				$data['char_count_info'] = $charCount;
				$data['benefit'] = $benefits;
			}
		}
		
		if ($returnCode > 0)
			$data['msg'] = $msg;
		
		return $this->response($data);
	}
}