<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;

class UserServerInteraction extends Controller
{
	public function EnterGame(): bool
	{
		$returnCode = 0;
		$data['result_code'] = $returnCode;
		
		return $this->response($data);
	}
	
	public function LeaveGame(): bool
	{
		if (!isset($this->request['user_srl']) || !isset($this->request['serviceCode']) || !isset($this->request['play_time'])) {
			$data['result_code'] = 2;
			$data['msg'] = 'Error with LeaveGame';
			return $this->response($data);
		}
		
		$data['result_code'] = 0;
		return $this->response($data);
	}
}
