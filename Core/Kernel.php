<?php

namespace Core;
class Kernel
{
	public static function web() {
		$request = $_SERVER['REQUEST_URI'];
		$request = substr($request, 1);
		
		$GLOBALS['Http'] = $request;
		
		Http::receivedInput();
		Routes::create();
	}
}