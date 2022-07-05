<?php

namespace App\Models;

use Core\Model;

class Users extends Model
{
	public function getUserInfo(array|string|null $select = '*', string $whereKey = '', int|string $user = ''): array|null
	{
		return $this->select($select)
			->where([$whereKey => "'$user'"])
			->getRow();
	}
	
	public function updateAuthKey(string $username, string $authKey): bool
	{
		return $this->update("authKey = '$authKey'")
			->where(['userName' => "'$username'"])
			->execute();
	}
	
	public function updateData(array|string $args, int $id): bool
	{
		return $this->update($args)
			->where(['accountDBID' => $id])
			->execute();
	}
	
	public function getAuthKey(int $id): object|array|null
	{
		return $this->select('authKey')->where(['accountDBID' => $id])->getRow();
	}
	
	public function createUser(array $array, $cols): bool
	{
		$state = $this->insert($array, $cols)->execute();
		
		if (!$state)
			die();
		return true;
	}
	
	protected string $table = 'accountinfo';
	protected int $db = 1;
}