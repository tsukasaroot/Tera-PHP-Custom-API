<?php

namespace Core;

use Closure;
class Routes
{
	private static function perform_route_check(string $method, string $route): bool
	{
		if ($_SERVER['REQUEST_METHOD'] !== $method)
			return false;
		if ($GLOBALS['Http'] !== $route)
			return false;
		return true;
	}
	
	public static function get(string $route, string $action = '', Closure $closure = null)
	{
		if (!self::perform_route_check('GET', $route))
			return;
		
		if (!$closure)
			self::call($action);
		else
			$closure();
		die();
	}
	
	public static function post(string $route, string $action = '', Closure $closure = null)
	{
		if (!self::perform_route_check('POST', $route))
			return;
		
		if (!$closure)
			self::call($action);
		else
			$closure();
		die();
	}
	
	public static function put(string $route, string $action = '', Closure $closure = null)
	{
		if (!self::perform_route_check('PUT', $route))
			return;
		
		if (!$closure)
			self::call($action);
		else
			$closure();
		die();
	}
	
	public static function patch(string $route, string $action = '', Closure $closure = null)
	{
		if (!self::perform_route_check('PATCH', $route))
			return;
		
		if (!$closure)
			self::call($action);
		else
			$closure();
		die();
	}
	
	public static function delete(string $route, string $action = '', Closure $closure = null)
	{
		if (!self::perform_route_check('DELETE', $route))
			return;
		
		if (!$closure)
			self::call($action);
		else
			$closure();
		die();
	}
	
	public static function catch_all()
	{
		http_response_code(404);
		Http::send_json([ 'Error' => '404 not found' ]);
		die();
	}
	
	public static function create()
	{
		require '../routes/api.php';
		self::catch_all();
	}
	
	private static function call($action)
	{
		$action = explode('@', $action);
		$action[0] = 'App\\Controllers\\' . $action[0];
		
		$obj = new $action[0]();
		call_user_func([$obj, $action[1]]);
	}
}
