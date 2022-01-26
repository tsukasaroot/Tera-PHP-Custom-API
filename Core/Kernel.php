<?php

namespace Core;

use Core\Routes;

class Kernel
{
	public static function web() {
		$request = $_SERVER['REQUEST_URI'];
		$request = substr($request, 1);
		
		$routing = new Routes();
		$routing->create($request);
	}
}