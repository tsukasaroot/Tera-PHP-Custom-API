<?php

namespace App\Controllers;

use App\Models\Logs;
class logController extends Controller
{
	public static function insert(string $text)
	{
		$logs = new Logs();
		$logs->insertLog("'$text'");
	}
}