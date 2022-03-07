<?php

namespace App\Models;

use Core\Model;

class Logs extends Model
{
	public function insertLog(array|string $data): bool
	{
		$this->insert($data, 'log')->execute();
		return true;
	}
	
	public function retrieveLogs(): object|bool|array
	{
		return $this->select()->get();
	}
	
	protected string $table = 'logs';
	protected int $db = 1;
}