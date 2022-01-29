<?php

namespace App\Controllers\Arbiter;
use App\Controllers\Controller;

class ArbiterApi extends Controller
{
	public function CreateChar(): bool
	{
		$msg = '';
		$result_code = 0;
		$data = [];
		
		file_put_contents('logs.txt', print_r($_POST, true));
		
		/*if ($result_code > 0)
			$data['msg'] = $msg;*/
		
		$data['result_code'] = $result_code;
		
		return $this->response($data);
	}
	
	public function ModifyChar(): bool
	{
		return $this->response($this->toReword());
	}
	
	public function DeleteChar(): bool
	{
		return $this->response($this->toReword());
	}
	
	public function ServiceTest(): bool
	{
		$result_code = 0;
		$data['server_time'] = time();
		$data['result_code'] = $result_code;
		
		return $this->response($data);
	}
	
	public function GetServerPermission(): bool
	{
		$result_code = 0;
		$permission = 0;
		
		$data['permission'] = $permission;
		$data['result_code'] = $result_code;
		
		return $this->response($data);
	}
	
	public function ServerDown(): bool
	{
		$result_code = 0;
		$data['result_code'] = $result_code;
		
		return $this->response($data);
	}
	
	/**
	 * @return void
	 */
	private function toReword(): array
	{
		$msg = '';
		$result_code = 0;
		$data = [];
		
		file_put_contents('logs.txt', print_r($_POST, true));
		
		if ($result_code > 0)
			$data['msg'] = $msg;
		
		$data['result_code'] = $result_code;
		return $data;
	}
}