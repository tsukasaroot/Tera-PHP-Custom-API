<?php

namespace App\Models;

use Core\Model;

class Users extends Model
{
	public function getUserInfo(array|string|null $select = '*', string $whereKey = '', int|string $user = 0): array|null
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
	
	public function updateData(array|string $args, int $id): bool
	{
		return $this->update($args)
			->where(['accountDBID' => $id])
			->execute();
	}
	
	public function getAuthKey(int $id): object|array|null
	{
		return $this->select('authKey')->where(['accountDBID' => $id])->get_row();;
	}
	
	protected string $table = 'accountinfo';
}