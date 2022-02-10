<?php

namespace Core;

class Http
{
	public static function received_input()
	{
		if (empty($_POST) && $json = file_get_contents('php://input')) {
			$var = [];
			$json = json_decode($json);
			
			foreach ($json as $k => $v) {
				$var[$k] = $v;
			}
			$_POST = $var;
		}
	}
	
	public static function send_json($data)
	{
		header('Content-Length: ' . strlen(json_encode($data)));
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
	}
}
