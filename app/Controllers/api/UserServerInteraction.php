<?php

namespace App\Controllers\Api;

use App\Models\JSON;
class UserServerInteraction
{
	public function EnterGame()
	{
		$returnCode = 0;
		$data['result_code'] = $returnCode;
		
		JSON::send_json($data);
	}
	
	public function LeaveGame()
	{
		$_POST = JSON::get_json_input(file_get_contents('php://input'));
		$msg = '';
		$result_code = 0;
		
		if (!isset($_POST['user_srl']) || !isset($_POST['serviceCode'])) {
			$result_code = 2;
			$msg = 'Error with LeaveGame';
		}
		
		$data['result_code'] = $result_code;
		if ($result_code > 0)
			$data['msg'] = $msg;
		
		JSON::send_json($data);
	}
}