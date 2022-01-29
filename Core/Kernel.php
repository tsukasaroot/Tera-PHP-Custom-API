<?php

namespace Core;
class Kernel
{
	public static function web()
	{
		$request = $_SERVER['REQUEST_URI'];
		$request = substr($request, 1);
		
		$GLOBALS['Http'] = $request;
		
		$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/../.env');
		
		foreach ($env as $k => $v) {
			$GLOBALS[$k] = $v;
		}
		
		Http::receivedInput();
		Routes::create();
	}
}