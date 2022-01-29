<?php

namespace App\Models;

use Core\Model\Model;

class Users extends Model
{
	public function getUserInfo(array|string|null $select = '*', string $whereKey='', int|string $user = 0): array|null
	{
		return $this->select($select)
			->where([$whereKey => "'$user'"])
			->get_row();
	}
	
	public function updateAuthKey(string $username, string $authKey): bool
	{
		return $this->update("authKey = '$authKey'")
			->where(['userName' => "'$username'"])
			->execute();
	}
	
	protected string $table = 'accountinfo';
}