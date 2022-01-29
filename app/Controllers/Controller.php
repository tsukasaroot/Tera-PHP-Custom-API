<?php

namespace App\Controllers;

use Core\Http;

class Controller
{
	public function response(array|string $arg): bool
	{
		Http::sendJson($arg);
		return true;
	}
}