<?php

namespace App\Controllers\Arbiter;

use App\Models\JSON;

class ArbiterApi
{
	public function CreateChar()
	{
		$msg = '';
		$result_code = 0;
		$data = [];
		
		$_POST = JSON::get_json_input(file_get_contents('php://input'));
		
		file_put_contents('logs.txt', print_r($_POST, true));
		
		/*if ($result_code > 0)
			$data['msg'] = $msg;*/
		
		$data['result_code'] = $result_code;
		
		JSON::send_json($data);
	}
	
	public function ModifyChar()
	{
		$msg = '';
		$result_code = 0;
		$data = [];
		
		$_POST = JSON::get_json_input(file_get_contents('php://input'));
		
		file_put_contents('logs.txt', print_r($_POST, true));
		
		if ($result_code > 0)
			$data['msg'] = $msg;
		
		$data['result_code'] = $result_code;
		
		JSON::send_json($data);
	}
	
	public function DeleteChar()
	{
		$msg = '';
		$result_code = 0;
		$data = [];
		
		$_POST = JSON::get_json_input(file_get_contents('php://input'));
		
		file_put_contents('logs.txt', print_r($_POST, true));
		
		if ($result_code > 0)
			$data['msg'] = $msg;
		
		$data['result_code'] = $result_code;
		
		JSON::send_json($data);
	}
	
	public function ServiceTest()
	{
		$result_code = 0;
		$data['server_time'] = time();
		$data['result_code'] = $result_code;
		
		JSON::send_json($data);
	}
	
	public function GetServerPermission()
	{
		$result_code = 0;
		$permission = 0;
		
		$data['permission'] = $permission;
		$data['result_code'] = $result_code;
		
		JSON::send_json($data);
	}
	
	public function ServerDown()
	{
		$result_code = 0;
		$data['result_code'] = $result_code;
		
		JSON::send_json($data);
	}
}