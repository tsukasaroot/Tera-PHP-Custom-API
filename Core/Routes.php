<?php

namespace Core;

class Routes
{
	public static function get(string $route, string $action)
	{
		if ($GLOBALS['Http'] !== $route)
			return;
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			echo 'nope';
			die();
		}
		
		call($action);
	}
	
	public static function post(string $route, string $action)
	{
		if ($GLOBALS['Http'] !== $route)
			return;
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo 'nope';
			die();
		}
		
		call($action);
	}
	
	public static function create()
	{
		require '../routes/web.php';
	}
}

function call($action)
{
	$action = explode('@', $action);
	$action[0] =  'App\\Controllers\\' . $action[0];
	
	$obj = new $action[0]();
	call_user_func([ $obj, $action[1] ]);
}