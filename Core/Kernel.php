<?php

namespace Core;
class Kernel
{
	public static function web()
	{
		if (empty($_SERVER['REQUEST_METHOD'])) {
			echo 'nope';
			die();
		}
		$request = $_SERVER['REQUEST_URI'];
		
		$GLOBALS['Http'] = $request;
		
		$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/../.env');
		
		foreach ($env as $k => $v) {
			if (str_starts_with($k, 'db'))
				$GLOBALS['Database'][$k] = $v;
			else
				$GLOBALS[$k] = $v;
		}
		
		date_default_timezone_set($GLOBALS['timezone'] ?? 'Europe/Paris');
		
		error_reporting($GLOBALS['debug'] ?? false);
		ini_set('display_errors', $GLOBALS['debug'] ?? false);
		
		$token = new Token();
		$token->check_token($_SERVER['AUTH_TOKEN'] ?? '');
		
		Http::received_input();
		Routes::create();
	}
}