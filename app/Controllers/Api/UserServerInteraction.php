<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Controllers\logController;
use App\Models\Users;
class UserServerInteraction extends Controller
{
	public function EnterGame(): bool
	{
		$returnCode = 0;
		$data['result_code'] = $returnCode;
		
		$user = new Users();
		
		$user->updateData([
			'playCount' => 'playCount + 1'
		], $this->request['user_srl']);
		$this->response($data);
		
		return true;
	}
	
	public function LeaveGame(): bool
	{
		if (!isset($this->request['user_srl']) || !isset($this->request['serviceCode']) || !isset($this->request['play_time'])) {
			$data['result_code'] = 2;
			$data['msg'] = 'Error with LeaveGame';
			return $this->response($data);
		}
		$user = new Users();
		
		$res = $user->updateData([
			'playTimeLast' => $this->request['play_time'],
			'playTimeTotal' => 'playTimeTotal + ' . $this->request['play_time']
		], $this->request['user_srl']);
		
		if (!$res) {
			$data['result_code'] = 501000;
			$data['msg'] = 'Error while updating play_time';
			return $this->response($data);
		}
		
		$data['result_code'] = 0;
		$this->response($data);
		
		logController::insert('User no ' . $this->request['user_srl'] . ' has left game');
		return false;
	}
}
