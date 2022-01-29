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
		$msg = '';
		$result_code = 0;
		
		if (!isset($this->request['user_srl']) || !isset($this->request['serviceCode'])) {
			$result_code = 2;
			$msg = 'Error with LeaveGame';
		}
		
		$data['result_code'] = $result_code;
		if ($result_code > 0)
			$data['msg'] = $msg;
		
		return $this->response($data);
	}
}